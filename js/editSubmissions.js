$(function () {
    $('.enable-input').click(function (e) {
        e.preventDefault();
        $(this).parent().prev().children().first().children().first().prop('disabled',false);
    })

});