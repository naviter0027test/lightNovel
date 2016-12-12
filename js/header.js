function loginPanelPositionSet() {
    var offset = $(".rightNav").offset();
    $(".floatPanel").offset( { 'top' : offset.top+50, 'left': offset.left-190});
}

function memberPanelBind() {
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
}

function loginPanelBind() {
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
}

function registerPanelBind() {
    /*
    $("#header .register").on("click", function() {
        console.log("register");
        $("#loginPanel").removeClass("show");
        $("#loginPanel").fadeOut();
        $("#registerPanel").fadeIn();
        loginPanelPositionSet();
        return false;
    });
    */
}

$(document).ready(function() {
    $.getScript("lib/CookieAPI.js");
    loginPanelPositionSet();
    $(window).resize(function() {
        loginPanelPositionSet();
    });
    //memberPanelBind();
    //loginPanelBind();
    //registerPanelBind();
    var headerPanel = new HeadPanel({'el' : '#header', 'model' : new MemberModel()});
    setTimeout(function() {
        headerPanel.model.isLogin();
    }, 200);
});

