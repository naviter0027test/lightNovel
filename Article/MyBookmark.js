MyBookmarkList = Backbone.View.extend({
    initialize : function() {
    },

    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        $("#content").html(this.template(data));
    }
});

BookmarkModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'nowPage' : 1,
        'data' : null
    },

    list : function(nowPage) {
        var self = this;
        var postData = {};
        postData['instr'] = "bookmarkList";
        postData['nowPage'] = nowPage;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) {
                self.set("data", data);
            }
            else
                console.log(data);
        });
    },

    cancel : function(bid) {
        var postData = {};
        postData['instr'] = "bookmarkCancel";
        postData['bookId'] = bid;

        $.post("instr.php", postData, function(data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) 
                alert("取消成功");
            
            else 
                alert("取消失敗");
            
            history.go(-1);
        });
    }
});
