$(document).ready(function() {
    // Scroll to link
    setTimeout(function () {
        var pathname = window.location.pathname;
        var pathnameAlt = pathname.substring(1, pathname.length);

        $('.sidebar__links a[href="'+ pathnameAlt +'"]').parent().addClass('active');

        $('#sidebar').animate({
            scrollTop: $('.sidebar__links a[href="'+ pathnameAlt +'"]').offset().top - 80
        }, 0);
    });


    // Tab select
    $('body').on('click', '[data-action="tab-select"]', function (e) {
        e.preventDefault();

        var pane = $(this).attr('href');
        var label = $(this).text();
        var parent = $(this).closest('.tab-select');

        parent.find('.tab-select__current').text(label);
        parent.find('[data-action="tab-select"]').parent().removeClass('active');
        $(pane).parent().find('.tab-select-content__pane').removeClass('active');
        $(pane).addClass('active');
        $(this).parent().addClass('active');
    });
});