<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/6/7
 * Time: 19:57
 */
return [
    /*应用依赖*/
    'DEPENDENCY_INJECTION_MAP' => [
        'curl' => function ($c) {
            $curl = new \Curl\Curl();
            if (ENVIRONMENT == 'develop') {
                $curl->setOpt(CURLOPT_PROXY, '127.0.0.1:7777');
            }
            $curl->setOpt(CURLOPT_TIMEOUT, 60);
            return $curl;
        },
    ],
    '404_PAGE' => '/error/404.html'
];