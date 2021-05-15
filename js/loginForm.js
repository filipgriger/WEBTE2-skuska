$(document).ready(function () {
    $('#teacherButton').click(toggleSignUp);
    $('#studentButton').click(toggleSignUp);
})

function toggleSignUp(e) {
    e.preventDefault();
    $('#teacherForm').toggle();
    $('#studentForm').toggle();

    if ($('#studentButton').hasClass("active")) {
        $('#studentButton').removeClass("active");
        $('#teacherButton').addClass("active");
    } else {
        $('#studentButton').addClass("active");
        $('#teacherButton').removeClass("active");
    }
}