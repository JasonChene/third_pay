<?php
namespace Home\Controller;
use Think\Controller;
use Org\Net\Http;
use Think\Log;
class IndexController extends Controller {

    public function index(){
        $this->display();
    }

    public function doPostTest(){
    	$goodsname = I('money',0,'float');
    	if ($goodsname == 0 || !is_numeric($goodsname)) {
    		$data = array('status'=>0,'msg'=>'输入金额不合法');
    		$this->ajaxReturn($data,'json');
    	}
    	$type = I('type',1,'intval');
        $data['uid'] = '此处填写你的用户id';
        $data['istype'] = $type;  // 1-微信  2-支付宝
        $data['orderuid'] ='o1234i';
        $data['goodsname'] = $goodsname;
        $str_rand = rand(1,10000);  // 加入随机数
        $data['orderid'] = time().$str_rand;
        $data['notify_url'] = 'www.baidu.com';
        $data['need_qrcode'] = 1;
        $data['cashier_account'] = '';

        $user_token = '填写你的商户token';
        $key = md5($data['goodsname'].$data['istype'].$data['notify_url'].$data['orderid'].$data['orderuid'].$user_token.$data['uid']);
        
        $data['key'] = $key;

        $url = 'http://13.115.95.198/api/fmpay';
        $result_json = Http::request($url,$data,'POST');
        Log::write(sprintf('日志！post:%s Result:%s',json_encode($data),$result_json), Log::INFO,"",LOG_PATH.'doPostTest-'.date('Y-m-d').'.log');
        $result = json_decode($result_json,true);
        if ($result['code'] == 1 && $result['msg'] == '成功预充值') {
            if ($type == 2) {
                $call_back = 'alipays://platformapi/startapp?appId=10000007&&qrcode='.$result['data']['qrcode'];
            }else{
                $call_back = '';
            }
        	$data = array('status'=>1,'msg'=>'成功','url'=>$result['data']['qrcode'],'call_back'=>$call_back);
        }else{
        	$data = array('status'=>0,'url'=>'','msg'=>$result['msg'],'call_back'=>'');
        }
    	$this->ajaxReturn($data,'json');
    }
}