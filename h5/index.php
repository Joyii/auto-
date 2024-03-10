<?php
require_once('../comm.php');
require_once(BASEDIR.'/helpers/input.class.php');
require_once(BASEDIR.'/helpers/utiler.class.php');
require_once(BASEDIR.'/helpers/database.class.php');


$sql = "SELECT 
    p.id,
    p.name,
    p.desc,
    IF(COUNT(pp.param_name) = 0, NULL, GROUP_CONCAT(pp.param_name)) AS params " .
    "FROM ai_prompt p " .
    "LEFT JOIN ai_promot_params pp ON p.id = pp.prompt_id " .
    "GROUP BY p.id, p.name, p.desc";

// 调用 select 方法查询数据
$properties = Database::select($sql, null, 'default');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Left-Middle-Right Layout</title>
    <link href="<?= $domain ?>style/app.css" rel="stylesheet">
    <link href="<?= $domain ?>style/site.css" rel="stylesheet">
    <script src="<?= $domain ?>javascript/app.js"></script>
    <script src="<?= $domain ?>javascript/utiler.js"></script>
    <script src="<?= $domain ?>javascript/dialog/sweetalert.min.js"></script>
    <script src="<?= $domain ?>javascript/dialog/tools.js"></script>
    <script src="<?= $domain ?>javascript/ilu/builder.js"></script>
    <script src="<?= $domain ?>javascript/ilu/handler.js"></script>
    <script src="<?= $domain ?>javascript/ilu/table.js"></script>
    <script src="<?= $domain ?>javascript/ilu/validator/validate.js" id="_validate-scripttag"></script>
    <script src="<?= $domain ?>javascript/ilu/validator/validator.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #divMain {
            width: 98%;
            border: 1px solid #ccc;
        }

        #divPrompt, #divResult {
            border: 1px solid #ccc;
        }

        #divPrompt {
            display: flex;
            align-items: flex-start; /* 将子元素垂直对齐方式设置为顶部对齐 */
        }
        #divFileInput {
            width: 100px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        #divPromptMain {
            flex: 1; /* 设置 divPromptMain 自动填充剩余空间 */
            display: flex;
            justify-content: space-between; /* 左右对齐 */
        }

        #divPromptMain {
            display: flex;
        }
        #divLPromptList {
            width: 400px;
            border: 1px solid #ccc;
            overflow-x: auto;
            overflow-y: auto;
        }
        #tablePrompt {
            width: 100%;
            border-collapse: collapse;
        }
        #tablePrompt td {
            padding: 8px;
            border-bottom: 1px solid #ccc;
        }
        #divPromptInput {
            flex: 1;
            border: 1px solid #ccc;
            padding: 10px;
        }
        #textPrompt {
            width: 100%;
            height: 100%;
        }
        #btnCopy {
            margin-top: 5px;
        }


        #divResult {
            border: 1px solid #ccc;
            margin-top: 10px;
        }
        .tab-labels {
            display: flex;
        }
        .tab-label {
            width:100px;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            border: 1px solid #cccccc;
            background-color: #f0f0f0;
            transition: background-color 0.3s;
        }
        .tab-label.active {
            background-color: pink; /* 选中状态的背景色 */
        }
        .tab-content {
            display: none;
            padding: 10px;
        }
        .tab-content.active {
            display: block;
        }


        .icon-right img {
            width: 42px; /* 容器宽度 */
        }

        .textSql{
            width: 100%;
            height:300px;
        }
        #drop-zone {
            border: 2px dashed #ccc;
            height: 200px;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            color: #888;
        }
    </style>
</head>
<body>

<div id="divMain">
    <div id="divPrompt">
        <div id="drop-zone">
            请拖拽文件到这里
        </div>
        <div id="divPromptMain">
            <div id="divLPromptList">

                <div class="table-container" id="default-table" data-searcher="#default-search" >
                    <table id="tablePrompt" class="table table-bordered table-striped">
                        <thead><th>id</th><th>名称</th><th>内容</th><th>操作</th>
                        </thead>
                        <?php
                        foreach($properties as $property) {
                            ?>

                            <tr>
                                <td><?= $property['id'] ?></td>
                                <td><?= $property['name'] ?></td>
                                <td><?= $property['desc'] ?></td>
                                <td class="icon-right" onclick="generatePrompt(<?= $property['id'] ?>)"><img src="h5/images/right.png" />
                                    <input type="hidden" value="<?= $property['params'] ?>" id="hidden_params_<?= $property['id'] ?>">
                                    <input type="hidden" value="<?= $property['desc'] ?>" id="hidden_desc_<?= $property['id'] ?>">
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                    </table>


                </div>

            </div>
            <div id="divPromptInput">
                <div style="width: 100%;height: 85%">
                <textarea id="textPrompt"></textarea></div>
                <div style="width: 100%;">
                <label id="labelPromptInfo" style="width: 85%;margin-left: 20px;">提示词处理信息</label>
                <button id="btnCopy" onclick="copyData()">复制到AI</button></div>
            </div>
        </div>
    </div>


    <div id="divResult">
        <div class="tab-labels">
            <div class="tab-label active" onclick="showTab('divFileProcess')">文件处理</div>
            <div class="tab-label" onclick="showTab('divSqlProcess')">sql执行</div>
            <div></div>
        </div>
        <div class="tab-content active" id="divFileProcess">

            <div id="preview"></div>


        </div>
        <div class="tab-content" id="divSqlProcess">
            <div><textarea id="textSql" class="textSql" placeholder="请从AI界面将生成的sql复制到此页面"></textarea>
            </div>
            <div>
                <label id="labelSqlInfo" style="width: 90%">sql处理信息</label>
                <button id="btnExecSql" onclick="execSql()">执行</button>
            </div>
        </div>
    </div>


</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script>
    let dropZone = document.getElementById('drop-zone');
    let previewDiv = document.getElementById('preview');

    dropZone.addEventListener('dragover', handleDragOver, false);
    dropZone.addEventListener('drop', handleFileSelect, false);

    function handleDragOver(e) {
        e.stopPropagation();
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
    }

    function handleFileSelect(e) {
        e.stopPropagation();
        e.preventDefault();

        let files = e.dataTransfer.files;
        if (files.length > 0) {
            let file = files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                let data = e.target.result;
                let workbook = XLSX.read(data, { type: 'binary' });
                let sheetName = workbook.SheetNames[0];
                let worksheet = workbook.Sheets[sheetName];
                let html = XLSX.utils.sheet_to_html(worksheet);
                previewDiv.innerHTML = html;
            }

            reader.readAsBinaryString(file);
        }
    }
