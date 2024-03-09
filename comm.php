<?php
    error_reporting(E_ERROR);
    define('BASEDIR', dirname(__DIR__).'/auto-/');

    $domain = 'http://'.$_SERVER['HTTP_HOST'].'/auto-/';

    // 权限校验
    require_once(BASEDIR.'/auth.php');
    Auth::check();