<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/com.css">
    <!-- <link rel="stylesheet" type="text/css" href="/Public/css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="/Public/css/date_input.css">
    <!-- <link rel="stylesheet" href="css/info.css"> -->
    <link rel="stylesheet" href="/Public/css/addPatient.css">
    <link rel="stylesheet" href="/Public/css/adminHospitalList.css">
    <link rel="stylesheet" href="/Public/css/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.mCustomScrollbar.js"></script>
   <!--  <script type="text/javascript" src='js/jquery.min.js'></script> -->
    <script type="text/javascript" src='/Public/js/jquery.date_input.pack.js'></script>
    <script type="text/javascript" src="/Public/js/com.js"></script>
    <script type="text/javascript" src="/Public/js/addPatient.js"></script>
	
    <title>管理首页</title>
	<script type="text/javascript"> 
		$(function(){

			$('input[name=sort]').change(function(){
				if($(this).val == '') return;
				var id = $(this).attr('data-id');
				$.post("<?php echo U('Hospital/updateSort');?>", {sort:$(this).val(), id:id}, null, 'json').fail(function(xhr){
					tips('修改失败');
				}).done(function(data){
					tips('修改成功');
					setTimeout(function(){
						window.location.reload();
					},1500);
				});
			});
		});
	</script>
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
                        <div class="left"><a href="/Master/Index/index" title='点我返回首页'>返回首页</a></div>

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
                                <li>添加日期</li>
                                <li>医院名称</li>
                                <li>剩余金额</li>
                                <li>目前比例</li>
                                <li>已提取金额</li>
                                <li>当前状态</li>
                                <li>优先度</li>
                              </ul>
                              <?php if(is_array($hospitalList)): $i = 0; $__LIST__ = $hospitalList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hospital): $mod = ($i % 2 );++$i;?><ul class="input">
                                        <input type="hidden" name="unique" value="<?php echo ($add_form_unique); ?>">
                                        <li><?php echo ($hospital["add_datetime"]); ?></li>
                                        <li><a href="/Master/Hospital/info/id/<?php echo ($hospital["id"]); ?>"><?php echo ($hospital["name"]); ?></a></li>
                                        <li><?php echo ($hospital["leav"]); ?></li>
                                        <li><?php echo ($hospital['percent'] * 100); ?>%</li>
                                        <li><?php echo ($hospital['cost_sum']); ?></li>
                                     
                                        <li><?php echo ($hospital['is_use'] ? '合作中' : '结束合作'); ?></li>
										 <li><input type="text" value="<?php echo ($hospital['sort']); ?>" data-id=<?php echo ($hospital['id']); ?> name="sort"  style="text-align:center;"></li>
                                    </ul><?php endforeach; endif; else: echo "" ;endif; ?>
                              
                            </div>
                        </div>

                    </div>

                      
                </form>    
                </div>
            </div>
        </div>
    </div>

</body>
</html>