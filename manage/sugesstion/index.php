<?php
    require_once('../../comm.php');
    require_once(BASEDIR.'/helpers/input.class.php');
    require_once(BASEDIR.'/helpers/utiler.class.php');
    require_once(BASEDIR.'/helpers/database.class.php');

    $request = new Input();
    $properties = [
  0 => [
    'title' => '主键，自动递增',
    'name' => 'id',
  ],
  1 => [
    'title' => '监控项名称',
    'name' => 'monitoring_item',
  ],
  2 => [
    'title' => '网段',
    'name' => 'subnet',
    'format' => 'utiler.substring',
    'args' => 32,
  ],
  3 => [
    'title' => '交换机',
    'name' => 'switch',
    'format' => 'utiler.substring',
    'args' => 32,
  ],
  4 => [
    'title' => '端口',
    'name' => 'port',
    'format' => 'utiler.substring',
    'args' => 32,
  ],
  5 => [
    'title' => 'status',
    'name' => 'status',
  ],
  6 => [
    'title' => '最后检查时间',
    'name' => 'last_checked',
    'format' => 'utiler.datetime',
  ],
  7 => [
    'title' => '创建时间',
    'name' => 'created_at',
    'format' => 'utiler.datetime',
  ],
];
    $operators = [
  0 => [
    'title' => '修改',
    'handler' => 'dialog',
    'icon' => 'pencil-square',
    'properties' => [
      'data-href' => './edit.php?id={id}',
      'data-class' => 'middler',
      'data-message' => '修改网络监控信息',
    ],
  ],
  1 => [
    'title' => '删除',
    'handler' => 'request',
    'icon' => 'trash-fill',
    'class' => 'btn-danger',
    'properties' => [
      'data-href' => './service/destroy.php?id={id}',
      'data-keep' => 'false',
      'data-message' => '确定要删除网络监控吗？',
    ],
  ],
];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>网络监控列表</title>
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
        <?php require_once('../header.php') ?>
        <div id="container">
            <div class="container">
                <nav style="--bs-breadcrumb-divider: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&quot;);" aria-label="breadcrumb" id="ibreadcrumb">
                    <span class="title">网络监控管理</span>
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><i class="bi bi-house-fill"></i>网络监控管理</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="./index.php">网络监控列表</a></li>
                    </ol>
                </nav>
            </div>
            <div id="contenter">
                <div class="container">
                    <div class="search-container" id="default-search" data-tabler="default-table">
                        <div class="row">
                            
                    <div class="mb-3 col">
                        <label class="form-label">主键，自动递增</label>
                        <input class="form-control tabler" name="id" value="" type="text" placeholder="id">
                    </div>
                    <div class='mb-3 col'>
                        <label class='form-label'>监控项名称</label>
                        <select class='form-select tabler' name="monitoring_item">
                            <option value="">--</option>
                            <option value='服务器负载'>服务器负载</option><option value='网络延迟'>网络延迟</option><option value='防火墙状态'>防火墙状态</option><option value='入侵检测'>入侵检测</option><option value='网络流量异常'>网络流量异常</option><option value='路由器状态'>路由器状态</option><option value='DNS响应时间'>DNS响应时间</option><option value='入侵日志'>入侵日志</option><option value='网络设备温度'>网络设备温度</option><option value='防火墙日志'>防火墙日志</option><option value='网络连接数'>网络连接数</option><option value='VPN状态'>VPN状态</option><option value='入侵尝试'>入侵尝试</option><option value='网络拥堵'>网络拥堵</option><option value='蜜罐状态'>蜜罐状态</option><option value='网络异常流量'>网络异常流量</option><option value='DDoS攻击'>DDoS攻击</option><option value='网络设备故障'>网络设备故障</option><option value='带宽利用率'>带宽利用率</option><option value='流量突增'>流量突增</option>
                        </select>
                    </div>
                    <div class='mb-3 col'>
                        <label class='form-label'>status</label>
                        <select class='form-select tabler' name="status">
                            <option value="">--</option>
                            <option value='正常'>正常</option><option value='警告'>警告</option><option value='严重'>严重</option>
                        </select>
                    </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <a class="me-3" href="./document/design.php" target="_blank">设计文档</a>
                                <a class="me-3" href="./document/check.php" target="_blank">测试文档</a>
                                <a class="me-3" href="./document/accept.php" target="_blank">测试报告</a>
                            </div>
                            <div class="col" style="text-align:right;">
                                <button class="btn btn-secondary filter-button"><i class="bi bi-search"></i>筛选</button>
                                <a class="btn btn-primary" data-href="./create.php" data-class="middler" data-message="创建网络监控" onclick="utiler.dialog(this);"><i class="bi bi-cloud-plus-fill"></i>创建</a>
                            </div>
                        </div>
                        <input class="tabler" name="pageSize" value="20" type="hidden">
                    </div>
                    <div class="table-container" id="default-table" data-searcher="#default-search" data-index="5172481488006022">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                            <?php
                            foreach($properties as $property) {
                                ?>
                                <th class="<?= isset($property['th_class']) ? $property['th_class'] : '' ?>" width="<?= isset($property['width']) ? $property['width'] : 'auto' ?>">
                                    <?= $property['title'] ?>
                                </th>
                                <?php
                            }
                            if(count($operators)) {
                                ?>
                                <th>操作</th>
                                <?php
                            }
                            ?>
                            </tr>
                            </thead>
                            <tbody><tr class="text-danger"><td colspan="<?= count($properties) + (isset($operators) ? 1 : 0) ?>">点击右上角`<i class="bi bi-search"></i>筛选`按钮查询数据！</td></tr></tbody>
                        </table>

                        <!-- 分页 -->
                        <div class="handler" id="page-container">
                            <div class="operater"></div>
                            <div class="page-render"></div>
                        </div>

                        <textarea id="properties" hidden>[{"title":"\u4e3b\u952e\uff0c\u81ea\u52a8\u9012\u589e","name":"id"},{"title":"\u76d1\u63a7\u9879\u540d\u79f0","name":"monitoring_item"},{"title":"\u7f51\u6bb5","name":"subnet","format":"utiler.substring","args":32},{"title":"\u4ea4\u6362\u673a","name":"switch","format":"utiler.substring","args":32},{"title":"\u7aef\u53e3","name":"port","format":"utiler.substring","args":32},{"title":"status","name":"status"},{"title":"\u6700\u540e\u68c0\u67e5\u65f6\u95f4","name":"last_checked","format":"utiler.datetime"},{"title":"\u521b\u5efa\u65f6\u95f4","name":"created_at","format":"utiler.datetime"}]</textarea>
                        <textarea id="operators" hidden>[{"title":"\u4fee\u6539","handler":"dialog","icon":"pencil-square","properties":{"data-href":".\/edit.php?id={id}","data-class":"middler","data-message":"\u4fee\u6539\u7f51\u7edc\u76d1\u63a7\u4fe1\u606f"}},{"title":"\u5220\u9664","handler":"request","icon":"trash-fill","class":"btn-danger","properties":{"data-href":".\/service\/destroy.php?id={id}","data-keep":"false","data-message":"\u786e\u5b9a\u8981\u5220\u9664\u7f51\u7edc\u76d1\u63a7\u5417\uff1f"}}]</textarea>
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