function changeRole(userId) {
    var elementId = '#' + userId;
    var parentElement = '.' + userId;


    if ($(elementId).hasClass('system_u')) {
        $(elementId).removeClass('system_u');
        $(elementId).addClass('role_u');
        user = '<div class="' + userId + '">' + $(parentElement).html() + '</div>';

        $(parentElement).remove();
        $('#rl-user').prepend(user);
        return 0;

    }
    if ($(elementId).hasClass('role_u')) {
        $(elementId).removeClass('role_u');
        $(elementId).addClass('system_u');

        user = '<div class="' + userId + '">' + $(parentElement).html() + '</div>';
        $(parentElement).remove();
        $('#sm-user').prepend(user);
        return 0;
    }

}
