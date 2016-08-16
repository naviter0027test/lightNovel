ArticleTable = Backbone.View.extend({
    initialize : function() {
        this.template = _.template($("#articleListTem").html());
    },
    el : '',

    events : {
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        //console.log(data);
        this.$el.html(this.template(data));
    }
});

ArticleModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'data' : null
    },
    list : function(nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "articleList";
        postData['nowPage'] = nowPage;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    }
});
