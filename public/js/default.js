$(document).ready(function(){
    $('.message i').click(function(){
        $.ajax({
            url: '/message/remove',
            success: function(data){
                console.log( 'message was removed' + data );
                $('.message').fadeOut(100);
            },
            error: function(){
                console.log( 'message wasn\'t removed' );
            }            
        });
    });
});