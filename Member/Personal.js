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

PassForm = Backbone.View.extend({ 
    initialize : function() {
    },
    events : {
        "click button" : "changePass"
    },
    el : '',
    template : null,
    changePass : function() {
        //如果沒填完
        if(!this.$el.validationEngine('validate')) 
            return false;

        this.$el.ajaxSubmit(function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200)
                alert("修改成功");
            else {
                if(data['msg'] == "old pass error")
                    alert("舊密碼輸入錯誤");
            }
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
            data = JSON.parse(data);
            if(data['status'] == 200) {
                alert("修改成功");
                location.reload();
            }
        });
        return false;
    }
});
