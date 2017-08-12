<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/7/5
 * Time: 17:47
 */

namespace app\model;


class DictionaryModel extends BaseModel
{
    protected $tableName = 'dictionarys';
    protected $pk = 'id';

    /**
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
    public function getDictionaryList($orm = '', $offset = '', $limit = '', $for_count = false, $field = '*')
    {
        $orm = $this->getOrm($orm)->where_equal('deleted', 0)->select($field);
        if ($for_count) {
            $result = $orm->count();
        } else {
            $result = $orm->offset($offset)
                ->limit($limit)
                ->order_by_desc($this->pk)
                ->find_array();
        }
        return $result;
    }

    public function getDictionaryInfo($orm,$filed='*')
    {
        $result = $this->getOrm($orm)->where('deleted',0)->select($filed)->find_one();
        if (!empty($result)) {
            return $result->as_array();
        } else {
            return false;
        }
    }

    public function getAllDictionary($field='*'){
      return   $this->orm()
          ->where_equal('deleted', 0)
          ->select_expr($field)
          ->find_array();
    }

    /**
     * 添加字典
     *
     * @access public
     * @author furong
     * @param $data
     * @return bool
     * @since  2017年4月10日 09:59:33
     * @abstract
     */
    public function addDictionary($data)
    {
        $return = false;
        $id = $data[$this->pk];
        $data['modified'] = getDateTime();
        $model = $this->orm();
        if (empty($data[$this->pk])) {
            #添加
            unset($data[$this->pk]);
            $data['created'] = getDateTime();
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
                $this->setMessage('文章不存在');
            }
        }
        return $return ? $id : $return;
    }


}