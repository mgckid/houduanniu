<?php

/**
 * Description of BaseModel
 *
 * @author Administrator
 */

namespace app\model;

use houduanniu\base\Model;
use app\model\DictionaryModel;

class BaseModel extends Model
{


    /**
     * 获取orm
     * @access public
     * @author furong
     * @param $orm
     * @return \idiorm\orm\ORM
     * @since 2017年7月6日 17:23:04
     * @abstract
     */
    public function getOrm($orm)
    {
        if (empty($orm)) {
            $orm = $this->orm();
        }
        return $orm;
    }

    /**
     * 删除记录
     * @access public
     * @author furong
     * @param $data
     * @return bool|mixed
     * @since 2017年7月28日 09:37:59
     * @abstract
     * @throws \Exception
     */
    public function addRecord($data)
    {
        $return = false;
        $id = $data[$this->pk];
        $data['modified'] = getDateTime();
        $model = $this->orm();
        if (empty($data[$this->pk])) {
            #添加
            unset($data[$this->pk]);
            $data['created'] = $data['modified'];
            $return = $model->create($data)
                ->save();
            $id = $model->id();
        } else {
            #修改
            $result = $model->find_one($id);
            if ($result) {
                $return = $result->set($data)
                    ->save();
            } else {
                $this->setMessage('插入记录失败');
            }
        }
        return $return ? $id : $return;
    }

    /**
     * 获取单条记录
     * @access public
     * @author furong
     * @param $id
     * @param string $filed
     * @return array|bool
     * @since 2017年7月28日 09:40:34
     * @abstract
     */
    public function getRecordInfoById($id, $filed = '*')
    {
        $orm = $this->orm()->where($this->pk, $id);
        $result = $this->getRecordInfo($orm, $filed);
        return $result;
    }

    /**
     * 删除单条记录
     * @access public
     * @author furong
     * @param $id
     * @return bool
     * @since 2017年7月28日 09:46:43
     * @abstract
     */
    public function deleteRecordById($id)
    {
        $data = [
            $this->pk => $id,
            'deleted' => 1
        ];
        return $this->addRecord($data);
    }

    /**
     * 获取所有记录
     * @access public
     * @author furong
     * @return array
     * @since 2017年7月28日 09:49:20
     * @abstract
     */
    public function getAllRecord($field = '*', $orm = '')
    {
        return $this->getOrm($orm)->select_expr($field)->find_array();
    }

    /**
     * 获取记录列表
     * @access public
     * @author furong
     * @param $orm
     * @param string $offset
     * @param string $limit
     * @param bool $for_count
     * @param string $field
     * @return void
     * @since 2017年7月6日 17:14:49
     * @abstract
     */
    public function getRecordList($orm = '', $offset = '', $limit = '', $for_count = false, $order_by_id_desc = true, $field = '*')
    {
        $orm = $this->getOrm($orm)->where_equal('deleted', 0);
        if ($for_count) {
            $result = $orm->count();
        } else {
            $model = $orm->offset($offset)
                ->limit($limit)
                ->select($field);
            if ($order_by_id_desc) {
                $model = $model->order_by_desc($this->pk);
            } else {
                $model = $model->order_by_asc($this->pk);
            }
            $result = $model->find_array();
        }
        return $result;
    }

    /**
     * 获取单条记录
     * @access public
     * @author furong
     * @param string $orm
     * @param string $field
     * @return array|false
     * @since 2017年8月17日 16:40:20
     * @abstract
     */
    public function getRecordInfo($orm = '', $field = '*')
    {
        $orm = $this->getOrm($orm);
        $result = $orm->select_expr($field)->find_one();
        if (!empty($result)) {
            $result = $result->as_array();
        }
        return $result;
    }


}
