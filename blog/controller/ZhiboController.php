<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/7/31
 * Time: 9:10
 */

namespace app\controller;


use houduanniu\base\Controller;
use houduanniu\web\View;
use houduanniu\base\Application;

class ZhiboController extends Controller
{

    public function index()
    {
        View::setDirectory(__PROJECT__ . '/' . strtolower(Application::getModule()) . '/' . C('DIR_VIEW'));
        View::display('zhibo/index');
    }
} 