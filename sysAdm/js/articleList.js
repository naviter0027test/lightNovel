var articlesView = null;
var pager = null;
var nowpage = 1;
$(document).ready(function() {
    $("#articleListScript").load("template/articleList.html", function() {
        articlesView = new ArticleTable({'el' : "#articleList", 'model' : new ArticleModel()});
        pager = new Pager({'el' : "#pager", "model" : articlesView.model});
        articlesView.model.on("change:data", function() {
            articlesView.render();
            pager.render2(nowpage, 20);
        });
        new ArticleRout();
        Backbone.history.start();
    });
});

ArticleRout = Backbone.Router.extend({
    routes : {
        "list/:nowPage" : "list",
        "search/:nowPage" : "search"
    },

    list : function(nowPage) {
        nowpage = nowPage;
        setTimeout(function() {
            articlesView.model.list(nowPage);
        }, 200);
    },

    search : function(nowPage) {
        nowpage = nowPage;
        setTimeout(function() {
            articlesView.model.list(nowPage);
        }, 200);
    }
});
