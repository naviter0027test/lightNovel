$(document).ready(function() {
    var postForm = new PostArticleForm({'el' : "#postArticleForm"});


    $(".cpPanel a").on("click", function() {
        $(".cpPanel a").removeClass("nowChoose");
        $(this).addClass("nowChoose");
        $(".cpPanel div").hide();
        $($(this).attr("href")).show();
        return false;
    });
    $(".cpPanel button").on("click", function() {
        $(".cpPanel").fadeOut();
        return false;
    });

    var cpInput = null;

    $(".cpPanel button.check").on("click", function() {
        var cpDiv = $(".cpPanel a.nowChoose").attr("href");
        var radioChoose = $(cpDiv).find("input:checked");
        //console.log($(radioChoose).val());
        $(cpInput).val($(radioChoose).val());
        cpInput = null;
    });

    $("[name='cp1[]'],[name='cp2[]']").on("focus", function() {
        cpInput = this;
        $(".cpPanel").fadeIn();
    });
});
