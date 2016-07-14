Page = Backbone.View.extend({
    initialize : function() {
    },
    template : null,
    render : function() {
    }
});

PageModel = Backbone.Model.extend({
    initialize : function() {
        console.log("page model");
    },
    defaults : {
	'data' : null,
    },
    getPage : function(pageName) {
        var self = this;
        var postData = {};
        postData['instr'] = "pageShow";
        postData['page'] = pageName;
        $.post("instr.php", postData, function(data) {
            console.log(data);
            data = JSON.parse(data);
            self.set("data", data);
        });
    }
});
