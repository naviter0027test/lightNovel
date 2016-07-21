MyArticle = Backbone.View.extend({
    initialize : function() {
        console.log("my article view created");
    },

    el : '',
    template : null,
    render : function(data) {
        $("#content").html(this.template(data));
    }
});

ArticleModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'myLastArticles' : null
    },

    myLastArticles : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "memArticleList";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            console.log(data);
            self.set("myLastArticles", data);
        });
    }
});
