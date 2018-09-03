<?php
namespace app\admin\validate;

use think\Validate;

class AdminArticleValidate extends Validate
{
    protected $rule = [
        'categories' => 'require',
        'article_title' => 'require',
    ];
    protected $message = [
        'categories.require' => '请指定文章分类！',
        'article_title.require' => '文章标题不能为空！',
    ];

    protected $scene = [
//        'add'  => ['user_login,user_pass,user_email'],
//        'edit' => ['user_login,user_email'],
    ];
}