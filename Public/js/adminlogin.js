$(function(){
    
    var userSubmit = [];
    $('form[name=username] input').change(function(e){
        if ($(this).val() == '') {
            $(this).siblings('p').show();
             userSubmit.push(false);
        }else{
            $(this).siblings('p').hide();
             userSubmit.push(true);
        }
    });

    $('form[name=username] input[type=button]').click(function(event) {
       
        $('form[name=username] input').trigger('change');
        if(userSubmit.indexOf(false) == '-1'){
             $('form[name=username]').submit();
        }else{
            userSubmit = [];
        }
    });
    
});