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
class AdminCategoryController extends AdminBaseController
{

    public function index()
    {

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

        $result = $this->validate($data, 'AdminCategory');

        if ($result !== true) {
            $this->error($result);
        }

        $result = $CategoryModel->addCategory($data);

        if ($result === false) {
            $this->error('添加失败!');
        }

        $this->success('添加成功!', url('AdminCategory/index'));
        dump($this->request->param());
    }
}