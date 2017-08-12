<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2016/7/29
 * Time: 15:01
 */

namespace app\model;


class FlinkModel extends BaseModel
{
    protected $tableName = 'site_flink';
    protected $pk = 'id';

    /**
     * 获取权限列表
     * @param type $field
     * @return type
     */
    public function getFlinkList($offset, $limit, $isCount = FALSE, $field = array('*'))
    {
        $obj = $this->orm();
        if ($isCount) {
            $result = $obj->count();
        } else {
            $result = $obj->select($field)
                ->limit($limit)
                ->offset($offset)
                ->findArray();
        }
        return $result;
    }

    /**
     * 增加广告位
     * @param $data
     * @return bool
     */
    public function addFlink($data)
    {
        $data['modified'] = getDateTime();
        $model = $this->orm();
        $return = false;
        if (empty($data[$this->pk])) {
            unset($data[$this->pk]);
            #添加
            $data['created'] = getDateTime();
            $return = $model->create($data)
                ->save();
            $return = $model->id();
        } else {
            #修改
            $id = $data[$this->pk];
            $result = $model->find_one($id);
            if ($result) {
                $return = $result->set($data)
                    ->save();
            } else {
                $this->setMessage('友情链接不存在');
            }
        }
        return $return;
    }

    public function getFlinkByID($id)
    {
        $orm = $this->orm();
        $result = $orm->find_one($id);
        if($result){
            $result = $result->as_array();
        }
        return $result;
    }
} 