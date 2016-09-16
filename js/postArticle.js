$(document).ready(function() {
    var postForm = new PostArticleForm({'el' : "#postArticleForm"});
    var memModel = new MemberModel();

    memModel.on("change:seriesList", function() {
        var data = this.get("seriesList");
        if(data['status'] == 200) {
            data = data['data'];
            $("select[name=series]").html("<option num='X' value=''>請選擇</option>");

            var asid = null;
            if(getQueryVariable("isChapter") == "Y") 
                 asid = getCookie("asid");
            
            for(var i in data) {
                var option = document.createElement("option");
                $(option).attr("num", i);
                if(asid == data[i]['as_id'])
                    $(option).attr("selected", true);
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

    if(getQueryVariable("isChapter") == "Y") {
        atTitle = decodeURIComponent(getCookie("atTitle"));
        $("input[name=title]").val(atTitle);

        var mainCp = decodeURIComponent(getCookie("mainCp")).split(",");
        mainInputs = $("input[name='cp1[]']");
        $(mainInputs[0]).val(mainCp[0]);
        $(mainInputs[1]).val(mainCp[1]);

        var mainCp2 = decodeURIComponent(getCookie("mainCp2"));
        if(mainCp2 != "") {
            mainCp2 = mainCp2.split(",");
            main2Inputs = $("input[name='cp2[]']");
            $(main2Inputs[0]).val(mainCp2[0]);
            $(main2Inputs[1]).val(mainCp2[1]);
        }

        var viceCp = decodeURIComponent(getCookie("subCp"));
        if(viceCp != "") {
            $("input[name=viceCp]").val(viceCp.replace(",", ";"));
        }

        var alertstr = decodeURIComponent(getCookie("alert"));
        var alerts = $("input[name='alert[]']");
        for(var i = 0;i < alerts.length;++i) 
            if(alertstr.search($(alerts[i]).val()) != -1) 
                $(alerts[i]).attr("checked", true);
        //console.log(alerts);

        var tagstr = decodeURIComponent(getCookie("tag"));
        var tags = $("input[name='tag[]']")
        for(var i = 0;i < tags.length;++i)
            if(tagstr.search($(tags[i]).val()) != -1) 
                $(tags[i]).attr("checked", true);
        //console.log(tags);
        $("input[name=aChapter]").val("");
        $("input[name=aChapter]").attr("readonly", false);
    }

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
