<?php
/**
 * 接口请求类
 * 
 * 
 */

class Request {

    // 成功状态常量
    const RequestSuccess = 200;

    /**
     * 默认选项格式
     * @var array
     */
    private $_default = [
        'isReturnHeader' => false,
        'header' => [],
        'timeout' => ['connect' => 5, 'active' => 60],
        'cert' => ['key' => '', 'password' => '', 'verify' => false],
        'userAgent' => 'Mozilla/5.0',
    ];

    /**
     * 通过POST请求接口
     * @param string $action 地址
     * @param mixed $params 参数
     * @param array $options 配置
     * @param array $cOpts 配置
     * @return mixed
     */
    public static function post($action, $params = null, $options = [], $cOpts = [])
    {
        $response = self::_request('POST', $action, $params, $options, $cOpts);
        return $response;
    }

    /**
     * 通过GET请求接口
     * @param string $action 地址
     * @param mixed $params 参数
     * @param array $options 配置
     * @param array $cOpts 配置
     * @return mixed
     */
    public static function get($action, $params = null, $options = [], $cOpts = [])
    {
        return self::_request('GET', $action, $params, $options, $cOpts);
    }

    /**
     * 通过PUT方式请求
     * @param string $action 地址
     * @param mixed $params 参数
     * @param array $options 配置
     * @param array $cOpts 配置
     * @return array
     */
    public static function put($action, $params = null, $options = [], $cOpts = [])
    {
        return self::_request("PUT", $action, $params, $options, $cOpts);
    }

    /**
     * 是否请求成功
     * @return boolean
     */
    public static function succeed($code)
    {
        if(is_array($code)) {
            $code = $code['code'];
        }
        return in_array($code, [self::RequestSuccess]);
    }
    /**
     * 是否请求超时
     * @return boolean
     */
    public static function overtime($code)
    {
        return in_array($code, ['100', '500', '499', '504']);
    }

    /**
     * 发送HTTP REQUEST请求
     * @param string $method 请求方式：GET|POST|PUT
     * @param string $action 地址
     * @param string $params 参数
     * @param array $options 配置
     * @param array $cOpts 其他配置：CURLOPT_*
     * @return array
     */
    public static function _request($method, $action, $params, $options = [], $cOpts = [])
    {
        $curlHandle = curl_init();
        // 初始化配置参数
        $options = array_merge([
            'isReturnHeader' => false,
            'header' => [],
            'timeout' => ['connect' => 5, 'active' => 60],
            'cert' => ['key' => '', 'password' => '', 'verify' => false],
            'userAgent' => 'Mozilla/5.0',
        ], $options);
        if ($method === "GET" && !empty($params)) {
            $action .= (strpos($action, "?") === false ? "?" : "&") . (is_array($params) ? http_build_query($params) : $params);
        }
        $cOpts = $cOpts + [
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_URL => $action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $options['userAgent'],
            CURLOPT_HEADER => $options['isReturnHeader'],
            CURLOPT_HTTPHEADER => $options['header'],
            CURLOPT_CONNECTTIMEOUT => $options['timeout']['connect'],
            CURLOPT_TIMEOUT => $options['timeout']['active'],
            CURLOPT_SSL_VERIFYPEER => $options['cert']['verify'],
        ];
        if ($method !== "GET" && !empty($params)) {
            $cOpts[CURLOPT_POSTFIELDS] = is_array($params) ? http_build_query($params) : $params;
        }
        // 证书访问
        if(!empty($options['cert']['key'])) {
            $cOpts[CURLOPT_SSL_VERIFYHOST] = 2;
            $cOpts[CURLOPT_SSL_VERIFYPEER] = true;
            $cOpts[CURLOPT_CAINFO] = $options['cert']['key'];
            $cOpts[CURLOPT_SSLCERT] = $options['cert']['key'];
            $cOpts[CURLOPT_SSLCERTPASSWD] = $options['cert']['password'];
        }

        curl_setopt_array($curlHandle, $cOpts);
        $response = curl_exec($curlHandle);
        // print_r($response);

        // 获取返回头信息
        $info = curl_getinfo($curlHandle);
        $header = [];
        if($options['isReturnHeader']) {
            $_header = substr($response, 0, $info["header_size"]);
            $response = substr($response, $info["header_size"]);
            if ($_header) {
                foreach (explode("\r\n", trim($_header)) as $line) {
                    $lineArr = explode(": ", $line);
                    if(isset($lineArr[1])) {
                        $header[$lineArr[0]] = $lineArr[1];
                    }
                    else {
                        $header[] = $lineArr[0];
                    }
                }
            }
        }

        // 记录请求超时日志
        if(self::overtime($info['http_code'])) {
            // 
        }
        // 设置返回状态码
        $message = curl_errno($curlHandle) ? 'request failed: ' . curl_error($curlHandle) : 'request success';
        curl_close($curlHandle);

        if($options['isReturnHeader']) {
            return ['code' => $info['http_code'], 'message' => $message, 'response' => $response, 'header' => $header];
        }
        return ['code' => $info['http_code'], 'message' => $message, 'response' => $response];
    }
}
