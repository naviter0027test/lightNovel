var articles = null;
var pager = null;
var nowpage = null;
var pageLimit = 25;
$(document).ready(function() {
    $("#indexScript").load("template/index.html", function() {
        articles = new Article({ 'el' : '#content', "model" : new ArticleModel()});
        pager = new Pager({'el' : '#pager', 'model' : articles.model});
        articles.model.on("change:data", function() {
            articles.render();
            pager.render2(nowpage, pageLimit);
        });
        new IndexRout();
        Backbone.history.start();
        location.href = "#/1";
    });
});

IndexRout = Backbone.Router.extend({
    routes : {
        ":nowPage" : "articleList"
    },

    articleList : function(nowPage) {
        setTimeout(function() {
            nowpage = nowPage;
            articles.model.articleList(nowPage);
        }, 200);
    }
});
