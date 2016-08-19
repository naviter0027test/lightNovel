$(document).ready(function() {
    $("img[name=captcha]").on("click", function() {
        $(this).attr("src", "instr.php?instr=captchaForget&rand="+ Math.random());
    });

    $("#forgetContent").submit(function() {
        $(this).ajaxSubmit(function(data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) {
                alert("新密碼將寄送至您之前填寫的email");
            }
            else {
                if(data['msg'] == "captcha error") {
                    alert("驗證碼錯誤");
                    $("img[name=captcha]").attr("src", "instr.php?instr=captchaForget&rand="+ Math.random());
                }
            }
        });
        return false;
    });
});
