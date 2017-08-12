<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2016/8/19
 * Time: 17:34
 */

namespace app\model;


class NavModel extends BaseModel
{
    protected $tableName = 'site_nav';
    protected $pk = 'id';

    public function init()
    {
        if ($this->orm()->count() != 0) {
            return true;
        }
        $data = array(
            'name' => '根目录',
            'path' => '0',
            'url' => '/',
        );
        return $this->orm()->create($data)->save();
    }

} 