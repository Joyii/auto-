<?php
require_once(BASEDIR.'/comm.php');
require_once(BASEDIR.'/helpers/input.class.php');
require_once(BASEDIR.'/helpers/utiler.class.php');
require_once(BASEDIR.'/helpers/database.class.php');

function listPage($parameters)
{
    $columns = Database::select("show full columns from {$parameters['table']}");

    $properties = [];
    $form = '';
    foreach($columns as $column) {
        if(count($properties) >= 10) {
            break;
        }
        if(stripos($column['Type'], 'text') !== false) {
            continue;
        }
        if(in_array($column['Field'], ['updated_at'])) {
            continue;
        }
        $enums = [];
        preg_match("/varchar\((?<length>\d+)\)/i", $column['Type'], $match);
        $ppt = ['title' => $column['Comment'] ? $column['Comment'] : $column['Field'], 'name' => $column['Field']];
        if(isset($match['length']) && $match['length'] >= 32) {
            $ppt = array_merge($ppt, ['format' => 'utiler.substring', 'args' => 32]);
        }
        if(in_array($column['Type'], ['timestamp', 'datetime', 'date'])) {
            $ppt = array_merge($ppt, ['format' => 'utiler.datetime']);
        }
        $properties[] = $ppt;

        if($column['Null'] == 'NO' && in_array($column['Default'], ['NULL', ''])) {
            if(in_array($column['Field'], ['created_at'])) {
                continue;
            }
            if(stripos($column['Type'], 'text') !== false) {
                continue;
            }
            $title = $column['Comment'] ? $column['Comment'] : $column['Field'];

            if(mb_substr($column['Type'], 0, 4) == 'enum') {
                $enums = Utiler::enumArray($column['Type']);
            }

            $title = $column['Comment'] ? $column['Comment'] : $column['Field'];
            if($enums) {
                $options = '';
                foreach($enums as $k) {
                    //  \$model[{$column["Field"]}] == $k ? 'checked' : ''
                    $options = "{$options}<option value='{$k}'>{$k}</option>";
                }
                $tmp = <<<form
                    <div class='mb-3 col'>
                        <label class='form-label'>{$title}</label>
                        <select class='form-select tabler' name="{$column["Field"]}">
                            <option value="">--</option>
                            {$options}
                        </select>
                    </div>
form;
            }
            else {
                $tmp = <<<form
                    <div class="mb-3 col">
                        <label class="form-label">{$title}</label>
                        <input class="form-control tabler" name="{$column['Field']}" value="" type="text" placeholder="{$column['Field']}">
                    </div>
form;
            }
            $form = <<<form1
{$form}
{$tmp}
form1;
        }
    }
    $propertiesJson = json_encode($properties, true);
    $properties = Utiler::pArray($properties);

    $operators = [
        ['title' => '修改', 'handler' => 'dialog', 'icon' => 'pencil-square', 'properties' => [
                'data-href' => './edit.php?id={id}', 'data-class' => 'middler', 'data-message' => "修改{$parameters['title']}信息"
            ]
        ],
        ['title' => '删除', 'handler' => 'request', 'icon' => 'trash-fill', 'class' => 'btn-danger', 'properties' => [
                'data-href' => './service/destroy.php?id={id}', 'data-keep' => 'false', 'data-message' => "确定要删除{$parameters['title']}吗？"
            ]
        ],
    ];
    $operatorsJson = json_encode($operators, true);
    $operators = Utiler::pArray($operators);
    // $header = file_get_contents('./header.php');
    return <<<str
<?php
    require_once('../../comm.php');
    require_once(BASEDIR.'/helpers/input.class.php');
    require_once(BASEDIR.'/helpers/utiler.class.php');
    require_once(BASEDIR.'/helpers/database.class.php');

    \$request = new Input();
    \$properties = {$properties};
    \$operators = {$operators};
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{$parameters['title']}列表</title>
	<link href="<?= \$domain ?>style/app.css" rel="stylesheet">
	<link href="<?= \$domain ?>style/site.css" rel="stylesheet">
	<script src="<?= \$domain ?>javascript/app.js"></script>
	<script src="<?= \$domain ?>javascript/utiler.js"></script>
	<script src="<?= \$domain ?>javascript/dialog/sweetalert.min.js"></script>
	<script src="<?= \$domain ?>javascript/dialog/tools.js"></script>
    <script src="<?= \$domain ?>javascript/ilu/builder.js"></script>
    <script src="<?= \$domain ?>javascript/ilu/handler.js"></script>
    <script src="<?= \$domain ?>javascript/ilu/table.js"></script>
    <script src="<?= \$domain ?>javascript/ilu/validator/validate.js" id="_validate-scripttag"></script>
    <script src="<?= \$domain ?>javascript/ilu/validator/validator.js"></script>
    <style>
        #navbarSupportedContent .nav-item {
            margin-right: 1rem;
        }
        #navbarSupportedContent .nav-link {
            padding: .7rem .5rem .5rem 0 !important;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <?php require_once(BASEDIR.'/header.php') ?>
        <div id="container">
            <div class="container">
                <nav style="--bs-breadcrumb-divider: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&quot;);" aria-label="breadcrumb" id="ibreadcrumb">
                    <span class="title">{$parameters['title']}管理</span>
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><i class="bi bi-house-fill"></i>{$parameters['title']}管理</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="./index.php">{$parameters['title']}列表</a></li>
                    </ol>
                </nav>
            </div>
            <div id="contenter">
                <div class="container">
                    <div class="search-container" id="default-search" data-tabler="default-table">
                        <div class="row">
                            {$form}
                        </div>
                        <div class="row">
                            <div class="col">
                                <a class="me-3" href="./document/design.php" target="_blank">设计文档</a>
                                <a class="me-3" href="./document/check.php" target="_blank">测试文档</a>
                                <a class="me-3" href="./document/accept.php" target="_blank">测试报告</a>
                            </div>
                            <div class="col" style="text-align:right;">
                                <button class="btn btn-secondary filter-button"><i class="bi bi-search"></i>筛选</button>
                                <a class="btn btn-primary" data-href="./create.php" data-class="middler" data-message="创建{$parameters['title']}" onclick="utiler.dialog(this);"><i class="bi bi-cloud-plus-fill"></i>创建</a>
                            </div>
                        </div>
                        <input class="tabler" name="pageSize" value="20" type="hidden">
                    </div>
                    <div class="table-container" id="default-table" data-searcher="#default-search" data-index="5172481488006022">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                            <?php
                            foreach(\$properties as \$property) {
                                ?>
                                <th class="<?= isset(\$property['th_class']) ? \$property['th_class'] : '' ?>" width="<?= isset(\$property['width']) ? \$property['width'] : 'auto' ?>">
                                    <?= \$property['title'] ?>
                                </th>
                                <?php
                            }
                            if(count(\$operators)) {
                                ?>
                                <th>操作</th>
                                <?php
                            }
                            ?>
                            </tr>
                            </thead>
                            <tbody><tr class="text-danger"><td colspan="<?= count(\$properties) + (isset(\$operators) ? 1 : 0) ?>">点击右上角`<i class="bi bi-search"></i>筛选`按钮查询数据！</td></tr></tbody>
                        </table>

                        <!-- 分页 -->
                        <div class="handler" id="page-container">
                            <div class="operater"></div>
                            <div class="page-render"></div>
                        </div>

                        <textarea id="properties" hidden>{$propertiesJson}</textarea>
                        <textarea id="operators" hidden>{$operatorsJson}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            var tableIndex = (Math.random() + '').replace('0.', '');

            window[tableIndex] = $("#default-table").table({
                url: './service/search.php', index: tableIndex, properties: JSON.parse($('#properties').val()), operators: JSON.parse($('#operators').val()),
                readyCall: true
            });

            // 筛选按钮点击事件绑定
            $("#default-search .filter-button").bind('click', function() {
                app.table(this).reload();
            });
            // 排序按钮点击事件绑定
            $("#default-table .orderby").bind('click', function() {
                var tableClass = app.table(this);
                // 数据加载中
                if(tableClass.status == 'loading') {
                    return ;
                }
                $("#default-table .orderby").removeClass('asc desc');
                if(utiler.in($(this).attr('data-by'), ['', 'desc'])) {
                    $(this).addClass('asc').attr('data-by', 'asc');
                }
                else {
                    $(this).addClass('desc').attr('data-by', 'desc');
                }
                tableClass.setOrderBy($(this).attr('data-order'), $(this).attr('data-by')).reload();
            });
        });
    </script>
