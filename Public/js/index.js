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
            $.post('/Home/Spending/select', {unique:unique,date:$(this).val()}, null, 'json').fail(function(xhr){
                tips('服务器错误：查询失败');
            }).done(function(data){
                if(data.status == 'ok'){
                    var str = '';
                    for(var tel in data.info){
                        str += '<ul>';
                        str += '<a href="/Home/Patient/info/tel/'+tel+'"><li>'+data.info[tel]['name']+'</li></a>';
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
        $.post('/Home/Patient/search',data ,null,'json').fail().done(function(data){
            
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
                        str += '<a href="/Home/Patient/info/tel/'+tel+'"><li>'+data.info[tel]['name']+'</li></a>';
                        for(var item in data.info[tel]['cost']){
                            // alert(data.info[name]['cost'][item]);
                            str += '<li>'+data.info[tel]['cost'][item]['cost_money']+'</li>'
                        }
                        str += '<li class="cost">'+data.info[tel]['sum_money']+'</li>';
                        str += '</ul>'
                    }
                $('.user_info').html(str);
            }else{
                tips(data.errMsg);
            }

        }); 
    });

});