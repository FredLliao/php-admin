<?php

/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/15
 * Time: 上午10:23
 */
class UUID
{
    static function get()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = ''
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
            return $uuid;
        }
    }

    static function getLowCaseUUID()
    {
        $uuid = self::get();
        $uuid = str_replace("-", "", $uuid);
        $uuid = strtolower($uuid);
        return $uuid;
    }

    static function getShortUUID()
    {

        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid = ''
            . substr($charid, 20, 12);
        return $uuid;
    }

    /**
     * 获取x位随机码
     *
     * @param $x
     * @return string
     */
    static function getRandUUID($x)
    {
        $uuid='';
        while($x>0){
            $uuid.=rand(0,9);
            $x--;
        }
        return $uuid;
    }

    /**
     * 短地址算法
     *
     * @param string $url
     * @param string $prefix
     * @param string $suffix
     * @return array
     */
    static function getShortUrl($url = '', $prefix = '', $suffix = '')
    {
        $base32 = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', '8', 'm', 'n', '7', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
            'y', 'z', '6', '9', '2', '3', '4', '5');

        $hex = md5($prefix . $url . $suffix);
        $hexLen = strlen($hex);
        $subHexLen = $hexLen / 8;
        $output = array();

        for ($i = 0; $i < $subHexLen; $i++) {
            $subHex = substr($hex, $i * 8, 8);
            $int = 0x3FFFFFFF & (1 * ('0x' . $subHex));
            $out = '';
            for ($j = 0; $j < 6; $j++) {
                $val = 0x0000001F & $int;
                $out .= $base32[$val];
                $int = $int >> 5;
            }
            $output[] = $out;
        }
        return $output;
    }


    /**
     * 生成不重复的BigInt类型 UUID，但还是有极小概率的重复
     * id(19位数字)=系统当前时间戳(10位)+毫、微妙(5位)+随机数(4位)
     *
     * 注意：由于当前mysql限制，bigint类型(有符号值)最大:9223373036854775807
     * 前10位：9223373036  对应时间=>  2262/4/12 8:3:56
     * 所以当时间到2262年的时候，该计算方法需要改，最好改为18位，但是重复率就提高了
     *
     * @return string
     */
    static function getBigIntUUID()
    {
        //! 计算 ID 时要添加多少位随机数
        $suffix_len = 4;
        $time = explode(' ', microtime());
        $id = $time[1] . substr($time[0], 2, 5);
        if ($suffix_len > 0) {
            $id .= substr(mt_rand(10000,100000000), 0, $suffix_len);
        }
        if(strlen($id)>19) {
            $id = substr($id,0,19);
        }
        return $id;
    }
}