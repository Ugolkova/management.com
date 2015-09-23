$(document).ready(function(){
    $('select[name*=field_type]').change(function(){
        var fieldType = $(this).val();
        $('.fieldOptions').fadeOut(0);
        $('#ft_' + fieldType).fadeIn(50);
    }).change();
}); 