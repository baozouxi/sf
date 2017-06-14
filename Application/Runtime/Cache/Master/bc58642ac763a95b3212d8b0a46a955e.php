<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <!-- <link rel="stylesheet" type="text/css" href="./css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <link rel="stylesheet" type="text/css" href="/Public/css/info.css">
    <!-- <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css"> -->
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <!-- <script type="text/javascript" src="js/jquery.mCustomScrollbar.js"></script> -->
   <!--  <script type="text/javascript" src='js/jquery.min.js'></script> -->
    <script type="text/javascript" src='/Public/js/jquery.date_input.pack.js'></script>
    <script type="text/javascript" src="/Public/js/com.js"></script>
    <script type="text/javascript" src="/Public/js/adminPatientInfo.js"></script>
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
                        <div class="left">当前医院：<a href='/Master/Index/index' title='点我返回首页'><?php echo ($hospital_name); ?></a><<span><?php echo ($patient["name"]); ?></span></div>
                        <div class="right">
                            <!-- 搜索框 
                            <div class="word">搜索病人：</div>
                            <div class="input search">
                                <input value="" type="text" maxlength="5">
                                <span class="search_button"></span>
                            </div>-->
                            <div class="word">选择月份：</div>
                            <div class="input">
                                <input value="<?php echo ($date); ?>" type="text" class="date_input">
                            </div>
                        </div>
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
                                    <li>入院日期</li>
                                    <li>姓名</li>
                                    <li>年龄</li>
                                    <li>性别</li>
                                    <li class="type">病种</li>
                                    <li>联系方式</li>
                                    <li>接诊医生</li>
                                    <li>消费总额</li>
                                    <li>当前状态</li>
                                </ul>
                                <form name='patient'>
                                <ul class="patient">
                                    <input type="hidden" name="unique" value="<?php echo ($unique); ?>">
                                    <input type="hidden" name="id" value="<?php echo ($patient["id"]); ?>">
                                    <li><?php echo ($patient["hospital_date"]); ?></li>
                                    <li><input type="text" value="<?php echo ($patient["name"]); ?>" name='name' readonly="true" ></li>
                                    <li><input type="text" value="<?php echo ($patient["age"]); ?>" name='age' readonly="true"></li>
                                    <li class='sex'><?php echo ($patient["sex"]); ?></li>
                                    <li class='sex_select' style="display: none;">
                                      <select name="sex" readonly="true">
                                          <option value="男" <?php echo ($patient['sex']=='男' ? 'selected="selected"' : ''); ?> >男</option>
                                          <option value="女" <?php echo ($patient['sex']=='女' ? 'selected="selected"' : ''); ?> >女</option>
                                      </select>
                                    </li>
                                    <li class="type ill"><?php echo ($patient['illness']); ?></li>
                                    <li class="type_select">
                                        <select name="illness">
                                                <option value="">--请选择--</option>
                                                <?php if(is_array($illness)): $i = 0; $__LIST__ = $illness;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ill): $mod = ($i % 2 );++$i; if($key == $patient['illness']): ?><option selected="selected" value="<?php echo ($ill); ?>"><?php echo ($ill); ?></option>
                                                    <?php else: ?> 
                                                        <option value="<?php echo ($ill); ?>"><?php echo ($ill); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                                <option value="">--手工填写--</option>
                                        </select>
                                    </li>
                                    <li><input type="text" value="<?php echo ($patient["tel"]); ?>" name='tel' readonly="true"></li>
                                    <li class="doctor"><?php echo ($patient['doctor_name']); ?></li>
                                    <li class="doctor_select">
                                        <select name="doctor_name" >
                                            <option value="">--请选择--</option>
                                            <?php if(is_array($doctor_info)): $i = 0; $__LIST__ = $doctor_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$doctor): $mod = ($i % 2 );++$i; if($key == $patient['doctor_name']): ?><option selected="selected" value="<?php echo ($patient['doctor_name']); ?>"><?php echo ($doctor); ?></option>
                                                <?php else: ?> 
                                                    <option value="<?php echo ($key); ?>"><?php echo ($doctor); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                            <option value="">手工填写</option>
                                        </select>
                                    </li>
                                    <li class='sum'><?php echo ($sum); ?></li>
                                    <li class='status'><?php echo ($patient['status']==0 ? '治疗中' : '已出院'); ?></li>
                                    <li class='status_select' style="display:none;">
                                      <select name="status">
                                        <option value="0" <?php echo ($patient['status']=='0' ? 'selected="selected"' : ''); ?> >治疗中</option>
                                        <option value="1" <?php echo ($patient['status']=='1' ? 'selected="selected"' : ''); ?> >已出院</option>
                                      </select>
                                    </li>
                                </ul>
                                </form>
                            </div>
                        </div>
                        <div class="cost_info">
                            <div class="fields">
                                <ul>
                                    <li>日期</li>
                                    <li>消费类型</li>
                                    <li class="money">消费金额</li>
                                    <li class="remark">备注</li>
                                    <li></li>
                                    <li>消费类型</li>
                                    <li class="money">消费金额</li>
                                    <li class="remark">备注</li>
                                </ul>
                              <?php if(is_array($spending_list)): $i = 0; $__LIST__ = $spending_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><ul class="cost" data-id=<?php echo ($item["id"]); ?>>
                                     <li><?php echo ($item["spending_date"]); ?></li>
                                     <li class="summary"><input type="text" value="<?php echo ($item["summary"]); ?>" name="summary" readonly="true"></li>
                                     <li class="money"><input type="text" value="<?php echo ($item["cost_money"]); ?>" name='cost_money' readonly="true"></li>
                                     <li class="remark"><input type="text" value="<?php echo ($item["remark"]); ?>" name='remark' readonly="true"></li>
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