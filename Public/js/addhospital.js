$(function(){

    var input_con = $('ul.input').html();
    var submit = false;
    //调用滚动插件
    $('.form_con').mCustomScrollbar();

    $('.addButton').click(function(event) {
        $('.fields').append('<ul class="input">'+input_con+'</ul>');
    });

        //输入框检验部分



    $('body').on('change', 'input.name', function(event) {
       if ($(this).val() != '') {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('医院名称不能为空');
        }
    });


  $('body').on('change', 'input.intro', function(event) {
        if ($(this).val() != '') {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('医院简介不能为空');
        }
    });

    $('body').on('change', 'input.remaining_sum', function(event) {
        if (($(this).val() != '') && !isNaN($(this).val())) {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('初始金额不正确');
        }
    });

    $('body').on('change', 'input.percent', function(event) {
        if (($(this).val() != '') && !isNaN($(this).val())) {
            if($(this).val() < 1){
                 submit = false;
                 tips('请填写数值');
            }else{
                submit = true;
                $(this).parent('li').removeClass('error');
            }
           
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('请直接填写数字');
        }
    });


    //提交
    $('form[name=add]').submit(function(event) {
        event.preventDefault();
        var url = $(this).attr('action');
        $(this).find('input').trigger('change');
        if (submit) {
            $.post(url, $(this).serialize(), null, 'json').fail(function(xhr){
                if (xhr.status == 403) {
                    tips('无权访问');
                }
            }).done(function(data){
                if (data.status == 'ok') {
                    tips('添加成功');
                } else {
                    tips('添加失败：'+data.errMsg);
                }
            });
        }
    });
});

