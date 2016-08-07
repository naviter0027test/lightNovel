$(document).ready(function() {
    dashboard = new Dashboard({'el' : '#dashboard'});
    new DashboardRout();
    Backbone.history.start();
});

DashboardRout = Backbone.Router.extend({
    routes : {
        "changePage/:page" : "changePage"
    },
    changePage : function(page) {
        console.log(page);
        //console.log("template/"+$(evt.target).attr("href"));
        //console.log(evt.target);
        var memModel = new MemberModel();
        var self = dashboard;
        var loadPage = "template/"+ page+ ".html";
        var clickBtn = $("#dashboard a[temid="+page+"]");

        //變換按鈕顏色
        var allLi = $("#dashboard").find("li");
        $(allLi).removeClass("nowChoose");
        $(clickBtn).parent().addClass("nowChoose");

        //設定資料修改的瞬間進行render
        memModel.on("change:myData", function() {
            self.render(memModel.get("myData"));
        });
        memModel.on("change:seriesList", function() {
            self.render(memModel.get("seriesList"));
        });

        $("#contentTem").load(loadPage, function() {
            var idname = $(clickBtn).attr("temid");
            self.template = _.template($("#"+idname).html());

            if(idname == "personal") {
                memModel.getMyData();
            }
            else if(idname == "mySeries") {
                var para = {};
                para['nowPage'] = 1;
                para['pageLimit'] = 10;
                memModel.getMySeriesList(para);
            }
            else
                self.render();
        });
    }
});
