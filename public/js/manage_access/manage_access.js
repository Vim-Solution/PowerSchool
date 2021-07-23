function swapFunctionality(funcId) {
    var elementId = '#' + funcId;
    var parentElement = '.' + funcId;

    if ($(elementId).hasClass('system_func')) {
        $(elementId).removeClass('system_func');
        $(elementId).addClass('role_func');
        functionality = '<div class="' + funcId + '">' + $(parentElement).html() + '</div>';

        $(parentElement).remove();
        $('#us-func').prepend(functionality);
        return 0;

    }
        if ($(elementId).hasClass('role_func')) {
            $(elementId).removeClass('role_func');
            $(elementId).addClass('system_func');

            functionality = '<div class="' + funcId + '">' + $(parentElement).html() + '</div>';
            $(parentElement).remove();
            $('#sm-func').prepend(functionality);
            return 0;
        }

}
