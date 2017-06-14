<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <!-- <link rel="stylesheet" type="text/css" href="./css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/info.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/adminUpdate.css">
    <link rel="stylesheet" href="/Public/css/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.mCustomScrollbar.js"></script>
   <!--  <script type="text/javascript" src='js/jquery.min.js'></script> -->
    <script type="text/javascript" src='/Public/js/jquery.date_input.pack.js'></script>
    <script type="text/javascript" src="/Public/js/com.js"></script>
    <script type="text/javascript" src="/Public/js/adminUpdate.js"></script>
    <title>管理首页</title>
</head>
<body>
    <div id="body_wrap">

    <!-- 提示框 -->
    <div class="sys_msg_tips"></div>
        <!--头部-->
                
    <!-- 头部 -->
        <div id="header">
            <div class="warp">
                <div class="left">信息管理系统</div>
                <div class="right"><img src="/Public/img/top.png" alt=""></div>
            </div>
        </div>

        <!-- 主体 -->
        <div id="con">
            <div class="wrap">
            <!--导航栏部分-->
                 <!--导航栏部分-->
                <div class="main_nav">

                     <?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu_item): $mod = ($i % 2 );++$i;?><div class="nav_item">
                            <p><?php echo ($key); ?></p>
                            <ul>
                                <?php if(is_array($menu_item)): $i = 0; $__LIST__ = $menu_item;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu_url): $mod = ($i % 2 );++$i;?><a href="<?php echo ($menu_url["url"]); ?>" <?php echo ($menu_url['hidden'] ? 'style="display:none"' : ''); ?> ><li><?php echo ($key); ?></li></a><?php endforeach; endif; else: echo "" ;endif; ?>

                            </ul>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        <div class="nav_item">
                            <a href="/Master/Login/logout"><p>退出登录</p></a>
                        </div>
                </div>

                <!--显示表单部分-->
                <div class="form">
                    <div class="title">
                        <div class="left"><a href='/Master/Index/index' title='点我返回首页'>返回首页</a></div>
                        <div class="right">
                        
                        </div>
                    </div>

                    <!--左侧导航隐藏按钮-->
                    <div class="_button">
                        <div class="hide"></div>
                    </div>  

                    <!--内容主体-->
                    <div class="form_con">
                        <?php if($admin_info["username"] == "" || $admin_info["password"] == "" ): ?><form action="" name='update'>
                             <div class="add_admin">
                                <p class='title1'>完善账户信息</p>
                                <input type="hidden" value="<?php echo ($unique); ?>" name='unique'>
                                <input type="hidden" value="<?php echo ($admin_info["id"]); ?>" name='id'>
                                <p>姓名：　　　<input type="text" value="<?php echo ($admin_info["name"]); ?>" name='admin_name' readonly="true"></p>
                                <p>用户名：　　<input type="text" value="" name='admin_username'></p>
                                <p>新密码：　　<input type="password" value="" name='admin_new_password'></p>
                                <p>确认密码：　<input type="password" value="" class="checkPass"></p>
                                <p>电话号码：　<input type="text" value="<?php echo ($admin_info["tel"]); ?>" readonly="true" maxlength="11"></p>
                                <p class='ver_right'>验证码：　　<input type="text" value=""  name="verify" class="verify"><img src="<?php echo U('Login/verify');?>" alt=""></p>
                                <p class="button"><span>完善</span></p>
                            </div>
                            </form>
                        <?php else: ?>
                            <form action="" name='update'>
                            <div class="add_admin">
                                <p class='title1'>修改密码</p>
                                <input type="hidden" value="<?php echo ($unique); ?>" name='unique'>
                                <input type="hidden" value="<?php echo ($admin_info["id"]); ?>" name='id'>
                                <p>姓名：　　　<input type="text" value="<?php echo ($admin_info["name"]); ?>" name='admin_name' readonly="true"></p>
                                <p>原密码：　　<input type="password" value="" name='admin_old_password'></p>
                                <p>新密码：　　<input type="password" value="" name='admin_new_password'></p>
                                <p>确认密码：　<input type="password" class='checkPass'></p>
                                <p>电话号码：　<input type="text" value="<?php echo ($admin_info["tel"]); ?>" readonly="true" maxlength="11"></p>
                                <p class='ver_right'>验证码：　　<input type="text" value=""  name="verify" class="verify"><img src="<?php echo U('Login/verify');?>" alt=""></p>
                                <p class="button"><span>修改</span></p>
                            </div>
                            </form><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>