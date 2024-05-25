Pace.on("start", function () {
    $("#overlay-pilates").show();
});
$.hasAjaxRunning = function () {
    return $.active != 0;
};
Pace.on("done", function () {
    hideOverlay();
});

function showOverlay() {
    $("#overlay-pilates").show();
}
function hideOverlay() {
    $("#overlay-pilates").delay(100).fadeOut(200);
}
$("form").submit(function (e) {
    showOverlay();
});

$(document).ready(function () {
    $(document).on("focus", ":input", function () {
        $(this).attr("autocomplete", "off");
    });
});

$(".modal")
    .on("show.bs.modal", function () {
        $("html").css("overflow", "hidden");
    })
    .on("hide.bs.modal", function () {
        $("html").css("overflow", "auto");
    });
