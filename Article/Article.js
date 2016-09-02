//所有使用者瀏覽文章用的view
Article = Backbone.View.extend({
    initialize : function() {
        var self = this;
        this.template = _.template($("#contentTem").html());
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        for(var idx in data['data']) {
            data['data'][idx]['a_mainCp'] = data['data'][idx]['a_mainCp'].replace(";", "/");
            if(data['data'][idx]['a_mainCp2'] != null) {
                var cp2 = data['data'][idx]['a_mainCp2'];
                data['data'][idx]['a_mainCp2'] = cp2.replace(";", "/");
            }
        }
        this.$el.html(this.template(data));
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
        "click button.postBtn" : "postArt"
    },
    el : '',
    template : null,
    render : function() {
    },
    postArt : function() {
        if($("select[name=series]").val() != "" || $("input[name=newSeries]").val() != "") {
            $("input[name=aChapter]").addClass("validate[required, custom[integer]]");
        }
        else
            $("input[name=aChapter]").removeClass("validate[required, custom[integer]]");
        if(!this.$el.validationEngine("validate"))
            return false;
        this.$el.validationEngine("hideAll");
        var postData = {};
        postData['instr'] = $("input[name=instr]").val();
        if(postData['instr'] == "articleEdit")
            postData['aid'] = $("input[name=aid]").val();
        postData['title'] = $("input[name=title]").val();
        postData['articleType'] = $("input[name=articleType]").val();
        postData['level'] = $("select[name=level]").val();
        //console.log($("input[name='cp1[]']"));
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
            postData['viceCp'] = $("input[name=viceCp]").val();
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

        if($("input[name=aTitle]").val() != "") 
            postData['aTitle'] = $("input[name=aTitle]").val();

        if($("input[name=aChapter]").val() != "")
            postData['aChapter'] = $("input[name=aChapter]").val();

        postData['aMemo'] = $("input[name=aMemo]").val();

        if(CKEDITOR.instances.editor1.getData() != "") 
            postData['content'] = CKEDITOR.instances.editor1.getData();

        $.post("instr.php", postData, function(data) {
            console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                if(postData['instr'] == "articleEdit")
                    alert("編輯成功");
                else
                    alert("發文成功!");
                location.href = "index.html#/1";
            }
            else {
                if(data['msg'] == "series is repeat")
                    alert("系列名重复");
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
