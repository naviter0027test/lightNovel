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
        $("#logout").on("click", function() {
            var postData = {};
            postData['instr'] = "logout";
            $.post("instr.php", postData, function(data) {
                console.log(data)
                data = JSON.parse(data);
                console.log(data)
                if(data['status'] == 200) {
                    alert("登出成功");
                    location.href = "index.html";
                }
                else 
                    alert("登出失敗");

            });
            return false;
        });
        return false;
    });
    $("#content").load("template/index.html");
    isLogin();
});
