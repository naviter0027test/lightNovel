SearchPanel = Backbone.View.extend({
    initialize : function() {
    },
    events : {
        "click button" : "search"
    },
    el : '',
    template : null,
    render : function() {
    },

    search : function(evt) {
        var self = this;
        this.$el.ajaxSubmit(function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.model.set("data", data);
        });
        return false;
    }
});

SearchModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'data' : null
    }
});
