<?php

/**
 * Desc: EventModel
 *
 * @author CPR137
 */

namespace app\model;

class JobRecordModel extends BaseModel {

    protected $tableName = 'job_record';
    protected $pk = 'id';

    public function getEventList($offset, $limit, $isCount = FALSE, $field = 'e.*') {
        $obj = $this->orm();
        if ($isCount) {
            $result = $obj->count();
        } else {
            $result = $obj->table_alias('e')
                    ->select_expr($field)
                    ->limit($limit)
                    ->offset($offset)
                    ->findArray();
        }
        return $result;
    }

}
