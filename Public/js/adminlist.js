$(function(){

    //调用滚动插件
    $('.form_con').mCustomScrollbar();

    $('.addButton').click(function(event) {
        $('.fields').append('<ul class="input">'+input_con+'</ul>');
    });

    $('select[name=admin_part]').change(function(){
        if ($(this).val() !== '') {
            $.post('/Master/Admin/modify', {admin_part: $(this).val(),unique:unique, id:$(this).attr('data-id')}, null, 'json').fail(function(xhr){
                tips('修改失败：'+xhr.status);
                setTimeout(function(){
                    window.location.reload();
                },2000);   
            }).done(function(data){
                if (data.status == 'ok') {
                    tips('修改成功');
                } else {
                    tips('修改失败,请重试');
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }
            });
        } 
    });

});

