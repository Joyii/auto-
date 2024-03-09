<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>机器人任务</title>
    <script src="javascript/app.js"></script>
	<script src="javascript/utiler.js"></script>
	<script src="javascript/dialog/sweetalert.min.js"></script>
	<script src="javascript/dialog/tools.js"></script>
	<link href="style/app.css" rel="stylesheet">
	<link href="style/site.css" rel="stylesheet">
    <script src="javascript/ilu/validator/validate.js" id="_validate-scripttag"></script>
    <script src="javascript/ilu/validator/validator.js"></script>
</head>
<body>
    <div id="wrapper">
        <?php
            require_once('./comm.php');
            require_once(BASEDIR.'/helpers/database.class.php');
            Auth::check();
            $row = Database::first("SELECT * FROM `wit_cps_company` WHERE `cmp_id` = ".Auth::id(), 'auth');

            require_once('./header.php');
            ?>
<div id="container">
    <div class="container">
        <nav style="--bs-breadcrumb-divider: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&quot;);" aria-label="breadcrumb" id="ibreadcrumb">
            <span class="title">资料修改</span>
            <ol class="breadcrumb justify-content-end">
                <li class="breadcrumb-item"><a href="./index.php"><i class="bi bi-house-fill"></i>首页</a></li>
                <li class="breadcrumb-item" aria-current="page">资料修改</li>
            </ol>
        </nav>
    </div>
    <div id="contenter">
        <div class="container">
            <form id="model-form" style="max-width:380px;margin:auto;" action="./sl_cmp_update.php" method="post">

                <div class="mb-3 checker">
                    <label class="form-label">邮箱</label>
                    <input class="form-control" name="cmp_email" type="text" value="<?=$row['cmp_email']?>">
                </div>
                <div class="mb-3 checker">
                    <label class="form-label">电话</label>
                    <input class="form-control" name="cmp_tel" type="text" value="<?=$row['cmp_tel']?>">
                </div>
                <div class="mb-3 checker">
                    <label class="form-label">密码</label>
                    <input class="form-control" name="cmp_pwd" type="text" value="<?=$row['cmp_pwd']?>">
                </div>
                <div class="mb-3 checker">
                    <label class="form-label">全称</label>
                    <input class="form-control" name="cmp_name" type="text" value="<?=$row['cmp_name']?>">
                </div>
                <div class="mb-3 checker">
                    <label class="form-label">地址</label>
                    <input class="form-control" name="cmp_add" type="text" value="<?=$row['cmp_add']?>">
                </div>
                <div class="mb-3 checker">
                    <label class="form-label">联系人</label>
                    <input class="form-control" name="cmp_pername" type="text" value="<?=$row['cmp_pername']?>">
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary " id="save-model" data-form="#model-form">保存</button>
                    <input name="cmp_type" value="<?=$per_type?>" type="hidden">
                    <textarea id="model-rule" data-form="#model-form" hidden>
                        <?php
                            echo json_encode([
                                'rules' => [
                                    'cmp_email' => 'required',
                                    'cmp_tel' => 'required',
                                    'cmp_name' => 'required',
                                ],
                                'messages' => [
                                    'cmp_email.required' => '邮箱不能为空',
                                    'cmp_tel.required' => '手机号不能为空',
                                    'cmp_name.required' => '全称不能为空',
                                ]
                            ], JSON_UNESCAPED_UNICODE);
                        ?>
                    </textarea>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function() {
        // 数据校验
        (new validator).init({ ruleDom: '#model-rule' });
    });
</script>

</div>
</body>
</html>