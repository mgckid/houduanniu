<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/10/9
 * Time: 12:23
 */

namespace app\controller;


use houduanniu\api\Controller;
use houduanniu\base\Dex3;

class BaseController extends Controller
{
    public function getRequestParam()
    {
//        if (ENVIRONMENT !== 'develop') {
//            $dex3 = new Dex3();
//            $encode_str = base64_decode($_REQUEST['param']);
//            $result = json_decode($dex3->decrypt($encode_str),true);
//        } else {
//            $result = $_REQUEST;
//        }
        $result = $_REQUEST;
        return $result;
    }
} 