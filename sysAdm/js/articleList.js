var articlesView = null;
$(document).ready(function() {
    $("#articleListScript").load("template/articleList.html", function() {
        articlesView = new ArticleTable({'el' : "#articleList", 'model' : new ArticleModel()});
        articlesView.model.on("change:data", function() {
            articlesView.render();
        });
        new ArticleRout();
        Backbone.history.start();
    });
});

ArticleRout = Backbone.Router.extend({
    routes : {
        "list/:nowPage" : "list"
    },

    list : function(nowPage) {
        setTimeout(function() {
            articlesView.model.list(nowPage);
        }, 200);
    },
});
