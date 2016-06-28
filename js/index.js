function loginPanelPositionSet() {
    var offset = $(".rightNav").offset();
    $("#loginPanel").offset( { 'top' : offset.top+50, 'left': offset.left-190});
}
$(document).ready(function() {
    TSC('gb');
    loginPanelPositionSet();
    $(window).resize(function() {
        loginPanelPositionSet();
    });
    $(".login").on("click", function() {
        if($("#loginPanel").hasClass("show")) {
            $("#loginPanel").fadeOut();
            $("#loginPanel").removeClass("show");
        }
        else {
            $("#loginPanel").fadeIn();
            $("#loginPanel").addClass("show");
            loginPanelPositionSet();
        }
    $(this).toggleClass("rightNavHover");
    return false;
    });
});

