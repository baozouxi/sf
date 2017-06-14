$(function(){



    menu();



    //菜单动画
    $('.nav_item').click(function(event) {
        $(this).find('p').addClass('active');
        $(this).find('ul').slideDown();
        $(this).siblings().find('ul').slideUp().siblings('p').removeClass('active');
    });


    //显示隐藏按钮
    $('._button').hover(function() {
        if ($('.hide').is(':animated')) {
            $('.hide').stop();
        }
        $('.hide').fadeIn('fast');
    }, function() {
        if ($('.hide').is(':animated')) {
            $('.hide').stop();
        }
        $('.hide').fadeOut('fast');
    });

    //菜单栏隐藏 显示
    $('.hide').click(function(event) {

        var nav_wid =  $('.main_nav').width();
        var form_wid = $('.form').width();
        if ($('.main_nav').is(':hidden')) {

            $(this).removeClass('show');
            $('.form').css({
                'width': form_wid-nav_wid-10,
                'margin-left': 10
            });  
            $('.fields').removeClass('long').siblings('.user_info').removeClass('long');
            $('.main_nav').fadeIn('fast');  

        }else{

           $(this).addClass('show');
            $('.main_nav').fadeOut('fast', function() {
                    $('.form').css({'width':nav_wid+form_wid+10,
                                    'margin-left':0
                    });
                    $('.fields').addClass('long').siblings('.user_info').addClass('long');
            });
        }
    });



});




var t = null;
//提示框
function tips(msg,type){
    if (t)   clearTimeout(t);
    $('.sys_msg_tips').text(msg).fadeIn('400');
    t = setTimeout(function(){
        $('.sys_msg_tips').text('').fadeOut('400');
    },2000);
}


//检验日期格式函数
function RQcheck(RQ) {
    var date = RQ;
    var result = date.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);

    if (result == null)
                return false;
    var d = new Date(result[1], result[3] - 1, result[4]);
    return (d.getFullYear() == result[1] && (d.getMonth() + 1) == result[3] && d.getDate() == result[4]);
}



function menu()
{
    var current =  window.location.pathname;

    $('.nav_item a').each(function(index, el) {
       href = $(this).attr('href');
       if (current.indexOf(href) !== -1) {
           $(this).addClass('active').parents('ul').slideDown().siblings('p').addClass('active');
       }
    });
}


