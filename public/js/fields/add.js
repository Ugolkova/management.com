$(document).ready(function(){
    $('select[name*=field_type]').change(function(){
        var fieldType = $(this).val();
        $('.fieldOptions').fadeOut(0);
        $('#ft_' + fieldType).fadeIn(50);
        
        $('.fieldOptions').find('input:required')
                .removeAttr('required');
        $('.fieldOptions').find('input.error')
                .removeClass('error');
        
        $('.fieldOptions:visible')
                .find('input').each(function(){
            if( $(this).attr('data-required') == '1' ){
                $(this).prop('required', true);
            }
        });        
    }).change();
}); 