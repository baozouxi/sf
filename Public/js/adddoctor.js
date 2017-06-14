$(function(){

    var input_con = $('ul.input').html();
    var submit = false;
    //调用滚动插件
    $('.form_con').mCustomScrollbar();

    $('.addButton').click(function(event) {
        $('.fields').append('<ul class="input">'+input_con+'</ul>');
    });

        //输入框检验部分

    $('body').on('change', 'input.input_date', function(event) {
        if (RQcheck($(this).val())) {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('日期格式错误');
        }
    });


    $('body').on('change', 'input.name', function(event) {
       if ($(this).val() != '') {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('姓名不能为空');
        }
    });

    $('body').on('change', 'input.age', function(event) {

        if (($(this).val() != '') && !isNaN($(this).val())) {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('年龄格式不正确');
        }

    });

    
 $('body').on('change', 'input.type', function(event) {
        if ($(this).val() != '') {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('病种不能为空');
            
        }
    });

  $('body').on('change', 'input.summary', function(event) {
        if ($(this).val() != '') {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('主治内容不能为空');
            
        }
    });

 $('body').on('change', 'input.tel', function(event) {
        if (($(this).val() != '') && !isNaN($(this).val())) {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('手机格式不正确');
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

