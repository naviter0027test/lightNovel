var articlesView = null;
var pager = null;
var nowpage = 1;
$(document).ready(function() {
    $("#sidebar").load("template/sidebar.html", function() {
        $("#sidebar a").on("click", function() {
            /*
            $("#header a").removeClass("choosed");
            $("#sidebar a").removeClass("choosed");
            $(this).addClass("choosed");
            */
        });
    });
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
        "del/:aid" : "delArticle",
        "list/:nowPage" : "list",
        "search/:nowPage" : "search"
    },

    delArticle : function(aid) {
        var postData = {};
        postData['instr'] = "articleDel";
        postData['aid'] = aid;
        if(confirm("是否刪除?")) {
            console.log(postData);
            $.post("instr.php", postData, function(data) {
                console.log("test");
                console.log(data);
                data = JSON.parse(data);
                console.log(data);
                if(data['status'] == 200) {
                    alert("刪除成功");
                }
                else {
                    alert("刪除失敗");
                }
                history.go(-1);
            });
        }
        else
            history.go(-1);
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
