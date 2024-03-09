<?php
require_once(BASEDIR.'/comm.php');
require_once(BASEDIR.'/helpers/input.class.php');
require_once(BASEDIR.'/helpers/utiler.class.php');
require_once(BASEDIR.'/helpers/database.class.php');

function createPage($parameters)
{
    $columns = Database::select("show full columns from {$parameters['table']}");

    // echo "<pre>";
    // var_export($columns);
    // echo "</pre>";
    // die;
    $rules = [];
    $form = '';
    foreach($columns as $column) {
        if(in_array($column['Field'], ['id', 'created_at', 'updated_at'])) {
            continue;
        }
        $enums = [];
        if($column['Null'] == 'NO' && in_array($column['Default'], ['NULL', ''])) {
            $rules['rules'][$column['Field']] = 'required';
            $rules['messages'][$column['Field'].'.required'] = "{$column['Comment']}不能为空";
        }
        else {
            $rules['rules'][$column['Field']] = 'nullable';
        }
        if(mb_substr($column['Type'], 0, 4) == 'enum') {
            $enums = Utiler::enumArray($column['Type']);
        }

        $title = $column['Comment'] ? $column['Comment'] : $column['Field'];
        if($enums) {
            $options = '';
            foreach($enums as $k) {
                $options = "{$options}<option value='{$k}'>{$k}</option>";
            }
            $tmp = <<<form
            <div class='mb-3 checker'>
                <label class='form-label'>{$title}</label>
                <select class='form-select' name='{$column["Field"]}' value=''>
                    <option value="">--</option>
                    {$options}
                </select>
            </div>
form;
        }
        else {
            // echo $column['Type'], ' ', stripos($column['Type'], 'text'), '<br>';
            if(stripos($column['Type'], 'text') !== false) {
                $tmp = <<<form
                <div class="mb-3 checker">
                    <label class="form-label">{$title}</label>
                    <textarea class="form-control" name="{$column['Field']}" placeholder="{$column['Field']}" rows="3"></textarea>
                </div>
form;
            }
            else {
                $tmp = <<<form
                <div class="mb-3 checker">
                    <label class="form-label">{$title}</label>
                    <input class="form-control" name="{$column['Field']}" value="" type="text" placeholder="{$column['Field']}">
                </div>
form;
            }
}
        $form = <<<form1
{$form}
{$tmp}
form1;
    }
    $rules = json_encode($rules, true);
    $enums = Utiler::pArray($enums);
    return <<<str
<?php
    require_once('../../comm.php');
    require_once(BASEDIR.'/helpers/input.class.php');
    require_once(BASEDIR.'/helpers/utiler.class.php');
    require_once(BASEDIR.'/helpers/database.class.php');
    ?>

    <div class="p-4">
        <form id="model-form" action="./service/create.php" method="POST" onsubmit="return false;">
            {$form}
            <div class="d-grid">
                <button class="btn btn-primary " id="save-model" data-form="#model-form">保存</button>
                <textarea id="model-rule" data-form="#model-form" hidden>{$rules}</textarea>
            </div>
        </form>
    </div>

    <script>
        jQuery(document).ready(function() {
            // 数据校验
            var checker = new validator();
            checker.init({ ruleDom: '#model-rule', isSubmit: false });

            $('#save-model').unbind('click').bind('click', function() {
                if(!checker.validate()) {
                    return false;
                }
                handler.immediately({
                    url: $(this).parents('form').attr('action'), mthis: this, isConfirm: false, requestSuccess: function(param) {
                        app.table().append_one(param.response.item);

                        handler.closeDialog(param.mthis);
                    }
                });
            });
        });
    </script>
str;
}

function createAjax($parameters)
{
    $columns = Database::select("show full columns from {$parameters['table']}");

    $items = [];
    $required = [];
    $dates = [];
    foreach($columns as $column) {
        if(in_array($column['Field'], ['id', 'created_at', 'updated_at'])) {
            continue;
        }
        if($column['Null'] == 'NO' && in_array($column['Default'], ['NULL', ''])) {
            $required[$column['Field']] = $column['Comment'];
        }
        if(in_array($column['Type'], ['datetime', 'date'])) {
            $dates[$column['Field']] = $column['Comment'];
        }
        $items[$column['Field']] = $column['Comment'];
    }

    $checks = Utiler::pArray($required);
    $dates = Utiler::pArray($dates);
    $only = Utiler::pArray(array_keys($items));
    return <<<str
<?php
    require_once('../../../comm.php');
    require_once(BASEDIR.'/helpers/input.class.php');
    require_once(BASEDIR.'/helpers/utiler.class.php');
    require_once(BASEDIR.'/helpers/database.class.php');

    \$checks = {$checks};
    \$request = new Input();
    foreach(\$checks as \$k => \$t) {
        if(!\$request->filled(\$k)) {
            return Utiler::epack('param.error', "请填写{\$t}");
        }
    }

    \$dates = {$dates};
    \$params = \$request->only($only);
    foreach(\$dates as \$k => \$t) {
        if(!\$request->filled(\$k)) {
            unset(\$params[\$k]);
        }
    }
    \$params['created_at'] = date('Y-m-d H:i:s');
    \$params['updated_at'] = date('Y-m-d H:i:s');

    if(!Database::insert('{$parameters['table']}', \$params)) {
        return Utiler::epack('insert.error', "{$parameters['title']}创建失败，请稍后重试");
    }
    \$params['id'] = Database::last_insert_id();
    return Utiler::epack(Utiler::Success, 'success', ['item' => \$params]);
str;
}