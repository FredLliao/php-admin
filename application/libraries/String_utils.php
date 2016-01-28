<?php

/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/1/15
 * Time: 上午10:23
 */
class String_utils {

    //************************* 常量和静态变量定义 begin *********************************

    const DefaultImgSize = 1048575;//1Mb = 1024Kb,1Kb = 1024b
    const DefaultFileSize = 5242875;//5Mb

    /**
     * 手机号码段大全
     * 说明: yd:移动(备注：178(4G)、147(上网卡))
     *      lt:联通(备注：176(4G)、145(上网卡))
     *      dx:电信(备注：177(4G))
     *      xn:虚拟运营商(备注：170)
     *
     * @var string
     */
    const MobilePrefixStr = '{
    "yd":[134,135,136,137,138,139,150,151,152,157,158,159,182,183,184,187,188,178,147],
    "lt":[130,131,132,155,156,185,186,176,145],
    "dx":[133,153,180,181,189,177],
    "xn":[170]
}';

    static $imageAllowFiles = [".png", ".jpg", ".jpeg", ".gif", ".bmp"]; /* 上传图片格式显示 */
    static $videoAllowFiles = [
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"]; /* 上传视频格式显示 */
    static $fileAllowFiles = [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ]; /* 上传文件格式显示 */

    //************************* 常量和静态变量定义 end *********************************


    //************************* 常用字符串处理 begin *********************************

    /**
     * 字符串截取，截取后的内容以...补充
     *
     * @param string $str
     * @param int $limit
     * @return string
     */
    static function subString($str, $limit)
    {
        if(empty($str)) return $str;
        $strLen = mb_strlen($str, 'utf-8');
        if ($strLen >= $limit && $strLen > 1) {
            $str = mb_substr($str, 0, $limit - 1, 'utf-8') . "...";
        }
        return $str;
    }

    /**
     * 判断字符串($str)是否由某字符($needle)开头
     *
     * @param string $str 原始字符串
     * @param string $needle 子字符串
     * @return bool
     */
    static function startWith($str, $needle)
    {
        return strpos($str, $needle) === 0;
    }

    /**
     * 判断字符串($str)是否由某字符($needle)结尾
     *
     * @param string $str 原始字符串
     * @param string $needle 子字符串
     * @return bool
     */
    static function endWith($str, $needle)
    {
        return substr($str, -strlen($needle)) === $needle;
    }

    /**
     * 判断$str是否包含$needle字符串
     *
     * @param string $str 原始字符串
     * @param string $needle 子字符串
     * @return bool
     */
    static function contain($str, $needle)
    {
        return !(strpos($str, $needle) === FALSE);//注意这里的"==="
    }

    /**
     * 判断url是否合法
     *
     * @param string $url
     * @return mixed
     */
    static function checkUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * 添加url参数到网址后面
     * @param $url
     * @param $string
     * @return string
     */
    static function addParameterToUrl($url, $string)
    {
        //如果$url中已经包含了$string,则直接返回
        if (self::contain($url, $string)) {
            return $url;
        }
        if (self::contain($url, "?")) {
            $url = $url . '&' . $string;
        } else {
            $url = $url . '?' . $string;
        }
        return $url;
    }

    /**
     * 获取标准全网手机号码段前3位，严格校验手机号码前3位
     *
     * @return array
     */
    static function getMobilePrefix()
    {
        $prefix = array();
        $mobilePrefixArray = json_decode(self::MobilePrefixStr);
        foreach ($mobilePrefixArray as $key => $v) {
            foreach ($v as $e) {
                array_push($prefix, $e);
            }
        }
        return $prefix;
    }

    /**
     * 验证是否是手机号码，不对前三位做过滤校验
     *
     * @param string $mobile
     * @return bool
     */
    static function isMobile($mobile)
    {
        if (preg_match('/^((\(\d{3}\))|(\d{3}\-))?1[3|4|5|7|8]\d{9}$/', $mobile)) {
            return true;
        }
        return false;
    }

    /**
     * 文件类型检测
     *
     * @param $fileName
     * @param array $allowFiles
     * @return bool
     */
    static function checkFileType($fileName, Array $allowFiles)
    {
        return in_array(self::getFileExt($fileName), $allowFiles);
    }

    /**
     * 获取文件扩展名
     *
     * @param $fileName
     * @return string
     */
    static function getFileExt($fileName)
    {
        return strtolower(strrchr($fileName, '.'));
    }

    /**
     * 验证图片大小
     *
     * @param int $size
     * @param int $target
     * @return bool
     */
    static function checkImgSize($size, $target = self::DefaultImgSize)
    {
        return self::checkFileSize($size, $target);
    }

    /**
     * 验证文件大小
     *
     * @param  int $size 待验证文件的大小
     * @param  int $target 指定文件大小
     * @return bool
     */
    static function checkFileSize($size, $target = self::DefaultFileSize)
    {
        if ($size <= $target) {
            return true;
        }
        return false;
    }

    /**
     * 清洗引号，回车换行等字符
     *
     * @param string $keyword
     * @return mixed|string
     */
    static function parseQuotationMark($keyword)
    {
        if (empty($keyword)) return $keyword;
        $keyword = trim($keyword);
        $keyword = str_replace("'", "\\'", $keyword);
        $keyword = str_replace('"', '\\"', $keyword);
        $keyword = str_replace("\n", "", $keyword);//去除回车、换行
        $keyword = str_replace("\r", "", $keyword);//去除回车、换行
        return $keyword;
    }

    /**
     * 清洗相关特殊字符
     *
     * @param string $keyword
     * @return mixed|string
     */
    static function parseParameter($keyword)
    {
        if (empty($keyword)) return $keyword;
        $parsed_keyword = preg_replace('[:cntrl:]', '', $keyword);
        $parsed_keyword = str_replace("\"", " ", $parsed_keyword);
        $parsed_keyword = str_replace("/", " ", $parsed_keyword);
        $parsed_keyword = str_replace("\\", " ", $parsed_keyword);
        $parsed_keyword = str_replace(":", " ", $parsed_keyword);
        $parsed_keyword = str_replace("?", " ", $parsed_keyword);
        $parsed_keyword = str_replace("'", " ", $parsed_keyword);
        $parsed_keyword = str_replace("[", " ", $parsed_keyword);
        $parsed_keyword = str_replace("]", " ", $parsed_keyword);
        $parsed_keyword = str_replace("{", " ", $parsed_keyword);
        $parsed_keyword = str_replace("}", " ", $parsed_keyword);
        $parsed_keyword = str_replace(")", " ", $parsed_keyword);
        $parsed_keyword = str_replace("(", " ", $parsed_keyword);
        $parsed_keyword = str_replace("~", " ", $parsed_keyword);
        $parsed_keyword = str_replace("\n", "", $parsed_keyword);//去除回车、换行
        $parsed_keyword = str_replace("\r", "", $parsed_keyword);//去除回车、换行
        $parsed_keyword = trim($parsed_keyword);
        return $parsed_keyword;
    }

    /**
     * 清洗html标签
     *
     * @param string $str
     * @return mixed
     */
    static function cleanHtml($str)
    {
        if (empty($str)) return $str;
        $str = preg_replace('/<\\s*\/?br.*?>|<\\s*\/?BR.*?>/', '', $str);
        $str = preg_replace('/<.*?>/', '', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $str = preg_replace('/&nbsp;/', '', $str);
        $str = preg_replace('[:cntrl:]', '', $str);
        return $str;
    }

    //************************* 常用字符串处理 end *********************************


    //************************* 日期处理相关方法 begin *********************************

    /**
     * 将长日期字符串转化为短日期时间格式，输出如：yyyy-mm-dd
     *
     * @param $longTimeStr
     * @return string
     */
    static function getShortTimeFromLongTimeStr($longTimeStr)
    {
        if (!isset($longTimeStr)) {
            return '';
        }
        return explode(' ',$longTimeStr)[0];
    }

    /**
     * 将时间戳转化为短日期时间格式，输出如：yyyy-mm-dd
     *
     * @param int $unixtimeStamp
     * @return bool|string
     */
    static function getShortTime($unixtimeStamp)
    {
        if (empty($unixtimeStamp)) {
            return '';
        }
        return date('Y-m-d', $unixtimeStamp);
    }

    /**
     * 将时间戳转化为标准时间格式，输出如：yyyy-mm-dd hh:ii:ss
     *
     * @param int $unixtimeStamp
     * @return bool|string
     */
    static function getLongTime($unixtimeStamp)
    {
        if (empty($unixtimeStamp)) {
            return '';
        }
        return date('Y-m-d H:i:s', $unixtimeStamp);
    }

    /**
     * 将时间格式字符串转化为时间戳
     *
     * @param $timeStr
     * @return int
     */
    static function timeToUnixTimeStamp($timeStr)
    {
        if (empty($timeStr)) {
            return 0;
        }
        return strtotime($timeStr);
    }

    /**
     * 获取当前时间，长日期格式
     *
     * @return bool|string
     */
    static function getCurrentDateTime()
    {
        return date('Y-m-d H:i:s', time());
    }

    /**
     * 获取当前时间，短日期格式
     *
     * @return bool|string
     */
    static function getCurrentDate()
    {
        return date('Y-m-d', time());
    }

    /**
     * 获取GMT标准时间 如:2015-03-14T14:38:08.531Z 在kibana上用得着
     * @return string
     */
    static function getGmdate()
    {
        $time = explode(" ", microtime());
        $time2 = explode(".", $time [0] * 1000);
        $ms = $time2 [0];//得到毫秒3位数
        return gmdate("Y-m-d\TH:i:s.$ms\Z", strtotime("+ 8 hours"));
    }

    /**
     * 获取带毫秒的时间 如:2015-03-14 14:38:08.531
     * @return string
     */
    static function getMillisecond()
    {
        $time = explode(" ", microtime());
        $time2 = explode(".", $time [0] * 1000);
        $ms = $time2 [0];//得到毫秒3位数
        $timeStr = date('Y-m-d H:i:s', $time[1]). "." . $ms;
        return $timeStr;
    }

    /**
     * 校验日期格式是否正确
     *
     * @param string $date 日期
     * @param array $formats 需要检验的格式数组
     * @return boolean
     */
    function checkDateIsValid($date, $formats = array('Y-m-d', 'Y-m-d H:i:s', 'Y/m/d', 'Y/m/d H:i:s')) {
        $unixTime = strtotime($date);
        if (!$unixTime) { //strtotime转换不对，日期格式显然不对。
            return false;
        }
        //校验日期的有效性，只要满足其中一个格式就OK
        foreach ($formats as $format) {
            if (date($format, $unixTime) == $date) {
                return true;
            }
        }
        return false;
    }

    /**
     * 时间格式转换为中文xx分钟前、xx天前等，参数类型必须为：Y-m-d H:i:s
     *
     * @param string $theTime
     * @return string
     */
    function timeTran($theTime)
    {
        //检查时间格式是否合法
        if(!self::checkDateIsValid($theTime)) {
            return '';
        }
        $now_time = time();
        $show_time = strtotime($theTime);
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return $theTime;
        } else {
            $second = 1;
            $minute = 60;
            $hour = 3600;
            $day = 86400;
            $week = 604800;
            $month = 2592000;
            $year = 31536000;
            if ($dur < $minute) {
                return $dur . '秒前';
            } else {
                if ($dur < $hour) {
                    return floor($dur / $minute) . '分钟前';
                } else {
                    if ($dur < $day) {
                        return floor($dur / $hour) . '小时前';
                    } else {
                        if ($dur < $week) {//一周内
                            return floor($dur / $day) . '天前';
                        } else {
                            if ($dur < $month) {//一个月内
                                return floor($dur / $week) . '周前';
                            } else {
                                if ($dur < ($month*3)) {//3个月内
                                    return floor($dur / $month) . '月前';
                                } else {
                                    //如过超过自定日期区间，则返回短日期格式
                                    $tmp = explode(' ',$theTime);
                                    return $tmp[0];
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    //************************* 日期处理相关方法 end *********************************

    /**
     * 获取ip地址
     *
     * @return string
     */
    static function getIP()
    {
        if ($_SERVER["HTTP_X_FORWARDED_FOR"])
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if ($_SERVER["HTTP_CLIENT_IP"])
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if ($_SERVER["REMOTE_ADDR"])
            $ip = $_SERVER["REMOTE_ADDR"];
        else if (getenv("HTTP_X_FORWARDED_FOR")) //开源代码OSPhP.COm.CN
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknown";
        return $ip;
    }


    /**
     * 数组转为对象
     *
     * @param array $e
     * @return object
     */
    static function arrayToObject(Array $e)
    {
        if (gettype($e) != 'array') return $e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $e[$k] = (object)self::arrayToObject($v);
        }
        return (object)$e;
    }

    /**
     * 对象转为数组
     *
     * @param $e
     * @return array
     */
    static function objectToArray($e)
    {
        $e = (array)$e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'resource') return $e;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $e[$k] = (array)self::objectToArray($v);
        }
        return $e;
    }

    /**
     * 格式化系统后台操作日志数据为json格式
     * @param bool $success 操作状态：是否成功
     * @param int $category 操作对象所属分类
     * @param int $operate_type 操作类型（增,删,改,查）
     * @param string $record_id 操作对象（数据库表记录）ID，可为空
     * @param string $message 关键/主要消息
     * @param array $additional 附加消息，数组类型
     * @param string $operator_id 操作人ID
     * @param string $operator_name 操作人姓名
     * @return array
     */
    static function  getSystemLogData($success = false, $category, $operate_type, $record_id = "",
                                      $message, $additional = array(), $operator_id = "", $operator_name = "")
    {
        $log = array("id" => Helper_UUID::getBigIntUUID(),
            "success" => $success,
            "category" => $category,
            "operate_type" => $operate_type,
            "record_id" => $record_id,
            "message" => $message,
            "additional" => $additional,
            "operator_id" => $operator_id,
            "operator_name" => $operator_name,
            "client_ip" => self::getIP(),//操作者所用ip
            "@timestamp" => self::getGmdate() //操作时间，unix时间戳
        );
        return $log;
    }
}