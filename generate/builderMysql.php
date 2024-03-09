<?php
require_once(BASEDIR.'/comm.php');
require_once(BASEDIR.'/helpers/input.class.php');
require_once(BASEDIR.'/helpers/utiler.class.php');
require_once(BASEDIR.'/helpers/database.class.php');

function mysqlPage($parameters)
{
    $columns = Database::select("show full columns from {$parameters['table']}");

    // echo "<pre>";
    // var_export($columns[1]);
    // echo "</pre>";
    // die;
    $enums = [];
    foreach($columns as $column) {
        if(in_array($column['Field'], ['id', 'created_at', 'updated_at'])) {
            continue;
        }
        if(mb_substr($column['Type'], 0, 4) == 'enum') {
            $enums[$column['Field']] = Utiler::enumArray($column['Type']);
        }
    }

    return "<?php\n\nreturn ". preg_replace(["/(\n[\s\t]*){0,1}array\s{0,1}\(/", "/\)/"], ['[', ']'], var_export($enums, true)).';';
}