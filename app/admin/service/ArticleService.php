<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/3
 * Time: 9:51
 */

namespace app\admin\service;

use app\admin\model\ArticleModel;
class ArticleService
{

    public function adminArticleList($filter, $isPage = false)
    {



        $join = [
            ['cmf_category b', 'a.category_id = b.id']
        ];

        $field = 'a.*';







        $ArticleModel = new ArticleModel();
        $articles        = $ArticleModel->alias('a')->field($field)
            ->join($join)


            ->paginate(10);

        return $articles;

    }
}