$(function(){

    //获取唯一标识
    var MsgUnique = '';
    var imgUrl = $('.ver_right img').attr('src');
    //验证用户是否存在
    $('input[name=tel]').change(function(){
        
        if ($(this).val().length != 11 || isNaN($(this).val())) {
            $(this).siblings('p').text('手机号码格式错误').show();
            return;
        }
        var unique = $('input[name=_unique]').val();
        $(this).siblings('p').hide();
        $.post(checkUrl, {tel: $(this).val(), 'form_unique' : unique},null,'json').fail(function(xhr){

            if(xhr.status == '403'){
                alert('非法请求');
            }
        }).done(function(data){
            if (data.status == 'ok') {
                MsgUnique = data._unique;
                $('.ver_right').addClass('send');
            }else{
                $('.ver_right').removeClass('send');
                $('input[name=tel]').siblings('p').text(data.errMsg).show();
            }
        });
    });

    //发送验证码
   $('body').on('click','.send',function(){
        if (!MsgUnique) {
            return;
        }
        $.post(verifyUrl,{tel: $('input[name=tel]').val(),msgUnique:MsgUnique}, null, 'json').fail(function(xhr){

        }).done(function(data){
            MsgUnique = data.msgUnique;
            if (data.status == 'ok') {
                alert('发送成功');
                $('.ver_right').addClass('wait').removeClass('send');
                 $('.wait').trigger('click');
            }else{
                alert('发送失败:'+data.errMsg);
            }
        });
   });
   
   //等待60秒
   $('body').on('click','.wait',function(){
        $(this).removeClass('wait').removeClass('send');
        var i = 60;
        var interval = setInterval(function(){
            i--;
            $('.ver_right').text(i+'秒后重新发送');
        },1000);

        setTimeout(function(){
            clearInterval(interval);
             $('.ver_right').text('发送验证码').addClass('send');
        },60000);
   });

   //刷新验证码
   $('.ver_right img').click(function(event) {
       
        var newUrl = imgUrl+'?'+Math.random();
        $(this).attr('src',newUrl);
   });

   //切换登录方式
   $('#login .wrap .box .change').click(function(event) {
        if($('form[name=tel]').is(':hidden')){
           $('form[name=tel]').fadeIn().siblings('form[name=username]').hide();
        }else{
           $('form[name=tel]').hide().siblings('form[name=username]').fadeIn();
        }
   });


});




