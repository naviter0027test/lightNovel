MyGift = Backbone.View.extend({
    initialize : function() {
    },

    events : {
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        this.$el.html(this.template(data));
    }
});

MyGiftModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'nowPage' : 1,
        'data' : null
    },

    lists : function(nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "giftList";
        postData['nowPage'] = nowPage;

        this.set("nowPage", nowPage);
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                self.set("data", data);
            }
            else
                console.log(data);
        });
    }
});
