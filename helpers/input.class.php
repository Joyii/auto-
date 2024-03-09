<?php
/**
 * 请求参数
 *
 *
 */

class Input {

    private $post;
    private $get;

    public function __construct()
    {
        //过滤_GET _POST _COOKIE
        foreach(['_GET','_POST','_COOKIE'] as $_request) {
            if(isset($$_request) && $$_request) {
                foreach($$_request as $_k => $_v) {
                    $$_request[$_k] = $this->filters($_v);
                }
            }
        }
        // foreach($_GET as $_k => $_v) {
        //     ${$_k} = $this->filters($_v);
        // }
        // foreach($_POST as $_k => $_v) {
        //     ${$_k} = $this->filters($_v);
        // }
        // foreach($_COOKIE as $_k => $_v) {
        //     ${$_k} = $this->filters($_v);
        // }

        $this->post = $_POST;
        $this->get = $_GET;
    }

    /**
     * 是否存在
     * @param string $key
     * @return boolean
     */
    public function filled($key)
    {
        $value = $this->find($key);
        return !($value == '' || $value == null);
    }

    /**
     * 获取get参数
     * @param string $key
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        if(is_null($key)) {
            return $this->get;
        }
        if(isset($this->get[$key])) {
            return $this->get[$key];
        }
        return $default;
    }

    /**
     * 获取post参数
     * @param string $key
     * @return mixed
     */
    public function post($key = null, $default = null)
    {
        if(is_null($key)) {
            return $this->post;
        }
        if(isset($this->post[$key])) {
            return $this->post[$key];
        }
        return $default;
    }

    /**
     * 获取get|post参数
     * @param string $key
     * @return mixed
     */
    public function find($key = null, $default = null)
    {
        if(isset($this->post[$key])) {
            return $this->post[$key];
        }
        if(isset($this->get[$key])) {
            return $this->get[$key];
        }
        return $default;
    }

    public function only($keys)
    {
        if(empty($keys)) {
            return [];
        }
        $input = [];
        foreach($keys as $k) {
            $input[$k] = $this->find($k);
        }
        return $input;
    }

    /**
     * 获取所有参数
     *
     * @return array
     */
    public function all()
    {
        return $this->get + $this->post;
    }

    /**
     * 判断请求类型
     * @param string $method
     * @return bool
     */
    public function isMethod($method)
    {
        return strtoupper($method) == strtoupper($_SERVER['REQUEST_METHOD']);
    }
    public function isGet()
    {
        return $this->isMethod('GET');
    }
    public function isPost()
    {
        return $this->isMethod('POST');
    }

    /**
     * 安全过滤
     *
     * @return array
     */
    public function filters(&$svar)
    {
        if(!get_magic_quotes_gpc())	{
            if( is_array($svar)) {
                foreach($svar as $_k => $_v) {
                    $svar[$_k] = $this->filters($_v);
                }
            }
            else {
                $svar = addslashes($svar);
            }
        }
        return $svar;
    }
}