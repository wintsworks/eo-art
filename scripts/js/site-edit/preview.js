//Allowing user to view live changes of content.
$(document).ready(function () {

    $(document).change(function () {
        selectedElement = $("#element_type").children("option:selected").val();
        renderPreview(selectedElement);
    });

    $(document).click(function () {
        selectedElement = $("#element_type").children("option:selected").val();
        renderPreview(selectedElement);
    });

});

function renderPreview(elementType) {
    $("#previewArea").empty();
    let html = "";
    let changeElementBody = "";
    switch (elementType) {
        case "h3":
            html = `<h3>${$("#h3").val()}</h3>`;
            changeElementBody = $("#h3").val();
            break;
        case "p":
            html = `<p>${$("#p").val()}</p>`;
            changeElementBody = $("#p").val();
            break;
        case "img":
            //html = `<img src="${directory_level}/images/${$("#element_html_img").val()}">`;
            //re-use this later ^
            html = `<img src="${$("#img").val()}">`;
            changeElementBody = $("#img").val();
            break;
        case "hr":
            html = "<h4>Example Heading, spacer below.</h4><hr>Example text content, spacer above.";
            break;
    }

    if (elementType != "hr") {
        $("#element_body").val(changeElementBody);
        $("#previewArea").html(html);
    } else {
        $("#previewArea").html(html);
    }
}