var memView = null;
var pager = null;
$(document).ready(function() {
    $("#memberListScript").load("template/memberList.html", function() {
        memView = new MemTable({'el' : "#memberList", 'model' : new MemModel()});
        pager = new Pager({'el' : '#pager', 'model' : memView.model});
        memView.model.on("change:data", function() {
            var nowPage = this.get("nowPage");
            memView.render();
            pager.render2(nowPage, 20);
        });
        new MemRout();
        Backbone.history.start();
    });
});

MemRout = Backbone.Router.extend({
    routes : {
        "list/:nowPage" : "list",
        "del/:mid" : "delMember"
    },

    list : function(nowPage) {
        setTimeout(function() {
            memView.model.list(nowPage);
        }, 200);
    },
    delMember : function(mid) {
        var postData = {};
        postData['instr'] = "memberDel";
        postData['mid'] = mid;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) 
                alert("會員刪除成功");
            else
                alert("會員刪除失敗");
            history.go(-1);
        });
    }
});
