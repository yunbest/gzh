<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/3
 * Time: 9:37
 */

namespace app\admin\model;

use think\Model;
use think\Db;
class ArticleModel extends Model
{

    protected $type = [
        'more' => 'array',
    ];

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;



    public function adminAddArticle($data, $categories)
    {


        $this->allowField(true)->data($data, true)->isUpdate(false)->save();



        return $this;

    }

    public function adminEditArticle($data){
        $this->allowField(true)->save($data,['id',$data['id']]);



        return $this;

    }

}