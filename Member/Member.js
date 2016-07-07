HeadPanel = Backbone.View.extend({
    initialize : function() {
        self = this;
        this.model.on("change:isLogin", function() {
            self.render();
        });
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
