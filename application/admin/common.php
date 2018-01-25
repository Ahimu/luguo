<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ] by catli
// +----------------------------------------------------------------------
use think\Db;
use think\Request;
function translation($content){
	$content = urlencode($content); //必须做 url 编码
	$url = "http://fanyi.youdao.com/openapi.do?keyfrom=xujiangtao&key=1490852988&type=data&doctype=json&version=1.1&q=".$content;
	$list = file_get_contents($url);
	$js_de =json_decode($list,true);
	return $js_de;
}
function weather($city){
	//https://api.seniverse.com/v3/weather/now.json?key=2l1y8ketdzpuubn6&location=beijing&language=zh-Hans&unit=c
	// 心知天气接口调用凭据
	$key = '2l1y8ketdzpuubn6'; // 测试用 key，请更换成您自己的 Key
	$uid = 'UF04D99F26'; // 测试用 用户ID，请更换成您自己的用户ID
	// 参数
	//https://api.seniverse.com/v3/weather/daily.json?key=2l1y8ketdzpuubn6&location=beijing&language=zh-Hans&unit=c&start=0&days=5;
	//$api = 'https://api.seniverse.com/v3/weather/now.json'; // 接口地址
	$api = 'https://api.seniverse.com/v3/weather/daily.json'; // 接口地址
	$location = $city; // 城市名称。除拼音外，还可以使用 v3 id、汉语等形式
	// 生成签名。文档：https://www.seniverse.com/doc#sign
	$param = [
	    'ts' => time(),
	    'ttl' => 300,
	    'uid' => $uid,
	];
	$sig_data = http_build_query($param); // http_build_query会自动进行url编码
	// 使用 HMAC-SHA1 方式，以 API 密钥（key）对上一步生成的参数字符串（raw）进行加密，然后base64编码
	$sig = base64_encode(hash_hmac('sha1', $sig_data, $key, TRUE));
	// 拼接Url中的get参数。文档：https://www.seniverse.com/doc#daily
	$param['sig'] = $sig; // 签名
	$param['location'] = $location;
	$param['start'] = -1; // 开始日期。0=今天天气
	$param['days'] = 4; // 查询天数，1=只查一天
	// 构造url
	$url = $api . '?' . http_build_query($param);
	$list = file_get_contents($url);

	//对json 转化成数组
	$list=json_decode($list,true);
	return $list;

}

/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
 */
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}
//整本书的字数
function Getbookwordnum($id){
    $chapter = Db::name('chapter')->select();
	$word_num = 0;
	foreach ($chapter as $v) {
	$word_num += $v['word_num'];
	}
	// if($word_num < 10000){
	// 	return $word_num.'字';
	// }else{
	// 	return round($word_num/10000,2) .'万字';
    //
	// }
    //
	return $word_num;
}
//整本书的价格
function Getbookprice($id){
    $chapter = Db::name('chapter')->select();
	$price = 0;
	foreach ($chapter as $v) {
	$price += $v['price'];
	}
	return $price;
}

/*
 * 获取排列序号
 *
 * @access  public
 * @param   $tbname   string  获取该表的最大ID
 * @return  $orderid  int     返回当前ID
*/
function GetOrderID($tbname)
{
	$r = Db::query("SELECT MAX(orderid) AS `orderid` FROM `$tbname`");
    $orderid = (empty($r['0']['orderid']) ? 1 : ($r['0']['orderid'] + 1));
	return $orderid;
}

/**
 * PHP格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

function toDate($time, $format = 'Y-m-d H:i:s') {
    if (empty ( $time )) {
        return '';
    }
    $format = str_replace ( '#', ':', $format );
    return date($format, $time );
}
