/**
 * Created by darlington on 12/11/16.
 */

$(document).ready(function () {

    // add bootstrap styles to FOS login forms

    $('input:submit').addClass('btn btn-primary');
    $('.fos_user_registration_register').addClass(' well col-md-6 marginT30 col-md-offset-3');
    $('.fos_user_change_password').addClass(' well col-md-6 marginT30 col-md-offset-3');
    $('.fos_user_profile_edit').addClass(' well col-md-6 marginT30 col-md-offset-3');
    $('form[action="/login_check"]').addClass(' well col-md-6 marginT30 col-md-offset-3');
    $('input[name="_username"]').addClass('form-control marginB15');
    $('input[name="_password"]').addClass('form-control marginB15');
    $('input[name="_submit"]').addClass('show marginT15');
    $('label[for="_remember_me"]').addClass('checkbox marginB15');

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $('.delete_book').on("click", function() {
        var c = confirm("Are you sure you want to delete this book?");
        return c;
    });

    $('.delete_review').on("click", function() {
        var c = confirm("Are you sure you want to delete this review?");
        return c;
    });

    $('#myTabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
});