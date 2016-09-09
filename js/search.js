var searchPanel = null;
$(document).ready(function() {
    $(".searchPrev").show();
    $(".searchRes").hide();

    $("#scriptTem").load("template/search.html", function() {
        searchPanel = new SearchPanel({'el' : "#searchPrev", 'model' : new SearchModel() });
        searchRes = new SearchResult({'el' : "#searchRes", 'model' : searchPanel.model });
        searchRes.template = _.template($("#searchResTem").html());
        searchRes.model.on("change:data", function() {
            var data = this.get("data");
            console.log("search res");
            console.log(data);

            searchRes.render();
            $(".searchPrev").hide();
            $(".searchRes").show();

        });
    });
});
