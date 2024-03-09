<?php

set_time_limit(3600);
require_once('../comm.php');

// 步骤1: 设立一个字符串，保存增加菜单项表格字符串
$mysqls = [
    'rb_roboter' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['hotel_id'] = rand(1, 426);
        //     $params['robot_version'] = "V1." . rand(0, 3); // 随机生成机器人版本
        //     $params['hardware_info'] = "硬件信息" . rand(1, 5); // 随机生成硬件信息
        //     $params['ros_version'] = "ROS" . rand(1, 3) . "." . rand(0, 9); // 随机生成ROS版本
        //     $params['stm32_version'] = "STM32" . rand(1, 3) . "." . rand(0, 9); // 随机生成STM32固件版本
        //     return $params;
        // }
    ],
    'rb_alarm_logger' => [
        // 'count' => 102,
        // 'function' => function($i, $enums) {
        // $errors = array(
        //     array(
        //         "alarm_type" => "硬件故障",
        //         "severity" => "高",
        //         "description" => "电机故障",
        //     ),
        //     array(
        //         "alarm_type" => "硬件故障",
        //         "severity" => "中",
        //         "description" => "传感器故障",
        //     ),
        //     array(
        //         "alarm_type" => "硬件故障",
        //         "severity" => "低",
        //         "description" => "电池问题",
        //     ),
        //     array(
        //         "alarm_type" => "硬件故障",
        //         "severity" => "中",
        //         "description" => "连接问题",
        //     ),
        //     array(
        //         "alarm_type" => "软件故障",
        //         "severity" => "高",
        //         "description" => "程序错误",
        //     ),
        //     array(
        //         "alarm_type" => "软件故障",
        //         "severity" => "中",
        //         "description" => "死锁",
        //     ),
        //     array(
        //         "alarm_type" => "软件故障",
        //         "severity" => "低",
        //         "description" => "内存问题",
        //     ),
        //     array(
        //         "alarm_type" => "安全问题",
        //         "severity" => "高",
        //         "description" => "防碰撞警报",
        //     ),
        //     array(
        //         "alarm_type" => "安全问题",
        //         "severity" => "高",
        //         "description" => "安全急停",
        //     ),
        //     array(
        //         "alarm_type" => "通信问题",
        //         "severity" => "中",
        //         "description" => "通信中断",
        //     ),
        //     array(
        //         "alarm_type" => "通信问题",
        //         "severity" => "中",
        //         "description" => "通信超时",
        //     ),
        //     array(
        //         "alarm_type" => "温度和环境问题",
        //         "severity" => "低",
        //         "description" => "温度警报",
        //     ),
        //     array(
        //         "alarm_type" => "温度和环境问题",
        //         "severity" => "中",
        //         "description" => "环境问题",
        //     ),
        //     array(
        //         "alarm_type" => "机械故障",
        //         "severity" => "高",
        //         "description" => "机械损坏",
        //     ),
        //     array(
        //         "alarm_type" => "机械故障",
        //         "severity" => "中",
        //         "description" => "姿态问题",
        //     ),
        //     array(
        //         "alarm_type" => "电力问题",
        //         "severity" => "低",
        //         "description" => "电源故障",
        //     ),
        // );
        //     $error = randEnum($errors);
        //     $params['roboter_id'] = rand(1, 1002); // 随机生成机器人编号
        //     $params['alarm_time'] = date("Y-m-d H:i:s"); // 当前时间作为报警时间
        //     $params['alarm_type'] = $error['alarm_type']; // 示例报警类型
        //     $params['severity'] = $error['severity']; // 随机生成严重程度
        //     $params['description'] = $error['description']; // 示例故障描述
        //     $params['action_taken'] = ["保修", '自检修复', '升级修复'][rand(0, 2)]; // 示例采取的措施
        //     $params['resolved'] = rand(0, 1) === 1 ? "是" : "否"; // 随机生成是否已解决
        //     $params['resolution_time'] = $params['resolved'] === "是" ? date("Y-m-d H:i:s") : null; // 解决时间为当前时间或空
        //     return $params;
        // },
    ],
    'rb_app_setting' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = $i; // 随机生成机器人编号
        //     $params['volume_setting'] =  rand(1, 100);
        //     return $params;
        // },
    ],
    'rb_tasker' => [
        // 'count' => 60931,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002);
        //     $r = rand(1, 25);
        //     $params['end_pointer'] = rand(0, 1) ? '大厅' : '房间'.rand(1, 25).($r > 9 ? $r : '0'.$r);
        //     $params['start_pointer'] = "前台"; // 构造出发地址
        //     return $params;
        // },
    ],
    'rb_authentication' => [
        // 'count' => 104,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002); // 随机生成机器人编号
        //     $params['keyword'] = substr(md5($i), 0, rand(12, 32)).'=';
        //     $params['rsa_public'] =  'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqPzBkkzB4pz6hLOKbfD6oYenQdQxo6Gr39BIDtI3E/H3ZrMOKP2bZ5DFgkOPbBcnMrXUqvyZPvSOadYFMTy8HJFEfsm/vpIuT8FzJfu6mhDS3etXodNIoJ/W4T656usiZS0VhGC1WEl6QVqDwcFkbhpoCWZEtpACUsn9z6NgxcbX8jRIKCPzAoYz8CVCCOceT3JMTC5MINC/fGZhu0MJv5BLZwgWVnnaHZMDUhoO5hDeTq0UbtHf4Vg1NEYiBbWv1/PiLu1Mga0I3VGrLg0G/4n4F46NqmIg0fG7PLoxwMesGTb7T0mzd5ruK3IuOSyie/T1QvJ0h1XyfrsleCDUEwIDAQAB';
        //     $params['rsa_private'] = '';
        //     return $params;
        // },
    ],
    'rb_version' => [
        // 'count' => 1245,
        // 'function' => function($i, $enums) {
        //     $models = ['ROS1.9', 'ROS3.3', 'ROS3.1', 'ROS2.9', 'ROS3.8', 'ROS1.0', 'ROS3.9', 'ROS2.7', 'ROS1.1', 'ROS2.1', 'ROS1.3', 'ROS2.4', 'ROS3.4', 'ROS1.5', 'ROS2.6', 'ROS2.2', 'ROS3.5', 'ROS2.0', 'ROS3.2', 'ROS2.5', 'ROS2.3', 'ROS1.4', 'ROS1.2', 'ROS3.7', 'ROS1.7', 'ROS3.6', 'ROS3.0', 'ROS2.8', 'ROS1.6', 'ROS1.8'];
        //     $params['version_number'] = 'v'.rand(2, 5).'.'.rand(0, 9).'.'.rand(0, 9); // 随机选择版本号
        //     $params['description'] = "版本 {$params['version_number']} 更新描述"; // 生成更新描述
        //     $params['compatible_models'] = randEnum($models); // 随机选择并生成设备型号
        //     $params['link'] = "https://example.com/upgrade/{$params['version_number']}.zip"; // 生成升级包地址
        //     return $params;
        // },
    ],
    'rb_upgrade' => [
        // 'count' => 8954,
        // 'function' => function($i, $enums) {
        //     // 设备类型列表
        //     $device_types = ['电池', '马达', '摄像头', '激光雷达', '红外传感器', '超声波传感器'];
        //     $params['roboter_id'] = rand(1, 1002); // 随机生成机器人编号
        //     $params['old_version'] = "1.0"; // 设定当前版本号
        //     $params['device_id'] = $device_types[array_rand($device_types)]; // 随机选择设备类型
        //     return $params;
        // },
    ],
    'rb_roboter_configure' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = $i; // 随机选择机器人编号
        //     $params['load_capacity'] = rand(100, 1000) / 100; // 随机生成最大承载重量
        //     $params['dimensions'] = '200x100x80'; // 固定尺寸和形状
        //     $params['battery_type'] = '锂电池'; // 固定电池类型
        //     $params['battery_life'] = rand(1, 10); // 随机生成电池续航能力
        //     $params['mapping_software'] = 'GraphHopper'; // 固定地图和路径规划软件
        //     $params['remote_control_interface'] = 'Web界面'; // 固定远程控制接口
        //     $params['storage_space'] = '500L'; // 固定货物存储空间大小和形状
        //     $params['auto_loading_mechanism'] = '电动升降台'; // 固定自动开关门机制
        //     $params['temperature_control_system'] = '温度传感器'; // 固定温控系统
        //     $params['collision_detection_system'] = '激光雷达'; // 固定碰撞检测系统
        //     $params['emergency_stop_mechanism'] = '急停按钮'; // 固定紧急停止机制
        //     $params['surveillance_system'] = '摄像头'; // 固定视频监控系统
        //     $params['voice_control'] = '语音识别'; // 固定语音控制功能
        //     $params['operating_system'] = 'Linux'; // 固定操作系统
        //     $params['learning_algorithm'] = '深度学习'; // 固定自学习算法
        //     $params['remote_update_maintenance'] = '远程升级和维护'; // 固定远程更新和维护功能
        //     return $params;
        // },
    ],
    'rb_hardware' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $manufacturers = [
        //         "制造商A", "制造商B", "制造商C", "制造商D", "制造商E"
        //     ];
        //     $params['model_number'] = "Model-" . rand(100, 999); // 随机生成型号
        //     $params['manufacturer'] = $manufacturers[array_rand($manufacturers)]; // 随机选择制造商信息
        //     $params['roboter_id'] = $i;
        //     return $params;
        // },
        // 'looper' => 'component_name',
    ],
    'rb_status' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = $i;
        //     return $params;
        // },
    ],
    'rb_battery_status' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = $i; // 随机生成机器人编号
        //     $params['voltage'] = rand(220, 240) + (rand(1, 99) / 100); // 随机生成电压（伏特）
        //     $params['current'] = rand(1, 10) + (rand(1, 99) / 100); // 随机生成放电电流（安培）
        //     $params['temperature'] = rand(-10, 50) + (rand(1, 9) / 10); // 随机生成温度（摄氏度）
        //     $params['charge_level'] = rand(20, 80); // 随机生成充电水平百分比
        //     return $params;
        // },
    ],
    'rb_battery_charging' => [
        // 'count' => 86004,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002); // 随机生成机器人编号
        //     $params['charging_start_time'] = $enums['time'];
        //     $params['charging_end_time'] = date('Y-m-d H:i:s', strtotime($enums['time']) + rand(2450, 36000));
        //     $params['start_charge_level'] = rand(20, 80); // 随机生成开始充电时的电池电量百分比
        //     $params['end_charge_level'] = rand(80, 100); // 随机生成结束充电时的电池电量百分比
        //     $params['energy_consumed'] = ($params['end_charge_level'] - $params['start_charge_level']) / 100 * 10; // 计算本次充电消耗的能量
        //     $params['charging_rate'] = rand(1, 5); // 随机生成充电速率
        //     return $params;
        // },
    ],
    'rb_controller_hardware' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = $i; // 随机生成机器人编号
        //     $params['model'] = "Model " . ($i * 100);
        //     $params['notes'] = "Test notes for component $i";
        //     return $params;
        // },
        // 'looper' => 'component_type',
    ],
    'rb_vui_interaction' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = $i; // 随机生成机器人编号
        //     $params['interaction_type'] = randEnum($enums['interaction_type']);
        //     $params['interaction_content'] = "这是机器人：{$i}，{$params['interaction_type']} 的交互内容"; // 生成交互内容
        //     $params['audio_file_path'] = "https://example.com/audio/{$i}_{$params['interaction_type']}/$i.mp3"; // 生成录音文件路径
        //     return $params;
        // },
        // 'looper' => 'interaction_type',
    ],
    'rb_vui_interaction_log' => [
        // 'count' => 491234,
        // 'function' => function($i, $enums) {
        //     // 用户的语音查询列表
        //     $user_queries = ["请帮我送一杯咖啡到房间", "告诉我今天的天气预报", "我需要一瓶矿泉水", "能否提供早餐服务？", "告诉我酒店的健身房时间"];
        //     // 机器人的响应列表
        //     $robot_responses = ["好的，我会尽快为您送咖啡到房间。", "今天的天气预报显示晴天，最高温度28°C。", "好的，我将为您送一瓶矿泉水。", "是的，我们提供早餐服务，早餐时间是7:00 AM - 10:00 AM。", "健身房开放时间是每天早上6:00 AM - 晚上10:00 PM。"];
        //     // 查询的意图列表
        //     $query_intents = ["送物", "天气查询", "送水服务", "早餐服务", "健身房信息"];
        //     // 响应类型列表
        //     $params['roboter_id'] = rand(1, 1002); // 随机生成机器人编号
        //     $params['user_id'] = rand(1, 50); // 随机生成用户编号
        //     $params['user_query'] = $user_queries[array_rand($user_queries)]; // 随机选择用户的语音查询
        //     $params['robot_response'] = $robot_responses[array_rand($robot_responses)]; // 随机选择机器人的响应
        //     $params['query_intent'] = $query_intents[array_rand($query_intents)]; // 随机选择查询的意图
        //     $params['session_id'] = "session_$i"; // 生成会话标识
        //     return $params;
        // },
    ],
    'rb_delivery_logger' => [
        // 'count' => 46242,
        // 'function' => function($i, $enums) {
        //     $r = rand(1, 28);
        //     $params['roboter_id'] = rand(1, 1002); // 随机生成机器人编号
        //     $params['tasker_id'] = rand(1, 20931);
        //     $params['operator'] = ['张三', '李四', '王五', '陈六', '赵七'][rand(0, 4)];
        //     $params['room_number'] = rand(1, 25).($r > 9 ? $r : '0'.$r);
        //     $params['operation_log'] = rand(1, 3) % 2 ? "开始给{$params['room_number']}房间送".['外卖', '快递', '外卖'][rand(0, 2)] : "{$params['room_number']}房间的".['外卖', '快递', '外卖'][rand(0, 2)]."已送达";
        //     return $params;
        // },
    ],
    'rb_takeaway_logger' => [
        // 'count' => 55234,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002);
        //     $r = rand(1, 25);
        //     $params['room_number'] = rand(1, 25).($r > 9 ? $r : '0'.$r);
        //     $params['tasker_id'] = rand(1, 20931); // 随机选择任务编号
        //     $params['operator'] = ['张三', '李四', '王五', '陈六', '赵七'][rand(0, 4)];
        //     $params['customer_phone_suffix'] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 随机生成客人手机号后四位
        //     return $params;
        // },
    ],
    'rb_tasker_interaction' => [
        // 'count' => 60931,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002);
        //     $params['tasker_id'] = rand(1, 60931);
        //     $params['vui_interaction'] = "这是第 $i 条VUI交互内容"; // 构造出发地址
        //     return $params;
        // },
    ],
    'rb_elevator_interaction' => [
        // 'count' => 62482,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002); // 随机生成机器人编号
        //     $params['tasker_id'] = rand(1, 20931); // 任务编号
        //     $params['current_floor'] = rand(1, 25); // 当前楼层
        //     $params['target_floor'] = rand(1, 25); // 目标楼层
        //     if($params['current_floor'] == $params['target_floor']) {
        //         $params['target_floor'] = rand(1, 25);
        //     }
        //     $params['direction'] = $params['current_floor'] > $params['target_floor'] ? '下行' : '上行';
        //     $params['elevator_id'] = rand(1, 426); // 电梯编号
        //     $params['elevator_status'] = rand(0, 3) ? '等待' : (rand(0, 3) ? '到达' : (rand(0, 3) ? '进入' : '行进')); // 电梯状态
        //     $params['vui_interaction'] = "本宝宝正在执行秘密任务，不能被打扰哦！"; // VUI交互内容
        //     return $params;
        // },
    ],
    'rb_destination_interaction' => [
        // 'count' => 6242,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002); // 随机生成机器人编号
        //     $params['tasker_id'] = rand(1, 20931);
        //     $params['destination'] = "Destination " . $i;
        //     $params['vui_interaction'] = "Test VUI interaction for task $i";
        //     $params['local_log'] = "Test local log for task $i";
        //     return $params;
        // },
    ],
    'rb_power_management' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = $i; // 随机选择机器人编号
        //     return $params;
        // },
    ],
    'rb_oat_upgrade' => [
        // 'count' => 29002,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002); // 随机选择机器人编号
        //     $params['battery_level'] = rand(20, 100); // 随机生成电池电量
        //     $params['new_version'] =  'V'.rand(1, 5).'.0'; // 随机选择地图版本
        //     return $params;
        // },
    ],
    'rb_map_data' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['hotel_id'] = rand(1, 426); // 酒店编号
        //     $params['map_id'] = rand(1, 2242); // 随机生成地图标识符
        //     $params['roboter_position'] = "0.00,0.00"; // 随机生成机器人当前位置
        //     $params['landmark_annotations'] = "酒店内部地图";
        //     return $params;
        // },
    ],
    'rb_status_logger' => [
        // 'count' => 45234,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002);
        //     $r = rand(1, 25);
        //     $params['destination'] = rand(0, 1) ? '回到前台' : '前往房间'.rand(1, 25).($r > 9 ? $r : '0'.$r);
        //     return $params;
        // },
    ],
    'rb_map_management' => [
        // 'count' => 1002,
        // 'function' => function($i, $enums) {
        //     $params['roboter_id'] = rand(1, 1002); // 随机选择机器人编号
        //     $params['hotel_id'] = rand(1, 426); // 酒店
        //     $params['hotel_name'] = "酒店" . $i; // 酒店名称
        //     $params['map_version'] = 'V'.rand(1, 5).'.0'; // 随机选择地图版本
        //     $params['content'] = "地图内容示例"; // 地图内容示例
        //     return $params;
        // },
    ],
    'rb_elevator_hardware' => [
        // 'count' => 426,
        // 'function' => function($i, $enums) {
        //     $controlBoards = ['TCM-2000', 'TCM-8000', 'KCM-7111', 'ECL-2000', 'KDL-2000', 'LCM-100', 'CCM-300', 'EMC-500', 'SCM-600', 'MCE-7000'];
        //     $cardReaders = ['RFID-1000', 'RFID-2000', 'ProxCard II', 'iCLASS SE', 'HID OMNIKEY 3121', 'HID OMNIKEY 5321', 'HID OMNIKEY 5421', 'HID OMNIKEY 5022', 'HID OMNIKEY 5127CK', 'HID OMNIKEY 5427 CK'];
        //     $controllers = ['T-1000系列', 'T-2000系列', 'ELEVATE-200', 'AccessMaster', 'ELCON-500', 'LIFTPRO-3000', 'ELEVATOR-PRO', 'EC-1000', 'EC-2000', 'KONE LCE-02'];
        //     $doorOperators = ['OTIS DO2000', 'Schindler DO5000', 'Thyssenkrupp DOR3000', 'KONE DOR-200', 'Mitsubishi DOP-100', 'Fujitec DOX-500', 'Hitachi DOH-200', 'Toshiba DOL-300', 'Hyundai DOH-500', 'LG DOL-200'];
        //     $doorContacts = ['TLP-5000', 'EMR-3000', 'DCM-200', 'MCD-1000', 'SMD-500', 'EDC-200', 'LCM-300', 'RMC-600', 'VCM-700', 'HMD-400'];
        //     $params['elevator_id'] = $i;
        //     $params['control_board'] = randEnum($controlBoards);
        //     $params['card_reader'] = randEnum($cardReaders);
        //     $params['elevator_controller'] = randEnum($controllers);
        //     $params['door_operator'] = randEnum($doorOperators);
        //     $params['door_contact'] = randEnum($doorContacts);
        //     return $params;
        // },
    ],
    'rb_elevator' => [
        // 'count' => 426,
        // 'function' => function($i, $enums) {
        //     $params['hotel_id'] = $i;
        //     $params['control_supplier'] = "Supplier " . $i;
        //     $params['control_module_id'] = "Module " . $i;
        //     $params['elevator_mac'] = "MAC " . $i;
        //     $params['elevator_scheme'] = "Scheme " . $i;
        //     $params['elevator_brand'] = "Brand " . $i;
        //     $params['false_call_cancel_method'] = "Cancel Method " . $i;
        //     $params['modification_description'] = "Modification Description " . $i;
        //     $params['remarks'] = "Remarks " . $i;
        //     $params['total_floors'] = rand(1, 20);
        //     $params['control_valid_floors'] = "Valid Floors " . $i;
        //     $params['departure_floor_calibration'] = "Calibration Floor " . $i;
        //     $params['current_floor'] = rand(1, $params['total_floors']);
        //     return $params;
        // },
    ],
    'rb_elevator_upgrade' => [
        // 'count' => 2226,
        // 'function' => function($i, $enums) {
        //     $upgrade_descriptions = [
        //         "升级电梯控制系统软件",
        //         "更换电梯按钮和显示器",
        //         "修复电梯门操作器",
        //         "更新电梯传感器",
        //         "升级电梯通信模块",
        //         "增加视频监控摄像头",
        //         "安装紧急停电设备",
        //     ];
        //     $params['elevator_id'] = rand(1, 426);
        //     $params['upgrade_description'] = $upgrade_descriptions[array_rand($upgrade_descriptions)]; // 随机选择升级描述
        //     $params['notified_robot'] = rand(0, 3) ? '是' : '否'; // 随机生成是否已通知机器人更新
        //     return $params;
        // },
    ],
    'rb_elevator_remote_control' => [
        // 'count' => 92226,
        // 'function' => function($i, $enums) {
        //     $params['elevator_id'] = rand(1, 426);
        //     $params['current_floor'] = rand(1, 25);
        //     $params['target_floor'] = rand(1, 25);
        //     if($params['current_floor'] == $params['target_floor']) {
        //         $params['target_floor'] = rand(1, 25);
        //     }
        //     return $params;
        // },
    ],
    'rb_elevator_control_reports' => [
        // 'count' => 90926,
        // 'function' => function($i, $enums) {
        //     $params['elevator_id'] = rand(1, 426);
        //     $params['request_type'] = "API";
        //     $params['command'] = "POST";
        //     $params['current_floor'] = rand(1, 25);
        //     return $params;
        // },
    ],
    'rb_vending_machine' => [
        // 'count' => 1245,
        // 'function' => function($i, $enums) {
        //     // 货柜位置列表
        //     $locations = ['大堂', '餐厅', '客房楼层', '游泳池区域', '健身房'];
        //     $params['hotel_id'] = $i % 426 + 1; // 随机选择酒店编号
        //     $params['location'] = $locations[array_rand($locations)]; // 随机选择货柜位置
        //     return $params;
        // },
    ],
    'rb_sip_service' => [
        // 'count' => 426,
        // 'function' => function($i, $enums) {
        //     $service_names = ["房间电话", "客房送餐", "客房清洁", "物品取送"];
        //     $params['hotel_id'] = $i; // 随机选择酒店编号
        //     $params['service_name'] = randEnum($service_names); // 随机选择服务名称
        //     $params['sip_server_address'] = "sip.example.com"; // SIP服务器地址
        //     $params['sip_server_port'] = rand(5060, 5069); // 随机生成SIP服务器端口
        //     $params['authentication_method'] = "RSA";
        //     return $params;
        // },
    ],
    'rb_sip_integration' => [
    //     'count' => 426,
    //     'function' => function($i, $enums) {
    //         // 语音文本或音频文件列表
    //         $voice_texts_or_audios = ["欢迎光临！", "请问您需要什么帮助？", "感谢您选择我们的酒店！", "房间服务随时为您提供服务。", "如果您有特殊需求，请随时告诉我们。"];
    //         $params['hotel_id'] = $i; // 随机选择酒店编号
    //         $r = rand(1, 28);
    //         $params['room_number'] = rand(1, 25).($r > 9 ? $r : '0'.$r);
    //         $params['voice_text_or_audio'] = randEnum($voice_texts_or_audios); // 随机选择语音文本或音频文件
    //         $params['playback_count'] = rand(1, 5); // 随机生成轮播次数
    //         $params['wait_interval'] = rand(10, 60); // 随机生成等待间隔（秒）
    //         return $params;
    //     },
    //     'looper' => 'robot_delivery_status',
    ],


    // AIHT
    'network_monitoring' => [
        // 'count' => 1274656,
        // 'function' => function($i, $enums) {
        //     // 生成随机的IP
        //     $generate = function () {
        //         $subnet = "";
        //         for ($i = 0; $i < 4; $i++) {
        //             $subnet .= rand(0, 255);
        //             if ($i < 3) {
        //                 $subnet .= ".";
        //             }
        //         }
        //         return $subnet;
        //     };
        //     $params['subnet'] = $generate().'/24'; // 随机生成端口
        //     $params['switch'] = $generate(); // 随机生成端口
        //     $params['port'] = rand(2033, 60000); // 随机生成端口
        //     return $params;
        // }
    ],
    'server_monitoring' => [
        // 'count' => 1112238,
        // 'function' => function($i, $enums) {
        //     // 生成随机的IP
        //     $generate = function () {
        //         $subnet = "";
        //         for ($i = 0; $i < 4; $i++) {
        //             $subnet .= rand(0, 255);
        //             if ($i < 3) {
        //                 $subnet .= ".";
        //             }
        //         }
        //         return $subnet;
        //     };
        //     $names = [
        //         'Server001',
        //         'WebServer01',
        //         'DatabaseServerA',
        //         'AppServer42',
        //         'MailServer3',
        //         'BackupServerAlpha',
        //         'DevelopmentServer2',
        //         'ProductionServerX',
        //         'TestingServer17',
        //         'FileServerBeta',
        //     ];
        //     $params['server_name'] = $names[array_rand($names)];
        //     $params['subnet'] = $generate().'/24'; // 随机生成端口
        //     $params['ip'] = $generate(); // 随机生成端口
        //     return $params;
        // }
    ],
    'db_monitoring' => [
        // 'count' => 1042774,
        // 'function' => function($i, $enums) {
        //     // 生成随机的IP
        //     $generate = function () {
        //         $subnet = "";
        //         for ($i = 0; $i < 4; $i++) {
        //             $subnet .= rand(0, 255);
        //             if ($i < 3) {
        //                 $subnet .= ".";
        //             }
        //         }
        //         return $subnet;
        //     };
        //     $params['subnet'] = $generate().'/24'; // 随机生成端口
        //     $params['ip'] = $generate(); // 随机生成端口
        //     $params['server_name'] = "Server " . ($i % 100 + 1);
        //     $params['instance'] = "{$params['ip']}:3306:root@123456";
        //     $params['port'] = 3306;
        //     $params['database_name'] = "生产数据库";
        //     $params['query_latency_ms'] = rand(1, 100);
        //     $params['storage_size_mb'] = rand(100, 10000) / 100;
        //     $params['available_storage_mb'] = rand(1, 10000) / 100;
        //     $params['error_message'] = "Error message " . ($i + 1);
        //     $params['additional_info'] = json_encode(['key' => 'value']);

        //     return $params;
        // }
    ],
    'storage_monitoring' => [
        // 'count' => 1082774,
        // 'function' => function($i, $enums) {
        //     // 生成随机的IP
        //     $generate = function () {
        //         $subnet = "";
        //         for ($i = 0; $i < 4; $i++) {
        //             $subnet .= rand(0, 255);
        //             if ($i < 3) {
        //                 $subnet .= ".";
        //             }
        //         }
        //         return $subnet;
        //     };
        //     $models = [
        //         'Dell EMC Unity 300',
        //         'NetApp AFF A200',
        //         'HPE Nimble Storage AF20',
        //         'IBM FlashSystem 5200',
        //         'Hitachi Vantara VSP G350',
        //         'Pure Storage FlashArray//X10',
        //         'Huawei OceanStor Dorado 5000 V6',
        //         'Lenovo ThinkSystem DE4000F',
        //         'Fujitsu ETERNUS AF250',
        //         'Western Digital Ultrastar DC SN640',
        //         'Seagate Exos X16',
        //         'Toshiba N300 NAS Hard Drive',
        //         'Cisco UCS S3260 Storage Server',
        //         'Oracle ZFS Storage Appliance',
        //         'Infinidat InfiniBox F6000',
        //     ];

        //     $params['subnet'] = $generate().'/24'; // 随机生成端口
        //     $params['ip'] = $generate(); // 随机生成端口
        //     $params['server_name'] = "Server " . ($i % 100 + 1);
        //     $params['storage_model'] = $models[array_rand($models)];
        //     $params['disk_name'] = "存储服务器".['上海', '北京', '武汉', '深圳', '香港'][$i % 5];
        //     $params['mount_point'] = '/mnt/'.['data', 'storage', 'backups', 'logs', 'database', 'files', 'images', 'videos', 'baklogs', 'documents', 'archives', 'logs'][$i % 12];
        //     $params['total_size_mb'] = rand(100, 10000) / 100;
        //     $params['used_size_mb'] = rand(1, $params['total_size_mb'] * 100) / 100;
        //     $params['free_size_mb'] = $params['total_size_mb'] - $params['used_size_mb'];
        //     $params['usage_percentage'] = number_format($params['used_size_mb'] / $params['total_size_mb'] * 100, 2);
        //     $params['iops'] = rand(10000, 1000000);

        //     return $params;
        // }
    ],
];

