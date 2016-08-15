function isLogin() {
    var postData = {};
    postData['instr'] = "isLogin";
    $.post("instr.php", postData, function(data) {
        //console.log(data);
        data = JSON.parse(data);
        //console.log(data);
        //console.log(location.pathname);
        var pathArr = location.pathname.split("/");
        pathname = pathArr[pathArr.length-1];
        //console.log(pathname);
        if(data['status'] == 200) {
            if(pathname == "index.html")
                location.href = "admin.html";
        }
        else {
            if(pathname != "index.html")
                location.href = "index.html";
        }
    });
}

Admin = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
    }
});

