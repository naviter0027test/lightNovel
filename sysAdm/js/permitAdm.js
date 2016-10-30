var permitModel = null;
var permitTable = null;
$(document).ready(function() {
    $("#contentTem").load("template/permitAdm.html", function() {
        permitModel = new Admin();
        permitTable = new PermitContent({'el' : "#content", 'model' : permitModel});
        permitTable.template = _.template($("#permitListTem").html());
        permitModel.on("change:data", function() {
            permitTable.render();
            $("#permitEdit").hide();
            $("#permitAddForm").show();
            $("#permitList").show();
        });
        new PermitRout();
        Backbone.history.start();
    });
});

PermitRout = Backbone.Router.extend({
    routes : {
        "del/:admid" : "del",
        "list" : "list",
        "edit/:admid" : "edit"
    },

    del : function(admid) {
    },

    list : function() {
        permitModel.list();
    },

    edit : function(admid) {
        permitTable.render();
        $("#permitEdit").show();
        $("#permitAddForm").hide();
        $("#permitList").hide();
    }
});
