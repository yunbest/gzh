<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/3
 * Time: 9:51
 */

namespace app\admin\service;

use app\admin\model\ArticleModel;
use think\Db;

class ArticleService
{

    public function adminArticleList($filter, $isPage = false)
    {


        $ArticleModel = new ArticleModel();
        //如果查找条件为空  返回所有数据
        if (!empty($filter)&&$filter['category']==0){
            $data = $ArticleModel->where('is_del','0')->select()->toArray();
            foreach ($data as $k=>$v){
                $re =   Db::name('category')->where('id',$v['type'])->find();
                $data[$k]['category'] = $re['title'];
            }

            return $data;
        }
        if (empty($filter)){
            $data = $ArticleModel->where('is_del','0')->select()->toArray();
            foreach ($data as $k=>$v){
              $re =   Db::name('category')->where('id',$v['type'])->find();
                $data[$k]['category'] = $re['title'];
            }

           return $data;
        }else{

            $where = [
                'a.ctime' => ['>=', 0],
                'a.is_del' => 0
            ];

            $join = [
                ['cmf_category c','a.type=c.id']
            ];
            if (!empty($filter['type'])){
                $where['a.type'] = ['eq',$filter['category']];
            }

            $startTime = empty($filter['start_time']) ? 0 : strtotime($filter['start_time']);
            $endTime   = empty($filter['end_time']) ? 0 : strtotime($filter['end_time']);

            if (!empty($startTime) && !empty($endTime)) {
                $where['a.ctime'] = [['>= time', $startTime], ['<= time', $endTime]];
            } else {
                if (!empty($startTime)) {
                    $where['a.ctime'] = ['>= time', $startTime];
                }
                if (!empty($endTime)) {
                    $where['a.ctime'] = ['<= time', $endTime];
                }
            }

            $keyword = empty($filter['keyw']) ? '' : $filter['keyw'];
            if (!empty($keyword)) {
                $where['a.post_title'] = ['like', "%$keyword%"];
            }
            $field = "a.*,c.title as category";

            $articles        = $ArticleModel->alias('a')->field($field)
                ->join($join)
                ->where($where)

                ->paginate(10);

            return $articles;

        }








    }
}