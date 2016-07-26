Message = Backbone.View.extend({
    initialize : function() {
        var self = this;
        this.template = _.template($("#msgListTem").html());
        this.model.on("change:data", function() {
            self.render();
        });
    },

    events : {
        "click #msgSend" : "sendMsg"
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        this.$el.html(this.template(data));
    },

    sendMsg : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "addMessage";
        postData['aid'] = this.model.get("aid");
        postData['message'] = $("#msgInput").val();
        console.log(postData);
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                alert("留言成功");
                self.model.list();
            }
        });
    }
});

MsgModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'nowPage' : 1,
        'aid' : null,
        'data' : null
    },
    list : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "msgList";
        postData['aid'] = this.get("aid");
        postData['nowPage'] = this.get("nowPage");
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    }
});
