function waitShow() {
    $.blockUI({ 
        css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        },
        message : '<h3>搜寻中，请稍后...</h3>'
    });
}
var searchPanel = null;
var pager = null;
$(document).ready(function() {
    $(".searchPrev").show();
    $(".searchRes").hide();

    $("#scriptTem").load("template/search.html", function() {

        searchPanel = new SearchPanel({'el' : "#searchPrev", 'model' : new SearchModel() });
        searchRes = new SearchResult({'el' : "#searchRes", 'model' : searchPanel.model });
        pager = new Pager({'model' : searchPanel.model });
        searchRes.template = _.template($("#searchResTem").html());
        searchRes.model.on("change:data", function() {
            var data = this.get("data");
            var nowPage = this.get("nowPage");
            console.log(data);

            searchRes.render();

            pager.render2(nowPage, 20);
            $.unblockUI();
        });
        new SearchRout();
        Backbone.history.start();
    });
});

SearchRout = Backbone.Router.extend({
    routes : {
        "" : "searchPrev",
        "search/:nowPage" : "searchResult",
        "search/:nowPage/:cls/:arg" : "searchOne"
    },

    searchPrev : function() {
        $(".searchPrev").show();
        $(".searchRes").hide();
    },

    searchResult : function(nowPage) {
        waitShow();
        $("#searchPrev input[name=nowPage]").val(nowPage);
        searchPanel.model.set("nowPage", nowPage);
        searchPanel.$el.ajaxSubmit(function(data) {
            console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            searchPanel.model.set("data", data);
            $(".searchPrev").hide();
            $(".searchRes").show();
        });
    },

    searchOne : function(nowPage, cls, arg) {
        $("#searchPrev").resetForm();
        $(".searchPrev").hide();
        $("input[name='alert[]']").prop("checked", false);
        $("input[name='tag[]']").prop("checked", false);
        if(cls == "level[]")
            $("input[name='"+ cls+ "'][value="+ arg+ "]").prop("checked", true);
        else if (cls == "alert[]") 
            $($("input[name='alert[]'][type=text]")[0]).val(arg);
        else if (cls == "tag[]")
            $($("input[name='tag[]'][type=text]")[0]).val(arg);
        else
            $("input[name='"+ cls+ "']").val(arg);
        $("#searchPrev input[name=nowPage]").val(nowPage);
        searchPanel.model.set("nowPage", nowPage);
        waitShow();
        searchPanel.$el.ajaxSubmit(function(data) {
            console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            searchPanel.model.set("data", data);
            $(".searchPrev").hide();
            $(".searchRes").show();
        });
    }
});