foreach($mysqls as $table => $parameters) {
    echo "开始生成表：{$table}的数据<br>";
    $columns = Database::select("show full columns from {$table}");
    if(empty($parameters)) {
        echo "  暂未配置，跳过<br>";
        continue;
    }
    $enums = require_once("./mysql/{$table}.php");
    $timestamp = strtotime('2021-04-12 09:11:29');
    $lefttime = time() - strtotime('2021-04-12 09:11:29');

    Database::delete($table, '1 = 1');
    $data = [];
    // 生成测试数据并插入到表中
    $count = isset($parameters['count']) ? $parameters['count'] : rand(102, 2934);
    for ($i = 1; $i <= $count; $i++) {
        $datetime = date('Y-m-d H:i:s', $timestamp + ($lefttime / $count) * $i);
        $tmp = [];
        foreach($columns as $column) {
            if(in_array($column['Field'], ['id'])) {
                continue;
            }
            if(substr($column['Type'], 0, 4) == 'enum') {
                $tmp[$column['Field']] = randEnum(array_values($enums[$column['Field']]));
            }
            if(substr($column['Type'], 0, 4) == 'date') {
                $tmp[$column['Field']] = $datetime;
            }
        }
        // echo "<PRE>";
        // var_export($tmp);
        // die;
        if(isset($parameters['function'])) {
            $array = $parameters['function']($i, array_merge($enums, ['time' => $datetime]));
            $tmp = array_merge($tmp, $array);
        }
        if($parameters['looper']) {
            foreach($enums[$parameters['looper']] as $k) {
                $tmp[$parameters['looper']] = $k;
                $data[] = $tmp;
            }
        }
        else {
            $data[] = $tmp;
        }
        if(count($data) > 10000) {
            if(Database::inserts($table, $data)) {
                echo '  入库10000<br>';
                $data = [];
            }
            else {
                echo '  入库10000失败<br>';
            }
        }
    }

    // echo "<PRE>";
    // var_export($data);
    // die;
    if($data) {
        if(Database::inserts($table, $data)) {
            echo '  创建成功<br>';
        }
        else {
            echo '  失败<br>';
        }
    }
}

function randEnum($array)
{
    return $array[array_rand($array)];
}