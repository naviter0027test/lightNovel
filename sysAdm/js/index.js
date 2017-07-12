$(document).ready(function() {
    $(".captcha").attr("src", "instr.php?instr=captchaLogin&ran="+ Math.random());
    $("#loginForm").submit(function() {
        var self = this;
        $(this).ajaxSubmit(function(data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200){
                alert("登入成功");
                location.href = "admin.html";
            }
            else {
                alert("登入失敗");
                $(self).clearForm();
                $(".captcha").attr("src", "instr.php?instr=captchaLogin&ran="+ Math.random());
            }
        });
        return false;
    });
    $("#forget").on("click", function() {
        if(confirm("确定重发新密码?")) {
            var postData = {};
            postData['instr'] = "forget";
            $.post("instr.php", postData, function(data) {
                console.log(data);
                data = JSON.parse(data);
                console.log(data);
                if (data['status'] == 200) {
                    alert("重发新密码信件成功，请至邮箱领取");
                }
            });
        }
        return false;
    });
    isLogin();
});
