var synView = null;
var pager = null;
var nowpage = 1;
$(document).ready(function() {
    $("#synonymsScript").load("template/synonyms.html", function() {
    });
    new SynonymsRout();
    Backbone.history.start();
});

SynonymsRout = Backbone.Router.extend({
    routes : {
        "list/:nowPage" : "list"
    },

    list : function(nowPage) {
        var postData = {};
        postData['instr'] = "synonymsList";
        postData['nowPage'] = nowPage;
        $.post("instr.php", postData, function(data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data['status'] == 200) {
            }
        });
    }
});
