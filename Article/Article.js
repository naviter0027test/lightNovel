//所有使用者瀏覽文章用的view
Article = Backbone.View.extend({
    initialize : function() {
        var self = this;
        //if($("#contentTem").html() != undefined)
        this.template = _.template($("#contentTem").html());
    },

    events : {
        "click h3 a" : "articleClick"
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        //console.log(data);
        for(var idx in data['data']) {
            if(data['data'][idx] == null) {
                delete data['data'][idx];
                continue;
            }
            if(data['data'][idx]['a_author'] != "" && data['data'][idx]['a_author'] != null) {
                var authorArr = data['data'][idx]['a_author'].split(";");
                for(var jdx in authorArr) {
                    authorArr[jdx] = "<a href='search.html#search/1/author/"+ authorArr[jdx]+ "'>"+ authorArr[jdx]+ "</a>";
                }
                data['data'][idx]['a_author'] = authorArr.join(";");
            }
            if(data['data'][idx]['a_mainCp'] != null) {
                //data['data'][idx]['a_mainCp'] = data['data'][idx]['a_mainCp'].replace(";", "/");
                var mainCpArr = data['data'][idx]['a_mainCp'].split(";");
                for(var jdx in mainCpArr) {
                    mainCpArr[jdx] = "<a href='search.html#search/1/mainCp/"+ mainCpArr[jdx]+ "'>"+ mainCpArr[jdx]+ "</a>";
                }
                data['data'][idx]['a_mainCp'] = mainCpArr.join("/");
            }
            if(data['data'][idx]['a_mainCp2'] != "" && data['data'][idx]['a_mainCp2'] != null) {
                var cp2 = data['data'][idx]['a_mainCp2'];
                //data['data'][idx]['a_mainCp2'] = cp2.replace(";", "/");
                var mainCp2Arr = data['data'][idx]['a_mainCp2'].split(";");
                for(var jdx in mainCp2Arr) {
                    mainCp2Arr[jdx] = "<a href='search.html#search/1/mainCp/"+ mainCp2Arr[jdx]+ "'>"+ mainCp2Arr[jdx]+ "</a>";
                }
                data['data'][idx]['a_mainCp2'] = mainCp2Arr.join("/");
                if(data['data'][idx]['a_subCp'] != "") {
                    data['data'][idx]['a_mainCp2'] = data['data'][idx]['a_mainCp2'] + ";";
                }
            }
            if(data['data'][idx]['a_subCp'] != "") {
                var subCp = data['data'][idx]['a_subCp'];
                if(subCp.lastIndexOf(";") == subCp.length-1) {
                    subCp = subCp.substr(0, subCp.length-1);
                }
                var subCpArr = subCp.split(";");
                //console.log(subCpArr);
                for(var jdx in subCpArr) {
                    var subNames = subCpArr[jdx].split("/");
                    for(var ldx in subNames) {
                        subNames[ldx] = "<a href='search.html#search/1/mainCp/"+ subNames[ldx]+ "'>"+ subNames[ldx]+ "</a>";
                    }
                    subCpArr[jdx] = subNames.join("/");
                }
                //console.log(subCpArr);
                data['data'][idx]['a_subCp'] = subCpArr.join(";");
            }
            if(data['data'][idx]['a_alert'] != "") {
                var alertArr = data['data'][idx]['a_alert'].split(";");
                for(var jdx in alertArr) {
                    alertArr[jdx] = "<a href='search.html#search/1/alert[]/"+ alertArr[jdx]+ "'>"+ alertArr[jdx]+ "</a>";
                    //console.log(alertArr[idx]);
                }
                data['data'][idx]['a_alert'] = alertArr.join(";");
            }
            if(data['data'][idx]['a_tag'] != "") {
                var tagArr = data['data'][idx]['a_tag'].split(";");
                for(var jdx in tagArr) {
                    tagArr[jdx] = "<a href='search.html#search/1/tag[]/"+ tagArr[jdx]+ "'>"+ tagArr[jdx]+ "</a>";
                    //console.log(tagArr[idx]);
                }
                data['data'][idx]['a_tag'] = tagArr.join(";");
            }
        }
        this.$el.html(this.template(data));
    },

    articleClick : function(evt) {
        var link = evt.target;
        var linkArr = $(link).attr("href").split("/");
        //console.log(linkArr);
        var aid = linkArr[linkArr.length-2];
        //console.log(aid);
        var postData = {};
        postData['instr'] = "articleClick";
        postData['aid'] = aid;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] != 200)
                console.log(data);
            setTimeout(function() {
                location.href = $(link).attr("href");
            }, 200);
        });
        return false;
    }
});

//登入會員用的view
MyArticle = Backbone.View.extend({
    initialize : function() {
    },

    el : '',
    template : null,
    render : function(data) {
        $("#content").html(this.template(data));
    }
});

