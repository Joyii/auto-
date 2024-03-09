<?php
/**
 * 数据库操作类
 *
 */
include_once(__DIR__.'/utiler.class.php');

class Database {

    // 单例变量
    public static $instance = [];

    // 链接变量
    private $conn = null;

    // 配置
    private $host = null;
    private $port = null;
    private $user = null;
    private $pwd = null;
    private $db = null;

    /**
     * 数据库配置
     */
    private $configure = [];

    /**
     * 初始化函数
     * @param string $type 配置名称
     */
    public function __construct($type = 'default')
    {
        $this->configure = include(__DIR__.'/configure.php');

        if(!isset($this->configure[$type])) {
            die('未定义的数据库配置');
        }
        $configure = $this->configure[$type];
        foreach($configure as $k => $v) {
            $this->{$k} = $v;
        }
    }

    /**
     * 获取单例实例
     * @return Resource#
     */
    public static function getInstance($type = 'default')
    {
        if(!isset(static::$instance[$type])) {
            static::$instance[$type] = new Database($type);
        }

        return static::$instance[$type];
    }

    /**
     * 建立链接
     */
    public function connect()
    {
        if($this->conn != null) {

            // 判断是否链接还在保持
            if(Utiler::isLess7Version() ? mysql_ping($this->conn) : mysqli_ping($this->conn)) {
                return $this->conn;
            }

            Utiler::isLess7Version() ? mysql_close($this->conn) : mysqli_close($this->conn);
        }
        // global $con;
        // var_dump($con); die;
        // if($con) {
        //     $this->conn = $con;
        //     return $this->conn;
        // }
        // 建立链接
        if (Utiler::isLess7Version()) {
            $this->conn = mysql_connect($this->host.":".$this->port, $this->user, $this->pwd);
        } else {
            $this->conn = mysqli_connect($this->host.":".$this->port, $this->user, $this->pwd);
        }

        if(!$this->conn ) {
            echo "数据库错误!";
            die();
        }
        // 定义字符集为UTF-8
        define("EC_CHARSET", "utf-8");
        // 选择数据库
        if (Utiler::isLess7Version()) {
            mysql_select_db($this->db, $this->conn);
            mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=utf8", $this->conn);
            mysql_query("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'", $this->conn);
            mysql_query("set names utf8");
        } else {
            mysqli_select_db($this->conn, $this->db);
            mysqli_query($this->conn, "SET character_set_connection=utf8, character_set_results=utf8, character_set_client=utf8");
            mysqli_query($this->conn, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'");
            mysqli_query($this->conn, "set names utf8");
        }

        return $this->conn;
    }

    /**
     * 执行SQL
     * @return mixed
     */
    public function exec($sql)
    {
        if (Utiler::isLess7Version()) {
            return mysql_query($sql, $this->connect());
        }
        return mysqli_query($this->connect(), $sql);
    }

    /**
     * 查询
     * @param string $sql
     * @param string $index
     * @return array
     */
    public function fetch($sql, $index = null)
    {
        $info = $this->exec($sql);
        if(empty($info)) {
            return [];
        }
        $result = [];
        while($row = (Utiler::isLess7Version() ? mysql_fetch_array($info) : mysqli_fetch_array($info))) {
            if($index) {
                $result[$row[$index]] = $row;
            }
            else {
                $result[] = $row;
            }
        }
        return $result;
    }

    /**
     * 查询
     * @param string $sql
     * @param string $index
     * @param string $type
     * @return array
     */
    public static function select($sql, $index = null, $type = 'default')
    {
        // echo $sql, '<br>';
        return static::getInstance($type)->fetch($sql, $index);
    }

    /**
     * 查询一条数据
     * @param string $sql
     * @param string $type
     * @return array
     */
    public static function first($sql, $type = 'default')
    {
        if(strpos($sql, 'limit') === false) {
            $sql .= " limit 1";
        }
        $result = static::getInstance($type)->fetch($sql);
        if(count($result) >= 1) {
            return current($result);
        }
        return [];
    }

    /**
     * 执行SQL语句
     * @param string $sql
     * @param string $type
     * @return mixed
     */
    public static function query($sql, $type = 'default')
    {
        return static::getInstance($type)->exec($sql);
    }

    /**
     * 执行count SQL语句
     * @param string $sql
     * @param string $type
     * @return mixed
     */
    public static function count($table, $where = null, $type = 'default')
    {
        $sql = "select count(1) as c from `{$table}`";
        if($where) {
            $sql .= " where {$where}";
        }
        $result = static::getInstance($type)->first($sql);
        if(isset($result['c'])) {
            return $result['c'];
        }
        return 0;
    }

    /**
     * 执行更新语句
     * @param string $table
     * @param array $params
     * @param string $type
     * @return mixed
     */
    public static function update($table, $params, $where = null, $type = 'default')
    {
        if(empty($params)) {
            return true;
        }
        if(empty($where) || $where == '1=1') {
            return false;
        }
        $values = [];
        foreach($params as $k => $value) {
            $value = str_replace('\'', '"', $value);
            $values[] = "`{$k}` = '{$value}'";
        }
        $sql = "update `{$table}` set ".implode(',', $values)." where {$where};";
        // echo $sql, "\n";
        // return true;
        return static::getInstance($type)->exec($sql);
    }

    /**
     * 执行插入语句
     * @param string $table
     * @param array $params
     * @param string $type
     * @return mixed
     */
    public static function insert($table, $params, $type = 'default')
    {
        if(empty($params)) {
            return true;
        }
        $values = [];
        foreach($params as $value) {
            $values[] = str_replace('\'', '"', $value);
        }
        $sql = "insert into `{$table}` (`".implode('`,`', array_keys($params)).'`) values (\''.implode("','", $values).'\');';
        // echo $sql, "\n";
        return static::getInstance($type)->exec($sql);
    }

    /**
     * 执行插入语句
     * @param string $table
     * @param array $multip
     * @param string $type
     * @return mixed
     */
    public static function inserts($table, $multip, $type = 'default')
    {
        if(empty($multip)) {
            return true;
        }
        $result = true;
        foreach(array_chunk($multip, 200) as $params) {

            $values = [];
            foreach($params as $value) {
                foreach($value as $k => $v) {
                    $value[$k] = str_replace('\'', '"', $v);
                }
                $values[] = '(\''.implode("','", $value).'\')';
            }
            $sql = "insert into `{$table}` (`".implode('`,`', array_keys($params[0])).'`) values '.implode(",", $values).';';
            // var_dump($sql); die;
            // if($table == 'yd_caseno_products') {
                // echo $sql, "\n";
            //     return true;
            // }
            $result = $result && static::getInstance($type)->exec($sql);
        }
        return $result;
    }

    /**
     * 删除操作
     * @param string $table
     * @param string $where
     * @param string $type
     * @return boolean
     */
    public static function delete($table, $where, $type = 'default')
    {
        if(empty($where)) {
            return false;
        }
        $sql = "delete from `{$table}` where {$where}";
        return static::getInstance($type)->exec($sql);
    }

    /**
     * 最后一次入库id
     * @param string $type
     * @return integer
     */
    public static function last_insert_id($type = 'default')
    {
        // $sql = "SELECT LAST_INSERT_ID() as id;";
        if (Utiler::isLess7Version()) {
            return mysql_insert_id(static::getInstance($type)->connect());
        }
        return mysqli_insert_id(static::getInstance($type)->connect());
    }
}