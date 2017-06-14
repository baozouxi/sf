<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/login.css">
    <script type="text/javascript" src="http://www.js-css.cn/jscode/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/login.js"></script>
    <script type="text/javascript">
        var checkUrl  = "<?php echo U('checkUser');?>",
            verifyUrl = "<?php echo U('sendVerify');?>",
            loginUrl  = "<?php echo U('login');?>";

    </script>
</head>
<body>
    <div id="login">
        <div class="wrap">
            <div class="box">
                <div class="left">
                    <img src="/Public/img/tu1.png" alt="">
                </div>
                <div class="right">
               
                   <div class="title">信息管理系统</div>
                    <!--电话登录部分-->
                    <form action="<?php echo U('login');?>" method="post" name="tel" style="display: none;">
                    <input type="hidden" name='_unique' value="<?php echo ($_unique); ?>">
                       <div class="input_item">
                            手机号：<input type="text" name='tel'>
                            <p style="text-align: center; font-size:13px;color:red;"></p>
                       </div>
                       <div class="input_item verify">
                            <div class="ver_left">
                                验证码：<input type="text" name='verify'>
                            </div>
                            <div class="ver_right">
                                发送验证码
                            </div>
                       </div>
                       <div class="input_item">
                            <div class="submit"></div>
                           <input type="submit" class="submit" value="登录">
                       </div>
                    </form>
                
                <!--用户名登录部分-->
                     <form action="<?php echo U('login');?>" method="post" name="username"  >
                    <input type="hidden" name='_unique' value="<?php echo ($_unique); ?>">
                        <div class="input_item">
                            用户名：<input type="text" name='username'>
                            <p style="text-align: center; font-size:13px;color:red;"></p>
                        </div>
                        <div class="input_item">
                            密　码：<input type="password" name='password'>
                            <p style="text-align: center; font-size:13px;color:red;"></p>
                        </div>
                        <div class="input_item verify">
                            <div class="ver_left">
                                验证码：<input type="text" name='verify'>
                            </div>
                            <div class="ver_right verifyImg">
                                <img src="<?php echo U('verify');?>" alt="">
                            </div>
                        </div>
                       <div class="input_item">
                            <div class="submit"></div>
                           <input type="submit" class="submit" value="登录">
                       </div>
                    </form>

                </div>
            <div class="change">切换登录方式</div>
            </div>
        </div>
    </div>
</body>
</html>