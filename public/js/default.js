$(document).ready(function(){
    $('.message i').click(function(){
        $('.message').fadeOut(100);
    });
    
    $('input[name=check_all]').click(function(){
        $(this).closest('table')
               .find('tbody')
               .find('tr')
               .find('input[type=checkbox]').click();         
    });
    
    $('select[name=action]').change(function(){
        var action = $(location).attr('href').split("/").splice(0, 4).join("/");
        $(this).closest('form').attr('action', action + '/' + $(this).val() + '/');
    });
    
    if( $('.message').length ){
        $.ajax({
            url: '/message/remove',
            success: function(data){
                console.log( 'message was removed' + data );
            },
            error: function(){
                console.log( 'message wasn\'t removed' );
            }            
        });
    }
});