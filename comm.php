<?php
    error_reporting(E_ERROR);
    define('BASEDIR', dirname(__DIR__).'/aiht');

    $domain = 'http://'.$_SERVER['HTTP_HOST'].'/claiht/';

    // 权限校验
    require_once(BASEDIR.'/auth.php');
    Auth::check();