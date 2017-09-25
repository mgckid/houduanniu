<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/3/22
 * Time: 15:29
 */

return array(
    /**
     * 框架核心库文件非必要不要去修改
     */
    /***************************核心库文件 开始****************************/
    #后端牛框架库文件
    'houduanniu' => __DIR__ . '/houduanniu',

    #自动加载库
    'Aura\Autoload' => __DIR__ . '/Aura.Autoload-2.x/src',

    #配置类(Config is a lightweight configuration file loader that supports PHP, INI, XML, JSON, and YAML files)
    'Noodlehaus' => __DIR__ . '/hassankhan/config/src',

    #idiorm\orm类
    'idiorm\orm' => __DIR__ . '/idiorm-master/src/idiorm/orm',

    #模版引擎
    'League\Plates' => __DIR__ . '/thephpleague/plates/src',

    #A small PHP 5.3 dependency injection container http://pimple.sensiolabs.org
    'Pimple' => __DIR__ . '/Pimple-master/src/Pimple',

    #异常类 A small library that aims at supplementing default exceptions in PHP
    'Exceptions' => __DIR__ . '/standard-exceptions-master/Exceptions',

    #验证类(Laravel Validation 简化无依赖版)
    'Overtrue\Validation' => __DIR__ . '/overtrue/validation/src',
    /****************************核心库文件 结束***************************/
);
