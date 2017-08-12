<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/3/29
 * Time: 15:54
 */

namespace app\model;


class CollectRuleModel extends BaseModel
{
    protected $tableName = 'collect_rule';
    protected $pk = 'rule_id';

    /**
     * 添加内容
     *
     * @access public
     * @author furong
     * @param $data
     * @return bool
     * @since  2017年3月29日 15:56:02
     * @abstract
     */
    public function addRule($data)
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
                $this->setMessage('规则不存在');
            }
        }
        return $return;
    }

    /**
     * 获取规则信息
     * @param type $id 文章id
     * @param type $field 字段名
     * @return type
     */
    public function getRuleInfo($id, $field = "*")
    {
        $result = $this->orm()
            ->select_expr($field)
            ->find_one($id);
        if (!$result) {
            return false;
        }
        return $result->as_array();;
    }

    /**
     * 获取规则列表
     * @param type $condition 条件
     * @param type $offset 偏移量
     * @param type $limit 获取条数
     * @param type $forCount 统计
     * @param type $field 字段
     * @return type
     */
    public function getRuleList($condition, $offset, $limit, $forCount = false, $field = '*')
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
                ->order_by_desc($this->pk)
                ->find_array();
        }
        return $result;
    }

}