$(document).ready(function(){
    var searchUrl = '/users/search/';
    var obj = $( 'input[name*="user_name"]' ).autocomplete({
        source: function(request, response) {
            $.get( searchUrl, { key: request.term, autocomplete: 1 }, 
                    function(data) {
                response($.parseJSON(data));
                
                if( data.length > 2 ){
                    $('input[name*="user_name"]:focus').addClass('error');
                } else {
                    $('input[name*="user_name"]:focus').removeClass('error');
                }
            });
        },
        minLength: 2,
        select: function( event, ui ) {
            // TODO
        }
    });
    if( obj.data( "ui-autocomplete" ) ){
        obj.data("ui-autocomplete")._renderItem = function(ul, item) {
            $(ul).attr('id', 'search-autocomplete');
            return $( '<li></li>' )
                .data( 'item.autocomplete', item )
                .append( '<a href="' + item.link + '" onclick="alert(\'User already isset\'); return false;">' + item.name + '</a>')
                .appendTo(ul);
        };   
    }  
});