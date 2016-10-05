$(document).ready(function() {
    new CpRout();
    Backbone.history.start();
});

CpRout = Backbone.Router.extend({
    routes : {
        "edit" : "getCp"
    },

    getCp : function() {
        var postData = {};
        postData['instr'] = "cpGet";
        $.post("instr.php", postData, function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                $("textarea[name=cp1]").val(data['cpData']['cp1']['value']);
                $("textarea[name=cp2]").val(data['cpData']['cp2']['value']);
            }
            else 
                console.log(data);
        });
    }
});
