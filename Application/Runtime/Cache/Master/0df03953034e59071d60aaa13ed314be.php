<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <!-- <link rel="stylesheet" type="text/css" href="/Public/css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <!-- <link rel="stylesheet" href="css/info.css"> -->
    <link rel="stylesheet" href="/Public/css/addPatient.css">
    <link rel="stylesheet" href="/Public/css/illness.css">
    <link rel="stylesheet" href="/Public/css/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.mCustomScrollbar.js"></script>
   <!--  <script type="text/javascript" src='js/jquery.min.js'></script> -->
    <script type="text/javascript" src='/Public/js/jquery.date_input.pack.js'></script>
    <script type="text/javascript" src="/Public/js/com.js"></script>
    <script type="text/javascript" src="/Public/js/adminAddIllness.js"></script>
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
                        <div class="left">当前医院：<a href="/Master/Index/index" title='点我返回首页'><?php echo ($hospital_name); ?></a></div>
                        <!-- <div class="right">
                        
                            搜索框 
                            <div class="word">搜索病人：</div>
                            <div class="input search">
                                <input value="" type="text" maxlength="5">
                                <span class="search_button"></span>
                            </div>
                            <div class="word">选择月份：</div>
                            <div class="input">
                                <input value="2016-05-08" type="text" class="date_input">
                            </div>
                        </div>-->
                    </div>

                    <!--左侧导航隐藏按钮-->
                    <div class="_button">
                        <div class="hide"></div>
                    </div>  
                <form action="<?php echo U('addPatient');?>" method="post" name="add">                    <!--内容主体-->
                    <div class="form_con">
                        <div class="addForm">
                            <div class="fields">
                              <ul>
                                <li>疾病名称</li>
                                <li>操作</li>
                                <li>疾病名称</li>
                                <li>操作</li>
                                <li>疾病名称</li>
                                <li>操作</li>
                              </ul>
                              <ul class="input">
                                <input type="hidden" name="unique" value="<?php echo ($add_form_unique); ?>">
                                <li><input type="text" name="name" class="name"></li>
                                <li class="add"><span class="ok">√</span><span class="no">×</span></li>
                              </ul>
                              <ul class="input">
                                <input type="hidden" name="unique" value="<?php echo ($add_form_unique); ?>">
                                <li><input type="text" name="name" class="name"></li>
                                <li class="add"><span class="ok">√</span><span class="no">×</span></li>
                              </ul>
                              <ul class="input">
                                <input type="hidden" name="unique" value="<?php echo ($add_form_unique); ?>">
                                <li><input type="text" name="name" class="name"></li>
                                <li class="add"><span class="ok">√</span><span class="no">×</span></li>
                              </ul>
                            </div>
                        </div>

                        <div class="button_wrap">
                          <div class="addButton"></div>
                        </div>
                    </div>        
                </form>    
                </div>
            </div>
        </div>
    </div>

</body>
</html>