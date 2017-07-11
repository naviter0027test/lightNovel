function isLogin() {
    var postData = {};
    postData['instr'] = "isLogin";
    $.post("instr.php", postData, function(data) {
        //console.log(data);
        data = JSON.parse(data);
        //console.log(data);
        console.log(location.pathname);
        var pathArr = location.pathname.split("/");
        pathname = pathArr[pathArr.length-1];
        console.log(pathname);
        if(data['status'] == 200) {
            if(pathname == "index.html" || pathname == "")
                location.href = "admin.html";
        }
        else {
            if(pathname != "index.html" && pathname != "") {
                alert("尚未登入");
                location.href = "index.html";
            }
        }
    });
}

PermitContent = Backbone.View.extend({
    initialize : function() {
    },
    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        //console.log(data);
        if(data == null)
            data = {'data' : null};
        this.$el.html(this.template(data));
    }
});

Admin = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        "passAdm.html" : "密碼管理",
        "permitAdm.html#list" : "權限管理",
        "memberList.html#list/1" : "會員列表",
        "articleList.html#list/1" : "文章列表",
        "cpPanel.html#edit" : "主CP管理",
        "synonyms.html#list/1" : "同義字管理",
        "data" : null
    },

    list : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "permitList";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            console.log(data);
            self.set("data", data);
        });
    }
});

ForgetSetPanel = Backbone.View.extend({
    initialize : function() {
    },

    events : {
        'click button' : 'modifyMail'
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        this.$el.validationEngine();
        //console.log(data);
        if(data == null)
            data = {'data' : null};
        this.$el.html(this.template(data));
    },

    modifyMail : function(evt) {
        if(!this.$el.validationEngine("validate"))
            return false;
        this.$el.ajaxSubmit(function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data)
            if(data['status'] == 200) {
                alert("修改成功");
            }
            else {
                alert("修改失敗");
                console.log(data);
            }
        });
        return false;
    }
});

SysModel = Backbone.Model.extend({
    initialize : function() {
    },

    defaults : {
        "data" : null
    },

    getForgetToSendMail : function() {
        var self = this;
        var postData = {};
        postData['instr'] = 'forgetGet';
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data)
            self.set('data', data);
        });
    }
});
