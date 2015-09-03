$(document).ready(function(){
    $('.message i').click(function(){
        $('.message').fadeOut(100);
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