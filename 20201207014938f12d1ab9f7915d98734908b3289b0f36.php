<?php
error_reporting(0);
header("Content-type: text/html; charset=utf-8");
$meid = "2082901";   //商户后台API管理获取
$key = "a7477768da2cce117c22e7aa7518556d";
$getway = "http://cf.paypaytwo.xyz/zhifu/pay";
$meorder = 'E'.date("YmdHis").rand(100000,999999);    
$amount = $_GET['amount'];    

$host = $_SERVER['SERVER_NAME'];
	    	$scheme = $_SERVER['REQUEST_SCHEME'];
		    $notifyurl = $scheme."://".$host."/nt.php";  //异步回调地址，外网能访问
		    


$returnurl = "https://ppff.me/?dc=LMA";  
$apicode = $_GET['apicode'];  
$subject = 'vip'; 
$clientip = getIp();
$attach = '123';
$timestamp = time();
$sign = md5($meid.$key);
echo $sign;
$data = array(
		'meid' => $meid,
		'meorder' => $meorder,
		'amount' => $amount,
		'notifyurl' => $notifyurl,
		'returnurl' => $returnurl,
		'apicode' => $apicode,
		'subject' => $subject,
		'clientip' => $clientip,
		'attach' => $attach,
		'timestamp	' => '',
		'sign	' => $sign	

);

$res = pay_post($getway,$data);

$res = json_decode($res,true);

if ($res && $res['result_code']=='SUCCESS') {
	$url = $res['payurl'];
	header("location:$url");
	exit();
}else{
	exit($res['err_msg']);
}




function getIp()
{
    if ($_SERVER["HTTP_CLIENT_IP"] && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown")) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } else {
        if ($_SERVER["HTTP_X_FORWARDED_FOR"] && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown")) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            if ($_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) {
                $ip = $_SERVER["REMOTE_ADDR"];
            } else {
                if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],
                        "unknown")
                ) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $ip = "unknown";
                }
            }
        }
    }
    return ($ip);
}



 /**
     * POST PUT 请求
     * @param string $url
     * @param array $param
     * @param string $method
     * @return string content
     */
    function pay_post($url,$data){
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 90); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $result;
    }

?>