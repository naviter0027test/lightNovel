SynonymsForm = Backbone.View.extend({
    initialize : function() {
        //console.log("synonyms view created");
    },
    el : '',
    events : {
        "click button" : "add"
    },
    add : function(evt) {
        this.$el.ajaxSubmit(function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                alert("新增成功");
            }
            else
                alert("新增失敗");
            history.go(-1);
        });
        return false;
    }
});

SynonymsList = Backbone.View.extend({
    initialize : function() {
        //console.log("synonyms view created");
    },
    el : '',

    template : null,
    render : function() {
        var data = this.model.get("data");
        //console.log(data);
        this.$el.html(this.template(data));
    }

});

SynonymsModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'search' : "",
        'data' : null
    },
    list : function(nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "synonymsList";
        postData['nowPage'] = nowPage;
        postData['search'] = this.get("search");
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    }
});
