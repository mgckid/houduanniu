<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/8/28
 * Time: 15:37
 */

namespace app\controller;


class TestController extends BaseController {
    public function index(){
        $this->display('Test/index',[]);
    }
} 