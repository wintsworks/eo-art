$(document).ready(function () {

    //Grabbing page information
    let currentPage = $("#whichPage").val();

    switch (currentPage) {
        case "construct":
            animateConstruction();
            break;
    }
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

//Adjusts the Width of the Hr element.
function adjustHrSize () {
    let hrContainerWidth = $("#constructHrContainer").width();


    $("#constructHr").width($("#constructText").width(), () => {
        $("#constructHrContainer").width(hrContainerWidth);
    });
}