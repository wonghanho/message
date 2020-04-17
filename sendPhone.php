<?php
/*
    ***聚合数据（JUHE.CN）短信API服务接口PHP请求示例源码
    ***DATE:2015-05-25
*/
header('content-type:text/html;charset=utf-8');

$redis = new Redis;
$redis->connect('localhost',6379);


// 13051700087
$phone = $_GET['phone'];//手机号
$phone_code = rand(1000,9999);  //验证码

$sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
  
$smsConf = array(
    'key'   => '927285103c8d4455c87bfdff47249bde', //您申请的APPKEY
    'mobile'    => $phone, //接受短信的用户手机号码
    'tpl_id'    => '132154', //您申请的短信模板ID，根据实际情况修改
    'tpl_value' =>'#code#='.$phone_code //您设置的模板变量，根据实际情况修改
);
 
$content = juhecurl($sendUrl,$smsConf,1); //请求发送短信
 


if($content){
    $result = json_decode($content,true); //转数组
    $error_code = $result['error_code'];
    if($error_code == 0){

        // 将值存在redis中
        $redis->setex('code_phone',1800,$phone_code);  // 第二个参数为声明周期 (单位为秒)
        //状态为0，说明短信发送成功
        $arr = [
            'code'=>0,
            'msg'=>"短信发送成功,短信ID：".$result['result']['sid'],
        ];
        echo json_encode($arr);

    }else{
        //状态非0，说明失败
        $msg = $result['reason'];
        $arr = [
            'code'=>$error_code,
            'msg'=>"短信发送失败(".$error_code.")：".$msg,
        ];
        echo json_encode($arr);
    }
}else{
    //返回内容异常，以下可根据业务逻辑自行修改
    $arr = [
        'code'=>'',
        'msg'=>'请求发送短信失败',
    ];
    echo json_encode($arr);
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
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
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

?>