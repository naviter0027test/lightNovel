MyArticle = Backbone.View.extend({
    initialize : function() {
    },

    el : '',
    template : null,
    render : function(data) {
        $("#content").html(this.template(data));
    }
});

PostArticleForm = Backbone.View.extend({
    initialize : function() {
    },
    events : {
        "click button.postBtn" : "postArt"
    },
    el : '',
    template : null,
    render : function() {
    },
    postArt : function() {
        if(!this.$el.validationEngine("validate"))
            return false;
        return false;
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