</script>
<script>

    function execSql() {

        let textSql = $("#textSql").val();
        if(textSql === ""){
            alert('没有需要执行的 sql');
            return false;
        }

        let labelSqlInfo = $('#labelSqlInfo');

        // 调用确认对话框
        if( confirm("确定要执行 sql 操作吗？")) {
            $.ajax({
                type: "POST",
                url: "h5/service_prompt.php",
                data: { sql: textSql ,action: 'create_table'},
                dataType: "json",
                success: function(response) {
                    if(response['message'])
                        labelSqlInfo.text(response['message'] );
                    else if(response['error'])
                        labelSqlInfo.text(response['error'] );
                    else
                        labelSqlInfo.text('未知信息');
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

    }

    //将textPrompt的值保存到数据表，包括prompt_id, prompt_value, create_time
    function copyData() {
        var promptText = $("#textPrompt").val();

        let labelPromptInfo = $('#labelPromptInfo');

        $.ajax({
            type: "POST",
            url: "h5/service_prompt.php",
            data: { prompt_id: current_id, prompt_text: promptText ,action: 'insert'},
            dataType: "json",
            success: function(response) {
                labelPromptInfo.text(response['message'] + ",复制成功");
            },
            error: function(xhr, status, error) {
                labelPromptInfo.text(error['message']);
            }
        });

        //复制到剪贴板
        copyToClipboard(promptText);
    }

    function copyToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }

    function showTab(tabId) {
        let tabs = document.querySelectorAll('.tab-content');
        let labels = document.querySelectorAll('.tab-label');

        let table_index = 0;

        tabs.forEach(function(tab, index) {
            if (tab.id === tabId) {
                tab.classList.add('active');
                table_index = index;
            } else {
                tab.classList.remove('active');
            }
        });

        labels.forEach(function(label, index) {
            if (table_index === index) {
                label.classList.add('active');
            } else {
                label.classList.remove('active');
            }
        });
    }

    let current_id = 0;
    function generatePrompt(id) {
        current_id= id;//记录当前id
        // 从隐藏的输入框中获取参数字符串和标题值
        let paramsStr = $('#hidden_params_' + id ).val();
        let desc = $('#hidden_desc_' + id ).val();

        // 获取表格元素
        // let table = $('#tableFileProcess');
        let table = $('#preview table');
        if(!table){
            alert('请先上传excel文件，并保证excel格式正确');
            return;
        }


        // 将参数字符串以逗号分隔为数组
        let params = paramsStr.split(',');

        // 遍历参数数组，替换标题中的参数值
        params.forEach(function(param, index) {
            // 获取表格的第 index 行内容

            let rowContent = '';
            table.find('tr').eq(index).find('td').each(function() {
                rowContent += " " + $(this).text(); // 获取 <td> 元素的文本内容并拼接
            });

            if (param === "param_data") {

                rowContent += "\n"; // 换行
                // 获取当前行的索引
                let currentIndex = index+1;

                // 继续循环 table 后续的表格
                while (currentIndex < table.find('tr').length) {
                    // 遍历下一行的每个 <td> 元素，将内容拼接到 rowContent 中并换行
                    table.find('tr').eq(currentIndex).find('td').each(function() {
                        rowContent += " " + $(this).text(); // 获取 <td> 元素的文本内容并拼接
                    });
                    rowContent += "\n"; // 换行

                    currentIndex++; // 更新索引以继续下一行
                }

                desc = desc.replace(new RegExp('{{param_data}}', 'g'), rowContent);
            }
            else{
                desc = desc.replace(new RegExp('{{param_' + (index+1) + '}}', 'g'), rowContent);
            }

        });

        // 返回替换后的标题值
        $('#textPrompt').text(desc);
    }

</script>

</body>
</html>

