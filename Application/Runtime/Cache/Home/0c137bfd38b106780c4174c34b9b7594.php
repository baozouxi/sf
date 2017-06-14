<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <!-- <link rel="stylesheet" type="text/css" href="/Public/css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <!-- <link rel="stylesheet" href="css/info.css"> -->
    <link rel="stylesheet" href="/Public/css/addPatient.css">
    <link rel="stylesheet" href="/Public/css/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src='/Public/js/jquery.min.js'></script>
    <script type="text/javascript" src='/Public/js/WdatePicker.js'></script>
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
                        <div class="left">医院名称：<a href="/" title='点我返回首页'><?php echo ($hospital_name); ?></a></div>
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
                                <li>入院日期</li>
                                <li>姓名</li>
                                <li class="age">年龄</li>
                                <li>性别</li>
                                <li>病种</li>
                                <li>手机号码</li>
                                <li class="doctor">接诊医生</li>
                                <li>操作</li>
                              </ul>
                              <ul class="input">
                                <input type="hidden" name="unique" value="<?php echo ($add_form_unique); ?>">
                                <li><input type="text" name="hospital_date" class="input_date" onclick="WdatePicker()"></li>
                                <li><input type="text" name="name" class="name"></li>
                                <li class="age"><input type="text" name="age" class="age" maxlength="3"></li>
                                <li>
                                    <select name="sex">
                                        <option value="男">---- 男 ----</option>
                                        <option value="女">---- 女 ----</option>
                                    </select>
                                </li>
                                <!-- <li><input type="text" name="illness" class="type"></li> -->
                                <li>
                                    <select name="illness">
                                        <option value="">--请选择--</option>
                                        <?php if(is_array($illness)): $i = 0; $__LIST__ = $illness;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ill): $mod = ($i % 2 );++$i;?><option value="<?php echo ($ill["illness_name"]); ?>"><?php echo ($ill["illness_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        <option value="">--手工填写--</option>
                                    </select>
                                </li>
                                <li><input type="text" name="tel" class="tel" maxlength="11"></li>
                                <li class="doctor">
                                    <select name="doctor" class="select_doctor">
                                        <option value="">--请选择--</option>
                                        <?php if(is_array($doctors)): $i = 0; $__LIST__ = $doctors;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$doctor): $mod = ($i % 2 );++$i;?><option value="<?php echo ($doctor["name"]); ?>"><?php echo ($doctor['name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        <option value="">--手工填写--</option>
                                    </select>
                                </li>
                                <li class="add"><span class="ok">√</span><span class="no">×</span></li>
                              </ul>
                            </div>
                        </div>

                        <div class="button_wrap">
                          <div class="addButton"></div>
                        </div>
                    </div>
                     <!--  <div class="submit">
                            添加病人
                     <input type="submit" value="添加">
                     </div> -->
                      
                </form>    
                </div>
            </div>
        </div>
    </div>

</body>
</html>