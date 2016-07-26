Pager = Backbone.View.extend({
    initialize : function() {
        //console.log("pager created");
        this.template = _.template($("#pagerTem").html());
    },
    events : {
    },
    el : '',
    template : null,
    render : function(nowPage, pageLimit) {
        var data = {};
        data['nowPage'] = nowPage;
        data['pageLimit'] = pageLimit;
        data['amount'] = this.model.get("seriesAmount");
        $("#pager").html(this.template(data));
    },
    render2 : function(nowPage, pageLimit) {
        var data = {};
        data['nowPage'] = nowPage;
        data['pageLimit'] = pageLimit;
        console.log(this.model.get("data"));
        data['amount'] = this.model.get("data")['amount'];
        $("#pager").html(this.template(data));
    }
});
