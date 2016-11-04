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
            $(".searchPrev").hide();
            $(".searchRes").show();

            pager.render2(nowPage, 20);
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
        $("#searchPrev input[name=nowPage]").val(nowPage);
        searchPanel.model.set("nowPage", nowPage);
        searchPanel.$el.ajaxSubmit(function(data) {
            console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            searchPanel.model.set("data", data);
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
        searchPanel.$el.ajaxSubmit(function(data) {
            console.log(data);
            data = JSON.parse(data);
            //console.log(data);
            searchPanel.model.set("data", data);
        });
    }
});
