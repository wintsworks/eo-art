/** WW-Works Function 
 * 
 * Adds clickable links to any element. 
 * <caption>Adding a Link using addLinks()</caption>
 * @example <button id="home-button">Home Page</button>
 * let target = document.getElementById('home-button');
 * 
 * addLink(target, "https://www.google.com/");
 * 
 * @param {string} target Element ID to attach link to.
 * @param {string} link Link to attach to Element ID.
*/
function addLink(target, link) {
    if (typeof target === "string" && typeof link === "string") {
        $(`#${target}`).click(function () {
            window.location.replace(link);
        });

        $(`#${target}`).css("cursor", "pointer");
    }
}