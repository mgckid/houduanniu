<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/3/28
 * Time: 15:34
 */

namespace app\model;


class CollectItemPoolModel extends BaseModel
{
    public $tableName = 'collect_item_pool';
    public $pk = 'id';

    /**
     * 添加内容
     *
     * @access public
     * @author furong
     * @param $data
     * @return bool
     * @since ${DATE}
     * @abstract
     */
    public function addItem($data)
    {
        $data['modified'] = getDateTime();
        $model = $this->orm();
        $return = false;
        if (empty($data['id'])) {
            unset($data['id']);
            #添加
            $data['created'] = getDateTime();
            $return = $model->create($data)
                ->save();
            $return=$model->id();
        } else {
            #修改
            $id = $data['id'];
            $result = $model->find_one($id);
            if ($result) {
                $return = $result->set($data)
                    ->save();
            } else {
                $this->setMessage('文章不存在');
            }
        }
        return $return;
    }

    /**
     * 获取内容列表
     * @param type $condition 条件
     * @param type $offset 偏移量
     * @param type $limit 获取条数
     * @param type $forCount 统计
     * @param type $field 字段
     * @return type
     */
    public function getItemList($condition, $offset, $limit, $forCount = false, $field = '*')
    {
        $orm = $this->orm()
            ->select_expr($field);
        if ($condition) {
            foreach ($condition as $key => $value) {
                $orm = call_user_func_array(array($orm, $key), $value);
            }
        }
        if ($forCount) {
            $result = $orm->count();
        } else {
            $result = $orm
                ->limit($limit)
                ->offset($offset)
                ->order_by_desc('id')
                ->find_array();
        }
        return $result;
    }

    /**
     * 获取采集内容信息
     * @param type $id 文章id
     * @param type $field 字段名
     * @return type
     */
    public function getItemInfo($id, $field = "*")
    {
        $result = $this->orm()
            ->select_expr($field)
            ->find_one($id);
        if (!$result) {
            return false;
        }
        return $result->as_array();;
    }



}