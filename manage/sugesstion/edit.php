<?php
    require_once('../../comm.php');
    require_once(BASEDIR.'/helpers/input.class.php');
    require_once(BASEDIR.'/helpers/utiler.class.php');
    require_once(BASEDIR.'/helpers/database.class.php');

    $request = new Input();
    if(!$request->filled('id')) {
        return Utiler::epack('param.error', '页面异常，请刷新重试');
    }

    $model = Database::first("select * from network_monitoring where id = {$request->find('id')}");
    if(!$model) {
        return Utiler::epack('model.unexists', '数据没有找到，请刷新重试');
    }
    ?>

    <div class="p-4">
        <form id="model-form" action="./service/edit.php?id=<?=$model['id']?>" method="POST" onsubmit="return false;">

            <div class='mb-3 checker'>
                <label class='form-label'>监控项名称</label>
                <select class='form-select' name='monitoring_item'>
                    <option value="">--</option>
                    <option value='服务器负载' <?=$model['monitoring_item'] == '服务器负载' ? 'selected' : ''?>>服务器负载</option><option value='网络延迟' <?=$model['monitoring_item'] == '网络延迟' ? 'selected' : ''?>>网络延迟</option><option value='防火墙状态' <?=$model['monitoring_item'] == '防火墙状态' ? 'selected' : ''?>>防火墙状态</option><option value='入侵检测' <?=$model['monitoring_item'] == '入侵检测' ? 'selected' : ''?>>入侵检测</option><option value='网络流量异常' <?=$model['monitoring_item'] == '网络流量异常' ? 'selected' : ''?>>网络流量异常</option><option value='路由器状态' <?=$model['monitoring_item'] == '路由器状态' ? 'selected' : ''?>>路由器状态</option><option value='DNS响应时间' <?=$model['monitoring_item'] == 'DNS响应时间' ? 'selected' : ''?>>DNS响应时间</option><option value='入侵日志' <?=$model['monitoring_item'] == '入侵日志' ? 'selected' : ''?>>入侵日志</option><option value='网络设备温度' <?=$model['monitoring_item'] == '网络设备温度' ? 'selected' : ''?>>网络设备温度</option><option value='防火墙日志' <?=$model['monitoring_item'] == '防火墙日志' ? 'selected' : ''?>>防火墙日志</option><option value='网络连接数' <?=$model['monitoring_item'] == '网络连接数' ? 'selected' : ''?>>网络连接数</option><option value='VPN状态' <?=$model['monitoring_item'] == 'VPN状态' ? 'selected' : ''?>>VPN状态</option><option value='入侵尝试' <?=$model['monitoring_item'] == '入侵尝试' ? 'selected' : ''?>>入侵尝试</option><option value='网络拥堵' <?=$model['monitoring_item'] == '网络拥堵' ? 'selected' : ''?>>网络拥堵</option><option value='蜜罐状态' <?=$model['monitoring_item'] == '蜜罐状态' ? 'selected' : ''?>>蜜罐状态</option><option value='网络异常流量' <?=$model['monitoring_item'] == '网络异常流量' ? 'selected' : ''?>>网络异常流量</option><option value='DDoS攻击' <?=$model['monitoring_item'] == 'DDoS攻击' ? 'selected' : ''?>>DDoS攻击</option><option value='网络设备故障' <?=$model['monitoring_item'] == '网络设备故障' ? 'selected' : ''?>>网络设备故障</option><option value='带宽利用率' <?=$model['monitoring_item'] == '带宽利用率' ? 'selected' : ''?>>带宽利用率</option><option value='流量突增' <?=$model['monitoring_item'] == '流量突增' ? 'selected' : ''?>>流量突增</option>
                </select>
            </div>
                    <div class="mb-3 checker">
                        <label class="form-label">网段</label>
                        <input class="form-control" name="subnet" value="<?=$model['subnet']?>" type="text" placeholder="subnet">
                    </div>
                    <div class="mb-3 checker">
                        <label class="form-label">交换机</label>
                        <input class="form-control" name="switch" value="<?=$model['switch']?>" type="text" placeholder="switch">
                    </div>
                    <div class="mb-3 checker">
                        <label class="form-label">端口</label>
                        <input class="form-control" name="port" value="<?=$model['port']?>" type="text" placeholder="port">
                    </div>
            <div class='mb-3 checker'>
                <label class='form-label'>status</label>
                <select class='form-select' name='status'>
                    <option value="">--</option>
                    <option value='正常' <?=$model['status'] == '正常' ? 'selected' : ''?>>正常</option><option value='警告' <?=$model['status'] == '警告' ? 'selected' : ''?>>警告</option><option value='严重' <?=$model['status'] == '严重' ? 'selected' : ''?>>严重</option>
                </select>
            </div>
                    <div class="mb-3 checker">
                        <label class="form-label">最后检查时间</label>
                        <input class="form-control" name="last_checked" value="<?=$model['last_checked']?>" type="text" placeholder="last_checked">
                    </div>
            <div class="d-grid">
                <button class="btn btn-primary " id="save-model" data-form="#model-form">保存</button>
                <textarea id="model-rule" data-form="#model-form" hidden>{"rules":{"monitoring_item":"required","subnet":"nullable","switch":"nullable","port":"nullable","status":"required","last_checked":"nullable"},"messages":{"monitoring_item.required":"\u76d1\u63a7\u9879\u540d\u79f0\u4e0d\u80fd\u4e3a\u7a7a","status.required":"\u4e0d\u80fd\u4e3a\u7a7a"}}</textarea>
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