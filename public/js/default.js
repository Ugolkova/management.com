$(document).ready(function(){
    $('menu').find('li').find('a').attr('title', '');
    
    $('.message i').click(function(){
        $('.message').slideUp(100);
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
    
    
    var searchUrl = $('form.searchForm').attr('action');
    
    var obj = $( "input[name='key']" ).autocomplete({
        source: function(request, response) {
            $.get( searchUrl, { key: request.term, autocomplete: 1 }, 
                    function(data) {
                response($.parseJSON(data));
            });
        },
        minLength: 2,
        select: function( event, ui ) {
            //event.preventDefault();
            //$( "input[name='key']" ).val( ui.item.name );
        }
    });
    if( obj.data( "ui-autocomplete" ) ){
        obj.data("ui-autocomplete")._renderItem = function(ul, item) {
            $(ul).attr('id', 'search-autocomplete');
            return $( '<li></li>' )
                .data( 'item.autocomplete', item )
                .append( '<a href="' + item.link + '">' + item.name + '</a>')
                .appendTo(ul);
        };   
    }    
});