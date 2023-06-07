<?php
function handle_exception(Throwable $e)
{
    $response = [
        'code' => $e->getCode() === 0 ? -10086 : $e->getCode(),
        'message' => $e->getMessage(),
    ];
    exit(json_encode($response));
}


/**
 * 检查数据
 * @param $data
 * @return array|string
 */
function checkData($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $v) {
            $data[$key] = checkData($v);
        }
    } else {
        if (!is_bool($data)) {
            $data = trim($data);
//            $data = strip_tags($data);
//            $data = htmlspecialchars($data);
//            $data = addslashes($data);
        }
    }
    return $data;
}

function post_curl($url, $post_data, $timeout = 5)
{
    $ch = curl_init();  //初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);  //抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);  //设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //设置不输出直接返回字符串
    curl_setopt($ch, CURLOPT_POST, 1);  //post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CAINFO, CURL_CERT_FILE_PATH);  //设置CA证书
    $result = curl_exec($ch);  //运行curl
    curl_close($ch);

    return $result;
}

function get_curl($url, $timeout = 5)
{
    $ch = curl_init();  //初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);  //抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);  //设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //设置不输出直接返回字符串
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CAINFO, CURL_CERT_FILE_PATH);  //设置CA证书
    $result = curl_exec($ch);  //运行curl
    curl_close($ch);

    return $result;
}

function curl($url, $info, $time = '', $act = '', $timeout = 8, $post = 1)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if ($post) {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($post == 2) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $info);
        } else {//为了兼容之前的post请求方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, "act=$act&" . $info . "&time=" . $time . "&sign=" . md5($time . "@tan@wan@"));
        }
        curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIEJAR);
    } else {
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $info);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    ob_start();
    curl_exec($ch);
    $contents = ob_get_contents();
    ob_end_clean();
    curl_close($ch);

    return $contents;
}

/**
 * 获取客户的ip
 */
function get_client_ip()
{
    if ($_SERVER["HTTP_X_FORWARDED_FOR"] ?? false) {
        $ip  = $_SERVER["HTTP_X_FORWARDED_FOR"];
        $ips = explode(',', $ip);//阿里cdn
        $ip  = $ips[0];
        if($ip=='unknown'){$ip=$ips[1];}
    } elseif ($_SERVER["HTTP_CDN_SRC_IP"] ?? false) {
        $ip = $_SERVER["HTTP_CDN_SRC_IP"];
    } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $ip = str_replace(array('::ffff:', '[', ']'), array('', '', ''), $ip);
    return $ip;
}

/**
 * 检查是否是xml
 *
 * @param $str
 * @return bool
 */
function is_xml($str)
{
    $xml_parser = xml_parser_create();
    if (!xml_parse($xml_parser, $str, true)) {
        xml_parser_free($xml_parser);
        return false;
    }

    return true;
}

function build_query_no_encode($param)
{
    $pre_str = '';
    foreach ($param as $key => $val) {
        $pre_str .= $key . '=' . $val . '&';
    }
    //去掉最后一个&字符
    $pre_str = substr($pre_str, 0, -1);

    return $pre_str;
}

/**
 * 把“元”转换成“分”
 *
 * @param float $fee
 * @return int  转换后的金额
 * @author Daihuanqi
 */
function convert_to_cent($fee)
{
    return bcmul($fee, 100, 0);
}

/**
 * PHP对象转数组
 *
 * @param object $object
 * @return array
 */
function object_to_array($object)
{
    $array = (array)$object;
    foreach ($array as $key => $value) {
        if (is_object($value)) {
            $array[$key] = object_to_array($value);
        }
    }

    return $array;
}

/**
 * XML字符串转数组对象
 *
 * @param string $xml_string
 * @return array
 */
function xml_to_array($xml_string)
{
    return object_to_array(simplexml_load_string($xml_string, null, LIBXML_NOCDATA));
}

/**
 * 判断是否是时间格式
 *
 * @param $dateTime
 * @return bool
 */
function is_date_time($dateTime)
{
    $ret = strtotime($dateTime);
    return $ret !== FALSE && $ret != -1;
}

/**
 * @param     $bytes
 * @param int $precision
 * @return string
 */
function to_size($bytes, $precision = 2)
{
    $rank = 0;
    $size = $bytes;
    $unit = "B";
    while ($size > 1024) {
        $size = $size / 1024;
        $rank++;
    }
    $size = round($size, $precision);
    switch ($rank) {
        case "1":
            $unit = "KB";
            break;
        case "2":
            $unit = "MB";
            break;
        case "3":
            $unit = "GB";
            break;
        case "4":
            $unit = "TB";
            break;
        default :

    }
    return $size . "" . $unit;
}

/**
 * 获取请求时间戳
 *
 * @return int
 */
function request_time()
{
    return $_SERVER['REQUEST_TIME'];
}

/**
 * 把秒数转换为时分秒的格式
 *
 * @param Int $times 时间，单位 秒
 * @return String
 */
function sec_to_time($times)
{
    $result = '00:00:00';
    if ($times > 0) {
        $hour = floor($times / 3600);
        $minute = floor(($times - 3600 * $hour) / 60);
        $second = floor((($times - 3600 * $hour) - 60 * $minute) % 60);
        $result = str_pad($hour, 2, 0, STR_PAD_LEFT)
            . ':' . str_pad($minute, 2, 0, STR_PAD_LEFT)
            . ':' . str_pad($second, 2, 0, STR_PAD_LEFT);
    }
    return $result;
}

/**
 * 读取/dev/urandom获取随机数
 * @param $len
 * @return mixed|string
 */
function randomFromDev($len)
{
    $fp = @fopen('/dev/urandom', 'rb');
    $result = '';
    if ($fp !== FALSE) {
        $result .= @fread($fp, $len);
        @fclose($fp);
    } else {
        trigger_error('Can not open /dev/urandom.');
    }
    // convert from binary to string
    $result = base64_encode($result);
    // remove none url chars
    $result = strtr($result, '+/', '-_');

    return substr($result, 0, $len);
}

/**
 * 获取http访问时间戳
 * @return int
 */
function getRequestTime()
{
    return $_SERVER['REQUEST_TIME'];
}

/**
 * 重定向url
 */
function redirectUrl($url)
{
    $urlTemplate = "/^http(s)?:\\/\\/.+/";
    if (preg_match($urlTemplate, $url)) {
        header("Location:{$url}");
    } else {
        exit;
    }
}

/**
 * 把返回的数据集转换成Tree
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int $root
 * @return array
 */
function listToTree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0)
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}
