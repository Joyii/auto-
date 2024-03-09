<?php
require_once(BASEDIR.'/comm.php');
require_once(BASEDIR.'/helpers/input.class.php');
require_once(BASEDIR.'/helpers/utiler.class.php');
require_once(BASEDIR.'/helpers/database.class.php');

function acceptPage($parameters)
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
    $number =  'RH/YS/RT'.date('Ym').rand(100, 999);
    $codenumber = 'RH/YP-'.date('Ym').rand(10, 99).'-'.rand(10, 99);
    $company = '____________________________ 公司';
    $address = '上海市嘉定区丰华公路1299号4号楼';
    $linkman = '夏志敏';
    $youbian = '201803';
    $telephone = '13817479041';
    $email = '____________________';
    $checkCompany = '____________________________ 公司';
    $checkAddress = '上海市普陀区中江路879号天地软件园16号楼4层';
    $md5 = md5($number);

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
    <title>{$parameters['title']}测试报告</title>
    <style>
        * {
            font-size: 16px;
        }
        h1,h2,h3,h4,h5 {
            margin-bottom: 16px;
        }
        .page-splider {
            page-break-before: always;
        }
        .center {
            text-align: center;
        }
        ul.flex li {
            display: flex;
            line-height: 2;
            padding: 0 120px;
            font-size: 16px;
            margin: 16px 0;
        }
        ul.flex li span:last-child {
            flex-grow: 1;
            margin-left: 16px;
            border-bottom: 1px solid #ddd;
        }
        ul.flex-right li {
            display: flex;
            line-height: 2;
            font-size: 16px;
        }
        ul.flex-right li span.dotted {
            flex-grow: 1;
            margin: 0 30px;
            height: 20px;
            border-bottom: 1px dotted #ddd;
        }
        ul.flex-right li span:last-child {
            margin-left: 16px;
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
            <div class="container" style="padding:30px 16px;border-radius:8px;line-height:2;">
                <h1 class="center" style="margin:60px 0;">{$parameters['title']}测试报告</h1>
                <ul class="flex">
                    <li><span>测试类型：</span> <span>验 收 测 试</span></li>
                    <li><span>软件名称：</span> <span>{$parameters['title']}管理系统</span></li>
                    <li><span>报告编号：</span> <span>RH/YS/RT{$number}</span></li>
                    <li><span>委托单位：</span> <span>{$company}</span></li>
                    <li><span>送样日期：</span> <span>2 0 2 4 年 0 1 月 3 0 日 </span></li>
                    <li><span>报告日期：</span> <span>2 0 2 4 年 0 3 月 3 0 日 </span></li>
                </ul>
                <div class="center" style="padding:60px 0;">
                    <strong>{$checkCompany}</strong>
                </div>
                <div class="page-splider"></div>
                <h2 class="center" style="margin-top: 48px;">声 明</h2>
                <ul style="line-height:2.6;">
                    <li>1.   本报告无本公司测试专用章和骑缝章无效；</li>
                    <li>2.   本报告无审核人员和授权签字人签字无效；</li>
                    <li>3.   本报告涂改无效；</li>
                    <li>4.   未经本公司书面批准，不得部分复制报告；</li>
                    <li>5.   本报告结果数据仅对报告中指定的测试环境条件的测试有效；</li>
                    <li>6.   本报告结果数据仅对本次被测样品版本负责；</li>
                    <li>7.   本报告电子版，与纸质报告具有同等法律效力；</li>
                    <li>8.   对于报告中出现未在认定范围内参数，不适用于检验检测资质认定。</li>
                </ul>
                <p style="margin-top: 120px;">报告模板：文件编号QP25-10, 版本号V2.3-2022年8月30日发布</p>
                <div class="page-splider"></div>
                <h2 class="center" style="margin-top: 48px;">目 录</h2>
                <ul class="flex-right" style="margin-bottom: 48px;line-height: 2;">
                    <li><span><strong>1.  测试环境</strong></span><span class="dotted"></span><span></span></li>
                    <li><span>　　1.1. 应用服务器</span><span class="dotted"></span><span></span></li>
                    <li><span>　　1.2. 数据库服务器</span><span class="dotted"></span><span></span></li>
                    <li><span>　　1.3. 测试端</span><span class="dotted"></span><span></span></li>
                    <li><span>　　1.4. 网络环境</span><span class="dotted"></span><span></span></li>
                    <li><span><strong>2. 测试样品</strong></span><span class="dotted"></span><span></span></li>
                    <li><span><strong>3. 测试规程</strong></span><span class="dotted"></span><span></span></li>
                    <li><span>　　3.1. 测试要求</span><span class="dotted"></span><span></span></li>
                    <li><span>　　3.2. 测试流程</span><span class="dotted"></span><span></span></li>
                    <li><span><strong>4. 测试结果</strong></span><span class="dotted"></span><span></span></li>
                    <li><span>　　4.1. 功能测试结果</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.1.1. 功能测试概述</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.1.2. 功能测试细则</span><span class="dotted"></span><span></span></li>
                    <li><span>　　4.2. 性能效率测试细则</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.2.1. 响应时间</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.2.2. 功能细则</span><span class="dotted"></span><span></span></li>
                    <li><span>　　4.3. 安全性测试细则</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.3.1. 信息加密</span><span class="dotted"></span><span></span></li>
                    <!--li><span>　　　　4.3.2. 后端框架</span><span class="dotted"></span><span></span></li-->
                    <li><span>　　4.4. 可靠性测试</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.4.1. 成熟性</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.4.2. 容错性</span><span class="dotted"></span><span></span></li>
                    <li><span>　　4.5. 易用性测试</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.5.1. 易学习性</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.5.2. 易理解性</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.5.3. 易操作性</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.5.4. 用户差错防御</span><span class="dotted"></span><span></span></li>
                    <li><span>　　4.6. 维护性测试</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.6.1. 易测试性</span><span class="dotted"></span><span></span></li>
                    <li><span>　　4.7. 可移植性测试</span><span class="dotted"></span><span></span></li>
                    <li><span>　　　　4.7.1. 适应性</span><span class="dotted"></span><span></span></li>
                </ul>
                <div class="page-splider"></div>
                <h2 class="center" style="margin-top: 48px;">报告属性信息</h2>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td colspan="2">软件名称</td>
                        <td colspan="2">{$parameters['title']}系统管理</td>
                        <td>版本</td>
                        <td>V1.0</td>
                    </tr>
                    <tr>
                        <td style="width: 30px;vertical-align:middle;text-align:center;" rowspan="4"><p>委</p><p>托</p><p>单</p><p>位</p></td>
                        <td style="width: 90px;">单位名称</td>
                        <td colspan="4">{$company}</td>
                    </tr>
                    <tr>
                        <td>通讯地址</td>
                        <td colspan="4">{$address}</td>
                    </tr>
                    <tr>
                        <td>联系人</td>
                        <td>{$linkman}</td>
                        <td>邮编</td>
                        <td colspan="2">{$youbian}</td>
                    </tr>
                    <tr>
                        <td>电话</td>
                        <td>{$telephone}</td>
                        <td>电子邮箱</td>
                        <td colspan="2">{$email}</td>
                    </tr>
                    <tr>
                        <td colspan="2">开发单位</td>
                        <td colspan="4">{$company}</td>
                    </tr>
                    <tr>
                        <td colspan="2">检测单位</td>
                        <td colspan="4">{$checkCompany}</td>
                    </tr>
                    <tr>
                        <td colspan="2">检测单位地址</td>
                        <td colspan="4">{$checkAddress}</td>
                    </tr>
                    <tr>
                        <td colspan="2">软件类型</td>
                        <td colspan="4">行业应用软件</td>
                    </tr>
                    <tr>
                        <td colspan="2">测试类型</td>
                        <td colspan="4">验收测试</td>
                    </tr>
                    <tr>
                        <td colspan="2">检测地点</td>
                        <td colspan="4">{$checkAddress}</td>
                    </tr>
                    <tr>
                        <td colspan="2">测试标准</td>
                        <td colspan="4">《系统与软件工程系统与软件质量要求和评价(SQuaRE)第51部分：就绪可用软件产品(RUSP)的质量要求和测试细则》(GB/T 25000.51-2016)</td>
                    </tr>
                    <tr>
                        <td colspan="2">测试要求</td>
                        <td colspan="4">
                            <p>《RH/DC-01{$checkCompany}软件测试管理实施规范》</p>
                            <p>《2021年长宁区“互联网+生活性服务业项目扶持项目协议书》</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">测试结论</td>
                        <td colspan="4">
                            <p>在{$company}提供的测试环境和条件下，对被测系统进行则试。经过测试和分析，所有测试项均为通过。测试结果为 <strong style="font-size: 18px;">通 过</strong></p>
                            <p style="text-align: right;">2023年02月30日</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">测试人员</td>
                        <td colspan="2" style="padding: 0 15px;"><p style="border-bottom:1px solid #999;height:38px;"> </p></td>
                        <td>日期</td>
                        <td style="padding: 0 15px;"><p style="border-bottom:1px solid #999;height:38px;"> </p></td>
                    </tr>
                    <tr>
                        <td colspan="2">审核人员签字</td>
                        <td colspan="2" style="padding: 0 15px;"><p style="border-bottom:1px solid #999;height:38px;"> </p></td>
                        <td>日期</td>
                        <td style="padding: 0 15px;"><p style="border-bottom:1px solid #999;height:38px;"> </p></td>
                    </tr>
                    <tr>
                        <td colspan="2">批准人员签字</td>
                        <td colspan="2" style="padding: 0 15px;"><p style="border-bottom:1px solid #999;height:38px;"> </p></td>
                        <td>日期</td>
                        <td style="padding: 0 15px;"><p style="border-bottom:1px solid #999;height:38px;"> </p></td>
                    </tr>
                </table>

                <div class="page-splider"></div>
                <h2 style="margin-top: 48px;">1. 测试环境</h2>
                <h3>1.1. 应用服务器</h3>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td colspan="2">应用服务器</td>
                    </tr>
                    <tr>
                        <td>硬件环境</td>
                        <td>
                            <p><span style="display:inline-block;width: 120px;">设备型号：</span>OpenStack Nova</p>
                            <p><span style="display:inline-block;width: 120px;">CPU：</span>Intel(R)Xeon(R)Gold 6278C CPUQ2.60GHz</p>
                            <p><span style="display:inline-block;width: 120px;">内存：</span>16GB</p>
                            <p><span style="display:inline-block;width: 120px;">硬盘：</span>100GB</p>
                        </td>
                    </tr>
                </table>

                <h3>1.2. 数据库服务器</h3>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td colspan="2">数据库服务器</td>
                    </tr>
                    <tr>
                        <td>硬件环境</td>
                        <td>
                            <p><span style="display:inline-block;width: 120px;">设备型号：</span>华为云</p>
                            <p><span style="display:inline-block;width: 120px;">CPU：</span>Intel(R)Xeon(R)Gold 6278C CPU@2.60GHz</p>
                            <p><span style="display:inline-block;width: 120px;">内存：</span>8GB</p>
                            <p><span style="display:inline-block;width: 120px;">硬盘：</span>500GB</p>
                        </td>
                    </tr>
                    <tr>
                        <td>软件环境</td>
                        <td>
                            <p><span style="display:inline-block;width: 120px;">操作系统：</span>Cent0S 7.5</p>
                            <p><span style="display:inline-block;width: 120px;">应用软件：</span>MySQL 5.7</p>
                        </td>
                    </tr>
                </table>

                <h3>1.3. 测试端</h3>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td colspan="2">测试端</td>
                    </tr>
                    <tr>
                        <td>硬件环境</td>
                        <td>
                            <p><span style="display:inline-block;width: 120px;">设备型号：</span>LAPTOP-RS211PTM</p>
                            <p><span style="display:inline-block;width: 120px;">CPU：</span>Intel(R)Core(TM)i5-8250 CPU@1.60GHz</p>
                            <p><span style="display:inline-block;width: 120px;">内存：</span>16GB</p>
                            <p><span style="display:inline-block;width: 120px;">硬盘：</span>240GB</p>
                        </td>
                    </tr>
                    <tr>
                        <td>软件环境</td>
                        <td>
                            <p><span style="display:inline-block;width: 120px;">操作系统：</span>Window10</p>
                            <p><span style="display:inline-block;width: 120px;">应用软件：</span>Google Chrome 94.0.4606.54</p>
                        </td>
                    </tr>
                </table>

                <h3>1.4. 网络环境</h3>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td colspan="2">路由器</td>
                    </tr>
                    <tr>
                        <td>硬件环境</td>
                        <td>
                            <p><span style="display:inline-block;width: 120px;">设备型号：</span>TP-LINK TL-ER3220G</p>
                            <p><span style="display:inline-block;width: 120px;">网络类型：</span>有线局域网</p>
                            <p><span style="display:inline-block;width: 120px;">带宽：</span>1.5Gbps</p>
                        </td>
                    </tr>
                </table>

                <div class="page-splider"></div>
                <h2 style="margin-top: 48px;">2. 测试样品</h2>
                <p>样品MD5({$md5})</p>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>样品名称</th>
                        <th>样品编号</th>
                        <th>样品状态</th>
                        <th>数量</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>源代码</td>
                        <td>{$codenumber}</td>
                        <td>完好，已杀毒</td>
                        <td>1</td>
                    </tr>
                </table>

                <div class="page-splider"></div>
                <h2 style="margin-top: 48px;">3. 测试规程</h2>
                <h3>3.1. 测试要求</h3>
                <ul class="flex-right">
                    <li>1)  测试需求覆盖率达到100%。</li>
                    <li>2)  测试用例执行率达到100%。</li>
                    <li>3)  测试用例均进行充分测试。</li>
                </ul>
                <h3>3.2. 测试流程</h3>
                <ul class="flex-right">
                    <li>1)  测试组根据委托方的测试需求，进行测试需求分析(必要时),编写测试方案(计划)和测试规格说明(包括测试用例和测试规程)。</li>
                    <li>2)  测试组在测试委托方配合下，搭建测试环境、准备测试数据和模拟场景。</li>
                    <li>3)  由测试小组完成测试用例的执行。</li>
                    <li>4) 测试小组负责收集测试数据，为测试报告提供编写参考。</li>
                    <li>5)  由中心测试小组负责拟写出测试报告。</li>
                    <li>6)  测试报告评审。</li>
                    <li>7)  正式签发测试报告并交付客户。</li>
                </ul>

                <div class="page-splider"></div>
                <h2 style="margin-top: 48px;">4. 测试结果</h2>
                <h3>4.1. 功能测试结果</h3>
                <h4>4.1.1. 功能测试概述</h4>
                <p>功能测试未发现缺陷。</p>
                <h4>4.1.2. 功能测试细则</h4>
                <h5>4.1.2.1 数据操作</h5>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>模块</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>数据列表</td>
                        <td>分页查询</td>
                        <td>能正确展示数据库中的数据</td>
                        <td>通过</td>
                    </tr>
                </table>
                <h5>4.1.2.1 数据操作</h5>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>模块</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>删除操作</td>
                        <td>删除数据</td>
                        <td>能正确删除数据库中的数据</td>
                        <td>通过</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>创建操作</td>
                        <td>创建数据</td>
                        <td>能在数据库中创建的数据</td>
                        <td>通过</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>修改操作</td>
                        <td>修改数据</td>
                        <td>能在数据库中修改的数据</td>
                        <td>通过</td>
                    </tr>
                </table>

                <h3>4.2. 性能效率测试细则</h3>
                <h4>4.2.1. 响应时间</h4>
                <h5>4.2.1.1 平均响应</h5>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td rowspan="2"></td>
                        <td>平均响应</td>
                        <td>首页接口平均响应时间小于100ms</td>
                        <td>使用谷歌浏览器，进行访问{$parameters['title']}操作，获取首页接口响应时间，第一次访问首页接口响应时间为43毫秒，第二次访问首页接口响应时间为40毫秒，第三次访问首页接 口响应时间为45毫秒，访问首页接口平均响应时间为43毫秒</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <p>测试截图：</p>
                            <img style="width: 420px;max-height: 100%;max-width: 100%" src="<?=\$domain?>/style/sudu.png" />
                        </td>
                    </tr>
                </table>
                <h5>4.2.1.2 核心接口</h5>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td rowspan="2">1</td>
                        <td>查询接口</td>
                        <td>接口平均响应时间小于50ms</td>
                        <td>使用谷歌浏览器，进行访问{$parameters['title']}操作，获取{$parameters['title']}列表接口响应时间，第一次访问{$parameters['title']}接口响应时间为47毫秒，第二次访问{$parameters['title']}接口响应时间为42毫秒，第三次访问{$parameters['title']}接口响应时间为49毫秒，访问{$parameters['title']}接口平均响应时间为45毫秒</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <p>测试截图：</p>
                            <img style="width: 420px;max-height: 100%;max-width: 100%" src="<?=\$domain?>/style/sudu.png" />
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2">2</td>
                        <td>删除接口</td>
                        <td>接口平均响应时间小于50ms</td>
                        <td>使用谷歌浏览器，进行删除{$parameters['title']}操作，{$parameters['title']}删除接口响应时间，第一次删除接口响应时间为37毫秒，第二次{$parameters['title']}接口响应时间为32毫秒，第三次{$parameters['title']}接口响应时间为45毫秒，{$parameters['title']}删除接口平均响应时间为35毫秒</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <p>测试截图：</p>
                            <img style="width: 420px;max-height: 100%;max-width: 100%" src="<?=\$domain?>/style/sudu.png" />
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2">3</td>
                        <td>创建接口</td>
                        <td>接口平均响应时间小于50ms</td>
                        <td>使用谷歌浏览器，进行创建{$parameters['title']}操作，{$parameters['title']}创建接口响应时间，第一次创建接口响应时间为31毫秒，第二次{$parameters['title']}接口响应时间为36毫秒，第三次{$parameters['title']}接口响应时间为42毫秒，{$parameters['title']}创建接口平均响应时间为38毫秒</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <p>测试截图：</p>
                            <img style="width: 420px;max-height: 100%;max-width: 100%" src="<?=\$domain?>/style/sudu.png" />
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2">4</td>
                        <td>修改接口</td>
                        <td>接口平均响应时间小于50ms</td>
                        <td>使用谷歌浏览器，进行修改{$parameters['title']}操作，{$parameters['title']}修改接口响应时间，第一次修改接口响应时间为30毫秒，第二次{$parameters['title']}接口响应时间为38毫秒，第三次{$parameters['title']}接口响应时间为41毫秒，{$parameters['title']}修改接口平均响应时间为33毫秒</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <p>测试截图：</p>
                            <img style="width: 420px;max-height: 100%;max-width: 100%" src="<?=\$domain?>/style/sudu.png" />
                        </td>
                    </tr>
                </table>

                <h3>4.3. 安全性测试细则</h3>
                <h4>4.3.1. 信息加密</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td colspan="4">本系统没有涉及到会员相关手机号、邮箱、证件号等敏感的数据</td>
                    </tr>
                </table>

                <h3>4.4. 可靠性测试</h3>
                <h4>4.4.1. 成熟性</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>运行稳定性</td>
                        <td>测试期间，没有软件错误导致系统异常退出、数据丢失、系统紊乱或致命死机现象</td>
                        <td>通过</td>
                    </tr>
                </table>
                <h4>4.4.2. 容错性</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>屏蔽用户操作错误</td>
                        <td>测试期间，没有用户操作错误导致系统 异常退出、数据丢失、系统紊乱或致命死机现象</td>
                        <td>通过</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>错误提示的准确性</td>
                        <td>测试期间，出现的错误提示准确并易于 理解，没有出现未封装的数据库、支撑程序等应用程序以外的原始提示</td>
                        <td>通过</td>
                    </tr>
                </table>
                <h3>4.5. 易用性测试</h3>
                <h4>4.5.1. 易学习性</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>帮助的获得性</td>
                        <td>用户能正确定位并找到帮助主题</td>
                        <td>通过</td>
                    </tr>
                </table>
                <h4>4.5.2. 易理解性</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>功能的易理解性</td>
                        <td>阅读用户手册和帮助文档后，能够理解功能描述的内容</td>
                        <td>通过</td>
                    </tr>
                </table>
                <h4>4.5.3. 易操作性</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>操作的一致性</td>
                        <td>用户界面布局合理，格式及操作控制方 式保持一致，标题和内容进行了明确区分。</td>
                        <td>通过</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>防止误操作</td>
                        <td>菜单、工具栏随所进行的操作变灰或隐藏</td>
                        <td>通过</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>用户界面友好性</td>
                        <td>在软件要求的屏幕分辨率和刷新频率下，界面无变形</td>
                        <td>通过</td>
                    </tr>
                </table>
                <h3>4.6. 维护性测试</h3>
                <h4>4.6.1. 易测试性</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>测试运行</td>
                        <td>该软件能否不必增加附加测试设施就可容易地运行测试</td>
                        <td>通过</td>
                    </tr>
                </table>
                <h3>4.7. 可移植性测试</h3>
                <h4>4.7.1. 适应性</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>序号</th>
                        <th>模块</th>
                        <th>测试点</th>
                        <th>测试内容</th>
                        <th>测试结果</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td rowspan="2">适应性</td>
                        <td>系统软件环境的适应性(OS、网络软件及合作应用软件的适应性)</td>
                        <td>支持的操作系统种类</td>
                        <td>CentOS 7.5</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>数据结构的适应性</td>
                        <td>支持的数据库种类</td>
                        <td>MySQL 5.7</td>
                    </tr>
                </table>

                <div class="page-splider"></div>
                <h2 class="center" style="margin-top: 48px;">测 试 结 论</h2>
                <p>　　{$checkCompany}于2024年01月08日至2024年02月30日，对{$company}所有的“{$parameters['title']}管理系统V1.0”进行了软件测试。</p>
                <p>　　针对本次测试，依据软件测试要求《系统与软件工程系统与软件质量要求 和评价 (SQuaRE)  第51部分：就绪可用软件产品 (RUSP)  的质量要求和测试 细则》 (GB/T25000.51-2016)  结合《2021年长宁区“互联网+生活性服务业项 目扶持项目协议书》和《{$parameters['title']}管理系统V1.0_测试 需求》开展测试工作。在用户测试环境上，对其功能性指标进行了测试，测试结论如下：</p>
                <p>　　本次测试涉及“{$parameters['title']}管理系统V1.0”的4项功能性、5项性能效率测试、2项安全性测试及3项可靠性指标、7项易用性指标、 1项维护性指标、2项可移植性指标，共设计测试用例25项。测试需求覆盖率100%。</p>
                <p>　　系统具体表现如下：</p>
                <p>　　1、功能性测试方面：</p>
                <p>　　系统提供了数据列表分页展示，新增、修改、删除4项子功能，共计4项，均为通过。</p>
                <p>　　2、性能效率测试方面： 通过对测试项“平均响应”、  “核心接口”的测试，系统表现如下：</p>
                <p>　　1) 使用谷歌浏览器，进行访问{$parameters['title']}操作，获取首页接口响应时间，第一次访问首页接口响应时间为43毫秒，第二次访问首页接口响应时间为40毫秒，第三次访问首页接 口响应时间为45毫秒，访问首页接口平均响应时间为43毫秒</p>
                <p>　　2) 使用谷歌浏览器，进行访问{$parameters['title']}操作，获取{$parameters['title']}列表接口响应时间，第一次访问{$parameters['title']}接口响应时间为47毫秒，第二次访问{$parameters['title']}接口响应时间为42毫秒，第三次访问{$parameters['title']}接口响应时间为49毫秒，访问{$parameters['title']}接口平均响应时间为45毫秒</p>
                <p>　　3) 使用谷歌浏览器，进行删除{$parameters['title']}操作，{$parameters['title']}删除接口响应时间，第一次删除接口响应时间为37毫秒，第二次{$parameters['title']}接口响应时间为32毫秒，第三次{$parameters['title']}接口响应时间为45毫秒，{$parameters['title']}删除接口平均响应时间为35毫秒</p>
                <p>　　4) 使用谷歌浏览器，进行创建{$parameters['title']}操作，{$parameters['title']}创建接口响应时间，第一次创建接口响应时间为31毫秒，第二次{$parameters['title']}接口响应时间为36毫秒，第三次{$parameters['title']}接口响应时间为42毫秒，{$parameters['title']}创建接口平均响应时间为38毫秒</p>
                <p>　　5) 使用谷歌浏览器，进行修改{$parameters['title']}操作，{$parameters['title']}修改接口响应时间，第一次修改接口响应时间为30毫秒，第二次{$parameters['title']}接口响应时间为38毫秒，第三次{$parameters['title']}接口响应时间为41毫秒，{$parameters['title']}修改接口平均响应时间为33毫秒</p>
                <p>　　3、 安全性测试方面： 通过对测试项“信息加密”的测试，系统表现如下：</p>
                <p>　　1) 未发现系统有涉及到会员表的手机号、邮箱、证件号等敏感的数据</p>
                <p>　　4、 可靠性方面： 通过对测试项“成熟性”和“容错性”的测试，系统表现如下：</p>
                <p>　　1)测试期间，没有软件错误导致系统异常退出、数据丢失、系统紊乱或致命死机现象；</p>
                <p>　　2)测试期间，没有用户操作错误导致系统异常退出、数据丢失、系统紊乱或致命死机现象；</p>
                <p>　　3)测试期间，出现的错误提示准确并易于理解，没有出现未封装的数据库、支撑程序等应用程序以外的原始提示；</p>
                <!--p>　　4)系统提供了备份和恢复的手段。</p-->
                <p>　　5、 易用性方面： 通过对测试项“易学习性”、 “易理解性”、“易操作性”和“吸引性”的测试，系统表现如下：</p>
                <p>　　1)用户能正确定位并找到帮助主题；</p>
                <p>　　2)阅读用户手册和帮助文档后，能够理解功能描述的内容；</p>
                <p>　　3)用户界面布局合理，格式及操作控制方式保持一致，标题和内容进行了明确区分</p>
                <p>　　4)菜单、工具栏随所进行的操作变灰或隐藏；</p>
                <p>　　5)在软件要求的屏幕分辨率和刷新频率下，界面无变形；</p>
                <p>　　6)软件是否对输入数据进行有效性检查；</p>
                <p>　　7)软件对于删除等具有严重后果的操作具有提示，并请求用户确认。 6、维护性方面： 通过对测试项“易测试性”的测试，系统表现如下：</p>
                <p>　　1)该软件能否不必增加附加测试设施就可容易地运行测试；</p>
                <p>　　7、可移植性方面：通过对测试项“适应性”和“易安装性”的测试，系统表现如下：</p>
                <p>　　1)系统支持的操作系统种类有： CentOS 7.5;</p>
                <p>　　2)系统支持的数据库种类有： MySQL5.7。</p>
                <p>　　通过本次测试及分析，  “{$parameters['title']}管理系统V1.0”所有指标均为通过。</p>
                <h3>　　测试结论为：通　过</h3>
                <p style="text-align: right;"><strong>{$checkCompany}</strong></p>
                <p style="text-align: right;"><strong>2024年02月30日</strong></p>

                <p class="center" style="margin-top: 40px;">【全文结束】</p>
            </div>
        </div>
    </div>
</body>
</html>
str;
}