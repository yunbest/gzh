<?php
namespace app\admin\validate;

use think\Validate;

class AdminArticleValidate extends Validate
{
    protected $rule = [
        'categories' => 'require',
        'title' => 'require',
    ];
    protected $message = [
        'categories.require' => '请指定文章分类！',
        'title.require' => '文章标题不能为空！',
    ];


}