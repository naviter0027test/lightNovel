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

SearchResult = Backbone.View.extend({
    initialize : function() {
    },
    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        this.$el.html(this.template(data));
    }
});

SearchModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'data' : null
    }
});
