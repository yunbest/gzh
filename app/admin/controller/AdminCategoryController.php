<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/8/31
 * Time: 15:03
 */

namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use app\admin\model\CategoryModel;
use Qiniu\Http\Request;
use think\Db;
class AdminCategoryController extends AdminBaseController
{

    public function index()
    {
     $data = Db::name('category')->select()->toArray();
        $this->assign('vo',$data);
       return $this->fetch();
    }

    /**
     *添加文章分类
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     *添加文章提交
     */
    public function addPost()
    {
        $CategoryModel = new CategoryModel();

        $data = $this->request->param();
        //表单验证
        $result = $this->validate($data, 'AdminCategory');

        if ($result !== true) {
            $this->error($result);
        }

        $result = $CategoryModel->addCategory($data);

        if ($result === false) {
            $this->error('添加失败!');
        }

        $this->success('添加成功!', url('AdminCategory/index'));

    }


    /**
     *编辑分类
     */
    public function edit()
    {

        $id =   $this->request->param('id','','intval');
        $data = Db::name('category')->where('id',$id)->find();
        $this-> assign('vo',$data);
        return $this->fetch();
    }

    /**
     *编辑分类提交
     */
    public function editPost()
    {
        $data = $this->request->param();

    }
}