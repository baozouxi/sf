<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <!-- <link rel="stylesheet" type="text/css" href="/Public/css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <!-- <link rel="stylesheet" href="css/info.css"> -->
    <link rel="stylesheet" href="/Public/css/totalMonth.css">
    <link rel="stylesheet" href="/Public/css/style.css">
    <link rel="stylesheet" href="/Public/css/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.mCustomScrollbar.js"></script>
   <!--  <script type="text/javascript" src='js/jquery.min.js'></script> -->
    <script type="text/javascript" src='/Public/js/jquery.date_input.pack.js'></script>
    <script type="text/javascript" src="/Public/js/com.js"></script>
    <script type="text/javascript" src="/Public/js/addPatient.js"></script>
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
                    <div class="totalImg">
                        <h1 style="text-align: center;padding: 20px 0px;"><?php echo ($date); ?>年消费情况</h1>
        
                        <?php if(is_array($list_data)): $a = 0; $__LIST__ = $list_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($a % 2 );++$a;?><div style="width:100%; text-align: center;font-size: 15px;font-weight: bold; margin: 10px 0px;">---------<?php echo ($key); ?>-------------</div>
                            <?php if(is_array($item)): foreach($item as $k=>$_item): ?><div class="skillbar clearfix " data-percent="<?php echo ($_item['money']/$total*100); ?>%">
                                    <div class="skillbar-title" style="background: <?php echo ($color[$a]); ?>;"><span><?php echo ($k); ?></span></div>
                                    <div class="skillbar-bar" style="background: <?php echo ($color[$a]); ?>;"></div>
                                    <div class="skill-bar-percent"><?php echo ($_item["money"]); ?>元(<?php echo ($_item['count']); ?>人)</div>
                                </div> <!-- End Skill Bar --><?php endforeach; endif; endforeach; endif; else: echo "" ;endif; ?>

                        <div style="text-align:center;clear:both;">
                        <script src="/gg_bd_ad_720x90.js" type="text/javascript"></script>
                        <script src="/follow.js" type="text/javascript"></script>
                        </div>

                          <!-- <script src='js/jquery.js'></script> -->

                        <script src="/Public/js/index2.js"></script>
                    </div>   
                </div>
            </div>
        </div>
    </div>

</body>
</html>