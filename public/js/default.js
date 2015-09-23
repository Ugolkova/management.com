$(document).ready(function(){
    $('menu').find('li').find('a').attr('title', '');
    
    $('.message i').click(function(){
        $('.message').slideUp(100);
    });
    
    $('input[name=check_all]').click(function(e){
        var elements = $(this).closest('table')
                              .find('tbody')
                              .find('tr')
                              .find('input[type=checkbox]:enabled');
        if( $(this).is(':checked') ){
            elements.prop('checked', true);
            elements.each(function(){
                $(this).closest('tr').addClass('checked');
            });
        } else {
            elements.removeAttr('checked');            
            elements.each(function(){
                $(this).closest('tr').removeClass('checked');
            });
        }
    });
    
    $('table').find('tbody').find('input[type=checkbox]').click(function(){
        var inputCheckAll = $('input[name=check_all]');
        
        inputCheckAll.removeAttr('checked');
        
        if( $(this).is(':checked') ){
            if( $(this).closest('tbody').find('input[type=checkbox]:checked').length ==
                    $(this).closest('tbody').find('tr').length ){
                inputCheckAll.prop('checked', true);
            }
            
            $(this).closest('tr').addClass('checked');
        } else {
            $(this).closest('tr').removeClass('checked');
        }
    });
    
    $('select[name=action]').change(function(){
        var action = $(location).attr('href').split("/").splice(0, 4).join("/");
        $(this).closest('form').attr('action', action + '/' + $(this).val() + '/');
        
        var actionVal = $(this).val();  

        $(this).closest('form').find('tbody').find('tr').each(function(){
            if( actionVal == 'edit' || 
                    ($(this).find('input[type=checkbox]').attr('data-remove') == 'true' && 
                        actionVal == 'delete') ){
                $(this).find('input[type=checkbox]').prop('disabled', false);
            } else {
                $(this).find('input[type=checkbox]').prop('disabled', true);
            }
        });
        
    });
    
    if( $('.message').length ){
        $.ajax({
            url: '/index/removeMsg/',
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
        focus: function( event, ui ) {
            event.preventDefault();
            $( "input[name='key']" ).val( ui.item.name );            
        },
        select: function( event, ui ) {
            // TODO
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
    
    $('table#list').find('tbody').find('tr').click(function(e){
        if( e.target.nodeName == 'TD' ){
            $(this).find('input[type=checkbox]').click();
        }    
    });
    
    $('select[name=results_count]').change(function(){        
        var count = parseInt( $(this).val() );
        $.ajax({
            data: {count: count},
            url: '/index/changeEntriesCount/',
            success: function(data){
                location.reload();
            },
            error: function(){
                console.log( 'Can\'t change entries count.' );
            }            
        });
    });
    
    $('form').submit(function(){
        if( $('input[name=submit_action]').length ){
            if( $('select[name=action]').val() == 'delete' ){
                var entriesToRemove = new Array();
                entriesToRemove.push( $(this).closest('form')
                                          .find('tbody')
                                          .find('tr')
                                          .find('input:checked')
                                          .closest('tr')
                                                .find('td:nth-of-type(2)')
                                                .find('a').text().replace(/(\t|\r)/g, '') );
                console.log(entriesToRemove);
                if( !confirm('You are going to remove entries: \t' + entriesToRemove) ){
                    return false;
                }
            }
        }
    });
    
});