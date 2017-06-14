<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <!-- <link rel="stylesheet" type="text/css" href="./css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/info.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/hospitalInfo.css">
    <link rel="stylesheet" href="/Public/css/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.mCustomScrollbar.js"></script>
   <!--  <script type="text/javascript" src='js/jquery.min.js'></script> -->
    <script type="text/javascript" src='/Public/js/jquery.date_input.pack.js'></script>
    <script type="text/javascript" src="/Public/js/com.js"></script>
    <script type="text/javascript" src="/Public/js/adminInfo.js"></script>
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
                        <div class="left"><a onclick="history.back();">返回上一页</a></div>
                    </div>

                    <!--左侧导航隐藏按钮-->
                    <div class="_button">
                        <div class="hide"></div>
                    </div>  

                    <!--内容主体-->
                    <div class="form_con">
                        <div class="user_info">
                            <div class="fields">
                                <ul>
                                    <li>添加日期</li>
                                    <li>医院名称</li>
                                    <li>剩余金额</li>
                                    <li>目前比例(%)</li>
                                    <li>已提取金额</li>
                                    <li>短信余额</li>
                                    <li>添加人员</li>
                                    <li>当前状态</li>
                                </ul>
                              
                                <form name='patient'>
                                <ul class="patient">
                                    <input type="hidden" name="unique" value="<?php echo ($unique); ?>">
                                    <input type="hidden" name="id" value="<?php echo ($hospital["id"]); ?>">
                                    <li><?php echo ($hospital["add_datetime"]); ?></li>
                                    <li title="双击可修改"><input type="text" value="<?php echo ($hospital["name"]); ?>" name='name' readonly="readonly"></li>
                                    <li><?php echo ($hospital["leav"]); ?></li>
                                    <li title="双击可修改"><input type="number" step="0.0001" value="<?php echo ($hospital["percent"]); ?>" name='percent' readonly="true" title='可修改'></li>
                                    <input type="hidden" name="percentId" value="<?php echo ($hospital["porId"]); ?>" readonly="readonly">
                                    <li><?php echo ($hospital["cost"]); ?></li>  
                                    <li><?php echo ($hospital["verify_recharge_sum"]); ?></li>
                                    <li><?php echo ($hospital["add_admin"]); ?></li>
                                    <li title="双击可修改" class="use"><?php echo ($hospital['is_use'] ? '合作中' : '结束合作'); ?></li>
                                    <li class='use_select' style="display: none;">
                                        <select name="is_use">
                                            <option value="0" <?php echo ($hospital['is_use'] == '0' ? 'selected="selected"' : ''); ?>>结束合作</option>
                                            <option value="1" <?php echo ($hospital['is_use'] == '1' ? 'selected="selected"' : ''); ?>>合作中</option>
                                        </select>
                                    </li>
                                </ul>
                                </form>
                            </div>
                        </div>
                        <div class="cost_info">
                            <div class="fields">
                                <ul>
                                    <li class="title">医院操作</li>
                                </ul>
                                <ul class="list proportion do">
                                    <li class='chongzhi _yuE'>余额充值</li>
                                    <li class="chongzhi _verify_yuE">验证码余额充值</li>
                                    <li class="chongzhi _add_admin">添加管理员</li>
                                </ul>
                               
                                <ul>
                                    <li class="title">提取比例修改记录</li>
                                </ul>
                                <ul class="list pro">
                                    <li>开始日期</li>
                                    <li>结束日期</li>
                                    <li>修改人员</li>
                                    <li>提取比例%</li>
                                    <li>该比例提取金额</li>
                                </ul>
                                <!-- 比例修改记录遍历 -->
                                <?php if(is_array($percent_list)): $i = 0; $__LIST__ = $percent_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$percent): $mod = ($i % 2 );++$i;?><ul class="proportion pro">
                                        <li><?php echo ($percent["begin_date"]); ?></li>
                                        <li><?php echo ($percent["end_date"]); ?></li>
                                        <li><?php echo ($percent["add_admin"]); ?></li>
                                        <li><?php echo ($percent['percent']*100); ?></li>
                                        <li><?php echo ($percent['extract_money']); ?></li>
                                    </ul><?php endforeach; endif; else: echo "" ;endif; ?>
            
                                <ul>
                                    <li class="title">余额充值记录</li>
                                </ul>

                                <ul class="list rechange">
                                    <li>操作人员</li>
                                    <li>充值金额</li>
                                    <li>充值时间</li>
                                </ul>

                                <?php if(is_array($regList)): $i = 0; $__LIST__ = $regList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$reg): $mod = ($i % 2 );++$i;?><ul class="proportion rechange">
                                        <li><?php echo ($reg["add_admin"]); ?></li>
                                        <li><?php echo ($reg["money"]); ?></li>
                                        <li><?php echo ($reg["add_datetime"]); ?></li>
                                    </ul><?php endforeach; endif; else: echo "" ;endif; ?>

                                <ul>
                                    <li class="title">验证码余额充值记录</li>
                                </ul>

                                <ul class="list rechange">
                                    <li>操作人员</li>
                                    <li>充值金额</li>
                                    <li>充值时间</li>
                                </ul>

                                <?php if(is_array($verify_list)): $i = 0; $__LIST__ = $verify_list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$reg): $mod = ($i % 2 );++$i;?><ul class="proportion rechange">
                                        <li><?php echo ($reg["add_admin"]); ?></li>
                                        <li><?php echo ($reg["money"]); ?></li>
                                        <li><?php echo ($reg["add_datetime"]); ?></li>
                                    </ul><?php endforeach; endif; else: echo "$empty" ;endif; ?>

                                <ul>
                                    <li class="title">医院管理人员列表</li>
                                </ul>
                                <ul class="list member">
                                    <li>管理员姓名</li>
                                    <li>电话号码</li>
                                    <li>当前状态</li>
                                    <li>操作</li>
                                </ul>

                                <?php if(is_array($admin_list)): $i = 0; $__LIST__ = $admin_list;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$admin): $mod = ($i % 2 );++$i;?><ul class="patient proportion member">
                                        <input type="hidden" value="<?php echo ($admin["id"]); ?>" readonly="true">
                                        <li><input type="text" value="<?php echo ($admin["name"]); ?>" name="update_admin_name" readonly="true"></li>
                                        <li><input type="text" value="<?php echo ($admin["tel"]); ?>"  name="update_admin_tel"  maxlength="11"></li>
                                        <li><?php echo ($admin['is_use'] == '1' ? '正常' : '冻结'); ?></li>
                                        <?php if($admin['is_use'] == 0 ): ?><li><span class="undo" style="color:gray;">冻结</span>&nbsp;&nbsp;&nbsp;<span class="jiedong"  style="color:#000">解冻</span></li>

                                        <?php else: ?>
                                             <li><span class="dongjie" style="color:#000">冻结</span>&nbsp;&nbsp;&nbsp;<span class="undo" style="color:gray;">解冻</span></li><?php endif; ?>
                                    </ul><?php endforeach; endif; else: echo "$empty" ;endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
            <!-- 充值遮罩 -->
        <div id="light">
            <div class="back_wrap"></div>
            <div class="con">
                <!-- 余额充值    -->
                <div class="yuE">
                    <p class='title1'>当前账户信息</p>
                    <p>余额：　　　<input type="number" value="<?php echo ($hospital["leav"]); ?>" readonly="readonly"></p>
                    <p>充值：　　　<input type="number" value="" name='money'></p>
                    <p>预计可提取：<input type="number" value="0" readonly="readonly" class='yuEshow'></p>
                    <p>验证码：　　<input type="text" value=""  name="verify" class="verify"><img src="<?php echo U('Login/verify');?>" alt=""></p>
                    <p class="button"><span class="recharge">充值</span><span class="cancel">取消</span></p>
                </div>

                <!-- 验证码余额 -->
                <div class="verify_yuE">
                    <p class='title1'>当前账户信息</p>
                    <p>验证码余额：<input type="number" value="<?php echo ($hospital["verify_recharge_sum"]); ?>" readonly="readonly"></p>
                    <p>充值：　　　<input type="number" value="" name='verify_money'></p>
                    <p>验证码：　　<input type="text" value=""  name="verify_verify" class="verify"><img src="<?php echo U('Login/verify');?>" alt=""></p>
                    <p class="button"><span class="verify_recharge">充值</span><span class="cancel">取消</span></p>
                </div>

                <!-- 管理员添加 -->
                <div class="add_admin">
                    <p class='title1'>开通管理员账户</p>
                    <p>姓名：　　　<input type="text" value="" name='admin_name'></p>
                    <p>电话号码：　<input type="text" value="" name='admin_tel' maxlength="11"></p>
                    <p>所属权限：　<select name="permission">
										<option value="0" selected='selected'>普通权限</option>
										<option value="1">管理员</option>
										<option value="2">超级管理员</option>
									</select>
					</p>
                    <p>验证码：　　<input type="text" value=""  name="open_verify" class="verify"><img src="<?php echo U('Login/verify');?>" alt=""></p>
                    <p class="button"><span class="open">开通</span><span class="cancel">取消</span></p>
                </div>
            </div>
        </div>
</body>
</html>