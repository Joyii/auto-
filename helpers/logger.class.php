<?php
/**
 * 请求参数
 * 
 * 
 */

class Logger {

    /**
     * 写日期
     * @param string $title
     * @param array $data
     * @param string $file
     * @return boolean
     */
    public static function write($title, $data, $file = 'logger')
    {
        $handler = fopen(__DIR__.'/'.$file.'.txt', 'a+');
        $result = fwrite($handler, $title.' => '.var_export($data, true));
        fclose($handler);

        return $result;
    }
}