$(function(){

    var unique = $('input[name=unique]').val();
    var sum = 0;
     //调用日期插件
     $('.date_input').date_input();

     //内容修改部分
     
     //双击可以可修改
    $('ul.patient input').click(function(event) {
        $(this).removeAttr('readonly').css('border-width','1px');
    });

     //失焦锁定
     $('ul.patient input').blur(function(event) {
         $(this).attr('readonly',true).css('border-width','0px');
     });

     //change事件触发ajax  修改病人信息 
    $('ul.patient input,select').change(function(e){
        if ($(this).val() == ''){
            tips('修改失败：信息不能为空');
            return;   
        } 
        var obj = $(e.target);
        className = obj.closest('li').attr('class');
        $.post('/Home/Patient/save',$('form[name=patient]').serialize(),null,'json').fail(function(xhr){
                if(xhr.status == 403){
                    tips('无权访问');
                }else{
                    tips('修改失败');
                }
        }).done(function(data){

            if (className == 'sex_select') {
                $('li.sex').click();
            }

            if (className == 'status_select') {
                $('li.status').click();
            }
            if (className == 'doctor_select') {
                $('li.doctor').click();
            }

            if (data.status == 'ok') {
                tips('修改成功');
            }else{
                tips('修改失败'); 
            }
        });
    });

    $('li.type').click(function(){
        if($(this).hasClass('out')) return;
        if ($(this).is(':hidden')) {
            $(this).text($(this).siblings('li.type_select').find('select').val());
            $(this).show().siblings('li.type_select').hide();
        }else{
            $(this).hide().siblings('li.type_select').show();
        }
    });

    $('li.sex').click(function(){
        if ($(this).is(':hidden')) {
            $(this).text($(this).siblings('li.sex_select').find('select').val());
            $(this).show().siblings('li.sex_select').hide();
        }else{

            $(this).hide().siblings('li.sex_select').show();
        }
    });

    $('li.status').click(function(){
        if ($(this).is(':hidden')) {
            $(this).text($(this).siblings('li.status_select').find('select option:selected').text());
            $(this).show().siblings('li.status_select').hide();
        }else{
            $(this).hide().siblings('li.status_select').show();
        }
    });

    $('li.doctor').click(function(){
        if ($(this).is(':hidden')) {
            $(this).text($(this).siblings('li.doctor_select').find('select option:selected').text());
            $(this).show().siblings('li.doctor_select').hide();
        }else{
            $(this).hide().siblings('li.doctor_select').show();
        }
    });

    /*--------------------修改消费记录部分------------------------------------*/
    

    //失焦锁定
    $('ul.cost input').blur(function(event) {
        $(this).attr('readonly','true').css('border-width','0px');
    });

    //提交
    $('body').on('change', 'ul.cost input',function(){
        if($(this).val() == ''){
            tips('信息不能为空');
            return;
        }
        var id = $(this).closest('ul').attr('data-id');
        var name = $(this).attr("name");

        switch (name){
            case 'summary':
              var data ={'unique': unique, 'id':id, summary: $(this).val()};
            break;
            case 'cost_money':
              if(isNaN($(this).val())){
                tips('请输入数字');
                return;
              } 
              //改变总额
              sum = parseInt($(this).val()) - sum;
              sum = parseInt($('li.sum').text()) + sum;
              $('li.sum').text(sum);

              var data ={'unique': unique, 'id':id, cost_money: $(this).val()};
            break;
            case 'remark':
              var data ={'unique': unique, 'id':id, remark: $(this).val()};
            break;
        }

            $.post('/Home/Spending/save',data,null,'json').fail(function(xhr){
                
                if(xhr.status == 403){
                    tips('无权访问');
                }else{
                    tips('服务器错误：修改失败');
                }

            }).done(function(data){
                if (data.status == 'ok') {
                    tips('修改成功');
                }else{
                    tips(data.errMsg);                 
                }
            });

    }); 
        

    $('body').on('click', 'li.money', function(){
         if (!$(this).siblings('.summary').find('input[name=summary]').val()) {
            tips('请先输入消费类型');
            return;
        }
        $(this).find('input').removeAttr('readonly').css('border-width','1px');
        sum = parseInt($(this).find('input').val());
    });

    $('body').on('click', 'li.summary', function(){
        $(this).find('input').removeAttr('readonly').css('border-width','1px');
    });

    $('body').on('click', 'li.remark', function(){
        $(this).find('input').removeAttr('readonly').css('border-width','1px');
    });



      //日期查询
      $('input.date_input').change(function(){
            
            if(RQcheck($(this).val())){
                var tel = $('input[name=tel]').val();
                $.post('/Home/Patient/searchOne', {patient_tel:tel, unique:unique,date:$(this).val()}, null, 'json').fail().done(function(data){
                    var str = '';
                    if(data.status == 'ok'){
                        //无信息
                        if(!data.info){
                            tips('病人无该月消费记录');
                            return;
                        }

                        for (var i in data.info) {
                            if(data.info[i]['remark'] == null) data.info[i]['remark'] = '';
                            str +=   '<ul class="cost" data-id='+data.info[i]['id']+'>';                                
                            str +=   '<li>'+data.info[i]['spending_date']+'</li>';
                            str +=   '<li class="summary"><input type="text" value="'+data.info[i]['summary']+'" name="summary" readonly="true"></li>';
                            str +=   '<li class="money"><input type="text" value="'+data.info[i]['cost_money']+'" name="cost_money" readonly="true"></li>';
                            str +=   '<li class="remark"><input type="text" value="'+data.info[i]['remark']+'" name="remark" readonly="true"></li>';
                            str +=   '</ul>';
                        }
                        $('.cost_info .fields ul.cost').remove();
                        $('.cost_info .fields').append(str);
                    }else{
                        tips(data.errMsg);
                    }
                });
            }
      });

});