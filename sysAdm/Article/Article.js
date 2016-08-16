ArticleTable = Backbone.View.extend({
    initialize : function() {
        this.template = _.template($("#articleListTem").html());
    },
    el : '',

    events : {
        "submit .searchForm" : "searchForm"
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        //console.log(data);
        this.$el.html(this.template(data));
    },

    searchForm : function(evt) {
        var self = this;
        this.model.set("search", $(".searchForm input[name=search]").val());
        location.href = "#search/1";
        return false;
    }
});

ArticleModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'search' : "",
        'data' : null
    },
    list : function(nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "articleList";
        postData['nowPage'] = nowPage;
        postData['search'] = this.get("search");
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    }
});
