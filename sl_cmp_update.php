<?php
    include('/comm.php');
    require_once(BASEDIR.'/helpers/input.class.php');
    require_once(BASEDIR.'/helpers/database.class.php');

    Auth::check();
    $request = new Input();

    $row = Database::first("SELECT * FROM `wit_cps_company` WHERE `cmp_id` = ".Auth::id(), 'auth');

    $params = $request->only(['cmp_name', 'cmp_email', 'cmp_tel', 'cmp_add', 'cmp_pwd', 'cmp_pername']);

    if(Database::update('wit_cps_company', $params, 'cmp_id = '.Auth::id(), 'auth')) {

        echo "<script charset='utf-8'>alert(\"Update Sucess!\");window.location='{$url}index.php';"."</script>";
    }
    else {

        echo "<script charset='utf-8'>alert(\"修改失败，请稍后重试!\");window.location='com_edit.php';"."</script>";
    }