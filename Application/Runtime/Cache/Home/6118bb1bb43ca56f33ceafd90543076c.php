<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <!-- <link rel="stylesheet" type="text/css" href="/Public/css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <!-- <link rel="stylesheet" href="css/info.css"> -->
    <link rel="stylesheet" href="/Public/css/addPatient.css">
    <link rel="stylesheet" href="/Public/css/adminList.css">
    <link rel="stylesheet" href="/Public/css/hospitalAdminList.css">
    <link rel="stylesheet" href="/Public/css/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.mCustomScrollbar.js"></script>
   <!--  <script type="text/javascript" src='js/jquery.min.js'></script> -->
    <script type="text/javascript" src='/Public/js/jquery.date_input.pack.js'></script>
    <script type="text/javascript" src="/Public/js/com.js"></script>
    <script type="text/javascript" src="/Public/js/adminList.js"></script>
    <script type="text/javascript">
        var unique = '<?php echo ($unique); ?>';
    </script>
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
                        <div class="left"><a href="/Home/Index/index" title='点我返回首页'>返回首页</a></div>
                    </div>

                    <!--左侧导航隐藏按钮-->
                    <div class="_button">
                        <div class="hide"></div>
                    </div>  

                    <!--内容主体-->
                    <div class="form_con">
                        <div class="addForm">
                            <div class="fields">
                              <ul>
                                <li>姓名</li>
                                <li>电话</li>
                                <li>上次登录ip</li>
                                <li>上次登录时间</li>
                                <li>操作权限</li>
                                <li>当前状态</li>
                                <li>操作</li>
                              </ul>

                              <?php if(is_array($admin_list)): $admin_num = 0; $__LIST__ = $admin_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$admin): $mod = ($admin_num % 2 );++$admin_num;?><ul class="input">
                                        <li><?php echo ($admin["name"]); ?></li>
                                        <li><?php echo ($admin["tel"]); ?></li>
                                        <li><?php echo ($admin["last_login_ip"]); ?></li>
                                        <li><?php echo (date('Y-m-d',$admin['last_login_date'] )); ?></li>
                                        <?php switch($admin['permission']): case "0": ?><li>普通权限</li><?php break;?>
										<?php case "1": ?><li>管理员</li><?php break;?>
										<?php case "2": ?><li>超级管理员</li><?php break;?>
										<?php default: ?> <li></li><?php endswitch;?>
									
                                         <li><?php echo ($admin['is_use'] == '1' ? '正常' : '冻结'); ?></li>
                                            <?php if($admin['is_use'] == 0 ): ?><li><span>冻结</span>
                                                    <a href="/Home/Admin/access/unique/<?php echo ($unique); ?>/do/jiedong/id/<?php echo ($admin["id"]); ?>"><span>解冻</span></a> 
                                                    <!-- <a href="/Home/Admin/resetPass/unique/<?php echo ($unique); ?>/id/<?php echo ($admin["id"]); ?>"><span>重置密码</span></a> -->
                                                </li>
                                            <?php else: ?>
                                                <li><a href="/Home/Admin/access/unique/<?php echo ($unique); ?>/do/dongjie/id/<?php echo ($admin["id"]); ?>"><span>冻结</span></a> 
                                                   <span>解冻</span>
                                                    <!-- <a href="/Home/Admin/resetPass/unique/<?php echo ($unique); ?>/id/<?php echo ($admin["id"]); ?>"><span>重置密码</span></a> -->
                                                </li><?php endif; ?>
                                    </ul><?php endforeach; endif; else: echo "" ;endif; ?>
                              
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>