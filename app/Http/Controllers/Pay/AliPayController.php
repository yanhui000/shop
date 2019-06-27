<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AliPayController extends Controller
{
    public $app_id;
    public $gate_way;
    public $notify_url;
    public $return_url;
    public $rsaPrivateKeyFilePath = '';  //路径
    public $aliPubKey = '';  //路径
    public $privateKey = 'MIIEowIBAAKCAQEA1qyYz+PlAFMMQtypT1v/gXr7EAqXd5wlNpAyIKKOIIPac+BP6khBLIR3ds65PSD3N4lYQlOLIJe5SPlN3QSKNUMo8ADrRwMpZdjxPtpeXF7M55hj6g7xGwKO0jfxsECKwFuuBFwxJWi6LxoQe7pqzLEKx4EvWg3jhGlUCWN0R7hBYevWU8SCnX72cSEkXwzkGSClOig7DkSpwUgiG9jpjfYNAZcc7wfSORXBsIJGq1cA2C3xMWhKaUtTuzUiHg1CjYNyB4VUIqZLftkBm4CzE7tR1tSaoa/OGoO+q5sepZiZtWfmK99AgzxCob6CglrbeZPdLsdDs/M7CYbh+3QEkQIDAQABAoIBAFKyPlOnOKo9U7XWByrZB747P8fYLK0Y64TvW0ATHqkL4fVNbbuUhfa+OL79t4IC1vj/4Y37XNb7W5bu4r3HZ+5IptZ9pCTA5Quk3JoXyM3QfyKgI60ggGSlQZtdja8Vzd1387seQJhmlYJbign/r/CWKs7bxv/r5b82S/cp5rtRXDMkQ7QQhubwZBznucgM7umVLCV5hwAZUKNaRwMlmRTb5R6Vd87Bo8mUGDq2QHM7rpxcEYK44KjgFQ9dxaUtjctldTcxu16wrMWFv36x3WpGOATjJPdjK39hOvFdZQ1+Ci7tw6479b8FbCXpNF2WnaoClsCPhyC1E36YZDNCrSECgYEA/qDIT+UW3muRUY9Xiw4sm5ZvpxpTCM+nW94PWrEnH+v3kMqjKynX9V3Gfmgc0ajLIqj3F2KLOnZZnkq3ShYP7C4+mMx5eQNn+w60yXeTrNcaFra3Knf2H59kNeVJrOd3IYatZxbyVahVv3dyjVIqP2iXhXlc5dPYmXrdBOV5QZ0CgYEA19S0Zmf7wHisc7q1buQ5/oCADwgbpUygA6y4DikphWJs3zLZrWHhFoWbDYLcJY5wgtx2ksH2Ewi89cEZD2MeJCsdf5BwCR0iQGTrzx+saOZXGlx/k6DKV9ZbhUKX38FNvrVE/ECsjCffhic4fFluKxkTNhJwZKWNZWN3pCRURoUCgYA+7vJTfOXIF4IVTH5wqhIwamAyRvla5igRNrNI0RxAcYwAx3TIyFDOHl1fBNCKqVN7v5NCvmJ3EqVX0FO5BfbtgwiJr5Aebs8WaC4a1Wk0gP27u3FOF9RHQJf+EivBhnwVVzoojq1aqn53EkjH0RUMEwfQP4zMaS3R0WIrQaRUqQKBgQDKqT3j0swABM/Zs33d7IvQQBT/CFv9MSAnky0YjuogBzW3t2XSelAC992KKFBTK5bWKfFlCJ/pa5ETKgg//JA24g1G1Pd+d07MLHeH+3PG5FLxu91HX/nf1LYHQWqefkJkSo3GV2wuBtrKe1V83fSmn1r8sP+8Yf+NLoMPK9W+iQKBgFUW1ZGOjakMm4QrCe0k86gQIwtCAUfySShf0P40kPS4ULCoVrV4dDBSBjnYIXgdX2Mm7ksP93J6NqZVu/koYm6D2O3z25j+yiWud9yMMbybRLUqV+k68xUQBkquMAsvOl9sXZC99iCpCEqEWcL8fbNrxN7WcFlIm+KS9vtJwTSW';
    public $publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1qyYz+PlAFMMQtypT1v/gXr7EAqXd5wlNpAyIKKOIIPac+BP6khBLIR3ds65PSD3N4lYQlOLIJe5SPlN3QSKNUMo8ADrRwMpZdjxPtpeXF7M55hj6g7xGwKO0jfxsECKwFuuBFwxJWi6LxoQe7pqzLEKx4EvWg3jhGlUCWN0R7hBYevWU8SCnX72cSEkXwzkGSClOig7DkSpwUgiG9jpjfYNAZcc7wfSORXBsIJGq1cA2C3xMWhKaUtTuzUiHg1CjYNyB4VUIqZLftkBm4CzE7tR1tSaoa/OGoO+q5sepZiZtWfmK99AgzxCob6CglrbeZPdLsdDs/M7CYbh+3QEkQIDAQAB';
    public function __construct()
    {
        $this->app_id = '2016092900622102';
        $this->gate_way = 'https://openapi.alipaydev.com/gateway.do';
        $this->notify_url = env('APP_URL').'/notify_url';
        $this->return_url = env('APP_URL').'/return_url';
    }
    
    
    /**
     * 订单支付
     * @param $oid
     */
    public function pay(Request $request)
    {
    	// file_put_contents(storage_path('logs/alipay.log'),"\nqqqq\n",FILE_APPEND);
    	// die();
        //验证订单状态 是否已支付 是否是有效订单
        //$order_info = OrderModel::where(['oid'=>$oid])->first()->toArray();
        //判断订单是否已被支付
        // if($order_info['is_pay']==1){
        //     die("订单已支付，请勿重复支付");
        // }
        //判断订单是否已被删除
        // if($order_info['is_delete']==1){
        //     die("订单已被删除，无法支付");
        // }
        // $oid = time().mt_rand(1000,1111);  //订单编号
        //业务参数
        $oid=$request->get('id');
        // dd( $oid);
        $pay_money = DB::table('order')->where('oid',$oid)->value('pay_money');
        $bizcont = [
            'subject'           => 'Lening-Order: ' .$oid,
            'out_trade_no'      => $oid,
            'total_amount'      => $pay_money,
            'product_code'      => 'FAST_INSTANT_TRADE_PAY',
        ];
        // dd($bizcont);
        //公共参数
        $data = [
            'app_id'   => $this->app_id,
            'method'   => 'alipay.trade.page.pay',
            'format'   => 'JSON',
            'charset'   => 'utf-8',
            'sign_type'   => 'RSA2',
            'timestamp'   => date('Y-m-d H:i:s'),
            'version'   => '1.0',
            'notify_url'   => $this->notify_url,        //异步通知地址
            'return_url'   => $this->return_url,        // 同步通知地址
            'biz_content'   => json_encode($bizcont),
        ];
        //签名
        $sign = $this->rsaSign($data);
        $data['sign'] = $sign;
        $param_str = '?';
        foreach($data as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $url = rtrim($param_str,'&');
        $url = $this->gate_way . $url;
        header("Location:".$url);
    }
    public function rsaSign($params) {
        return $this->sign($this->getSignContent($params));
    }
    protected function sign($data) {
    	if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
    		$priKey=$this->privateKey;
			$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
				wordwrap($priKey, 64, "\n", true) .
				"\n-----END RSA PRIVATE KEY-----";
    	}else{
    		$priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $res = openssl_get_privatekey($priKey);
    	}
        
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, 'UTF-8');
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = 'UTF-8';
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }
    /**
     * 支付宝同步通知回调
     */
    public function aliReturn()
    {
        header('Refresh:2;url=/order/list');
        echo "订单： ".$_GET['out_trade_no'] . ' 支付成功，正在跳转';
//        echo '<pre>';print_r($_GET);echo '</pre>';die;
//        //验签 支付宝的公钥
//        if(!$this->verify($_GET)){
//            die('簽名失敗');
//        }
//
//        //验证交易状态
////        if($_GET['']){
////
////        }
////
//
//        //处理订单逻辑
//        $this->dealOrder($_GET);
    }
    /**
     * 支付宝异步通知
     */
    public function aliNotify()
    {
        $data = json_encode($_POST);
        $log_str = '>>>> '.date('Y-m-d H:i:s') . $data . "<<<<\n\n";
        //记录日志
        file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
        //验签
        $res = $this->verify($_POST);
        $log_str = '>>>> ' . date('Y-m-d H:i:s');
        if($res === false){
            //记录日志 验签失败
            $log_str .= " Sign Failed!<<<<< \n\n";
            file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
        }else{
            $log_str .= " Sign OK!<<<<< \n\n";
            file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
        }
        //验证订单交易状态
        if($_POST['trade_status']=='TRADE_SUCCESS'){
            //更新订单状态
            $oid = $_POST['out_trade_no'];     //商户订单号
            $info = [
                'is_pay'        => 1,       //支付状态  0未支付 1已支付
                'pay_amount'    => $_POST['total_amount'] * 100,    //支付金额
                'pay_time'      => strtotime($_POST['gmt_payment']), //支付时间
                'plat_oid'      => $_POST['trade_no'],      //支付宝订单号
                'plat'          => 1,      //平台编号 1支付宝 2微信 
            ];
            OrderModel::where(['oid'=>$oid])->update($info);
        }
        //处理订单逻辑
        $this->dealOrder($_POST);
        echo 'success';
    }
    //验签
    function verify($params) {
        $sign = $params['sign'];
        $params['sign_type'] = null;
        $params['sign'] = null;

        if($this->checkEmpty($this->aliPubKey)){
            $pubKey= $this->publicKey;
            $res = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        }else {
            //读取公钥文件
            $pubKey = file_get_contents($this->aliPubKey);
            //转换为openssl格式密钥
            $res = openssl_get_publickey($pubKey);
        }
        
       
        
        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);
        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
        //调用openssl内置方法验签，返回bool值
        $result = (openssl_verify($this->getSignContent($params), base64_decode($sign), $res, OPENSSL_ALGO_SHA256)===1);
        openssl_free_key($res);
        return $result;
    }
    /**
     * 处理订单逻辑 更新订单 支付状态 更新订单支付金额 支付时间
     * @param $data
     */
    public function dealOrder($data)
    {
        //加积分
        //减库存
    }
}
