Dashboard = Backbone.View.extend({
    initialize : function() {
    },
    events : {
        'click a' : 'changePage'
    },

    el : '',
    template : null,
    render : function() {
        $("#content").html(this.template());
    },

    changePage : function(evt) {
        //console.log("template/"+$(evt.target).attr("href"));
        //console.log(evt.target);
        var self = this;
        var loadPage = "template/"+$(evt.target).attr("href");

        var allLi = this.$el.find("li");
        $(allLi).removeClass("nowChoose");
        $(evt.target).parent().addClass("nowChoose");

        $("#contentTem").load(loadPage, function() {
            var idname = $(evt.target).attr("temid");
            self.template = _.template($("#"+idname).html());
            self.render();
        });
        return false;
    }
});
