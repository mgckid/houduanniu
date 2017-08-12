<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/25
 * Time: 22:54
 */

namespace app\model;


class AdvertisementPositioModel extends BaseModel
{
    public $tableName = 'advertisement_position';

    /**
     * 增加广告位
     * @param $data
     * @return bool
     */
    public function addAdvertisementPositio($data)
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
            $return = $model->id();
        } else {
            #修改
            $id = $data['id'];
            $result = $model->find_one($id);
            if ($result) {
                $return = $result->set($data)
                    ->save();
            } else {
                $this->setMessage('广告位不存在');
            }
        }
        return $return;
    }

    /**
     * 获取广告位列表
     * @param $offset
     * @param $limit
     * @param $forCount
     * @param string $field
     * @return mixed
     */
    public function getPostionList($offset, $limit, $forCount, $field = '*')
    {
        $orm = $this->orm();
        if ($forCount) {
            $result = $orm->count();
        } else {
            $result = $orm->offset($offset)
                ->select_expr($field)
                ->limit($limit)
                ->order_by_asc('id')
                ->find_array();
        }
        return $result;
    }

    public function getPostionInfoByID($positionId)
    {
        $orm = $this->orm();
        $result = $orm->find_one($positionId);
        if($result){
            $result = $result->as_array();
        }
        return $result;
    }
}