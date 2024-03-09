<?php


require_once('../comm.php');
require_once(BASEDIR.'/helpers/input.class.php');
require_once(BASEDIR.'/helpers/utiler.class.php');
require_once(BASEDIR.'/helpers/database.class.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == "insert") {
        $table = 'ai_prompt_use_list';
        $params = array(
            'prompt_id' => $_POST['prompt_id'],
            'prompt_text' => $_POST['prompt_text'],
            'create_time' => date('Y-m-d H:i:s') // 使用当前时间作为 create_time
        );

        $result = Database::insert($table, $params);


        if ($result) {
            echo json_encode(array('message' => '保存成功'));
        } else {
            echo json_encode(array('error' => '保存失败: '));
        }
    }
    elseif (isset($_POST['action']) && $_POST['action'] == "create_table") {

        $tableCreationSQL = $_POST['sql'];
        $db = Database::getInstance();

        // 检查 SQL 语句是否包含危险操作
        $dangerousOperations = ['DROP DATABASE', 'DROP TABLE', 'TRUNCATE TABLE', 'DELETE FROM'];
        $isDangerous = false;
        foreach ($dangerousOperations as $operation) {
            if (preg_match("/\b$operation\b/i", $tableCreationSQL)) {
                $isDangerous = true;
                break;
            }
        }

        if ($isDangerous) {
            echo json_encode(array('error' => '此语句包括非法危险的sql操作，请检查'));
        } else {
            $result = $db->exec($tableCreationSQL);


            if ($result) {
                echo json_encode(array('message' => 'sql执行成功'));
            } else {
                echo json_encode(array('error' => 'sql执行失败 '));
            }
        }
    }
    else {
        echo json_encode(array('error' => '无效请求方法'));
    }
}

?>
