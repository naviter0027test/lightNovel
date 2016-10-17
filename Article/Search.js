SearchPanel = Backbone.View.extend({
    initialize : function() {
    },
    events : {
        "click button" : "search"
    },
    el : '',
    template : null,
    render : function() {
    },

    search : function(evt) {
        location.href = "#search/1";
        return false;
    }
});

SearchResult = Backbone.View.extend({
    initialize : function() {
    },
    el : '',
    template : null,
    render : function() {
        var data = this.model.get("data");
        for(var idx in data['data']) {
            if(data['data'][idx]['a_mainCp'] != null) {
                //data['data'][idx]['a_mainCp'] = data['data'][idx]['a_mainCp'].replace(";", "/");
                var mainCpArr = data['data'][idx]['a_mainCp'].split(";");
                for(var jdx in mainCpArr) {
                    mainCpArr[jdx] = "<a href='search.html#search/1/mainCp/"+ mainCpArr[jdx]+ "'>"+ mainCpArr[jdx]+ "</a>";
                }
                data['data'][idx]['a_mainCp'] = mainCpArr.join("/");
            }
            if(data['data'][idx]['a_mainCp2'] != "" && data['data'][idx]['a_mainCp2'] != null) {
                var cp2 = data['data'][idx]['a_mainCp2'];
                //data['data'][idx]['a_mainCp2'] = cp2.replace(";", "/");
                var mainCp2Arr = data['data'][idx]['a_mainCp2'].split(";");
                for(var jdx in mainCp2Arr) {
                    mainCp2Arr[jdx] = "<a href='search.html#search/1/mainCp/"+ mainCp2Arr[jdx]+ "'>"+ mainCp2Arr[jdx]+ "</a>";
                }
                data['data'][idx]['a_mainCp2'] = mainCp2Arr.join("/");
            }
            if(data['data'][idx]['a_subCp'] != "") {
                var subCp = data['data'][idx]['a_subCp'];
                var subCpArr = data['data'][idx]['a_subCp'].split(";");
                //console.log(subCpArr);
                for(var jdx in subCpArr) {
                    var subNames = subCpArr[jdx].split("/");
                    for(var ldx in subNames) {
                        subNames[ldx] = "<a href='search.html#search/1/subCp/"+ subNames[ldx]+ "'>"+ subNames[ldx]+ "</a>";
                    }
                    subCpArr[jdx] = subNames.join("/");
                }
                //console.log(subCpArr);
                data['data'][idx]['a_subCp'] = subCpArr.join(";");
            }
            if(data['data'][idx]['a_alert'] != "") {
                var alertArr = data['data'][idx]['a_alert'].split(";");
                for(var jdx in alertArr) {
                    alertArr[jdx] = "<a href='search.html#search/1/alert[]/"+ alertArr[jdx]+ "'>"+ alertArr[jdx]+ "</a>";
                    //console.log(alertArr[idx]);
                }
                data['data'][idx]['a_alert'] = alertArr.join(";");
            }
            if(data['data'][idx]['a_tag'] != "") {
                var tagArr = data['data'][idx]['a_tag'].split(";");
                for(var jdx in tagArr) {
                    tagArr[jdx] = "<a href='search.html#search/1/tag[]/"+ tagArr[jdx]+ "'>"+ tagArr[jdx]+ "</a>";
                    //console.log(tagArr[idx]);
                }
                data['data'][idx]['a_tag'] = tagArr.join(";");
            }
        }
        this.$el.html(this.template(data));
    }
});

SearchModel = Backbone.Model.extend({
    initialize : function() {
    },
    defaults : {
        'data' : null
    }
});
