function initialShow() {
        $("#sidebarMenu").on("click", function() {
            $("#sidebar").fadeToggle("fast", "linear");
        });
        //browserResize();
}

if (window.addEventListener) {              
    //window.addEventListener("resize", browserResize);
} else if (window.attachEvent) {                 
    //window.attachEvent("onresize", browserResize);
}
var xbeforeResize = window.innerWidth;
var ybeforeResize = window.innerWidth;
var zbeforeResize = window.innerWidth;

function browserResize() {
    var afterResize = window.innerWidth;
    if ( afterResize <= (700 + 74) ) {
        xbeforeResize = afterResize;
        $("#sidebar").hide();
        $("#sidebarMenu").show();
    }
    else {
        $("#sidebar").show();
        $("#sidebarMenu").hide();
    }
}
