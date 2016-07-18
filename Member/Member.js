HeadPanel = Backbone.View.extend({
    initialize : function() {
        self = this;
        $('body').append("<div id='headTem' style='display: none;'></div>");
        $("#headTem").load("template/header.html");
        this.model.on("change:isLogin", function() {
            if(this.get("isLogin") == false) {
                var pathArr = location.pathname.split('/');
                var nowHref = pathArr[pathArr.length-1];

                //因為這兩個網頁需要登入才能使用，否則強至跳到首頁
                if(nowHref == "dashboard.html" || nowHref == "postArticle.html")
                    location.href = "index.html";
            }
            self.render();
        });

        this.model.on("change:myData", function() {
            var data = this.get("myData");
            $("#memberPanel .memHello").text(data['data']['m_user']+ " 您好！");
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

        var pathArr = location.pathname.split('/');
        //console.log(pathArr.length);
        //console.log(pathArr[pathArr.length-1]);
        var nowHref = pathArr[pathArr.length-1];
        if(nowHref == "")
            nowHref = "index.html";
        var links = this.$el.find(".nav a");
        $(links).removeClass("nowChoose");
        for(var idx = 0;idx < links.length;++idx) {
            if($(links[idx]).attr("href") == nowHref)
                $(links[idx]).addClass("nowChoose");
        }

        this.model.getMyData();
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
    },
    defaults : {
	'data' : null,
        'myData' : null,
        'seriesList' : null,
        'seriesAmount' : null,
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
            //console.log(data);
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
            //console.log(data);
            if(data['status'] == 200) 
                self.set("isLogin", true);
            else
                self.set("isLogin", false);
        });
    },
    getMyData : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "myData";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            self.set("myData", data);
        });
    },
    getMySeriesList : function(postData) {
        var self = this;
        postData['instr'] = "mySeriesList";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("seriesList", data);
        });
    },
    getMySerieses : function() {
        var self = this;
        postData = {};
        postData['instr'] = "memSrsPages";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("seriesAmount", data['amount']);
        });
    }
});
