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
        else if(cls == "all") {
            postData['instr'] = "subScriptAll";
        }
        postData['nowPage'] = nowPage;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    },

    seriesListByMem : function(mid, nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "seriesList";
        postData['mId'] = mid;
        postData['nowPage'] = nowPage;
        postData['pageLimit'] = 25;
        $.post("instr.php", postData, function(data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) {
                self.set("data", data);
            }
        });
    },

    articleListBySeries : function(asid, nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "articleListBySubSrs";
        postData['asid'] = asid;
        postData['nowPage'] = nowPage;
        postData['pageLimit'] = 25;
        $.post("instr.php", postData, function(data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) {
                self.set("data", data);
            }
        });
    },

    del : function(cls, id) {
        var postData = {};
        postData['instr'] = "subScriptDel";
        if(cls == "member")
            postData['mid'] = id;
        else if(cls == "series")
            postData['asid'] = id;
        else if(cls == "article")
            postData['aid'] = id;

        $.post("instr.php", postData, function(data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) 
                alert("取消訂閱成功");
            else {
                alert("取消訂閱失敗");
            }
            history.go(-1);
        });
    }
});
