Dashboard = Backbone.View.extend({
    initialize : function() {
    },
    events : {
    },

    el : '',
    template : null,
    render : function(data) {
        //console.log(data);
        $("#content").html(this.template(data));
    },

    changePage : function(evt) {
        //console.log("template/"+$(evt.target).attr("href"));
        //console.log(evt.target);
        var memModel = new MemberModel();
        var self = this;
        var loadPage = "template/"+$(evt.target).attr("href");

        //變換按鈕顏色
        var allLi = this.$el.find("li");
        $(allLi).removeClass("nowChoose");
        $(evt.target).parent().addClass("nowChoose");

        //設定資料修改的瞬間進行render
        memModel.on("change:myData", function() {
            self.render(memModel.get("myData"));
        });
        memModel.on("change:seriesList", function() {
            self.render(memModel.get("seriesList"));
        });

        $("#contentTem").load(loadPage, function() {
            var idname = $(evt.target).attr("temid");
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
        return false;
    }
});
