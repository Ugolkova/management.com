$(document).ready(function(){
    $('select[name*=field_type]').change(function(){
        var fieldType = $(this).val();
        var fieldId = $(this).closest('table').find('input[name*=field_id]').val();
        console.log(fieldId);
        $('.fieldOptions[data-field-id='+ fieldId + ']').fadeOut(0);
        $('.ft_' + fieldType + '[data-field-id='+ fieldId + ']').fadeIn(50);
        
        $('.fieldOptions[data-field-id='+ fieldId + ']').find('input:required')
                .removeAttr('required');
        $('.fieldOptions[data-field-id='+ fieldId + ']').find('input.error')
                .removeClass('error');
        
        $('.fieldOptions[data-field-id='+ fieldId + ']:visible')
                .find('input').each(function(){
            if( $(this).attr('data-required') == '1' ){
                $(this).prop('required', true);
            }
        });
    }).change();
});