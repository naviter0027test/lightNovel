$(document).ready(function() {
    $("#registerContent").submit(function() {
        if(!$(this).validationEngine("validate"))
            return false;
            $.blockUI({ 
                css: { 
                message: '<div>注册中，请稍后</div>',
                border: 'none', 
                padding: '15px', 
                backgroundColor: '#ddd', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .5, 
                color: '#000' 
                },
                overlayCSS: { backgroundColor: '#fff7e8'}
            }); 
        $(this).ajaxSubmit(function(data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            $.unblockUI();

            if(data['status'] == 200)
                alert("请至信箱领取验证信以完成注册程序");
            else {
                if(data['msg'] == "captcha error") {
                    alert("驗證碼錯誤");
                }
                else if(data['msg'] == "member repeat") {
                    alert("會員名稱重複");
                }
                $("img[name=captcha]").attr("src" , "instr.php?instr=captchaRegister&rand=" + Math.random());
            }
        });
        return false;
    });
});