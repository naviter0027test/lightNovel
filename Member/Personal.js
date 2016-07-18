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
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                alert("修改成功");
            }
            else
                alert("修改失敗");
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
