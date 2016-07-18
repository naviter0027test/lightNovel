Personal = Backbone.View.extend({ 
    initialize : function() {
    },
    events : {
        "click button" : "modifyPersonal"
    },
    el : '',
    template : null,
    modifyPersonal : function() {
        this.$el.ajaxSubmit(function(data) {
            console.log(data);
        });
        return false;
    }
});

PersonalImg = Backbone.View.extend({
    initialize : function() {
    },
    events : {
        "click button" : "uploadImg"
    },
    el : '',
    template : null,
    uploadImg : function() {
        this.$el.ajaxSubmit(function(data) {
            console.log(data);
        });
        return false;
    }
});
