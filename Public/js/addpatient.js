$(function(){

    var input_con = $('ul.input').html();
    var submit = false;
    //调用滚动插件
    $('.totalImg').mCustomScrollbar();

    $('.addButton').click(function(event) {
        $('.fields').append('<ul class="input">'+input_con+'</ul>');
    });

        //输入框检验部分

    $('body').on('change', 'input.input_date', function(event) {
        var rep_date = $(this).val();
        $(this).val(rep_date.replace(/\./g,'-'));
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
  $('body').on('change', 'input[name=doctor]', function(event) {
        if ($(this).val() != '') {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('请添加医生');
            
        }
    });

 $('body').on('change', 'input.tel', function(event) {
        if (($(this).val() != '') && !isNaN($(this).val()) && $(this).val().length == 11) {
           submit = true;
           $(this).parent('li').removeClass('error');
        } else {
            submit = false;
            $(this).parent('li').addClass('error');
            tips('手机格式不正确');
        }
    });

 $('body').on('change', 'select.select_doctor', function(event) {
        
        if ($(this).val() && $(this).val()!=='' && $(this).val()!==null) {
            submit = true;
            $(this).parent('li').removeClass('error');
        } else {
            if ($(this).find('option:selected').text() == '--手工填写--'){
              $(this).parent().append('<input type="text" name="doctor">');
              $(this).remove();    
              return;  
            } 
                
            submit = false;
            $(this).parent('li').addClass('error');
            tips('请先添加医生');
        }
    });

  $('body').on('change', 'select[name=illness]', function(event) {
        
        if ($(this).val() && $(this).val()!=='' && $(this).val()!==null) {
            submit = true;
            $(this).parent('li').removeClass('error');
        } else {
            if ($(this).find('option:selected').text() == '--手工填写--'){ 
              $(this).parent().append('<input type="text" name="illness">');
              $(this).remove();
              return;  
            } 
                
            submit = false;
            $(this).parent('li').addClass('error');
            tips('请先添加医生');
        }
    });

    $('body').on('click', 'span.no', function(event) {
       
        $(this).parent().siblings().removeClass('error').find('input').val('');
     });

  $('body').on('click', 'span.ok', function(event) {
        var url = '/Home/Patient/addPatient';
        $(this).closest('ul').find('input,select').trigger('change');
        if (submit) {
            var unique = $(this).parent().siblings('input[name=unique]').val()
            var hospital_date = $(this).parent().siblings().find('input[name=hospital_date]').val();
            var name = $(this).parent().siblings().find('input[name=name]').val();
            var age = $(this).parent().siblings().find('input[name=age]').val();
            var sex = $(this).parent().siblings().find('select[name=sex]').val();
            var illness = $(this).parent().siblings().find('input[name=illness],select[name=illness]').val();
            var tel = $(this).parent().siblings().find('input[name=tel]').val();
            var doctor_obj = $(this).parent().siblings().find('select[name=doctor]');
            var _parent = $(this).parent();
            if(doctor_obj && doctor_obj.length > 0 && doctor_obj.val()){
                var doctor = doctor_obj.val();
            }else{
                var doctor = $(this).parent().siblings().find('input[name=doctor]').val();
            }
            $.post(url, {unique:unique,
                        hospital_date:hospital_date,
                        name:name,
                        age:age,
                        sex:sex,
                        illness:illness,
                        tel:tel,
                        doctor:doctor
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
  $('.date_input').change(function(){
        var rep_date = $(this).val();
        $(this).val(rep_date.replace(/\./g,'-'));

  });

    //提交
    $('form[name=add]').submit(function(event) {
        event.preventDefault();
        var url = $(this).attr('action');
        $(this).find('input,select').trigger('change');
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

