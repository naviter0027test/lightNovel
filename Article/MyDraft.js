MyDraftList = Backbone.View.extend({
    initialize : function() {
    },

    el : '',
    template : null,
    render : function(data) {
        $("#content").html(this.template(data));
    }
});

MyDraftModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'data' : null
    },

    list : function(nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "myDraftList";
        postData['nowPage'] = nowPage;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    }
});
