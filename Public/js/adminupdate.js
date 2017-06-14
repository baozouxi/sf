$(function(){
    var err = false;
    var imgUrl = $('.ver_right img').attr('src');
    $('form[name=update] input').blur(function(event) {
        if ($(this).val() == '') {
            tips('请填写数据');
            err = true;
        }
    });

    $('input.checkPass').blur(function(){
        var old = $('input[name=admin_new_password]').val();
        if($(this).val() !== old){
            tips('两次密码不一致');
            err = true;
        }
    });

    //验证码刷新
    $('.ver_right img').click(function(event) {
       
        var newUrl = imgUrl+'?'+Math.random();
        $(this).attr('src',newUrl);
   });


     $('p.button span').click(function(event) {
        $('form[name=update] input').blur();
        if (err) {
            err = false;
            return false;
         }

        $.post('/Master/Admin/updateHandle', $('form[name=update]').serialize(), null, 'json').fail(function(xhr){
            tips('操作失败：'+xhr.status);
             $('.ver_right img').click();
         }).done(function(data){
            if(data.status == 'ok'){
                tips('操作成功');
            }else{
                tips(data.errMsg);
                $('.ver_right img').click();
            }
         });

     });
});