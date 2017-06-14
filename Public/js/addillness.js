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
            tips('疾病名称不能为空');
        }
    });


     $('body').on('click', 'span.no', function(event) {
        $(this).closest('ul').html(input_con);
     });


    $('body').on('click', 'span.ok', function(event) {
        var url = '/Home/Hospital/addIllness';
        var _parent = $(this).parent();
        $(this).closest('ul').find('input').trigger('change');
        if (submit) {
            var unique = $(this).parent().siblings('input[name=unique]').val()
            var name = $(this).parent().siblings().find('input[name=name]').val();
            $.post(url, {unique:unique,
                        name:name,
                    }, null, 'json').fail(function(xhr){
                if (xhr.status == 403) {
                    tips('无权访问');
                }
            }).done(function(data){
                if (data.status == 'ok') {
                    tips('添加成功');  
                    _parent.html('<b style="color:green;">已添加</b>');
                } else {
                    tips('添加失败：'+data.errMsg);
                }
            });
        }
  });

});

