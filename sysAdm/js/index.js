$(document).ready(function() {
    initialShow();
    $("#sidebar").load("template/sidebar.html", function() {
        $("#sidebar a").on("click", function() {
            $("#header a").removeClass("choosed");
            $("#sidebar a").removeClass("choosed");
            $(this).addClass("choosed");
        });
    });
    $("#header").load("template/header.html", function() {
        $("#header a").on("click", function() {
            $("#header a").removeClass("choosed");
            $("#sidebar a").removeClass("choosed");
            $(this).addClass("choosed");
            $("#content").load("template/"+$(this).attr("href"));
            return false;
        });
    });
    $("#content").load("template/index.html");
});
