$(function(){
    var imgUrl = $('p img').attr('src'); 
    var unique = $('input[name=unique]').val();
    var sum = 0;
     //调用日期插件
     $('.date_input').date_input();

     //滚动
    $('.form_con').mCustomScrollbar({
        'scrollButtons':{ enable: false }
    });

     //内容修改部分
     
     //双击可以可修改
    $('ul.patient input').click(function(event) {
        $(this).css('border-width','1px').removeAttr('readonly');
    });

     //失焦锁定
     $('ul.patient input,select').blur(function(event) {
         $(this).attr('readonly',true).css('border-width','0px');
         if($(this)[0].type == 'select-one'){
             $('li.use').click();
         }
     });

     //change事件触发ajax  修改病人信息 
    $('ul.patient input[name=name],input[name=intro],select[name=is_use]').change(function(e){
        if($(this).val() == ''){
            tips('数据不能为空,请重新填写');
            return false;
        }
        var data = {
            name :$('input[name=name]').val(),
            is_use:$('select[name=is_use]').val(),
            id :$('input[name=id]').val(),
            unique:unique
        };
        $(this).blur();
        $.post('/Master/Hospital/update',data,null,'json').fail(function(xhr){
            tips('修改失败：'+xhr.status);
        }).done(function(data){
            if(data.status == 'ok'){
                tips('修改成功');
            }else{
                tips('修改失败，请稍后重试');
            }
        });

    });

    $('li.use').click(function(){
        if ($(this).is(':hidden')) {
            $(this).text($(this).siblings('li.use_select').find('select option:selected').text());
            $(this).show().siblings('li.use_select').hide();
        }else{
            $(this).hide().siblings('li.use_select').show();
        }
    });


    //提取点修改
    $('input[name=percent]').change(function(){
        if(isNaN($(this).val())){
            tips('请填入数字');
            return false;
        }
        var data = {
            hospital_id : $('input[name=id]').val(),
            id:$('input[name=percentId]').val(),
            unique:unique,
            percent:$(this).val()
        };
        $.post('/Master/Hospital/updatePor',data, null, 'json').fail(function(xhr){
            tips('服务器错误：修改失败');
        }).done(function(data){
            if(data.status == 'ok'){
                tips('修改成功');
              setTimeout(function(){
                 window.location.reload();
              },2000); 
            }else{
              tips('修改失败');
            }
        });
    });
    
        
   //刷新验证码
   $('p img').click(function(event) {
        var newUrl = imgUrl+'?'+Math.random();
        $(this).attr('src',newUrl);
   });


  //余额充值框
  $('input[name=money]').keyup(function(event) {
    if (isNaN($(this).val())) {
      tips('请填写金额');
      return false;
    }
    var get = $(this).val() * ($('input[name=percent]').val()/100);
      $('input.yuEshow').val(get);
  });

  $('span.cancel').click(function(event) {
    $('#light').hide();
  });

  $('li._yuE').click(function(event) {
      $('#light').show().find('.yuE').show();
  });


  $('span.recharge').click(function(event) {

    var money = $('input[name=money]').val();
    var hospital_id = $('input[name=id]').val();
    var verify  = $('input[name=verify]').val();
    if (isNaN(money) || (parseInt(money) == 0 || money == '' )) {
      tips('请填写正确金额');
      return false;
    }
    $.post('/Master/Hospital/recharge',{hospital_id:hospital_id,unique:unique,money:money,verify:verify}, null, 'json').fail(function(xhr){
        tips('服务器错误：充值失败');
    }).done(function(data){
      if (data.status == 'ok') {

          tips('充值成功');
          setTimeout(function(){
            window.location.reload();
          },2000);

      } else {

        tips(data.errMsg);
      }
    });

  });

    //短信余额
  $('._verify_yuE').click(function(event) {
     $('#light').show().find('.verify_yuE').show();
  });

    $('span.verify_recharge').click(function(event) {

    var money = $('input[name=verify_money]').val();
    var hospital_id = $('input[name=id]').val();
    var verify  = $('input[name=verify_verify]').val();
    if (isNaN(money) || (parseInt(money) == 0 || money == '' )) {
      tips('请填写正确金额');
      return false;
    }
    $.post('/Master/Hospital/verifyRecharge',{hospital_id:hospital_id,unique:unique,money:money,verify:verify}, null, 'json').fail(function(xhr){
        tips('服务器错误：充值失败');
    }).done(function(data){
      if (data.status == 'ok') {

          tips('充值成功');
          setTimeout(function(){
            window.location.reload();
          },2000);

      } else {

        tips(data.errMsg);
      }
    });

  });


  //开通账户
  $('li._add_admin').click(function(e){
      $('#light').show().find('.add_admin').show();
  });

  $('span.open').click(function(event) {
    var hospital_id = $('input[name=id]').val();
    var name = $('input[name=admin_name]').val();
    var tel = $('input[name=admin_tel]').val();
    var permission = $('select[name=permission]').val();
    var verify  = $('input[name=open_verify]').val();
    if (isNaN(tel) || (parseInt(tel) == 0 || tel == '' ) || tel.length != 11) {
      tips('请填写正确手机号');
      return false;
    }

    if (name == '' || name.length > 5 || name.length < 2) {
      tips('请填写正确的姓名');
      return false;
    }

    $.post('/Master/Hospital/add_admin',{hospital_id:hospital_id,permission:permission,unique:unique,admin_name:name,admin_tel:tel,verify:verify}, null, 'json').fail(function(xhr){
        tips('服务器错误：开通失败');
         $('p img').click();
    }).done(function(data){
       $('p img').click();
      if (data.status == 'ok') {

          tips('账户已开通');
          setTimeout(function(){
            window.location.reload();
          },2000);

      } else {

        tips(data.errMsg);
      }
    });
  });


 //管理员账户update
 $('.member input').change(function(){
    var id   = $(this).parents().siblings('input[type=hidden]').val();
    var name = $(this).closest('ul').find('input[name=update_admin_name]').val();
    var tel  = $(this).closest('ul').find('input[name=update_admin_tel]').val();

    if (isNaN(tel) || (parseInt(tel) == 0 || tel == '' ) || tel.length != 11) {
      tips('请填写正确手机号');
      return false;
    }

    if (name == '' || name.length > 5 || name.length < 2) {
      tips('请填写正确的姓名');
      return false;
    }
    
    $.post('/Master/Hospital/admin_update', {admin_name: name, admin_tel : tel, unique : unique, id : id}, null, 'json').fail(function(xhr){
          tips('服务器错误：'+xhr.status);
    }).done(function(data){
      if (data.status == 'ok') {
        tips('修改成功');
        setTimeout(function(){
          window.location.reload();
        },2000);

      } else {
        tips(data.errMsg);
      }
    });

 });

  //冻结 解冻 重置密码
  $('ul.member span').click(function(event) {
     var id = $(this).closest('li').siblings('input[type=hidden]').val();
     var caozuo = $(this).attr('class');
     if(caozuo == 'jiedong' || caozuo == 'dongjie'){
       if (id) {
          $.post('/Master/Hospital/update_status', {id:id, caozuo:caozuo, unique:unique}, null, 'json').fail(function(xhr){
              tips('修改失败：'+xhr.status);
          }).done(function(data){
              if (data.status == 'ok') {
                tips('修改成功');
                setTimeout(function(){
                  window.location.reload();
                },2000);
              }else{
                tips('修改失败，请重试');
              }
          });
       }
    }
  });


});