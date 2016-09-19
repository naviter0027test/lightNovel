$(document).ready(function() {
    var postForm = new PostArticleForm({'el' : "#postArticleForm"});
    var memModel = new MemberModel();

    memModel.on("change:seriesList", function() {
        var data = this.get("seriesList");
        if(data['status'] == 200) {
            data = data['data'];
            $("select[name=series]").html("<option num='X' value=''>請選擇</option>");
            for(var i in data) {
                var option = document.createElement("option");
                $(option).attr("num", i);
                $(option).val(data[i]['as_id']);
                $(option).text(data[i]['as_name']);
                $("select[name=series]").append(option);
            }
        }
    });

    /*
    $("select[name=series]").on("change", function() {
        var data = memModel.get("seriesList")['data'];
        var num = $("select[name=series] option:selected").attr("num");
        if(num != "X") {
            var as_finally = data[num]['as_finally'];
            if(as_finally == 0)
                as_finally = "?";
            $("input[name=chapterSum]").val(as_finally);
        }

        if($(this).val() != "")
            $("input[name=aChapter]").addClass("validate[required]");
        else
            $("input[name=aChapter]").removeClass("validate[required]");
    });
    */

    $.getScript("lib/CookieAPI.js", function() {
        atTitle = getCookie("atTitle");
        $("input[name=title]").val(atTitle);
    });

    var mySerPost = {};
    mySerPost['nowPage'] = 1;
    mySerPost['pageLimit'] = 9999;
    memModel.getMySeriesList(mySerPost);

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
