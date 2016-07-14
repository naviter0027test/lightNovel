$(document).ready(function() {
    $("#explan").load("template/explan.html", function() {
        var explan = $('[data-remodal-id=explanStart]').remodal();
        explan.open();
    });
});
