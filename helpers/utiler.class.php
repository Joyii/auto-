<?php
/**
 * 辅助函数
 *
 *
 */

class Utiler {

    const Success = '200';

    /**
     * 校验是否邮箱
     * @param string $mail
     * @return boolean
     */
    public static function isEmail($mail)
    {
        if(empty($mail)) {
            return false;
        }

        return preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/", $mail);
    }

    /**
     * 校验是否手机号
     * @param string $phone
     * @return boolean
     */
    public static function isTelephone($phone)
    {
        if(empty($phone)) {
            return false;
        }

        return preg_match("/^(\+86)?1\d{10}$/", $phone);
    }

    /**
     * 打包数据
     * @param string $code
     * @param string $message
     * @param array $data
     * @return string
     */
    public static function epack($code, $message = '', $data = [])
    {
        echo self::pack($code, $message, $data);
        return true;
    }
    public static function pack($code, $message = '', $data = [])
    {
        $data['code'] = $code;
        $data['message'] = $message;
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 判断远程文件是否存在
     * @param string $url
     * @return boolean
     */
    public static function remote_file_exists($url)
    {
        return(bool)preg_match('~HTTP/1\.\d\s+200\s+OK~', @current(get_headers($url)));
    }

    /**
     * mysql断线重连
     * @return boolean
     */
    public static function reconnect()
    {
        global $db;
        global $con;

        if(self::isLess7Version() ? mysql_ping($con) : mysqli_ping($con)) {
            return true;
        }

        self::isLess7Version() ? mysql_close($con) : mysqli_close($con);

        //链接数据库配置
        if (self::isLess7Version()) {
            $con = mysql_connect($db['host'] . ":" . $db['port'], $db['user'], $db['pass']);  //主机,用户,密码
        } else {
            $con = mysqli_connect($db['host'] . ":" . $db['port'], $db['user'], $db['pass']);  //主机,用户,密码
        }
        if(!$con) {
            echo "数据库错误!";
            return false;
        }

        if (self::isLess7Version()) {
            mysql_select_db($db['name'], $con);  //数据库名字
            mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=utf8", $con);
            mysql_query("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'", $con);
            mysql_query("set names utf8");
        } else {
            mysqli_select_db($db['name'], $con);  //数据库名字
            mysqli_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=utf8", $con);
            mysqli_query("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'", $con);
            mysqli_query($con, "set names utf8");
        }

        return true;
    }

    /**
     * 判断是否小于PHP 版本是否小于7
     * @return boolean
     */
    public static function isLess7Version()
    {
        return version_compare(PHP_VERSION, '7.0.0', '<');
    }

    /**
     * 拼接sql And
     * @return string
     */
    public static function sqlAnd($field, $value, $where)
    {
        if (empty($value)) {
            return $where;
        }
        if (!empty($where)) {
            $where = $where." and ";
        }
        $where = $where.$field."='".$value."'";
        return $where;
    }

    /**
     * 拼接sql Where
     * @return string
     */
    public static function sqlWhere($where)
    {
        if (!empty($where)) {
            return " where ".$where;
        }
        return '';
    }

    /**
     * 将Array转为Array数组的str写法
     * @param array $arr
     * @return string
     */
    public static function pArray($arr)
    {
        return preg_replace(["/(\n[\s\t]*){0,1}array\s{0,1}\(/", "/\)/"], ['[', ']'], var_export($arr, true));
    }

    public static function enumArray($str)
    {
        preg_match_all("/'(?<lister>[^']+)'/i", $str, $match);
        if(isset($match['lister'])) {
            return $match['lister'];
        }
        return [];
    }
}