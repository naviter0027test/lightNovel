var articleEditForm = null;
var myArticleList = null;
var pgr = null;
var myDraftList = null;
var mySubScrt = null;
$(document).ready(function() {
    dashboard = new Dashboard({'el' : '#dashboard'});
    myArticleList = new MyArticle({'el' : '#content', 'model' : new ArticleModel()});
    myDraftList = new MyDraftList({'el' : '#content', 'model' : new MyDraftModel()});
    myDraftList.model.on("change:data", function() {
        var data = this.get("data");
        myDraftList.render(data);
    });
    mySubScrt = new SubScriptList({'el' : '#content', 'model' : new SubScriptModel() });
    mySubScrt.model.on("change:data", function() {
        mySubScrt.render();
    });
    new DashboardRout();
    Backbone.history.start();
});

DashboardRout = Backbone.Router.extend({
    routes : {
        "addSeries" : "addSeries",
        "editSeries/:sid/:nowPage" : "editMySeries",
        "delSeries/:sid" : "delSeries",
        "delArticleFromSeries/:aid" : "delArtFromSrs",
        "myDraft/:nowPage" : "myDraftList",
        "draftEdit/:mdid" : "draftEdit",
        "draftDel/:mdid" : "draftDel",
        "articlePlus/:atTitle" : "articlePlus",
        "articleEdit/:aid" : "articleEdit",
        "articleDel/:aid" : "articleDel",
        "myArticles/:nowPage" : "myArticles",
        "mySubscript/:cls/:nowPage" : "mySubScript",
        "subSeries/:mid/:nowPage" : "subSeries",
        "subScriptArticles/:asid/:nowPage" : "subScriptArticles",
        "subscriptDel/:cls/:id" : "subscriptDel",
        "changePage/:page" : "changePage",
        "changePage/:page/:nowPage/:pageLimit" : "changePage"
    },

    addSeries : function() {
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

    editMySeries : function(sid, nowPage) {
        var self = dashboard;
        var loadPage = "template/editMySeries.html";
        $("#contentTem").load(loadPage, function() {
            var postData = {};
            postData['instr'] = "seriesGet";
            postData['sid'] = sid;
            postData['nowPage'] = nowPage;
            $.post("instr.php", postData, function(data) {
                //console.log(data);
                data = JSON.parse(data);
                //console.log(data);
                self.template = _.template($("#editMySeriesTem").html());
                self.render(data)
                var pager = null;
                if(pager==null) 
                    pager = new Pager({'el' : '#pager'});
                pager.render3(nowPage, 10, data['articleAmount'], sid);

                $("#content input[name=chapterNum]").on("change", function() {
                    var postData = {};
                    postData['instr'] = "changeArticleChapter";
                    postData['aid'] = $(this).attr("aid");
                    postData['chapter'] = $(this).val();
                    console.log(postData);
                    $.post("instr.php", postData, function(data) {
                        //console.log(data);
                        data = JSON.parse(data);
                        console.log(data);
                        if(data['status'] == 200) {
                            alert("修改完成");
                            location.reload();
                        }
                        else {
                            alert("修改失敗");
                        }
                    });
                });

                $("#seriesEditForm").submit(function() {
                    if(!$(this).validationEngine("validate"))
                        return false;
                    $(this).ajaxSubmit(function(result) {
                        //console.log(result);
                        result = JSON.parse(result);
                        //console.log(result);
                        if(result['status'] == 200) {
                            alert("編輯成功");
                            history.go(-1);
                        }
                        else {
                            console.log(result);
                        }
                    });
                    return false;
                });
            });
        });
    },

    delSeries : function(sid) {
        var postData = {};
        postData['instr'] = "seriesDel";
        postData['seriesId'] = sid;
        if(confirm("確定刪除?")) 
            $.post("instr.php", postData, function(data) {
                //console.log(data);
                data = JSON.parse(data);
                //console.log(data);
                if(data['status'] == 200) {
                    alert("刪除成功");
                }
                else {
                    alert("刪除失敗");
                    console.log(data);
                }
                history.go(-1);
            });
    },

    delArtFromSrs : function(aid) {
        //console.log("delete article(" + aid+ ")");
        var postData = {};
        postData['instr'] = "delArticleFromSeries";
        postData['aid'] = aid;
        if(confirm("是否將該篇文章從此系列剔除?")) 
            $.post("instr.php", postData, function(data) {
                //console.log(data);
                data = JSON.parse(data);
                //console.log(data);
                if(data['status'] == 200) {
                    alert("剔除成功");
                }
                else 
                    alert("剔除失敗");
                history.go(-1);
            });
    },

    myDraftList : function(nowPage) {
        $("#contentTem").load("template/myDraftList.html", function() {
            myDraftList.template = _.template($("#myDraftListTem").html());
            myDraftList.model.list(nowPage);
            var data = myDraftList.model.get("data");
            if(data != null)
                myDraftList.render(data);
        });
    },

    draftEdit : function(mdid) {
        $("#contentTem").load("template/draftEdit.html", function() {
            $("#pager").html('');
            var postData = {};
            postData['instr'] = "draftGet";
            postData['mdid'] = mdid;
            $.post("instr.php", postData, function(data) {
                //console.log(data);
                data = JSON.parse(data);
                data['data']['a_mainCp'] = data['data']['a_mainCp'].split(";");
                data['data']['a_mainCp2'] = data['data']['a_mainCp2'].split(";");

                //console.log(data);
                if(data['status'] == 200) {
                    templateEdit = _.template($("#draftEdit").html());
                    $("#content").html(templateEdit(data));
                    var articleEditForm = new PostArticleForm({'el' : '#postArticleForm'});
                    CKEDITOR.replace("editor1");
                    CKEDITOR.instances.editor1.setData(data['data']['a_content']);

                    //文章修改的前置
                    var postForm = new PostArticleForm({'el' : "#postArticleForm"});
                    var memModel = new MemberModel();
                    var draftSeries = data['data']['as_id'];

                    memModel.on("change:seriesList", function() {
                        var data = this.get("seriesList");
                        if(data['status'] == 200) {
                            data = data['data'];
                            $("select[name=series]").html("<option num='X' value=''>請選擇</option>");
                            for(var i in data) {
                                var option = document.createElement("option");
                                $(option).attr("num", i);
                                if(draftSeries == data[i]['as_id'])
                                    $(option).attr("selected", true);
                                $(option).val(data[i]['as_id']);
                                $(option).text(data[i]['as_name']);
                                $("select[name=series]").append(option);
                            }
                        }
                    });

                    $("select[name=series]").on("change", function() {
                        var data = memModel.get("seriesList")['data'];
                        var num = $("select[name=series] option:selected").attr("num");
                        if(num != "X") {
                            var as_finally = data[num]['as_finally'];
                            if(as_finally == 0)
                                as_finally = "?";
                            $("input[name=chapterSum]").val(as_finally);
                        }

                        if($(this).val() != "")
                            $("input[name=aChapter]").addClass("validate[required]");
                        else
                            $("input[name=aChapter]").removeClass("validate[required]");
                    });

                    var mySerPost = {};
                    mySerPost['nowPage'] = 1;
                    mySerPost['pageLimit'] = 9999;
                    memModel.getMySeriesList(mySerPost);

                    $(".cpPanel a").on("click", function() {
                        $(".cpPanel a").removeClass("nowChoose");
                        $(this).addClass("nowChoose");
                        $(".cpPanel div").hide();
                        $($(this).attr("href")).show();
                        return false;
                    });
                    $(".cpPanel button").on("click", function() {
                        $(".cpPanel").fadeOut();
                        return false;
                    });

                    var cpInput = null;

                    $(".cpPanel button.check").on("click", function() {
                        var cpDiv = $(".cpPanel a.nowChoose").attr("href");
                        var radioChoose = $(cpDiv).find("input:checked");
                        //console.log($(radioChoose).val());
                        $(cpInput).val($(radioChoose).val());
                        cpInput = null;
                    });

                    $("[name='cp1[]'],[name='cp2[]']").on("focus", function() {
                        cpInput = this;
                        $(".cpPanel").fadeIn();
                    });
                }
            });
        });
    },

    draftDel : function(mdid) {
        var postData = {};
        postData['instr'] = "myDraftDel";
        postData['md_id'] = mdid;
        if(confirm("確定刪除?")) 
            $.post("instr.php", postData, function(data) {
                //console.log(data);
                data = JSON.parse(data);
                //console.log(data);
                if(data['status'] == 200) {
                    alert("刪除成功");
                }
                else {
                    alert("刪除失敗");
                    console.log(data);
                }
                history.go(-1);
            });
    },

    articlePlus : function(aid) {
        $.getScript("lib/CookieAPI.js", function() {
            var postData = {};
            postData['instr'] = "articleGet";
            postData['aid'] = aid;
            $.post("instr.php", postData, function(data) {
                //console.log(data);
                data = JSON.parse(data);
                //console.log(data);
                var article = data['data'];
                setCookie("atTitle", article['at_title'].replace(";", ","), 1);
                setCookie("alert", article['a_alert'].replace(";", ","), 1);
                setCookie("tag", article['a_tag'].replace(";", ","), 1);
                setCookie("asid", article['asid'].replace(";", ","), 1);
                location.href = "postArticle.html?isChapter=Y";
            });
        });
    },

    articleEdit : function(aid) {
        //console.log(aid);
        $("#contentTem").load("template/articleEdit.html", function() {
            var postData = {};
            postData['instr'] = "articleGet";
            postData['aid'] = aid;
            $.post("instr.php", postData, function(data) {
                //console.log(data);
                data = JSON.parse(data);
                //console.log(data);
                data['data']['a_mainCp'] = data['data']['a_mainCp'].split(";");
                if(data['data']['a_mainCp2'] != null)
                    data['data']['a_mainCp2'] = data['data']['a_mainCp2'].split(";");
                else
                    data['data']['a_mainCp2'] = ["", ""];

                //console.log(data);
                if(data['status'] == 200) {
                    templateEdit = _.template($("#articleEdit").html());
                    $("#content").html(templateEdit(data));
                    var articleEditForm = new PostArticleForm({'el' : '#postArticleForm'});
                    CKEDITOR.replace("editor1");
                    CKEDITOR.instances.editor1.setData(data['data']['a_content']);

                    //文章修改的前置
                    var postForm = new PostArticleForm({'el' : "#postArticleForm"});
                    var memModel = new MemberModel();
                    var draftSeries = data['data']['as_id'];

                    memModel.on("change:seriesList", function() {
                        var serdata = this.get("seriesList");
                        //console.log(data);
                        if(data['status'] == 200) {
                            $("select[name=series]").html("<option num='X' value='0'>請選擇</option>");
                            for(var i in serdata['data']) {
                                var option = document.createElement("option");
                                $(option).attr("num", i);
                                if(draftSeries == serdata[i]['as_id'])
                                    $(option).attr("selected", true);
                                $(option).val(serdata['data'][i]['as_id']);
                                $(option).text(serdata['data'][i]['as_name']);
                                if(data['data']['asid'] == serdata['data'][i]['as_id'])
                                    $(option).attr("selected", true);
                                $("select[name=series]").append(option);
                            }
                        }
                    });

                    /*
                    $("select[name=series]").on("change", function() {
                        var data = memModel.get("seriesList")['data'];
                        var num = $("select[name=series] option:selected").attr("num");
                        if(num != "X") {
                            var at_lastCh = data[num]['at_lastCh'];
                            if(at_lastCh == 0)
                                at_lastCh = "?";
                            $("input[name=chapterSum]").val(at_lastCh);
                        }

                        if($(this).val() != "")
                            $("input[name=aChapter]").addClass("validate[required]");
                        else
                            $("input[name=aChapter]").removeClass("validate[required]");
                    });
                    */

                    var mySerPost = {};
                    mySerPost['nowPage'] = 1;
                    mySerPost['pageLimit'] = 9999;
                    memModel.getMySeriesList(mySerPost);

                    $(".cpPanel a").on("click", function() {
                        $(".cpPanel a").removeClass("nowChoose");
                        $(this).addClass("nowChoose");
                        $(".cpPanel div").hide();
                        $($(this).attr("href")).show();
                        return false;
                    });
                    $(".cpPanel button").on("click", function() {
                        $(".cpPanel").fadeOut();
                        return false;
                    });

                    var cpInput = null;

                    $(".cpPanel button.check").on("click", function() {
                        var cpDiv = $(".cpPanel a.nowChoose").attr("href");
                        var radioChoose = $(cpDiv).find("input:checked");
                        //console.log($(radioChoose).val());
                        $(cpInput).val($(radioChoose).val());
                        cpInput = null;
                    });

                    $("[name='cp1[]'],[name='cp2[]']").on("focus", function() {
                        cpInput = this;
                        $(".cpPanel").fadeIn();
                    });
                }
            });
        });
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

    myArticles : function(nowPage) {
        var clickBtn = $("#dashboard a[temid=myArticles]");

        //變換按鈕顏色
        var allLi = $("#dashboard").find("li");
        $(allLi).removeClass("nowChoose");
        $(clickBtn).parent().addClass("nowChoose");
        $("#contentTem").load("template/myArticles.html", function() {
            myArticleList.template = _.template($("#myArticlesTem").html());
            pgr = new Pager({'el' : "#pager"});
            pgr.model = myArticleList.model;
            myArticleList.model.on("change:data", function() {
                var data = this.get("data");
                //console.log(data);
                myArticleList.render(data);
                pgr.render2(nowPage, 25);
            });
            var datalist = myArticleList.model.get("data");
            if(datalist != null) {
                myArticleList.render(datalist);
                pgr.render2(nowPage, 25);
            }
            myArticleList.model.myArticles(nowPage);
        });
    },

    mySubScript : function(cls, nowPage) {
        var clickBtn = $("#dashboard a[temid=mySubscript]");

        //變換按鈕顏色
        var allLi = $("#dashboard").find("li");
        $(allLi).removeClass("nowChoose");
        $(clickBtn).parent().addClass("nowChoose");
        $("#contentTem").load("template/mySubScript.html", function() {
            if(cls == "none") {
                mySubScrt.template = _.template($("#mySubScriptNone").html());
                mySubScrt.render();
            }
            else if(cls == "all") {
                mySubScrt.template = _.template($("#mySubScriptAll").html());
            }
            else if(cls == "article") {
                mySubScrt.template = _.template($("#mySubScriptArticle").html());
            }
            else if(cls == "series") {
                mySubScrt.template = _.template($("#mySubScriptSeries").html());
            }
            else if(cls == "member") {
                mySubScrt.template = _.template($("#mySubScriptMember").html());
            }

            if(cls != "none") 
                mySubScrt.model.list(cls, nowPage);
        });
    },

    subSeries : function(mid, nowPage) {
        $("#contentTem").load("template/mySubScript.html", function() {
            mySubScrt.template = _.template($("#subscriptSeriesTem").html());
            mySubScrt.model.seriesListByMem(mid, nowPage);
        });
    },

    subScriptArticles : function(asid, nowPage) {
        $("#contentTem").load("template/mySubScript.html", function() {
            mySubScrt.template = _.template($("#subscriptArticlesTem").html());
            mySubScrt.model.articleListBySeries(asid, nowPage);
        });
    },

    subscriptDel : function(cls, id) {
        if(confirm("確定取消訂閱?")) {
            mySubScrt.model.del(cls, id);
        }
    },

    changePage : function(page, nowPage, pageLimit) {
        //console.log(page);
        //console.log("template/"+$(evt.target).attr("href"));
        //console.log(evt.target);
        var memModel = new MemberModel();
        var articleModel = new ArticleModel();
        var self = dashboard;
        var myArticle = new MyArticle();
        var message = new MyMessage({"el" : "#content", "model" : new MsgModel()});
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
        message.model.on("change:data", function() {
            message.render();
            pager.render2(nowPage, pageLimit);
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
                var postData = {};
                postData['instr'] = "myData";
                $.post("instr.php", postData, function(data) {
                    //console.log(data);
                    data = JSON.parse(data);
                    self.render(data);
                    $.getScript("Member/Personal.js", function() {
                        personal = new PassForm({'el' : "#passUpdForm"});
                    });
                });
            }
            else if(idname == "getMessage") {
                function isNumeric(n) {
                    return !isNaN(parseFloat(n)) && isFinite(n);
                }
                if(isNumeric(nowPage))
                    message.model.set("nowPage", nowPage);
                pager = new Pager({'el' : '#pager', 'model' : message.model});
                message.template = _.template($("#getMessage").html());
                message.model.myList();;
            }
            else
                self.render();
        });
    }
});
