<?php

/**
 * base62编码/解码
 * @category PhalconCMS
 * @copyright Copyright (c) 2016 PhalconCMS team (http://www.marser.cn)
 * @license GNU General Public License 2.0
 * @link www.marser.cn
 */

namespace Marser\App\Libs;

class Base62{

    /**
     * base62编码与解码key，由10个数字、26个大写英文字母和26个小写英文字母打乱顺序随机生成
     * @var string
     */
    const KEY = 'ETZJ63Mc8iY9NW2eAbpR0LqUgIFwmGvykxKrohQH5Bs41nVtOduj7XSPfDzCla';

    /**
     * 获取密钥
     * @return string
     */
    protected static function get_key(){
        return self::KEY;
    }

    /**
     * base62编码
     * @param $number
     * @param string $encode
     * @return string
     */
    public static function base62_encode($number, $encode = ''){
        while($number > 0){
            $key = self::get_key();
            $mod = bcmod($number, 62);
            $encode .= $key[$mod];
            $number = bcdiv(bcsub($number, $mod), 62);
        }
        return strrev($encode);
    }

    /**
     * base62解码
     * @param $encode
     * @param int $number
     * @return int|string
     */
    public static function base62_decode($encode, $number = 0){
        $length = strlen($encode);
        $baselist = array_flip(str_split(self::get_key()));
        for($i = 0; $i < $length; $i++){
            $number = bcadd($number, bcmul($baselist[$encode[$i]],  bcpow(62, $length - $i - 1)));
        }
        return $number;
    }
}