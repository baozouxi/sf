<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <!-- <link rel="stylesheet" type="text/css" href="/Public/css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <!-- <link rel="stylesheet" href="css/info.css"> -->
    <link rel="stylesheet" href="/Public/css/addPatient.css">
    <link rel="stylesheet" href="/Public/css/addDoctor.css">
    <link rel="stylesheet" href="/Public/css/HomeAddAdmin.css">
    <link rel="stylesheet" href="/Public/css/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.mCustomScrollbar.js"></script>
   <!--  <script type="text/javascript" src='js/jquery.min.js'></script> -->
    <script type="text/javascript" src='/Public/js/jquery.date_input.pack.js'></script>
    <script type="text/javascript" src="/Public/js/com.js"></script>
    <script type="text/javascript" src="/Public/js/HomeAddAdmin.js"></script>
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
                                <?php if(is_array($menu_item)): $i = 0; $__LIST__ = $menu_item;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu_url): $mod = ($i % 2 );++$i;?><a href="<?php echo ($menu_url); ?>"><li><?php echo ($key); ?></li></a><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    <div class="nav_item">
                        <a href="/Home/Login/logout"><p>退出登录</p></a>
                    </div>
                </div>

                <!--显示表单部分-->
                <div class="form">
                    <div class="title">
                        <div class="left"><a href="/Master/Index/index" title='点我返回首页'>返回首页</a></div>
                    </div>

                    <!--左侧导航隐藏按钮-->
                    <div class="_button">
                        <div class="hide"></div>
                    </div>  
                <form action="<?php echo U('addAdminHandle');?>" method="post" name="add">                    <!--内容主体-->
                    <div class="form_con">
                        <div class="addForm">
                            <div class="fields">
                              <ul>
                                <li>姓名（必填）</li>
                                <li>手机号码(必填)</li>
                                <li>权限</li>
                                <li>操作</li>
                              </ul>
                              <ul class="input">
                                <input type="hidden" name="unique" value="<?php echo ($add_form_unique); ?>">
                                <li><input type="text" name="name" class="name"></li>
                                <li><input type="text" name="tel" class="tel" maxlength="11"></li>
                                <li>
                                    <select name="permission">
                                        <option value="0">普通权限</option>
                                        <option value="1">管理员</option>
                                        <option value="2">超级管理员</option>
                                    </select>
                                </li>
                                <li class="add"><span class="ok">√</span><span class="no">×</span></li>
                              </ul>
                            </div>
                        </div>

                        <div class="button_wrap">
                          <div class="addButton"></div>
                        </div>
                        <div>
                            
                        </div>
                    </div>

                   <!--  <div class="submit">
                        添加管理员
                        <input type="submit" value="添加">
                    </div> -->
                </form>    
                </div>
            </div>
        </div>
    </div>

</body>
</html>