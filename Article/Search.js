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
        location.href = "#search/1";
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
