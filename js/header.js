function loginPanelPositionSet() {
    var offset = $(".rightNav").offset();
    $(".floatPanel").offset( { 'top' : offset.top+50, 'left': offset.left-190});
}
$(document).ready(function() {
    TSC('gb');
    loginPanelPositionSet();
    $(window).resize(function() {
        loginPanelPositionSet();
    });
    $(".status").on("click", function() {
        if($("#memberPanel").hasClass("show")) {
            $("#memberPanel").fadeOut();
            $("#memberPanel").removeClass("show");
        }
        else {
            $("#memberPanel").fadeIn();
            $("#memberPanel").addClass("show");
            loginPanelPositionSet();
        }
        $(this).toggleClass("rightNavHover");
        return false;
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

