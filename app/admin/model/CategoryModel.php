<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/8/31
 * Time: 16:32
 */

namespace app\admin\model;

use think\Model;
class CategoryModel extends Model
{

    public function addCategory($data)
    {
        $result = true;
        self::startTrans();
        try {

            $this->allowField(true)->save($data);
            $id = $this->id;
            if (empty($data['parent_id'])) {

                $this->where(['id' => $id])->update(['path' => '0-' . $id]);
            } else {
                $parentPath = $this->where('id', intval($data['parent_id']))->value('path');
                $this->where(['id' => $id])->update(['path' => "$parentPath-$id"]);

            }
            self::commit();
        } catch (\Exception $e) {
            self::rollback();
            $result = false;
        }



        return $result;
    }
}