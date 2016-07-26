Message = Backbone.View.extend({
    initialize : function() {
        var self = this;
        this.template = _.template($("#msgListTem").html());
        this.model.on("change:data", function() {
            self.render();
        });
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        this.$el.html(this.template(data));
    }
});

MsgModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'data' : null
    },
    list : function(aid, nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "msgList";
        postData['aid'] = aid;
        postData['nowPage'] = nowPage;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            console.log(data);
            self.set("data", data);
        });
    }
});
