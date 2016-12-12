HeadPanel = Backbone.View.extend({
    initialize : function() {
        self = this;
        $('body').append("<div id='headTem' style='display: none;'></div>");
        $("#headTem").load("template/header.html");
        this.model.on("change:isLogin", function() {
            if(this.get("isLogin") == false) {
                var pathArr = location.pathname.split('/');
                var nowHref = pathArr[pathArr.length-1];

                var isFirst = getCookie("isFirst");

                //若是初次到訪會跳轉到 useExplan.html
                if(isFirst == "" && (nowHref == "index.html" || nowHref == "")) {
                    setCookie("isFirst", "hasSee", 3);
                    //location.href = "useExplan.html";
                    location.href = "cover.html";
                }

                //因為這兩個網頁需要登入才能使用，否則強至跳到首頁
                if(nowHref == "dashboard.html" || nowHref == "postArticle.html")
                    location.href = "index.html";

                //文章的订阅功能表
                $(".subscriptShow").hide();
            }
            else {
                //文章的订阅功能表
                $(".subscriptShow").show();
            }
            self.render();

            //取出'已讚'、'禮物'與'留言'的總數印在登入小窗上
            self.model.getPraiseAmount();
            self.model.getGiftAmount();
            self.model.getMsgAmount();
            self.model.getSubscriptAmount();
            self.model.on("change:praiseAmount", function() {
                var amount = this.get("praiseAmount");
                var recentAmount = this.get("recentPraiseAmount");
                //console.log(amount);
                $(".myPraiseAmount").text(amount);
                $(".myRecentPraiseAmount").text(amount);
            });
            self.model.on("change:giftAmount", function() {
                var amount = this.get("giftAmount");
                var recentAmount = this.get("recentGiftAmount");
                //console.log(recentAmount);
                $(".myGiftAmount").text(amount);
                $(".myRecentGiftAmount").text(recentAmount);
            });
            self.model.on("change:msgAmount", function() {
                var amount = this.get("msgAmount");
                var recentAmount = this.get("recentMsgAmount");
                //console.log(amount);
                $(".myMsgAmount").text(amount);
                $(".myRecentMsgAmount").text(recentAmount);
            });
            self.model.on("change:subscriptAmount", function() {
                var amount = this.get("subscriptAmount");
                //console.log(amount);
                $(".mySubscriptAmount").text(amount);
            });
        });

        //會員的資料取出,印出使用者帳號
        this.model.on("change:myData", function() {
            var data = this.get("myData");
            if(data['status'] == 200) {
                $("#memberPanel .memHello").text(data['data']['m_user']+ " 您好！");
                $(".rightNav span").text(data['data']['m_user']);
            }
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

        //有記住我的情況下，再次開啟瀏覽器即可登入
        if($("input[name=rememberMe]:checked").length == 1) 
            setCookie("PHPSESSID", getCookie("PHPSESSID"), 3);

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
        'praiseAmount' : null,
        'recentPraiseAmount' : null,
        'giftAmount' : null,
        'recentGiftAmount' : null,
        'msgAmount' : null,
        'recentMsgAmount' : null,
        'subscriptAmount' : null,
        'isLogin' : null
    },

    login : function(loginData) {
        var self = this;
        $.post("instr.php", loginData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                self.set("isLogin", true);
                alert("登入成功");
            }
            else {
                if(data['msg'] == "captcha error") 
                    alert("验证码错误");
                else if(data['msg'] == "not find member")
                    alert("帐密错误");
                else if(data['msg'] == "member not active")
                    alert("该会员未启用");
                else if(data['msg'] == "member is disable")
                    alert("该会员遭停用");
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
            //console.log(data);
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
    },

    getPraiseAmount : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "praiseAmount";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                self.set("praiseAmount", data['data']);
                self.set("recentPraiseAmount", data['recentAmount']);
            }
        });
    },

    getGiftAmount : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "giftAmount";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) {
                self.set("recentGiftAmount", data['recentAmount']);
                self.set("giftAmount", data['data']);
            }
        });
    },

    getMsgAmount : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "msgAmount";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                self.set("recentMsgAmount", data['recentAmount']);
                self.set("msgAmount", data['data']);
            }
        });
    },

    getSubscriptAmount : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "subscriptUpdAmount";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                self.set("subscriptAmount", data['data']);
            }
        });
    }
});
