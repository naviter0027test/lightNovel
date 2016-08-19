var article = null;
var msgArea = null;

$(document).ready(function() {
    $("#scriptLoad").load("template/article.html", function() {
        article = new Article({ 'el' : '#content', "model" : new ArticleModel()});
        article.model.on("change:data", function() {
            var data = this.get("data");
            article.$el.html(article.template(data));
            $("select[name=selectCh]").on("change", function() {
                location.href = "#article/"+ $(this).val();
            });
        });
        msgArea = new Message({ 'el' : '#msgList', "model" : new MsgModel()});
        new ArticleRout();
        Backbone.history.start();
    });
});

ArticleRout = Backbone.Router.extend({
    routes : {
        "pressPraise/:aid" : "pressPraise",
        "article/:aid" : "articleShow"
    },

    pressPraise : function(aid) {
        var postData = {};
        postData['instr'] = "pressPraise";
        postData['aid'] = aid;
        console.log(postData);
        $.post("instr.php", postData, function(data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) {
                alert("點讚成功");
                history.go(-1);
            }
            else {
                console.log(data['msg']);
                if(data['msg'] == "press praise repeat")
                    alert("你已經點讚過了");
                else if(data['msg'] == "member not login")
                    alert("你尚未登入");
                history.go(-1);
            }
        });
    },

    articleShow : function(aid) {
        article.model.getOne(aid);
        msgArea.model.set("aid", aid);
        msgArea.model.set("nowPage", 1);
        msgArea.model.list();
    }
});