PostArticleForm = Backbone.View.extend({
    initialize : function() {
        $("input[value=other]").on("change", function() {
            var otherInput = $("input[type=text][name='"+$(this).attr("name")+"']");
            if($(this).is(":checked")) 
                $(otherInput).addClass("validate[required]");
            else 
                $(otherInput).removeClass("validate[required]");
        });
    },
    events : {
        "click button.postBtn" : "postArt",
        "click button.storeBtn" : "storeArt"
    },
    el : '',
    template : null,
    render : function() {
    },
    storeArt : function() {
        if($("select[name=series]").val() != "" || $("input[name=newSeries]").val() != "") {
            $("input[name=aChapter]").addClass("validate[required]");
        }
        else
            $("input[name=aChapter]").removeClass("validate[required]");
        if(!this.$el.validationEngine("validate"))
            return false;
        this.$el.validationEngine("hideAll");
        var postData = {};
        postData['instr'] = $("input[name=instr]").val();
        if($("input[name=instr]").attr("draftcls") == "editDraft") {
            postData['mdid'] = $("input[name=mdid]").val();
            postData['instr'] = "editDraft";
        }
        else
            postData['instr'] = "storeDraft";
        postData['title'] = $("input[name=title]").val();
        postData['articleType'] = $("input[name=articleType]:checked").val();
        postData['level'] = $("select[name=level]").val();
        //console.log($("input[name=author]").val());
        postData['author'] = $("input[name=author]").val();
        postData['cp1'] = [];
        for(var i = 0;i < $("input[name='cp1[]']").length;++i) {
            var cpInput = $("input[name='cp1[]']")[i];
            postData['cp1'].push($(cpInput).val());
        }
        postData['cp2'] = [];
        for(var i = 0;i < $("input[name='cp2[]']").length;++i) {
            var cpInput = $("input[name='cp2[]']")[i];
            postData['cp2'].push($(cpInput).val());
        }

        if($("input[name=viceCp]").val() != "") {
            var viceCp = $("input[name=viceCp]").val();
            //副cp要追加檢查
            if(viceCp.search(";") == -1 && viceCp.search("/") == -1) {
                alert("副cp以a/b格式填写，多个cp以半角;隔开");
                return false;
            } else if (viceCp.search(";") != -1) {
                var viceCpArr = viceCp.split(";");
                for(var viceCpIdx = 0;viceCpIdx < viceCpArr.length;++viceCpIdx) {
                    if(viceCpArr[viceCpIdx].trim() == "")
                        continue;
                    if(viceCpArr[viceCpIdx].search("/") == -1) {
                        alert("副cp以a/b格式填写，多个cp以半角;隔开");
                        return false;
                    }
                }
            }
            postData['viceCp'] = viceCp;
        }

        if($("select[name=series]").val() != "") 
            postData['series'] = $("select[name=series]").val();
        else if($("input[name=newSeries]").val() != "")
            postData['newSeries'] = $("input[name=newSeries]").val();

        if($("input[name=newSeries]").val() != "") 
            postData['newSeries'] = $("input[name=newSeries]").val();

        postData['alert'] = [];
        for(var i = 0;i < $("input[name='alert[]']:checked").length;++i) {
            var alertInput = $("input[name='alert[]']:checked")[i];
            if($(alertInput).val() == "other") {
                var alertOther = $("input[name='alert[]'][type=text]").val();
                postData['alert'].push(alertOther);
                break;
            }
            postData['alert'].push($(alertInput).val());
        }
        postData['tag'] = [];
        for(var i = 0;i < $("input[name='tag[]']:checked").length;++i) {
            var tagInput = $("input[name='tag[]']:checked")[i];
            if($(tagInput).val() == "other") {
                var tagOther = $("input[name='tag[]'][type=text]").val();
                postData['tag'].push(tagOther);
                break;
            }
            postData['tag'].push($(tagInput).val());
        }

        if($("input[name=sendUser]").val() != "") 
            postData['sendUser'] = $("input[name=sendUser]").val();

        if($("input[name=aTitle]").val() != "") 
            postData['aTitle'] = $("input[name=aTitle]").val();

        if($("input[name=aChapter]").val() != "")
            postData['aChapter'] = $("input[name=aChapter]").val();

        if($("input[name=chapterSum]").val() != "")
            postData['chapterSum'] = $("input[name=chapterSum]").val();

        postData['aMemo'] = $("textarea[name=aMemo]").val();

        if(CKEDITOR.instances.editor1.getData() != "") 
            postData['content'] = CKEDITOR.instances.editor1.getData();

        $.post("instr.php", postData, function(data) {
            console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                if(postData['instr'] == "editDraft")
                    alert("編輯成功");
                else
                    alert("儲存成功!");
                location.href = "index.html#/1";
            }
        });
        return false;
    },

    postArt : function() {
        if(!this.$el.validationEngine("validate"))
            return false;
        this.$el.validationEngine("hideAll");
        var postData = {};
        postData['instr'] = $("input[name=instr]").val();
        if(postData['instr'] == "articleEdit")
            postData['aid'] = $("input[name=aid]").val();
        postData['title'] = $("input[name=title]").val();
        postData['articleType'] = $("input[name=articleType]:checked").val();
        postData['level'] = $("select[name=level]").val();
        if(postData['sendUser'] == undefined)
            delete postData['sendUser'];
        if(postData['chapterSum'] == undefined)
            delete postData['chapterSum'];
        console.log(postData);
        postData['author'] = $("input[name=author]").val();
        postData['cp1'] = [];
        for(var i = 0;i < $("input[name='cp1[]']").length;++i) {
            var cpInput = $("input[name='cp1[]']")[i];
            postData['cp1'].push($(cpInput).val());
        }
        var cp2_1 = $($("input[name='cp2[]']")[0]).val();
        var cp2_2 = $($("input[name='cp2[]']")[1]).val();
        if($.trim(cp2_1) != "" && $.trim(cp2_2) != "") {
            postData['cp2'] = [];
            for(var i = 0;i < $("input[name='cp2[]']").length;++i) {
                var cpInput = $("input[name='cp2[]']")[i];
                postData['cp2'].push($(cpInput).val());
            }
        }

        if($("input[name=viceCp]").val() != "") {
            var viceCp = $("input[name=viceCp]").val();
            //副cp要追加檢查
            if(viceCp.search(";") == -1 && viceCp.search("/") == -1) {
                alert("副cp以a/b格式填写，多个cp以半角;隔开");
                return false;
            } else if (viceCp.search(";") != -1) {
                var viceCpArr = viceCp.split(";");
                for(var viceCpIdx = 0;viceCpIdx < viceCpArr.length;++viceCpIdx) {
                    if(viceCpArr[viceCpIdx].trim() == "")
                        continue;
                    if(viceCpArr[viceCpIdx].search("/") == -1) {
                        alert("副cp以a/b格式填写，多个cp以半角;隔开");
                        return false;
                    }
                }
            }
            postData['viceCp'] = viceCp;
        }

        if($("select[name=series]").val() != "") 
            postData['series'] = $("select[name=series]").val();
        else if($("input[name=newSeries]").val() != "")
            postData['newSeries'] = $("input[name=newSeries]").val();

        if($("input[name=newSeries]").val() != "") 
            postData['newSeries'] = $("input[name=newSeries]").val();

        postData['alert'] = [];
        for(var i = 0;i < $("input[name='alert[]']:checked").length;++i) {
            var alertInput = $("input[name='alert[]']:checked")[i];
            if($(alertInput).val() == "other") {
                var alertOther = $("input[name='alert[]'][type=text]").val();
                postData['alert'].push(alertOther);
                break;
            }
            postData['alert'].push($(alertInput).val());
        }
        postData['tag'] = [];
        for(var i = 0;i < $("input[name='tag[]']:checked").length;++i) {
            var tagInput = $("input[name='tag[]']:checked")[i];
            if($(tagInput).val() == "other") {
                var tagOther = $("input[name='tag[]'][type=text]").val();
                postData['tag'].push(tagOther);
                break;
            }
            postData['tag'].push($(tagInput).val());
        }

        if($("input[name=sendUser]").val() != "") 
            postData['sendUser'] = $("input[name=sendUser]").val();

        if($("input[name=aTitle]").val() != "") 
            postData['aTitle'] = $("input[name=aTitle]").val();

        if($("input[name=aChapter]").val() != "")
            postData['aChapter'] = $("input[name=aChapter]").val();

        if($("input[name=chapterSum]").val() != "")
            postData['chapterSum'] = $("input[name=chapterSum]").val();

        postData['aMemo'] = $("textarea[name=aMemo]").val();

        if(CKEDITOR.instances.editor1.getData() != "") 
            postData['content'] = CKEDITOR.instances.editor1.getData();
        else
            postData['content'] = " ";

        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                if(postData['instr'] == "articleEdit")
                    alert("編輯成功");
                else {

                    //針對草稿編輯后發文的情況，發文成功要順帶刪除草稿
                    if(typeof(Backbone.history.fragment) !== 'undefined') 
                        if(Backbone.history.fragment.search("draftEdit") != -1) {
                            var draftArr = Backbone.history.fragment.split("/");
                            var postData2 = {};
                            postData2['instr'] = "myDraftDel";
                            postData2['md_id'] = draftArr[1];
                            $.post("instr.php", postData2, function(delDraftResult) {
                                //console.log(delDraftResult);
                                delDraftResult = JSON.parse(delDraftResult);
                                //console.log(delDraftResult);
                                if(delDraftResult['status'] == 200) {
                                    //console.log("delete draft finish");
                                }
                            });
                        }

                    alert("发文成功!");
                }
                location.href = "index.html#/1";
            }
            else {
                console.log(data);
                if(data['msg'] == "series is repeat")
                    alert("系列名重复");
                else if(data['msg'] == "title is used")
                    alert("已有人用過此標題");
                else if(data['msg'] == "send user not found");
                    alert("献给的对象找不到");
            }
        });
        return false;
    }
});

ArticleModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'data' : null
    },

    myLastArticles : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "memArticleList";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("myLastArticles", data);
        });
    },

    articleList : function(nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "articleList";
        postData['nowPage'] = nowPage;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    },

    getOne : function(aid) {
        var self = this;
        var postData = {};
        postData['instr'] = "articleGet";
        postData['aid'] = aid;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    },

    myArticles : function(nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "myArticleList";
        postData['nowPage'] = nowPage;
        //console.log(postData);
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    }
});
