
let resizeAmount = 994; //used for changing the document based on this pixel width.
let windowWidth = getWindowWidth();

$(document).ready(function () {

    adjustBodyContainer(windowWidth);


    $(window).resize(function () {
        windowWidth = getWindowWidth();
        adjustBodyContainer(windowWidth);
    });

    if ($("body").attr("id") == "edit-element") {
        $("textarea").css("font-size", $("section p").css("font-size"));
        $("textarea").css("font-family", $("section p").css("font-family"));

        $("#previewArea").css({
            border: "1px solid #2e2e2e"
        });
    }

    //$("a.edit-entry").height($("#input[type='submit']").height());
});


function adjustHeader() {

}

function adjustBodyContainer(windowWidth) {

    let bodyClasses = $("body").attr("class");

    if (windowWidth < resizeAmount) {

        if (!bodyClasses.includes("fluid")) {
            $("body").toggleClass("container container-fluid");
        }
    } else {
        if (bodyClasses.includes("fluid")) {
            $("body").toggleClass("container container-fluid");
        }
    }
}

function getWindowWidth() {
    let returnWidth = $(window).width();

    return returnWidth;
}