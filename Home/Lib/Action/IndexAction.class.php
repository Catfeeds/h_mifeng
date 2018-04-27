<?php
class IndexAction extends CommonAction {
	public function __construct() {
		parent::__construct();
	}

    public function index() {
        //导航索引ID
        $this->assign('nav_nid',1);
        /************************banner*****************************/
        $banner = D('Ads')->lists(1,10);
        $this->assign('banner',$banner);
        //幼教资讯
        $article_cat1 = M("Articlecat")->where("cat_id=75")->find();
        $article1 = D("Article")->listArticle(0,3,array("cat_id"=>75,"is_recommend"=>1));
        $this->assign('article_cat1',$article_cat1);
        $this->assign('article1',$article1);

        //蜜蜂热点
        $article_cat2 = D("Articlecat")->where("cat_id=76")->find();
        $article2 = D("Article")->listArticle(0,3,array("cat_id"=>76,"is_recommend"=>1));
        $this->assign('article_cat2',$article_cat2);
        $this->assign('article2',$article2);
        //蜜蜂蜂会
        $meeting_list = D('Meeting')->lists(0,3);
        $this->assign('meeting_list',$meeting_list);
        //幼儿干货
        $this->assign('Information_cat1',M("InformationCat")->where("cat_id=1")->find());
        $this->assign("Information1", D("Information")->InformationList(0,2,['cat_id'=>1,"verify"=>1]));
        $this->assign('Information_cat2',M("InformationCat")->where("cat_id=2")->find());
        $this->assign("Information2", D("Information")->InformationList(0,2,['cat_id'=>2,"verify"=>1]));
        $this->assign('Information_cat3',M("InformationCat")->where("cat_id=3")->find());
        $this->assign("Information3", D("Information")->InformationList(0,2,['cat_id'=>3,"verify"=>1]));

        // 蜜蜂精选
        $index_5 = D('Ads')->lists(8,3);
        $this->assign('index_5',$index_5);

        // 按钮
        $btn_list = D('Ads')->lists(9,4);
        $this->assign('btn_list',$btn_list);

        $this->display();
    }
    public function weidian(){
        $this->display();
    }

    public function notify(){
        require_once "wx/lib/WxPay.Api.php";
        require_once "wx/example/WxPay.JsApiPay.php";
        $streamData = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        if(empty($streamData)){
            $streamData = file_get_contents('php://input');  
        }
        error_log($streamData."\r\n",3,'res.log');
        if(!empty($streamData)){
            $streamData=$this->xmlToArray($streamData);
            if(!$this->Queryorder($streamData["transaction_id"])){
                // error_log('123'."\r\n",3,'res.log');
                $msg = "订单查询失败";
                error_log($streamData["transaction_id"]."查询失败"."\r\n",3,'res.log');
                return false;
            }
            $integral_order = M("IntegralOrder")->where("out_trade_no='".$streamData['out_trade_no']."'")->find();
            $price = round($integral_order['price']*100);
            if($streamData['total_fee']!=$price){
                // error_log('321'."\r\n",3,'res.log');
                error_log("付款金额不正确\r\n",3,'res.log');
                return false;
            }
            if($integral_order['status']==0){
                M("IntegralOrder")->where("out_trade_no='".$streamData['out_trade_no']."'")->data(['pay_status'=>1,"pay_time"=>time()])->save();
                if(D("Integral")->integral($integral_order['integral'],$integral_order['user_id'],"积分兑换(金额".$integral_order['price'].")",8)){
                    M("IntegralOrder")->where("out_trade_no='".$streamData['out_trade_no']."'")->data(['status'=>1])->save();
                }
            }
        }
    }
    //验证订单
    public function Queryorder($transaction_id){
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        if(array_key_exists("return_code", $result)&& array_key_exists("result_code", $result)&& $result["return_code"] == "SUCCESS"&& $result["result_code"] == "SUCCESS"){
            return true;
        }
        return false;
    }
    //xml转换
    public function xmlToArray($xml){ 
        libxml_disable_entity_loader(true); 
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
        $val = json_decode(json_encode($xmlstring),true); 
        return $val; 
    }
}
?>