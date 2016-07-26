var article = null;
var msgArea = null;

$(document).ready(function() {
    $("#scriptLoad").load("template/article.html", function() {
        article = new Article({ 'el' : '#content', "model" : new ArticleModel()});
        msgArea = new Message({ 'el' : '#msgList', "model" : new MsgModel()});
        new ArticleRout();
        Backbone.history.start();
    });
});

ArticleRout = Backbone.Router.extend({
    routes : {
        "article/:aid" : "articleShow"
    },
    articleShow : function(aid) {
        article.model.getOne(aid);
        msgArea.model.list(aid, 1);
    }
});
