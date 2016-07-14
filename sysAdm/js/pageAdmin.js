var page = null;
var rout = null;
$(document).ready(function() {
    initialShow();
    $("#sidebar").load("template/sidebar.html", function() {
    });
    $("#header").load("template/header.html", function() {
    });
    rout = new PageRout();
    page = new Page({'model' : new PageModel()});
    page.model.on("change:data", function() {
        var data = this.get("data");
        console.log(data);
        if(data['status'] == 200) {
            setTimeout(function() {
                $("input[name=page]").val(data['data']['p_page']);
                $("input[name=p_title]").val(data['data']['p_title']);
                $("textarea#editor1").val(data['data']['p_content']);
                CKEDITOR.instances.editor1.setData(data['data']['p_content']);
            }, 500);
        }
        else {
            if(data['msg'] == "no data") {
                alert("該頁無資料");
            }
        }
    });

    $("#pageEditForm").submit(function() {
        //console.log($("textarea#editor1").val());
        $("textarea#editor1").val(CKEDITOR.instances.editor1.getData());
        $(this).ajaxSubmit(function(data) {
            //console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            if(data['status'] == 200) {
                alertify.success("儲存成功");
                setTimeout(function() {
                    location.reload();
                }, 500);
            }
        });
        return false;
    });
    Backbone.history.start();
});

PageRout = Backbone.Router.extend({
    initialize : function() {
        console.log("init");
    },

    page : function(pageName) {
        console.log(pageName);
        page.model.getPage(pageName);
    },

    routes : {
	'page/:page' : 'page'
    }
});
