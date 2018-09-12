<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2018/7/25
 * Time: 23:54
 */

class Test 
{
	//发送请求
    public function pay(){
        $public_key = '公钥';
        $pu_key = openssl_pkey_get_public($public_key);
        $user_key = '用户key';
        $rand = rand(10,99);
        $data = array(
            'key' => md5($rand.$user_key),
            'member_id' => '商户ID',
            'rand'=>$rand,
            'order_id'=>'定单ID',
            'user_name'=>'用户名',
            'order_money'=>'定单金额',
            'istype'=>'支付方式',
        );
        $data = json_encode($data);
        $encrypted = encrypt_rsa($data,$pu_key);//公钥加密

        $res = curl_post('http://生产地址/api/order/pay',['info'=>$encrypted]);
        $res = json_decode($res,true);
    }

    //回调通知处理
    public function notify(){
        $public_key = '公钥';
        $pu_key = openssl_pkey_get_public($public_key);
        $user_key = '商户key';
        $data = input('info');
        $data = pub_decrypt_rsa($data,$pu_key);
        $data = json_decode($data,true);
        if($data['key'] == md5($data['rank'].$user_key)){
           //业务处理
        }
    }

    
	function encrypt_rsa($data, $pu_key){
    		$split = str_split($data, 100);// 1024bit && OPENSSL_PKCS1_PADDING  不大于117即可
    		$encode_data = '';
    		foreach ($split as $part) {
        	$isOkay = openssl_public_encrypt($part, $en_data, $pu_key);
        	if(!$isOkay){
            		return false;
        	}
        	// echo strlen($en_data),'<br/>';
        	$encode_data .= base64_encode($en_data);
    		}
    		return $encode_data;
	}
    function pub_decrypt_rsa($data, $pi_key){
        $split = str_split($data, 172);// 1024bit  固定172
        $decode_data = '';
        foreach ($split as $part) {
            $isOkay = openssl_public_decrypt(base64_decode($part), $de_data, $pi_key);// base64在这里使用，因为172字节是一组，是encode来的
            if(!$isOkay){
                return false;
            }
        $decode_data .= $de_data;
        }
        return $decode_data;
    }
}