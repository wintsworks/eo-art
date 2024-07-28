$(document).ready(function () {
    //addEditLinks();
});

function formatEditLinks() { //rebuilds elements on page to include an edit button.

    //edit-button-box

    // $(".edit-button-box").each(function () {
    //     $(this).mouseenter(function () {
    //         alert("something 3");
    //     })
    // });

    // $(".edit-entry").each(function () {
    //     $(this).toggleClass("button-show");
    // });





    // $(".edit-button-box").click(function () {
    //     alert("something 2");
    // });




    //let elements = document.body.getElementsByClassName("edit-entry");
}

// function toggleButtons(element) {
//     alert("works");
//     element.children("input[type='submit']").css({
//         "display": "inline-block"
//     });
// }

{/* <li class="nav-item">
<a class="nav-link" href="<?php echo "$directory_level/$page_name"; ?>?edit=disable">Disable Edit Mode</a>
</li> */}


// <li class="nav-item">
//                                             <a class="nav-link" href="<?php echo "$directory_level/$page_name"; ?>?edit=enable">Enable Edit Mode</a>
//                                         </li>





    // let editId = 0; //used for giving a unique id to each
    // $(".user-added").each(function () {
    //     editId++;
    //     let elementType = $(this).prop("nodeName").toLowerCase(); //used for giving an element type for the editing process.
    //     //determining which element type, and dealing with element appropriately.
    //     let elementClasses = $(this).attr("class");
    //     let elementIsInclude = elementClasses.includes("include-element");

    //     //used to append to the DOM.
    //     let addToElement = "";
    //     let appendElement = false;
    //     let userAddedText = "";
    //     let buildElement = "";

    //     //adding information about element; origination of element_body.
    //     switch (elementType) {
    //         case "h3":
    //         case "p":
    //             userAddedText = $(this).html();
    //             htmlSpecialFirst = userAddedText.replaceAll("<", "%less%");
    //             htmlSpecial = htmlSpecialFirst.replaceAll(">", "%great%");
    //             console.log(`${htmlSpecialFirst} is first\n${htmlSpecial} is second.`);
    //             appendElement = false;
    //             addToElement = `<input type="submit" class="edit-entry" value="Edit">
    //             <input type="hidden" name="element_body" value="${htmlSpecial}">`;
    //             break;

    //         case "img":
    //             appendElement = true;
    //             addToElement = `<input type="submit" class="edit-entry image-edit" value="Edit">
    //             <input type="hidden" name="element_body" value="${$(this).attr("src")}">`;
    //             $(this).css("margin-bottom", "10px");
    //             $(".image-edit").css("margin-left", "0");
    //             break;
    //     }

    //building the element back together
    // buildElement = `${userAddedText}
    // <form method="POST" action="${directory_level}/scripts/php/edit-element">
    // <input type="hidden" name="element_id" value="${(elementIsInclude ? "include" : $("body").attr("id"))}-${elementType}-${editId}">
    // <input type="hidden" name="element_page" value="${$("body").attr("id")}">
    // <input type="hidden" name="element_type" value="${elementType}">

    // ${addToElement}
    // </form>`;

    //placing element on page with appropriate edit buttons.
    //     appendElement ? $(this).parent().append(buildElement) : $(this).html(buildElement);
    // });