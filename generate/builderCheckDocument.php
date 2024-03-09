<?php
require_once(BASEDIR.'/comm.php');
require_once(BASEDIR.'/helpers/input.class.php');
require_once(BASEDIR.'/helpers/utiler.class.php');
require_once(BASEDIR.'/helpers/database.class.php');

function checkPage($parameters)
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
    <title>{$parameters['title']}系统验收测试文档</title>
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
                <h1>{$parameters['title']}系统验收测试文档</h1>
                <h2>1. 目的</h2>
                <p>本文档旨在提供一系列测试用例，用于验证“{$parameters['title']}系统”是否满足所有设计要求和功能规范。</p>
                <h2>2. 测试环境</h2>
                <ul>
                    <li>Web服务器： 应部署有最新版本的PHP和MySQL。</li>
                    <li>测试设备： 应包括不同操作系统和浏览器的电脑和移动设备。</li>
                </ul>
                <h2>3. 测试用例</h2>
                <h3>3.1 功能测试</h3>
                <h4>3.1.1 添加{$parameters['title']}</h4>
                <ul>
                    <li><strong>目的：</strong> 测试用户能否成功添加{$parameters['title']}。</li>
                    <li>
                        <strong>步骤：</strong>
                        <ol>
                            <li>打开添加表单。</li>
                            <li>输入{$comments}等信息并提交。</li>
                            <li>检查是否出现成功消息。</li>
                            <li>在数据库中验证新记录的存在。</li>
                        </ol>
                    </li>
                </ul>
                <h4>3.1.2 查看{$parameters['title']}列表</h4>
                <ul>
                    <li><strong>目的：</strong> 测试用户能否查看所有已添加{$parameters['title']}的列表。</li>
                    <li>
                        <strong>步骤：</strong>
                        <ol>
                            <li>访问{$parameters['title']}信息列表页面。</li>
                            <li>检查列表是否包含所有已添加{$parameters['title']}的信息。</li>
                        </ol>
                    </li>
                </ul>
                <h4>3.1.3 编辑{$parameters['title']}信息</h4>
                <ul>
                    <li><strong>目的：</strong> 测试用户能否编辑已添加{$parameters['title']}的信息。</li>
                    <li>
                        <strong>步骤：</strong>
                        <ol>
                            <li>在{$parameters['title']}列表中选择一个{$parameters['title']}并点击编辑。</li>
                            <li>更改{$comments}等信息并提交。</li>
                            <li>检查是否出现成功消息。</li>
                            <li>验证数据库中信息是否更新。</li>
                        </ol>
                    </li>
                </ul>
                <h4>3.1.4 删除{$parameters['title']}信息</h4>
                <ul>
                    <li><strong>目的：</strong> 测试用户能否删除已添加{$parameters['title']}的信息。</li>
                    <li>
                        <strong>步骤：</strong>
                        <ol>
                            <li>在{$parameters['title']}列表中选择一个{$parameters['title']}并点击删除。</li>
                            <li>确认删除操作。</li>
                            <li>检查是否出现成功消息。</li>
                            <li>验证数据库中该记录是否被删除。</li>
                        </ol>
                    </li>
                </ul>
                <h3>3.2 性能测试</h3>
                <ul>
                    <li><strong>目的：</strong> 测试系统在高负载下的响应时间和稳定性。</li>
                    <li>
                        <strong>步骤：</strong>
                        <ol>
                            <li>同时从多个设备提交数据。</li>
                            <li>检测系统的响应时间和稳定性。</li>
                        </ol>
                    </li>
                </ul>
                <h3>3.3 安全测试</h3>
                <ul>
                    <li><strong>目的：</strong> 验证系统的安全性，特别是防止SQL注入和跨站脚本攻击。</li>
                    <li>
                        <strong>步骤：</strong>
                        <ol>
                            <li>尝试在输入字段中输入SQL代码或脚本代码。</li>
                            <li>检查系统是否能有效阻止这类攻击。</li>
                        </ol>
                    </li>
                </ul>
                <h3>3.4 用户界面测试</h3>
                <ul>
                    <li><strong>目的：</strong> 确保用户界面友好且易于使用。</li>
                    <li>
                        <strong>步骤：</strong>
                        <ol>
                            <li>检查所有页面的布局、颜色和字体的一致性。</li>
                            <li>确认所有链接和按钮都能正常工作。</li>
                            <li>在不同设备和浏览器上测试页面的响应性。</li>
                        </ol>
                    </li>
                </ul>
                <h2>4. 测试结果记录</h2>
                <ul>
                    <li><strong>记录格式：</strong> 对于每个测试用例，记录测试日期、执行者、测试结果（通过/失败）和任何发现的问题。</li>
                </ul>
                <h2>5. 问题报告和解决</h2>
                <ul>
                    <li><strong>问题跟踪：</strong> 对于发现的每个问题，应创建一个详细的问题报告。</li>
                    <li><strong>解决方案：</strong> 对于每个问题，开发团队应提出并实施一个解决方案。</li>
                    <li><strong>再测试：</strong> 解决问题后，应重新进行测试以确认问题已被解决。</li>
                </ul>
                <h2>6. 总结</h2>
                <p></p>
            </div>
        </div>
    </div>
</body>
</html>
str;
}