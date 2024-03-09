<?php
require_once(BASEDIR.'/comm.php');
require_once(BASEDIR.'/helpers/input.class.php');
require_once(BASEDIR.'/helpers/utiler.class.php');
require_once(BASEDIR.'/helpers/database.class.php');

function deleteAjax($parameters)
{
    return <<<str
<?php
    require_once('../../../comm.php');
    require_once(BASEDIR.'/helpers/input.class.php');
    require_once(BASEDIR.'/helpers/utiler.class.php');
    require_once(BASEDIR.'/helpers/database.class.php');

    \$request = new Input();
    if(!\$request->filled('id')) {
        return Utiler::epack('param.error', '页面异常，请刷新重试');
    }

    \$model = Database::first("select * from {$parameters['table']} where id = {\$request->find('id')}");
    if(!\$model) {
        return Utiler::epack('model.unexists', '数据没有找到，请刷新重试');
    }

    if(!Database::delete('{$parameters['table']}', "id = {\$model['id']}")) {
        return Utiler::epack('delete.error', "{$parameters['title']}删除失败，请稍后重试");
    }
    return Utiler::epack(Utiler::Success, 'success', ['item' => ['id' => \$request->find('id')]]);
str;
}