$(document).ready(function() {
    $("#sidebar").load("template/sidebar.html", function() {
        $("#sidebar a").on("click", function() {
            /*
            $("#header a").removeClass("choosed");
            $("#sidebar a").removeClass("choosed");
            $(this).addClass("choosed");
            */
        });
    });
    $("#content").load("template/index.html");
    isLogin();
});
