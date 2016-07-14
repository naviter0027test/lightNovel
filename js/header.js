function loginPanelPositionSet() {
    var offset = $(".rightNav").offset();
    $(".floatPanel").offset( { 'top' : offset.top+50, 'left': offset.left-190});
}
$(document).ready(function() {
    loginPanelPositionSet();
    $(window).resize(function() {
        loginPanelPositionSet();
    });
    $("#header .status").on("click", function() {
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
    $("#header .login").on("click", function() {
        if($("#loginPanel").hasClass("show")) {
            $("#loginPanel").fadeOut();
            $("#loginPanel").removeClass("show");
            $(this).removeClass("rightNavHover");
        }
        else {
            $("#registerPanel").fadeOut();
            $("#loginPanel").fadeIn();
            $("#loginPanel").addClass("show");
            $(this).addClass("rightNavHover");
            loginPanelPositionSet();
        }
        return false;
    });
});

