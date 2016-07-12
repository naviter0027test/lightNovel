HeadPanel = Backbone.View.extend({
    initialize : function() {
        self = this;
        $('body').append("<div id='headTem' style='display: none;'></div>");
        $("#headTem").load("template/header.html");
        this.model.on("change:isLogin", function() {
            self.render();
        });
    },

    events : {
        'click #loginPanel button.loginBtn' : 'login',
        'click #memberPanel button.logout' : 'logout'
    },

    el : '',
    template : null,
    render : function() {
        var isLogin = this.model.get("isLogin");
        if(isLogin) {
            this.template = _.template($("#headerLogin").html());
        }
        else {
            this.template = _.template($("#headerNotLogin").html());
        }
	this.$el.html(this.template());
        loginPanelBind();
        registerPanelBind();
        memberPanelBind();
    },

    login : function() {
        var loginData = $("#loginPanel").formSerialize();
        //console.log(loginData);
        this.model.login(loginData);
        return false;
    },

    logout : function() {
        this.model.logout();
        this.render();
        return false;
    }
});

MemberModel = Backbone.Model.extend({
    initialize : function() {
        console.log("member model");
    },
    defaults : {
	'data' : null,
        'orderList' : null,
        'orderDetail' : null,
        'isLogin' : null
    },

    login : function(loginData) {
        var self = this;
        $.post("instr.php", loginData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            if(data['status'] == 200) 
                self.set("isLogin", true);
            else {
                if(data['msg'] == "captcha error") 
                    alert("驗證碼錯誤");
                else if(data['msg'] == "not find member")
                    alert("帳密錯誤");
                $("#loginPanel img").attr("src", "instr.php?instr=captchaLogin&math="+Math.random);
            }
        });
    },

    logout : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "logout";
        $.post("instr.php", postData, function(data) {
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) 
                self.set("isLogin", false);
            else
                self.set("isLogin", true);
        });
    },
    isLogin : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "isLogin";
        $.post("instr.php", postData, function(data) {
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) 
                self.set("isLogin", true);
            else
                self.set("isLogin", false);
        });
    }
});
