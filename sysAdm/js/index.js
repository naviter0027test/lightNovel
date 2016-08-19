$(document).ready(function() {
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
            }
        });
        return false;
    });
    isLogin();
});
