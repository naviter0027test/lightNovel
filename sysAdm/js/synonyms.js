var synForm = null;
var synView = null;
var synModel = null;
var pager = null;
var nowpage = 1;
$(document).ready(function() {
    $("#synonymsScript").load("template/synonyms.html", function() {
        synModel = new SynonymsModel();
        synView = new SynonymsList({'el' : "#synonymsList", 'model' : synModel});
        synForm = new SynonymsForm({'el' : "#synonymsAdd", 'model' : synModel});
        synView.template = _.template($("#synonymsListTem").html());
        synModel.on("change:data", function() {
            synView.render();
        });
    });
    new SynonymsRout();
    Backbone.history.start();
});

SynonymsRout = Backbone.Router.extend({
    routes : {
        "del/:id" : "del",
        "list/:nowPage" : "list"
    },

    del : function(id) {
        var postData = {};
        postData['instr'] = "synonymsDel";
        postData['syid'] = id;
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) 
                alert("刪除成功");
            else
                alert("刪除失敗");
            history.go(-1);
        });
    },

    list : function(nowPage) {
        setTimeout(function() {
            var postData = {};
            postData['instr'] = "synonymsList";
            postData['nowPage'] = nowPage;
            $.post("instr.php", postData, function(data) {
                //console.log(data);
                data = JSON.parse(data);
                //console.log(data);
                if(data['status'] == 200) {
                    synModel.set("data", data);
                }
            });
        }, 500);
    }
});
