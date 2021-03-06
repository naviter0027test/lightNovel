$(document).ready(function() {
    $(".passForm").validationEngine();
    $(".passForm").submit(function() {
        $(this).ajaxSubmit(function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                alert("修改成功");
            }
            else if(data['msg'] == "old password error") {
                alert("旧密码输入失败");
            }
            else {
                alert("修改失敗");
            }
        });
        return false;
    });
    $("#forgetSendFormTemplate").load("template/forgetSendForm.html", function() {
        var forgetSendForm = new ForgetSetPanel({'el' : '#forgetSendForm', 'model' : new SysModel()});
        forgetSendForm.template = _.template($("#forgetSendFormTem").html());
        forgetSendForm.model.on("change:data", function() {
            forgetSendForm.render();
        });
        forgetSendForm.model.getForgetToSendMail();
    });
});
