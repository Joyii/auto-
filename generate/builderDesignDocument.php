<?php
require_once(BASEDIR.'/comm.php');
require_once(BASEDIR.'/helpers/input.class.php');
require_once(BASEDIR.'/helpers/utiler.class.php');
require_once(BASEDIR.'/helpers/database.class.php');

function designPage($parameters)
{
    $columns = Database::select("show full columns from {$parameters['table']}");

    // echo "<pre>";
    // var_export($columns);
    // echo "</pre>";
    // die;
    $form = '';
    $comments = [];
    foreach($columns as $column) {
        if(!in_array($column['Field'], ['id', 'created_at', 'updated_at'])) {
            $comments[] = $column['Comment'] ? $column['Comment'] : $column['Field'];
        }
        $form = <<<form
{$form}
        <tr>
            <td>{$column['Field']}</td>
            <td>{$column['Type']}</td>
            <td>{$column['Comment']}</td>
        </tr>
form;
    }
    $comments = implode('、', $comments);

    return <<<str
<?php
    require_once('../../../comm.php');
    require_once(BASEDIR.'/helpers/input.class.php');
    require_once(BASEDIR.'/helpers/utiler.class.php');
    require_once(BASEDIR.'/helpers/database.class.php');
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{$parameters['title']}系统设计文档</title>
    <style>
        * {
            font-size: 16px;
        }
        h1,h2,h3,h4,h5 {
            margin-bottom: 16px;
        }
        table {
            border: 1px solid #ddd;
            width: 100%;
        }
        table td, table th {
            border: 1px solid #ddd;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <div id="contenter">
            <div class="container" style="padding:16px;border-radius:8px;line-height:2;">
                <h1>{$parameters['title']}系统设计文档</h1>
                <h2>1. 系统概述</h2>
                <p>“{$parameters['title']}系统”旨在为{$parameters['title']}提供一个管理平台。通过这个平台，用户可以添加新的{$parameters['title']}，记录{$parameters['title']}的{$comments}等。系统提供了添加、查看、编辑和删除{$parameters['title']}信息的功能。</p>

                <h2>2. 功能需求</h2>
                <h3>2.1 主要功能</h3>
                <ul>
                    <li><strong>新增{$parameters['title']}：</strong> 用户可以通过表单输入{$parameters['title']}的各项信息，包括{$comments}等，以添加新的{$parameters['title']}。</li>
                    <li><strong>查看{$parameters['title']}列表：</strong> 用户可以查看已新增的所有{$parameters['title']}的列表。</li>
                    <li><strong>编辑{$parameters['title']}信息：</strong> 用户可以修改已新增{$parameters['title']}的信息。</li>
                    <li><strong>删除{$parameters['title']}信息：</strong> 用户可以删除已新增的{$parameters['title']}信息。</li>
                </ul>
                <h3>2.2 用户界面</h3>
                <ul>
                    <li><strong>新增表单：</strong> 提供一个表单来输入{$parameters['title']}的相关信息。</li>
                    <li><strong>信息列表：</strong> 显示所有已新增{$parameters['title']}的列表，包括基本信息和编辑、删除选项。</li>
                </ul>
                <h2>3. 数据库设计</h2>
                <h3>3.1 数据库表结构</h3>
                <h4><code>{$parameters['table']}</code> 表</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                    <th>字段名</th>
                    <th>数据类型</th>
                    <th>描述</th>
                    </tr>
                    <thead>
                    <tbody>{$form}</tbody>
                </table>
                <h3>3.2 数据库操作</h3>
                <ul>
                    <li><strong>插入操作：</strong> 将新的{$parameters['title']}信息插入到robots表中。</li>
                    <li><strong>查询操作：</strong> 从robots表中检索{$parameters['title']}信息。</li>
                    <li><strong>更新操作：</strong> 更新robots表中的{$parameters['title']}信息。</li>
                    <li><strong>删除操作：</strong> 从robots表中删除{$parameters['title']}信息。</li>
                </ul>
                <h2>4. 前端设计</h2>
                <p><strong>前端主要包括两个部分：</strong>新增表单和{$parameters['title']}信息列表。</p>

                <h3>4.1 新增表单</h3>
                <ul>
                    <li>包括用于输入{$parameters['title']}信息的字段，如{$comments}等。</li>
                    <li>提交按钮用于将表单数据发送到服务器。</li>
                </ul>
                <h3>4.2 {$parameters['title']}信息列表</h3>
                <ul>
                    <li>显示所有新增{$parameters['title']}的列表。</li>
                    <li>每条记录包括编辑和删除按钮，用于执行相应操作。</li>
                </ul>
                <h2>5. 后端逻辑</h2>
                <h3>5.1 处理新增</h3>
                <ul>
                    <li>接收来自新增表单的数据。</li>
                    <li>验证数据的有效性。</li>
                    <li>将数据插入到数据库中。</li>
                </ul>
                <h3>5.2 管理{$parameters['title']}信息</h3>
                <ul>
                    <li>提供{$parameters['title']}信息的列表显示。</li>
                    <li>实现{$parameters['title']}信息的编辑和删除功能。</li>
                </ul>
                <h2>6. 安全和维护</h2>
                <ul>
                    <li>保证数据的有效性和安全性，防止SQL注入攻击。</li>
                    <li>提供错误处理和日志记录机制。</li>
                </ul>
                <h2>7. 结论</h2>
                <p>本文档提供了“{$parameters['title']}系统”的详细设计，包括系统功能、数据库设计、前端和后端逻辑等。此系统旨在为用户提供一个简单且高效的{$parameters['title']}新增和管理平台。<p>
            </div>
        </div>
    </div>
</body>
</html>
str;
}