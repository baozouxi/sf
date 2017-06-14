$(function(){

    var input_con = $('ul.input').html();
    var submit = false;
    var url = '/Home/Admin/addAdmin';
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
            tips('姓名不能为空');
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


 	$('body').on('click', 'span.ok', function(e){
 		$(this).parent().siblings('li').find('input').trigger('change');
        var _parent = $(this).parent();
 		if (submit) {
 			var name = $('input[name=name]').val();
 			var tel  = $('input[name=tel]').val();
 			var unique = $('input[name=unique]').val();
            var permission = $('select[name=permission]').val();
 			$.post(url, {name:name, tel:tel, permission:permission, unique:unique}, null, 'json').fail(function(data){
 				if (data.status == 403) {
 					tips('无权访问');
 				}
 			}).done(function(data){
 				
 				if (data.status == 'ok') {
 					 tips('添加成功');  
                    _parent.html('<b style="color:green;">已添加</b>');
 				} else {
                    tip(data.errMsg);
 				}
 			});
 		}
 	});

});