</body>
</html>
str;
}

function listSearch($parameters)
{
    $columns = Database::select("show full columns from {$parameters['table']}");

    $items = [];
    $integers = [];
    foreach($columns as $column) {
        $items[] = $column['Field'];
        if(stripos($column['Type'], 'int') >= 0) {
            $integers[$column['Field']] = true;
        }
    }

    $items = Utiler::pArray($items);
    $integers = Utiler::pArray($integers);
    return <<<str
<?php
    require_once('../../../comm.php');
    require_once(BASEDIR.'/helpers/input.class.php');
    require_once(BASEDIR.'/helpers/utiler.class.php');
    require_once(BASEDIR.'/helpers/database.class.php');

    \$items = {$items};
    \$integers = {$integers};
    \$request = new Input();
    \$where = ' 1 = 1 ';
    foreach(\$items as \$item) {
        if(\$request->filled(\$item)) {
            if(isset(\$integers[\$item])) {
                \$where = "{\$where} and {\$item} = '{\$request->find(\$item)}'";
            }
            else {
                \$where = "{\$where} and {\$item} like '%{\$request->find(\$item)}%'";
            }
        }
    }
    // echo \$where;
    \$total = Database::count('{$parameters['table']}', \$where);

    \$number = 20;
    \$sql = "select * from {$parameters['table']} where ".\$where;
    // echo \$sql;
    \$offset = (\$request->find('page', 1) - 1) * \$number;
    \$items = Database::select(\$sql." order by id desc limit {\$offset}, {\$number}");

    return Utiler::epack(Utiler::Success, 'success', [
        'lister' => \$items,
        'paginator' => [
            'total' => \$total,
            'current' => \$request->find('page', 1),
            'last' => ceil(\$total / \$number)
        ]
    ]);
str;
}