MemTable = Backbone.View.extend({
    initialize : function() {
        this.template = _.template($("#memberListTem").html());
    },
    el : '',

    events : {
        "change select[name=active]" : "changeAct"
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        this.$el.html(this.template(data));
    },

    changeAct : function(evt) {
        //console.log(evt.target);
        var postData = {};
        postData['instr'] = "memberActive";
        postData['mid'] = $(evt.target).attr('mid');
        postData['active'] = $(evt.target).val();
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) 
                alert("狀態修改成功");
            else
                alert("狀態修改失敗");
        });
    }
});

MemModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'nowPage' : 1,
        'data' : null
    },
    list : function(nowPage) {
        var self = this;
        var postData = {};
        this.set("nowPage", nowPage);
        postData['instr'] = "memberList";
        postData['nowPage'] = nowPage;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            self.set("data", data);
        });
    }
});
