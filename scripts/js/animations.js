$(document).ready(function () {

    //Grabbing page information
    let currentPage = $("body").attr("id");

    switch (currentPage) {
        case "constructPage":
            animateConstruction();
            break;
    }

    $(document).change(function () {
        console.log($(this).height());
    })

    animateAlert();
});

function animateConstruction() {

    setInterval(() => {
        $("#contructionImage").animate({
            top: "50px"
        }, 2500).animate({
            top: "0"
        }, 2500);
    });

    adjustHrSize();
}

async function animateAlert() {

    flashAlert();
    $(".alert").addClass("border-radius-20px");

    $("#alert").animate({
        bottom: "5%"
    }, 300).animate({
        bottom: 0
    }, 300);
    $(".alert").addClass("alert-animate");

    $(".alert").delay(1800).animate({
        bottom: "5%"
    }, 250).animate({
        bottom: "0",
        width: "0",
        opacity: 0
    }, 250);

    await new Promise(resolve => setTimeout(resolve, 2400));

    $(".alert").children().each(function () {
        $(this).fadeOut(630);
    })

    await new Promise(resolve => setTimeout(resolve, 938));

    $("#alert").remove();
}


//Adjusts the Width of the Hr element.
function adjustHrSize() {
    let hrContainerWidth = $("#constructHrContainer").width();

    $("#constructHr").width($("#constructText").width(), () => {
        $("#constructHrContainer").width(hrContainerWidth);
    });
}

async function flashAlert() {
    let type = $("#alert").attr("name");
    switch (type) {
        case "error":
            type = "error";
            break;
        default:
            type = "success";
    }

    await new Promise(resolve => setTimeout(resolve, 500));
    $(".alert").addClass(`alert-flash-${type}`);
    await new Promise(resolve => setTimeout(resolve, 250));
    $(".alert").removeClass(`alert-flash-${type}`);
}