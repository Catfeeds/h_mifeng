<?php
class IntegralOrderModel extends Model {
  public function out_trade_no(){
    $out_trade_no=date('YmdHis').mt_rand(10000,99999);
    $count = M("IntegralOrder")->where("out_trade_no='$out_trade_no'")->count();
    if($count){
        $out_trade_no = $this->out_trade_no();
    }
    return $out_trade_no;
  }
}
?>