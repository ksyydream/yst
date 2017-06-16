<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 系统设置模型
 * 可用于抓取系统初始数据以及定义系统变量和获取一些首页需要的信息
 *
 * @package		app
 * @subpackage	core
 * @category	model
 * @author		yaobin<645894453@qq.com>
 *        
 */
class Sysconfig_model extends MY_Model
{

    public function __construct ()
    {
        parent::__construct();
    }

    public function __destruct ()
    {
        parent::__destruct();
    }

    public function check_openid($openid){
        $user_info = $this->db->select()->from('user_info')->where('openid',$openid)->get()->row_array();
        if($user_info){
            $this->session->set_userdata('username',$user_info['name']);
        }else{
            $this->config->load('wxpay_config');
            $appid = $this->config->item('appid');
            $secret = $this->config->item('appsecret');
            $openid = $openid;
            $rs = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}");
            $rs=json_decode($rs,true);
            $access_token = $rs['access_token'];
            $rs = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN");
            $rs=json_decode($rs,true);
            $data = array(
                'name'=>$rs['nickname'],
                'sex'=>$rs['sex'],
                'openid'=>$openid
            );
            $this->db->insert('user_info',$data);
            $this->session->set_userdata('headimgurl', $rs['headimgurl']);
            $this->session->set_userdata('username',$user_info['name']);
        }
    }
    

}

/* End of file sysconfig_model.php */
/* Location: ./application/models/sysconfig_model.php */