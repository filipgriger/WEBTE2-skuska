$(function () {

    $('.activate-test,.deactivate-test').click(function () {
        updateTestStatus($(this).attr('data-test-id'), $(this).attr('data-status'), this);
    });


    function updateTestStatus(testId, newStatus, element) {
        $.ajax({
            url: '../router.php',
            method: 'post',
            data: {
                'route': 'updateTestStatus',
                'status': newStatus,
                'testId': testId,
            },
            success: function (response) {
                updateContent(element, response);
            }
        })
    }

    function updateContent(button, response) {
        let icon = $(button).parent().prev().children().first();
        if (parseInt(response)) {
            $(button).addClass('deactivate-test').removeClass('activate-test').removeClass('btn-success').addClass('btn-danger');
            $(button).text('Deactivate');
            $(button).attr('data-status', '0');
            icon.removeClass('text-danger')
                .removeClass('fa-times-circle')
                .addClass('text-success')
                .addClass('fa-check-circle');
        } else {
            $(button).addClass('activate-test').removeClass('deactivate-test').removeClass('btn-danger').addClass('btn-success');
            $(button).text('Activate');
            $(button).attr('data-status', '1');
            icon.removeClass('text-success')
                .removeClass('fa-check-circle')
                .addClass('text-danger')
                .addClass('fa-times-circle');
        }
    }

});