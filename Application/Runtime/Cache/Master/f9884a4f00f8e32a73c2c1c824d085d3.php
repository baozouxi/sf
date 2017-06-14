<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/index.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <link rel="stylesheet" href="/Public/css/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.mCustomScrollbar.js"></script>
   <!--  <script type="text/javascript" src='js/jquery.min.js'></script> -->
    <script type="text/javascript" src='/Public/js/jquery.date_input.pack.js'></script>
    <script type="text/javascript" src="/Public/js/com.js"></script>
    <script type="text/javascript" src="/Public/js/adminIndex.js"></script>
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
                        <div class="left">选择医院：
                            <span>
                                <select name="changeHospital">
                                    <?php if(is_array($hospital_list)): $i = 0; $__LIST__ = $hospital_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hospital): $mod = ($i % 2 );++$i; if($hospital["id"] == $_SESSION['hospital_id']): ?><option selected="selected" value="<?php echo ($hospital["id"]); ?>"><?php echo ($hospital["name"]); ?></option>  
                                            <?php else: ?> 
                                                <option value="<?php echo ($hospital["id"]); ?>"><?php echo ($hospital["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                    
                                </select>
                            </span>
                        </div>
                        <!-- <div class="middle">
                            <p>剩余金额：<span><?php echo ($leav); ?></span></p>
                            <p>当前比例：<span><?php echo ($hospital_info['percent']*100); ?>%</span></p>
                        </div> -->
                        <div class="right">

                            <!-- 筛选病人 -->
                            <div class="word" style="margin-right: 50px;">
                                <span class="search_open" style="color: #666; cursor: pointer;">筛选病人</span>
                            </div>

                         

 
                            <!-- 搜索框 -->
                            <div class="word">搜索病人：</div>
                            <div class="input search">
                                <input value="" type="text" maxlength="5">
                                <span class="search_button"></span>
                            </div>
                            <div class="word">月份：</div>
                            <div class="input">
                                <input value="<?php echo date('Y-m-d');?>" type="text" class="date_input">
                            </div>
                        </div>



                        <form name="search_form">
                        <input type="hidden" name='unique' value="<?php echo ($unique); ?>">
                            <!-- 筛选病人主体 -->
                        <div class='_search_pro'>
                            <div class="title">筛选病人</div>
                          <!--   <p class="item">是否复诊</p>
                            <div class="circle">
                                <input type="radio" name='is_back' value="1">是 
                            
                                <input type="radio" name='is_back' value="0">否 
                            </div> -->
                            <p class="item">选择医生</p>
                            <div class="circle">
                                <select name="doctor">
                                    <option value="">--请选择--</option>
                                    <?php if(is_array($doctors)): $i = 0; $__LIST__ = $doctors;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$doctor): $mod = ($i % 2 );++$i;?><option value="<?php echo ($doctor["name"]); ?>"><?php echo ($doctor["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>

                            <p class="item">消费区段</p>
                            <div class="circle">
                                最小金额：<input type="text" name="cost_least">
                                最大金额：<input type="text" name="cost_max">
                            </div>
                            <div style="clear: both;"></div>
                            <div class="button">
                                <span class="search_button">搜索</span>
                                <span class="search_close">关闭</span>
                            </div>
                        </div>
                        </form>
                    </div>

                    <!--左侧导航隐藏按钮-->
                    <div class="_button">
                        <div class="hide"></div>
                    </div>  
                    <!--内容主体-->
                    <div class="form_con">

                        <div class="fields">
                            <ul>
                                <li class="first">
                                    <div class="date_field">日期</div>
                                    <div class="name_field">姓名</div>
                                    <div class="diagonal"></div>
                                </li>
                                
                                <?php if(is_array($days)): $i = 0; $__LIST__ = $days;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$day): $mod = ($i % 2 );++$i;?><li><?php echo ($key+1); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                
                                <li class="cost">消费总额</li>
                            </ul>
                        </div>
                        <!-- 唯一标示 -->
                        <input type="hidden" name="unique"  value="<?php echo ($unique); ?>">

                        <div class="user_info">
                        <?php if(is_array($spending_list)): $i = 0; $__LIST__ = $spending_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item_info): $mod = ($i % 2 );++$i;?><ul>
                                <a href="/Master/Patient/info/tel/<?php echo ($key); ?>"><li><?php echo ($item_info["name"]); ?></li></a>
                                
                                <?php if(is_array($item_info["cost"])): $i = 0; $__LIST__ = $item_info["cost"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$patient_info): $mod = ($i % 2 );++$i;?><li><?php echo ($patient_info["cost_money"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                <li class='cost'><?php echo ($item_info["sum_money"]); ?></li>
                            </ul><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>