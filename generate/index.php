<?php

require_once('../comm.php');
require_once('./builderCreate.php');
require_once('./builderEdit.php');
require_once('./builderList.php');
require_once('./builderDelete.php');
require_once('./builderDesignDocument.php');
require_once('./builderCheckDocument.php');
require_once('./builderAcceptDocument.php');
require_once('./builderMysql.php');

// 步骤1: 设立一个字符串，保存增加菜单项表格字符串
$menus = [
    'unified_monitoring_alerts' => [
        'table' => 'unified_monitoring_alerts',
        'title' => '统一监控告警管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'alarm_statistics' => [
        'table' => 'alarm_statistics',
        'title' => '告警统计',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'asset_configuration_analysis' => [
        'table' => 'asset_configuration_analysis',
        'title' => '资产配置分析',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'bigdata_processing_platform_management' => [
        'table' => 'bigdata_processing_platform_management',
        'title' => 'Bigdata处理平台管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'business_modeling' => [
        'table' => 'business_modeling',
        'title' => '业务建模',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'change_control' => [
        'table' => 'change_control',
        'title' => '变更控制',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'change_management' => [
        'table' => 'change_management',
        'title' => '变更管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'component_usage' => [
        'table' => 'component_usage',
        'title' => 'Hadoop、Hbase、Hive、Spark等组件使用',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'configuration_management' => [
        'table' => 'configuration_management',
        'title' => '配置项完整性和精准性管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'core_database_node_management' => [
        'table' => 'core_database_node_management',
        'title' => '核心数据库节点管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'data_harmony' => [
        'table' => 'data_harmony',
        'title' => '数据调和',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'db_monitoring' => [
        'table' => 'db_monitoring',
        'title' => '数据库监控',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'email_integration' => [
        'table' => 'email_integration',
        'title' => '短信系统集成',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'event_management' => [
        'table' => 'event_management',
        'title' => '事件管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'hardware_compute_node' => [
        'table' => 'hardware_compute_node',
        'title' => '基础硬件计算节点管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'hotel_sop_knowledge' => [
        'table' => 'hotel_sop_knowledge',
        'title' => '酒店SOP知识库和知识图谱管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'intelligent_qa_system' => [
        'table' => 'intelligent_qa_system',
        'title' => '智能问答系统建设',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'iot_infrastructure_assets' => [
        'table' => 'iot_infrastructure_assets',
        'title' => 'loT基础设施台账管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'itil_best_practices' => [
        'table' => 'itil_best_practices',
        'title' => 'ITIL最佳实践',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'knowledge_base_management' => [
        'table' => 'knowledge_base_management',
        'title' => '知识库管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'monitoring_display' => [
        'table' => 'monitoring_display',
        'title' => '监控展示',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'monitoring_visualization' => [
        'table' => 'monitoring_visualization',
        'title' => '监控可视化展示',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'network_monitoring' => [
        'table' => 'network_monitoring',
        'title' => '网络监控',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'permission_management' => [
        'table' => 'permission_management',
        'title' => '权限管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'report_design_tool' => [
        'table' => 'report_design_tool',
        'title' => '报表设计工具',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'self_service_desk' => [
        'table' => 'self_service_desk',
        'title' => '自助服务台',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'server_monitoring' => [
        'table' => 'server_monitoring',
        'title' => '服务器监控',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'service_catalog_management' => [
        'table' => 'service_catalog_management',
        'title' => '服务目录管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'service_portal_management' => [
        'table' => 'service_portal_management',
        'title' => '统一服务门户管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'service_request_management' => [
        'table' => 'service_request_management',
        'title' => '服务请求管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'sms_integration' => [
        'table' => 'sms_integration',
        'title' => '短信系统集成',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'storage_monitoring' => [
        'table' => 'storage_monitoring',
        'title' => '存储监控',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'third_party_integration' => [
        'table' => 'third_party_integration',
        'title' => '第三方系统集成接口管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'ticket_flow_analysis' => [
        'table' => 'ticket_flow_analysis',
        'title' => '工单流转分析',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'user_profile_analysis' => [
        'table' => 'user_profile_analysis',
        'title' => '用户画像分析',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'performance_monitoring' => [
        'table' => 'performance_monitoring',
        'title' => '监控性能分析',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'problem_management' => [
        'table' => 'problem_management',
        'title' => '问题管理',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
    'auto_collection' => [
        'table' => 'auto_collection',
        'title' => '自动采集',
        'pages' => ['lister', 'create', 'edit', 'delete', 'document', 'mysql'],
    ],
];

foreach($menus as $name => $item) {
    $path = BASEDIR."/manage/{$name}/";

    // die('ignore');
    $modals = [
        'lister' => [
            'page' => [
                'file' => 'index.php',
                'function' => 'listPage',
                'parameters' => [],
            ],
            'ajax' => [
                'file' => 'service/search.php',
                'function' => 'listSearch',
                'parameters' => [],
            ],
        ],
        'create' => [
            'page' => [
                'file' => 'create.php',
                'function' => 'createPage',
                'parameters' => [],
            ],
            'ajax' => [
                'file' => 'service/create.php',
                'function' => 'createAjax',
                'parameters' => [],
            ],
        ],
        'edit' => [
            'page' => [
                'file' => 'edit.php',
                'function' => 'editPage',
                'parameters' => [],
            ],
            'ajax' => [
                'file' => 'service/edit.php',
                'function' => 'editAjax',
                'parameters' => [],
            ],
        ],
        'delete' => [
            'ajax' => [
                'file' => 'service/destroy.php',
                'function' => 'deleteAjax',
                'parameters' => [],
            ],
        ],
        'document' => [
            'design' => [
                'file' => 'document/design.php',
                'function' => 'designPage',
                'parameters' => [],
            ],
            'check' => [
                'file' => 'document/check.php',
                'function' => 'checkPage',
                'parameters' => [],
            ],
            'accept' => [
                'file' => 'document/accept.php',
                'function' => 'acceptPage',
                'parameters' => [],
            ],
        ],
        'mysql' => [
            'enum' => [
                'file' => '../../generate/mysql/{table}.php',
                'function' => 'mysqlPage',
                'parameters' => [],
            ],
        ],
    ];

    echo "<br>开始创建模块：{$item['title']}<br>";
    echo "  --页面路径：{$path}<br>";
    foreach($modals as $title => $pages) {
        if(!in_array($title, $item['pages'])) {
            echo "  ----忽略创建页面：{$title}<br>";
            continue;
        }
        echo "  --开始创建页面：{$title}<br>";
        if(!is_dir($path)) {
            mkdir($path, 0777);
        }
        if(!is_dir("{$path}service")) {
            mkdir("{$path}service", 0777);
        }
        if(!is_dir("{$path}document")) {
            mkdir("{$path}document", 0777);
        }
        foreach($pages as $k => $page) {
            $page['file'] = str_replace('{table}', $item['table'], $page['file']);
            echo "    {$path}{$page['file']}<br>";
            $function = $page['function'];
            $content = $function(array_merge($item, $page['parameters'], ['folder' => $name]));
            $handler = fopen("{$path}{$page['file']}", 'w+');
            fwrite($handler, $content);
            fclose($handler);
        }
    }
}