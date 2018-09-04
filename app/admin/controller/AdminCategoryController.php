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
use tree\Tree;
use think\Db;
class AdminCategoryController extends AdminBaseController
{

    public function index()
    {
     $data = Db::name('category')->where('pid','0')->where('is_del','0')->select()->toArray();

     $tree = new Tree();
     $re =  $tree->getT($data,0,0);

        $this->assign('vo',$re);
       return $this->fetch();
    }

//    public function index()
//    {
//        $CategoryModel = new CategoryModel();
//        $categoryTree        = $CategoryModel->adminCategoryTableTree();
//
//        $this->assign('category_tree', $categoryTree);
//        return $this->fetch();
//    }
    /**
     *分类ajax返回
     */
    public function ajax()
    {
         $id = $this->request->param('id','0','intval');
         $data = Db::name('category')->where('pid',$id)->select()->toArray();
        $tpl = '';
        foreach ($data as $v){
          $pid =  explode(',',$v['pids']);
          $num = count($pid)-1;
          $px = $num *20;
            $tpl.= "<tr class='a".$id." listAjax-".$v['id']."' onclick='categoryAjax(".$v['id'].")'> <th width=\"50\">" .$v['id']. "</th> <th style='text-indent: ".$px."px'>├─" .$v['title']. "</th>
                            <th>".$v['desc']."</th> 
                             <th width=\"180\"><a href=" . url("AdminCategory/add", ["id" => $v['id']]) .">添加子菜单</a>&nbsp&nbsp<a href=" . url("AdminCategory/edit", ["id" => $v['id']]) .">编辑</a>&nbsp&nbsp<a href=" . url("AdminCategory/delete", ["id" => $v['id']]) ." class='js-ajax-delete' >删除</a></th></tr>";
        }
        return json($tpl);
    }

    /**
     *分类联动ajax
     */
    public function selectAjax(){
        $id = $this->request->param('id','0','intval');

        $data = Db::name('category')
            ->where('pid',$id)

            ->select();
        return json($data);

    }
    /**
     *添加文章分类
     */
    public function add()
    {
        $id = $this->request->param('id','0','intval');
        $re = Db::name('category')->where('pid','0')->select()->toArray();

        $data = Db::name('category')->where('id',$id)->find();




        $this->assign('vo',$data);
        $this->assign('re',$re);
        return $this->fetch();

    }

    /**
     *添加分类提交
     */
    public function addPost()
    {
        $CategoryModel = new CategoryModel();

        $data = $this->request->param();
        if (is_array($data['pid'])) {
            $pid = end($data['pid']);
            $pids = implode(',', $data['pid']);
            $data['pids'] = $pids;
            $data['pid'] = $pid;
        }else{
            $data['pids'] = $data['pid'];
        }

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
        $re = Db::name('category')->where('pid','0')->select()->toArray();
        $this->assign('re',$re);
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
    /**
     * 删除文章分类
     */
    public function delete()
    {

        $id = $this->request->param('id');

        $CategoryModel = new CategoryModel();
        //获取删除内容
        $category = $CategoryModel->where('id',$id)->find();

        if (empty($category)){
            $this->error('分类不存在');
        }
        //判断有无子类
        $categoryCount = $CategoryModel->where(['pid'=>$id,'is_del'=>0])->count();
        if($categoryCount>0){
            $this->error('该分类下有子类无法删除');
        }

        $result = $CategoryModel->where('id',$id) ->update(['is_del' => 1]);;
        if ($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    public function select()
    {
        $ids                 = $this->request->param('ids');
        $selectedIds         = explode(',', $ids);
        $CategoryModel = new CategoryModel();

        $tpl = <<<tpl
<tr class='data-item-tr'>
    <td>
        <input type='checkbox' class='js-check' data-yid='js-check-y' data-xid='js-check-x' name='ids[]'
               value='\$id' data-name='\$name' \$checked>
    </td>
    <td>\$id</td>
    <td>\$spacer <a href='\$url' target='_blank'>\$name</a></td>
</tr>
tpl;

        $categoryTree = $CategoryModel->adminCategoryTableTree($selectedIds, $tpl);

        $where      = ['delete_time' => 0];
        $categories = $CategoryModel->where($where)->select();

        $this->assign('categories', $categories);
        $this->assign('selectedIds', $selectedIds);
        $this->assign('categories_tree', $categoryTree);
        return $this->fetch();
    }
}