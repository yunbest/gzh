<?php

namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use app\admin\model\ArticleModel;
use app\admin\model\CategoryModel;
use think\Db;
use app\admin\service\ArticleService;

class AdminArticleController extends AdminBaseController
{
    /**
     * 文章列表
     */
    public function index()
    {
        $param = $this->request->param();
        if (!empty($param)) {
            if ($param['category'] != 0) {
                $param['category'] = $param['category_id_erji'];
                unset($param['category_id_erji']);

            }
        }
        $categoryId = $this->request->param('category', 0, 'intval');

        $Service = new ArticleService();
        $data        = $Service->adminArticleList($param);






        //第一级分类
        $re = Db::name('category')->where('pid','0')->select()->toArray();
        $this->assign('re',$re);
        if (is_array($data)){
            $this->assign('articles',$data);

        }else{
            $this->assign('articles',$data->items());

        }

        $this->assign('start_time', isset($param['start_time']) ? $param['start_time'] : '');
        $this->assign('end_time', isset($param['end_time']) ? $param['end_time'] : '');
        $this->assign('keyword', isset($param['keyword']) ? $param['keyword'] : '');


        $this->assign('category', $categoryId);



        return $this->fetch();
    }

    /**
     * 添加文章
     */
    public function add()
    {
        $re = Db::name('category')->where('pid','0')->select()->toArray();
        $this->assign('re',$re);
        return $this->fetch();
    }

    /**
     * 添加文章提交
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();

            //状态只能设置默认值。未发布、未置顶、未推荐
            $data['article']['status'] = 0;
            $data['article']['top'] = 0;
            $data['article']['rec'] = 0;
            $data['article']['is_del'] = 0;
            $data['article']['ctime'] = time();
            //判断是否用二级分类
if ($data['article']['categories_erji']){
    $data['article']['categories'] = $data['article']['categories_erji'];
}
            $post   = $data['article'];

            $result = $this->validate($post, 'AdminArticle');
            if ($result !== true) {
                $this->error($result);
            }

            $ArticleModel = new ArticleModel();

            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {

                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl[] = cmf_asset_relative_url($url);

                }
               $data['article']['pic_urls'] =  implode(',',$photoUrl);
            }




            $ArticleModel->adminAddArticle($data['article'], $data['article']['categories']);


            $this->success('添加成功!', url('AdminArticle/edit', ['id' => $ArticleModel->id]));
        }

    }

    /**
     * 编辑文章
     */
    public function edit()
    {

        //第一级分类
        $re = Db::name('category')->where('pid','0')->select()->toArray();
        $this->assign('re',$re);
        $id = $this->request->param('id', 0, 'intval');

        $Article = new ArticleModel();
        $data            = $Article->where('id', $id)->find()->toArray();



       $data['pic']= explode(',',$data['pic_urls']);


        $this->assign('vo', $data);



        return $this->fetch();
    }

    /**
     * 编辑文章提交
     */
    public function editPost()
    {

        if ($this->request->isPost()) {
            $data   = $this->request->param();

            //需要抹除发布、置顶、推荐的修改。
            unset($data['post']['status']);
            unset($data['post']['top']);
            unset($data['post']['rec']);

            $post   = $data['article'];
            $result = $this->validate($post, 'AdminArticle');
            if ($result !== true) {
                $this->error($result);
            }

            $ArticleModel = new ArticleModel();

            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {

                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl[] = cmf_asset_relative_url($url);

                }
                $data['article']['pic_urls'] =  implode(',',$photoUrl);
            }


            $ArticleModel->adminEditArticle($data['article']);



            $this->success('保存成功!');

        }
    }

    /**
     * 文章删除
     */
    public function delete()
    {
        $param           = $this->request->param();
        $articleModel = new ArticleModel();
        //单个删除
        if (isset($param['id'])) {
            $id           = $this->request->param('id', 0, 'intval');

            $re =    $articleModel
                ->where(['id' => $id])
                ->update(['is_del' => 1]);
            if ($re) {
                $this->success("删除成功！", '');
            }

        }
        //批量删除
        if (isset($param['ids'])) {
            $ids     = $this->request->param('ids/a');

            $result  = $articleModel->where(['id' => ['in', $ids]])->update(['is_del' => 1]);
            if ($result) {
                $this->success("删除成功！", '');
            }
        }
    }

    /**
     * 文章发布
     */
    public function publish()
    {
        $param           = $this->request->param();
        $PostModel = new ArticleModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $PostModel->where(['id' => ['in', $ids]])->update(['status' => 1]);

            $this->success("发布成功！", '');
        }

        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $PostModel->where(['id' => ['in', $ids]])->update(['status' => 0]);

            $this->success("取消发布成功！", '');
        }

    }

    /**
     * 文章置顶
     */
    public function top()
    {
        $param           = $this->request->param();
        $PostModel = new ArticleModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $PostModel->where(['id' => ['in', $ids]])->update(['top' => 1]);

            $this->success("置顶成功！", '');

        }

        if (isset($_POST['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $PostModel->where(['id' => ['in', $ids]])->update(['top' => 0]);

            $this->success("取消置顶成功！", '');
        }
    }

    /**
     * 文章推荐
     */
    public function recommend()
    {
        $param           = $this->request->param();
        $PostModel = new ArticleModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $PostModel->where(['id' => ['in', $ids]])->update(['rec' => 1]);

            $this->success("推荐成功！", '');

        }
        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $PostModel->where(['id' => ['in', $ids]])->update(['rec' => 0]);

            $this->success("取消推荐成功！", '');

        }
    }




}
