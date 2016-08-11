MyArticle = Backbone.View.extend({
    initialize : function() {
        console.log("my article view created");
    },
    events : {
    },

    el : '',
    template : null,
    render : function() {
    }
});

ArticleModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'myLastArticles' : null
    },

    myLastArticles : function() {
        console.log("get last article");
        var postData = {};
        postData['instr'] = "memArticleList";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            console.log(data);
        });
    }
});
