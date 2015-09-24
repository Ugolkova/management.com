$(document).ready(function(){
    $('select[name=users_type]').change(function(){
        if( $(this).val() != '' ){
            window.location = '/users/get_entries/' + $(this).val() + '/';
        }
    });
});

