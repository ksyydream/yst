<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: yangyang
 * Date: 2016/4/29
 * Time: 22:03
 */
class Product extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('CI_Wechat');
        $this->load->model('product_model');
        $this->cismarty->assign('footer_flag',1);
    }

    function index(){
        $data=$this->product_model->get_product_recommend();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('index.html');
    }
    
    function product_main($flag=1){
        $data=$this->product_model->get_product_main($flag);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('product.html');
    }
    
    function product_list($id,$flag=1){
        $data=$this->product_model->get_product_list($id,$flag);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('product_list.html');
    }

    function product_info($id,$html_flag=-1){
        $data=$this->product_model->get_product_info($id);
        $this->cismarty->assign('html_flag',$html_flag);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('product_info.html');
    }

    function product_type(){
        $data=$this->product_model->get_product_type();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('prt_type.html');
    }

    /** 这里显示购物车信息 */
    function show_cart(){
        $data=$this->product_model->show_cart();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('cart.html');
    }

    /** 这里做地址的处理 */
    function add_address($type,$pid=-1,$html_flag=-1){
        $this->cismarty->assign('type',$type);
        $this->cismarty->assign('pid',$pid ? $pid : -1);
        $this->cismarty->assign('html_flag',$html_flag ? $html_flag : -1);
        $this->cismarty->display('add_address.html');
    }

    function save_address($page=1,$pid=-1,$html_flag=-1){
      //  die(var_dump($this->input->post())) ;
       $this->product_model->save_address();
        if ($page == 1){
            redirect('product/show_cart');
        }elseif ($page ==2){
            redirect('product/my_address');
        }else{
            if($pid > 0){
                redirect('product/product_info/'.$pid.'/'.$html_flag);
            }else{
                redirect('product/my_address');
            }

        }
    }

    function edit_address($id){
        //  die(var_dump($this->input->post())) ;
        $data = $this->product_model->get_address($id);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('edit_address.html');
    }

    function delete_address($id){
         $this->product_model->delete_address($id);
         redirect('product/my_address');
    }

    /** 这里保存订单信息 */
    function save_order(){
        $data=$this->product_model->save_order();
        if ($data == -1){
            redirect('product/show_cart');
        }else{
            redirect('product/order_info/'.$data);
        }
    }

    /** 这里显示订单信息 */
    function show_order(){
        $data=$this->product_model->show_order();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_order.html');
    }

    /** 这里显示订单详情 */
    function order_info($id,$status=null){
        $data=$this->product_model->order_info($id);
        $this->cismarty->assign('status',$status);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_order_info.html');
    }

    /** 这里进入个人中心 */
    function my_center(){
        $data=$this->product_model->order_num();
        $this->cismarty->assign('username',$this->session->userdata('username'));
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_center.html');
    }

    /** 这里显示各种状态的订单 */
    function status_order($status=null){
        $data=$this->product_model->show_order($status);
        $this->cismarty->assign('status',$status);
        $this->cismarty->assign('data',$data);
        switch ($status){
            case 1:
                $this->cismarty->display('my_order_status.html');
                break;
            case 2:
                $this->cismarty->display('my_order_status.html');
                break;
            case 3:
                $this->cismarty->display('my_order_status.html');
                break;
            case 4:
                $this->cismarty->display('my_order_status.html');
                break;
            case 5:
                $this->cismarty->display('my_order_status.html');
                break;
            default:
                $this->cismarty->display('my_order_status.html');
        }

    }

    function my_address(){
        $data=$this->product_model->my_address();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_address.html');
    }

    function feedback($id,$status=null){
        $this->cismarty->assign('id',$id);
        $this->cismarty->assign('status',$status);
        $this->cismarty->display('feedback.html');
    }

    function save_feedback($status=null){
        $this->product_model->save_feedback();
        redirect('product/status_order/'.$status);
    }

    function delete_order($id,$status=null){
        $this->product_model->delete_order($id);
        if($status==1){
            redirect('product/status_order/1');
        }else{
            redirect('product/status_order/4');
        }
    }

    function show_express($id,$status=null){
        $data = $this->product_model->show_express($id);
        //die(var_dump($data));
        if($data['head']!=1){
            $express = $this->getorder($data['head']['express'],$data['head']['express_num']);
        }
        if(isset($express['data'])){
            $data['express'] = $express['data'];
        }else{
            $data['express'] = array(array('context'=>'暂无快递信息','time'=>date('Y-m-d H:i:s',time())));
        }
        $this->cismarty->assign('status',$status);
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('express_info.html');
    }

    function my_info(){
        $data = $this->product_model->my_info();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_info.html');
    }

    function save_my_info(){
        $res = $this->product_model->save_my_info();
        redirect('product/my_center');
    }

    function my_house(){
        $data = $this->product_model->my_house();
        $this->cismarty->assign('data',$data);
        $this->cismarty->display('my_house.html');
    }

    function delete_house($id){
        $res = $this->product_model->delete_house($id);
        redirect('product/my_house');
    }

    function receipt_goods($id,$status=null){
        $res = $this->product_model->receipt_goods($id);
        redirect('product/status_order/'.$status);
    }

    function refund($id,$status=null){
        $res = $this->product_model->refund($id);
        redirect('product/status_order/'.$status);
    }

    function buy_pro($pid,$pd_id,$qty,$html_flag){
        $data = $this->product_model->get_pro_info($pid,$pd_id);
        $this->cismarty->assign('pid',$pid);
        $this->cismarty->assign('pd_id',$pd_id);
        $this->cismarty->assign('qty',$qty);
        $this->cismarty->assign('data',$data);
        $this->cismarty->assign('html_flag',$html_flag);
        $this->cismarty->display('order_confirm.html');
    }

    function save_order_one(){
        $data = $this->product_model->save_order_one();
        if ($data == -1){
            redirect('product/index');
        }else{
            redirect('product/order_info/'.$data);
        }
    }

    public function pay1($order_id){
        //微信支付配置的参数配置读取
        $this->load->config('wxpay_config');
        $wxconfig['appid']=$this->config->item('appid');
        $wxconfig['mch_id']=$this->config->item('mch_id');
        $wxconfig['apikey']=$this->config->item('apikey');
        $wxconfig['appsecret']=$this->config->item('appsecret');
        $wxconfig['sslcertPath']=$this->config->item('sslcertPath');
        $wxconfig['sslkeyPath']=$this->config->item('sslkeyPath');
        $this->load->library('Wechatpay',$wxconfig);
        //商户交易单号
        $order_data = $this->product_model->order_info($order_id);
        $out_trade_no = $order_data['main']['num'];
        $total_fee = 0;
        $body_info = "";
        $body_detail = 1;
        if($order_data['detail']==1){
            ///如果没有明细数据就返回
        }
        foreach($order_data['detail'] as $key => $value){
            $total_fee += $value['price'] * $value['qty'];
            if($key == 0){
                $body_info = $value['pro_name'].$value['de_size'];
            }else{
                $body_detail +=1;
            }
        }
        if($body_detail==1){
            $param['body'] = $body_info;
        }else{
            $param['body'] = $body_info.'等'.$body_detail.'商品';
        }

        $param['attach'] = '0012';
        $param['detail'] = "螺蛳湾-".$out_trade_no;
        $param['out_trade_no'] = $out_trade_no;
        $param['total_fee'] = $total_fee * 100;
        $param["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];
        $param["time_start"] = date("YmdHis");
        $param["time_expire"] = date("YmdHis", time() + 600);
        $param["goods_tag"] = "螺蛳湾";
        $param["notify_url"] = base_url()."/product/notify";
        $param["trade_type"] = "JSAPI";
        $param["openid"] = $this->session->userdata('openid');

        //统一下单，获取结果，结果是为了构造jsapi调用微信支付组件所需参数
        $result = $this->wechatpay->unifiedOrder($param);

        //如果结果是成功的我们才能构造所需参数，首要判断预支付id

        if (isset($result["prepay_id"]) && !empty($result["prepay_id"])) {
            //调用支付类里的get_package方法，得到构造的参数
            $data['parameters'] = json_encode($this->wechatpay->get_package($result['prepay_id']));
            $data['notifyurl'] = $param["notify_url"];
            $data['fee'] = $total_fee;
            $data['pubid'] = $out_trade_no;
            $data['orderid'] = $order_id;
            $this->load->view('pay', $data);
        }
    }

    function notify($order_num)
    {
        if ($this->session->userdata('openid')) {
            $rs = $this->product_model->confirm_order($order_num);
            echo $rs;
        }
    }
}