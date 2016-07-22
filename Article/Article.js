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
        if(!this.$el.validationEngine("validate"))
            return false;
        this.$el.validationEngine("hideAll");
        var postData = {};
        postData['instr'] = $("input[name=instr]").val();
        postData['title'] = $("input[name=title]").val();
        postData['articleType'] = $("input[name=articleType]").val();
        postData['level'] = $("select[name=level]").val();
        console.log($("input[name='cp1[]']"));
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
            postData['viceCp'] = $("input[name=viceCp]").val();
        }

        if($("select[name=series]").val() != "") 
            postData['series'] = $("select[name=series]").val();

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

        if(CKEDITOR.instances.editor1.getData() != "") 
            postData['content'] = CKEDITOR.instances.editor1.getData();

        console.log(postData);
        return false;
    }
});

ArticleModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'myLastArticles' : null
    },

    myLastArticles : function() {
        var self = this;
        var postData = {};
        postData['instr'] = "memArticleList";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            console.log(data);
            self.set("myLastArticles", data);
        });
    }
});
