$(function(){

    var unique = $('input[name=unique]').val()

    //调用滚动条插件
     $('.form_con').mCustomScrollbar({
        'scrollButtons':{ enable: false }
    });

        //调用日期插件
     $('.date_input').date_input();

     //ajax调数据
     $('input.date_input').change(function(){
        
        if (!RQcheck($(this).val())) {
            tips('请输入正确日期');
            return;
        }   

        if (unique) {
            $.post('/Master/Spending/select', {unique:unique,date:$(this).val()}, null, 'json').fail(function(xhr){
                tips('服务器错误：查询失败');
            }).done(function(data){
                if(data.status == 'ok'){
                    var str = '';
                    for(var tel in data.info){
                        str += '<ul>';
                        str += '<a href="/Master/Patient/info/tel/'+tel+'"><li>'+data.info[tel]['name']+'</li></a>';
                        for(var item in data.info[tel]['cost']){
                            // alert(data.info[name]['cost'][item]);
                            str += '<li>'+data.info[tel]['cost'][item]['cost_money']+'</li>'
                        }
                        str += '<li class="cost">'+data.info[tel]['sum_money']+'</li>';
                        str += '</ul>'
                    }
                     $('.user_info').html(str);

                    var dayStr = '<li class="first"><div class="date_field">日期</div><div class="name_field">姓名</div><div class="diagonal"></div></li>';

                    for(var i=1; i<=data.days;i++){
                        dayStr += '<li>'+i+'</li>';
                    }
                    dayStr += '<li class="cost">消费总额</li>';
                    $('.fields ul').html(dayStr);

                }else{
                    tips(data.errMsg);
                }
            });
        }
     });    

    //搜索病人  
    $('.search input').change(function(event) {
        if ($(this).val().length < 2 && $(this).val()!== '' ) {
            tips('请输入正确的姓名');
            return;
        }
        if(RQcheck($('input.date_input').val()) == false){
            dObj = new Date();
            date = dObj.toLocaleDateString();
        }else{
            date = $('input.date_input').val();
        }
        name2 = $(this).val();
        data = {name: $(this).val(),date:date,unique:unique};
        if($(this).val() == '') data.null = true;
        $.post('/Master/Patient/search',data ,null,'json').fail().done(function(data){
            
            if(data.status == 'ok'){
                //未查到数据
                if(data.info == null){
                    str1 = '日期：【'+date+'】当月无病人：【'+name2+'】消费记录';
                    tips(str1);
                    return;
                }
                 var str = '';
                    for(var tel in data.info){
                        str += '<ul>';
                        str += '<a href="/Master/Patient/info/tel/'+tel+'"><li>'+data.info[tel]['name']+'</li></a>';
                        for(var item in data.info[tel]['cost']){
                            // alert(data.info[name]['cost'][item]);
                            str += '<li>'+data.info[tel]['cost'][item]['cost_money']+'</li>'
                        }
                        str += '<li class="cost">'+data.info[tel]['sum_money']+'</li>';
                        str += '</ul>'
                    }
                $('.user_info').html(str);

                    var dayStr = '<li class="first"><div class="date_field">日期</div><div class="name_field">姓名</div><div class="diagonal"></div></li>';

                    for(var i=1; i<=data.days;i++){
                        dayStr += '<li>'+i+'</li>';
                    }
                    dayStr += '<li class="cost">消费总额</li>';
                    $('.fields ul').html(dayStr);
            }else{
                tips(data.errMsg);
            }

        }); 
    });

    //医院切换 
    $('select[name=changeHospital]').change(function(){
       var name = $(this).find('option:selected').text();
       var id   = $(this).val();
       var date = $('input.date_input').val();
       if(id && name){
            $.post('/Master/Index/changeHospital', {hospital_id: id, hospital_name:name,unique:unique,date:date}, null, 'json').fail(function(xhr){

            }).done(function(data){
                if (data.status == 'ok') {
                    // tips('切换成功');
                    window.location.reload();
                }else{
                    tips('切换失败:'+data.errMsg);

                }
            });
       }
    });
    

    //高级搜索
    

    //关闭
    $('.search_close').click(function(){
        $('._search_pro').slideUp();
    });

    //显示
    $('.word span').click(function(){
        $('._search_pro').slideDown();
    });

    $('.search_button').click(function(event) {

        var data = {};
        data.unique = $('input[name=unique]').val();
        data.date   = $('input.date_input').val();

        if ($('select[name=doctor]').val()){
            var doctor = $('select[name=doctor]').val();
            data.doctor = doctor;
        } 
        if ($('input[name=cost_max]').val()){
            var max = $('input[name=cost_max]').val();
            if(isNaN(max)){
                tips('消费最大值清输入数字');
                return;
            }
            data.max = max;
        } 

        if ($('input[name=cost_least]').val()) {
            var min = $('input[name=cost_least]').val();
            if (isNaN(min)) {
                tips('消费最小值输入数字');
                return;
            }
            data.min = min;
        }

        if (min && !max) {
            tips('请入消费最大值');
            return;
        }

        if (!min && max) {
            data.min = 0;
        }   

        $.post('searchPro', data, null, 'json').fail(function(xhr){
            if (xhr.status == 403) {
                tips('无权访问');
            } else {
                tips('查询失败，请稍后重试');
            }
        }).done(function(data){
            
            if(data.info){
                var str = '';
                for(var tel in data.info){
                    str += '<ul>';
                    str += '<a href="/Master/Patient/info/tel/'+tel+'"><li>'+data.info[tel]['name']+'</li></a>';
                    for(var item in data.info[tel]['cost_list']){
                        // alert(data.info[name]['cost'][item]);
                        str += '<li>'+data.info[tel]['cost_list'][item]['cost_money']+'</li>'
                    }
                    str += '<li class="cost">'+data.info[tel]['cost_sum']+'</li>';
                    str += '</ul>'
                }

                $('.user_info').html(str);

            }else{
                tips('无符合条件数据');
            }

        });

    });
    


});