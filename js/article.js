var article = null;
var msgArea = null;
var msgPager = null;

$(document).ready(function() {
    $("#scriptLoad").load("template/article.html", function() {
        article = new Article({ 'el' : '#content', "model" : new ArticleModel()});
        article.model.on("change:data", function() {
            var data = this.get("data");
            //console.log(data);
            data['data']['a_mainCp'] = data['data']['a_mainCp'].replace(";", "/");
            if(data['data']['a_mainCp2'] != null) {
                var cp2 = data['data']['a_mainCp2'];
                data['data']['a_mainCp2'] = cp2.replace(";", "/");
            }
            article.$el.html(article.template(data));
            $("select[name=selectCh]").on("change", function() {
                location.href = "#article/"+ $(this).val()+"/1";
            });
        });
        msgArea = new Message({ 'el' : '#msgList', "model" : new MsgModel()});
        msgPager = new Pager({'el' : '#pager', "model" : msgArea.model});

        msgArea.model.on("change:data", function() {
            var amount = this.get("data")['msgAmount'];
            var aid = this.get("aid");
            msgArea.render();
            msgPager.render4(this.get("nowPage"), 50, amount, aid);
        });

        new ArticleRout();
        Backbone.history.start();
    });
});

ArticleRout = Backbone.Router.extend({
    routes : {
        "pressPraise/:aid" : "pressPraise",
        "subscript/:cls/:id" : "subscript",
        "bookmark/:aid" : "bookmark",
        "article/:aid/:nowPage" : "articleShow",
        "article/:aid" : "articleShow"
    },

    pressPraise : function(aid) {
        var postData = {};
        postData['instr'] = "pressPraise";
        postData['aid'] = aid;
        //console.log(postData);
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
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

    subscript : function(cls, id) {
        var postData = {};
        postData['instr'] = "subscript";
        if(cls == "aid") {
            postData['aid'] = id;
        }
        else if(cls == "asid") {
            postData['asid'] = id;
        }
        else if(cls == "mid") {
            postData['mid'] = id;
        }

        //console.log(postData);
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                if(data['msg'] == "subscript success")
                    alert("訂閱成功");
                else if(data['msg'] == "subscriptCancel success")
                    alert("訂閱取消 成功");
            }
            else {
                alert("訂閱失敗");
                console.log(data);
            }
            history.go(-1);
        });
    },

    bookmark : function(aid) {
        var postData = {};
        postData['instr'] = "bookmark";
        postData['bookId'] = aid;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                if(data['msg'] == "bookmark success")
                    alert("收藏成功");
                else if(data['msg'] == "bookmarkCancel success")
                    alert("收藏取消 成功");
            }
            else 
                console.log(data);
            history.go(-1);
        });
    },

    articleShow : function(aid, nowPage) {
        article.model.getOne(aid);
        msgArea.model.set("aid", aid);
        msgArea.model.set("nowPage", nowPage);
        msgArea.model.list();
    },
});
