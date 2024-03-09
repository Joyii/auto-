<?php
    session_start();

    require_once(BASEDIR.'/helpers/database.class.php');

    class Auth {

        // 用户
        public static $member = null;

        /**
         * 判断用户是否登录
         * @param boolean $die 是否终止程序
         * @return boolean
         */
        public static function  check($die = true)
        {
            if(!self::member()) {
                if($die) {
                    self::error();
                }
                return false;
            }
            return true;
        }

        /**
         * 获取session cmp_id
         * @return string
         */
        public static function id()
        {
            return 1;
            return $_SESSION['cmp_id'];
        }

        /**
         * 获取用户资料
         * @param string $attribute
         * @return string
         */
        public static function attr($attribute)
        {
            if(self::check(false)) {
                return self::member()[$attribute];
            }
            return '--';
        }

        /**
         * 获取用户信息
         * @return boolean|array
         */
        public static function member()
        {
            return [
                'cmp_id' => 1,
                'cmp_type' => 'claiht',
                'cmp_name' => 'lier',
            ];
            $cmpid = self::id();

            if(empty($cmpid)) {
                return false;
            }
            self::$member = Database::first("select * from wit_cps_company where cmp_id = {$cmpid} and cmp_type = 'claiht'", 'auth');

            return self::$member;
        }

        /**
         * 失败函数
         * @param string $message
         * @param string $redirect
         * @return void
         */
        public static function error($message = '请登录', $redirect = '/saas/com_login.php?per=claiht') {

            echo '<script>alert("', $message, '");location.href="', $redirect, '";</script>';
            die;
        }
    }