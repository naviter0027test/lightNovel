SubScriptList = Backbone.View.extend({
    initialize : function() {
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        if(data == null)
            this.$el.html(this.template());
        else
            this.$el.html(this.template(data));
    }
});

SubScriptModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'cls' : "none",
        'data' : null
    },
    list : function(cls, nowPage) {
        var self = this;
        this.set("cls", cls);
        var postData = {};
        postData['instr'] = "subScriptList";
        if(cls == "member") {
            postData['subCls'] = "m_id";
        }
        else if(cls == "series") {
            postData['subCls'] = "as_id";
        }
        else if(cls == "article") {
            postData['subCls'] = "a_id";
        }
        postData['nowPage'] = nowPage;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    }
});
