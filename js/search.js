var searchPanel = null;
$(document).ready(function() {
    $(".searchPrev").show();
    $(".searchRes").hide();

    searchPanel = new SearchPanel({'el' : "#searchPrev", 'model' : new SearchModel() });
});
