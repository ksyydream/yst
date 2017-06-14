<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="/res/css/shop/style.css" />
    <link rel="stylesheet" type="text/css" href="/res/css/shop/jquery.bxslider.css" />
    <link rel="stylesheet" type="text/css" media="screen and (max-width: 480px)" href="/res/css/css_app.css" />
    <link rel="stylesheet" type="text/css" media="screen and (min-width: 480px)" href="/res/css/css_pc.css" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <script type="text/javascript" src="/res/js/jquery-1.11.0.min.js"></script>
    <title>微信支付</title>
</head>
<body>

    <?php
    $jsApiParameters = $parameters;//参数赋值
    ?>

    <script type="text/javascript">

        //我在这里选择了前台只要支付成功将单号传递更新数据
//        $.get('<?php //echo site_url('product/notify').'/'.$pubid?>//',function(data){
//            alert(data);
//        });


        //调用微信JS api 支付my_info
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
                function(res){
                    WeixinJSBridge.log(res.err_msg);

                    if(res.err_msg == "get_brand_wcpay_request:ok" ){

                        $.get('<?php echo site_url('product/notify').'/'.$pubid;?>',function(ret){
                            if(ret==1){
                                alert('支付成功');
                                //成功后返回我的订单页面
                                location.href='<?php echo base_url().'product/order_info/'.$orderid;?>';
                            }else{
                                alert(ret)
                            }
                        });

                    }else
                    {
                        alert('支付失败');
                    }
                }
            );
        }

        function callpay()
        {

            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
    <div class="hd">
        <h1 class="page_title">支付佣金</h1>
        <p class="page_desc">请认真核对佣金金额</p>
    </div>
    <div class="weui_cells">
        <div class="weui_cell">
            <div class="weui_cell_hd weui_cell_primary">
                该笔订单支付金额为<span style="color:#f00;font-size:50px"><?php echo $fee; ?></span>元钱
            </div>
        </div>
    </div>
    <button class="weui_btn weui_btn_primary" type="button" onclick="callpay()" >立即支付</button>

</body>
</html>