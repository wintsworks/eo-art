//Allowing user to view live changes of content.
let previousData = new Array;
let firstEdit = true;

//used for current custom elements available for editing.
let selectValues = ["h3", "p", "img", "hr"];

$(document).ready(function () {
    let selectedElement = $("#element_type").children("option:selected").val();
    !firstEdit ? saveFieldInMemory(getChildren()) : null;
    renderFields(selectedElement);
    firstEdit = false;


    $(document).on("keypress", () => {
        let selectedElement = $("#element_type").children("option:selected").val();
        renderPreview(selectedElement);
    });
    
    $("#element_type").change(function () {
        let selectedElement = $(this).children("option:selected").val();
        !firstEdit ? saveFieldInMemory(getChildren()) : null;
        renderFields(selectedElement);
});

});

//render labels and input/textarea/etc based on select menu (element_type), and populates fields with previously entered data.
function renderFields(elementType) {

    let previousHtml = "";
    let appendHtml = "";
    switch (elementType) {
        case "h3":
            previousData[0] != undefined ? previousHtml = previousData[0] : previousHtml = "";
            appendHtml = `<label class="form-label">Add Heading Text</label>
            <input type="text" id="h3" maxlength="60000" name="element_body" class="form-control" value="${previousHtml}">`;
            break;
        case "p":
            previousData[1] != undefined ? previousHtml = previousData[1] : previousHtml = "";
            appendHtml = `<label class="form-label">Add Text; type &#60;br&#62; to add a line break.</label>
            <textarea name="element_body" maxlength="60000" id="p" class="form-control">${previousHtml}</textarea>`;
            break;
        case "img":
            previousData[2] != undefined ? previousHtml = previousData[2] : previousHtml = "";
            appendHtml = `<label class="form-label">Image Source</label>
            <input type="text" name="element_body" maxlength="60000" id="img" class="form-control" value="${previousHtml}">`;
            break;
        case "hr":
            appendHtml = `<label class="form-label mb-5">Adds a separator underneath content.</label>`;
            break;
    }

    appendHtml += ""

    $("#dynamicFieldset").empty();
    $("#dynamicFieldset").append(appendHtml);
    return elementType;
}

//temporarily saves typed data in each input.
function saveFieldInMemory(elementType) {
    switch (elementType) {
        case "h3":
            previousData[0] = $("#h3").val();
            break;
        case "p":
            previousData[1] = $("#p").val();
            break;
        case "img":
            previousData[2] = $("#img").val();
            break;
    }
}

//gets children under the fieldset id "dynamicFieldset" on edit-element.php, 
function getChildren() {
    let child = ""; 
    let element = "";
    $("#dynamicFieldset").children().each(function () {
        child = $(this).attr("id") == undefined ? "non-custom" : $(this).attr("id");

        for (id of selectValues) {
            if (child.includes(id)) {
                element = id;
                console.log("Element: " + element);
            }
        }
    });
    return element;
}