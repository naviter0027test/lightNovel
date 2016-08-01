var articles = null;
$(document).ready(function() {
    $("#indexScript").load("template/index.html", function() {
        articles = new Article({ 'el' : '#content', "model" : new ArticleModel()});
    });
    new IndexRout();
    Backbone.history.start();
    location.href = "#/1";
});

IndexRout = Backbone.Router.extend({
    routes : {
        ":nowPage" : "articleList"
    },

    articleList : function(nowPage) {
        setTimeout(function() {
            articles.model.articleList(nowPage);
        }, 500);
    }
});
