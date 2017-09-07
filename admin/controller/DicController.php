<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/9/7
 * Time: 20:50
 */

namespace app\controller;
use app\model\DicModel;

/**
 * 数据管理控制器
 * @privilege 数据管理|Admin/Dic|1985b8d0-6166-11e7-ba80-fdjgfdjfhg555|1
 * @date 2016年5月4日 21:17:23
 * @author Administrator
 */
class DicController extends BaseController
{

    /**
     * 添加字典
     * @privilege 添加字典|Admin/Dic/addDictionary|75b601fb-6189-11e7-ac40-dhgsfdhgsfhds|2
     */
    public function addDictionary()
    {
        if (IS_POST) {

        } else {
            $level = isset($_GET['level']) ? intval($_GET['level']) : 1;
            $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
            #查询记录
            $model = new DicModel();

        }
    }
} 