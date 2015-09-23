$(document).ready(function(){
    $('select[name*=field_type]').change(function(){
        var fieldType = $(this).val();
        var fieldId = $(this).closest('table').find('input[name*=field_id]').val();
        console.log(fieldId);
        $('.fieldOptions[data-field-id='+ fieldId + ']').fadeOut(0);
        $('.ft_' + fieldType + '[data-field-id='+ fieldId + ']').fadeIn(50);
    }).change();
});