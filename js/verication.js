$(document).ready(function() {
    var postData = {};
    postData['instr'] = getQueryVariable("instr");
    postData['user'] = getQueryVariable("user");
    postData['email'] = getQueryVariable("email");
    $.post("instr.php", postData, function(data) {
        console.log(data);
        data = JSON.parse(data);
        console.log(data);

        if(data['status'] == 200) {
            $(".result").append("<p class='col-xs-12'>注册成功，您可以开始发布文章，给别人点赞，一起愉快的玩耍。</p>");
        }
        else {
            $(".result").append("<p class='col-xs-12'>验证错误，错误如下</p>");
            $(".result").append("<p class='col-xs-12'>"+ data['msg']+ "</p>");
            $(".result").append("<p class='col-xs-12'>请截图下来，并寄给管理员为您处理</p>");
        }
    });
});
