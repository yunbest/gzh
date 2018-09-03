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

    /**
     * 关联 user表
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo('UserModel', 'user_id')->setEagerlyType(1);
    }

    /**
     * 关联分类表
     */
    public function categories()
    {
        return $this->belongsToMany('CategoryModel', 'category', 'id', 'category_id');
    }

    public function adminAddArticle($data, $categories)
    {
        $data['user_id'] = cmf_get_current_admin_id();

        if (!empty($data['more']['thumbnail'])) {
            $data['more']['thumbnail'] = cmf_asset_relative_url($data['more']['thumbnail']);
            $data['thumbnail']         = $data['more']['thumbnail'];
        }

        $this->allowField(true)->data($data, true)->isUpdate(false)->save();

        if (is_string($categories)) {
            $categories = explode(',', $categories);
        }

//        $this->categories()->save($categories);

        $data['article_keywords'] = str_replace('，', ',', $data['article_keywords']);

        $keywords = explode(',', $data['article_keywords']);

//        $this->addTags($keywords, $this->id);

        return $this;

    }

}