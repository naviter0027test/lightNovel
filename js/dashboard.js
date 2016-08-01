$(document).ready(function() {
    dashboard = new Dashboard({'el' : '#dashboard'});
    new DashboardRout();
    Backbone.history.start();
});

DashboardRout = Backbone.Router.extend({
    routes : {
        "addSeries" : "addSeries",
        "articleEdit/:aid" : "articleEdit",
        "articleDel/:aid" : "articleDel",
        "changePage/:page" : "changePage",
        "changePage/:page/:nowPage/:pageLimit" : "changePage"
    },

    addSeries : function() {
        console.log("add series");
        var self = dashboard;
        var loadPage = "template/addSeries.html";
        $("#contentTem").load(loadPage, function() {
            self.template = _.template($("#addSeriesTem").html());
            self.render()
            $("#addSeriesForm").submit(function() {
                var postData = $(this).serialize();
                console.log(postData);
                $.post("instr.php", postData, function(data) {
                    console.log(data);
                    data = JSON.parse(data);
                    console.log(data);
                    if(data['status'] == 200) {
                        alert("新增成功");
                        history.go(-1);
                    }
                });
                return false;
            });
        });
    },

    articleEdit : function(aid) {
        console.log(aid);
    },

    articleDel : function(aid) {
        var postData = {};
        postData['instr'] = "articleDel";
        postData['aid'] = aid;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) {
                alert("刪除成功");
                history.go(-1);
            }
        });
    },

    changePage : function(page, nowPage, pageLimit) {
        //console.log(page);
        //console.log("template/"+$(evt.target).attr("href"));
        //console.log(evt.target);
        var memModel = new MemberModel();
        var articleModel = new ArticleModel();
        var self = dashboard;
        var myArticle = new MyArticle();
        var loadPage = "template/"+ page+ ".html";
        var clickBtn = $("#dashboard a[temid="+page+"]");

        //變換按鈕顏色
        var allLi = $("#dashboard").find("li");
        $(allLi).removeClass("nowChoose");
        $(clickBtn).parent().addClass("nowChoose");

        pager = null;

        //設定資料修改的瞬間進行render
        memModel.on("change:myData", function() {
            self.render(memModel.get("myData"));
            $.getScript("Member/Personal.js", function() {
                personal = new Personal({'el' : "#personalForm"});
                personalimg = new PersonalImg({'el' : "#personalImg"});
            });
        });
        memModel.on("change:seriesList", function() {
            self.render(memModel.get("seriesList"));
        });
        memModel.on("change:seriesAmount", function() {
            pager.render(nowPage, pageLimit);
        });

        //文章取得時切換html
        articleModel.on("change:myLastArticles", function() {
            myArticle.render(articleModel.get("myLastArticles"));
        });

        $("#pager").html('');
        $("#contentTem").load(loadPage, function() {
            var idname = $(clickBtn).attr("temid");
            self.template = _.template($("#"+idname).html());
            myArticle.template = _.template($("#"+idname).html());

            if(idname == "personal") {
                memModel.getMyData();
            }
            else if(idname == "mySeries") {
                var para = {};
                para['nowPage'] = nowPage;
                para['pageLimit'] = pageLimit;
                memModel.getMySeriesList(para);

                pager = new Pager({'el' : '#pager', 'model' : memModel});
                memModel.getMySerieses();
            }
            else if(idname == "myLastArticle") {
                articleModel.myLastArticles();
            }
            else if(idname == "resetPass") {
                self.render();
                $.getScript("Member/Personal.js", function() {
                    personal = new PassForm({'el' : "#passUpdForm"});
                });
            }
            else
                self.render();
        });
    }
});
