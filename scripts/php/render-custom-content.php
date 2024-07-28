<?php

function renderContent($directory_level, $is_admin, $db, $is_include = false, $additional_classes = "", $in_div = false, $original_page = "")
{
    echo "<div class='row'>";

    $page_name = !$is_include ? (str_replace(".php", "", basename($_SERVER["PHP_SELF"]))) : "include";
    $sql = "select * from element where element_page = :element_page";
    $statement = $db->prepare($sql);
    $statement->bindValue(":element_page", $page_name);

    if ($statement->execute()) {
        $elements = $statement->fetchAll();
        $statement->closeCursor();

        $additional_classes .= array_key_exists("edit", $_SESSION) ? ($_SESSION["edit"] == 1 ? " ww-content-edit" : "") : "";
        $results = 0; //used for deducing if any sql results appear.
        foreach ($elements as $element) {

            $results += 1;

            //opening and closing tag list
            $tags = array(
                "h3" => array(
                    "open" => '<h3 class="%classes%">',
                    "close" => '</h3>'
                ),
                "p" => array(
                    "open" => '<p class="%classes%">',
                    "close" => '</p>'
                ),
                "hr" => array(
                    "open" => '<hr class="%classes%">',
                    "close" => ''
                ),
                "img" => array(
                    "open" => '<img class="%classes%" src="%src%">',
                    "close" => ''
                ),
                "div" => array(
                    "open" => '<div class="%classes%">',
                    "close" => '</div>'
                )
            );


            $element_tag = $element["element_type"];
            $element_column = $element["element_column"];

            $edit_disabled = array_key_exists("edit", $_SESSION) ? ($_SESSION["edit"] == 1 ? false : true) : true;
            //rendering appropriate content based on element tag.
            switch ($element_tag) {
                case "h3":
                case "p":
                case "hr":

                    //adjusting special characters
                    $body_pre_step = $element["element_body"];
                    $body_first_step = str_replace("%less%", "<", $body_pre_step);
                    $body = str_replace("%great%", ">", $body_first_step);
                    $tag_with_classes = str_replace("%classes%", "user-added", $tags[$element_tag]["open"]);

                    //$append is the element to render (append to the parent calling this function).
                    $append = $tag_with_classes . $body . $tags[$element_tag]["close"];
                    
                    $append .= $is_admin && !$edit_disabled && !$is_include ? 
                        "<div class='edit-button-box'><form action='$directory_level/scripts/php/edit-element' method='POST'>
                        <input type='hidden' name='element_id' value='" . $element["element_id"] . "'>
                        <input type='hidden' name='element_page' value='$page_name'>
                        <input type='hidden' name='element_type' value='$element_tag'>
                        <input type='submit' class='edit-entry' value='Edit'>
                        <a href='" . $directory_level . "/scripts/php/delete-element?page_name=" . $page_name . "&element_id=" . $element["element_id"] . "' onclick='return confirm(\"Delete this content?\");' class='edit-entry'>Delete</a></form></div>" 
                    : "";

                    echo "<div class='ww-content col-sm-$element_column $additional_classes'>$append</div>";
                    // echo '<input type="submit" class="edit-entry" value="Edit">
                    // <input type="hidden" name="element_body" value="' . $body . '">';
                    break;


                case "img":
                    $replaced_classes = str_replace("%src%", $element["element_body"], $tags[$element_tag]["open"]);

                    $tag_with_source = str_replace("%classes%", "user-added", $replaced_classes);
                    $append = $tag_with_source . $tags[$element_tag]["close"];
                    $append .= $is_admin && !$edit_disabled ? 
                        "<div class='edit-button-box'><form action='$directory_level/scripts/php/edit-element' method='POST'>
                        <input type='hidden' name='element_id' value='" . $element["element_id"] . "'>
                        <input type='hidden' name='element_page' value='$page_name'>
                        <input type='hidden' name='element_type' value='$element_tag'>
                        <input type='submit' class='edit-entry' value='Edit'>
                        <a href='" . $directory_level . "/scripts/php/delete-element?page_name=" . $page_name . "&element_id=" . $element["element_id"] . "' onclick='return confirm(\"Delete this content?\");' class='edit-entry'>Delete</a></form></div>" : "";
                    echo "<div class='ww-content col-sm-$element_column $additional_classes'>$append</div>";
                    break;


                case "div":
                    $body_pre_step = $element["element_body"];
                    $body_first_step = str_replace("%less%", "<", $body_pre_step);
                    $body = str_replace("%great%", ">", $body_first_step);
                    $tag_with_classes = str_replace("%classes%", "user-added", $tags[$element_tag]["open"]);
                    $append = $tag_with_classes . $body . $tags[$element_tag]["close"];
                    $append .= $is_admin && !$edit_disabled && !$is_include ? "<div class='edit-button-box'><form action='$directory_level/scripts/php/edit-element' method='POST'>
                        <input type='hidden' name='element_id' value='" . $element["element_id"] . "'>
                        <input type='hidden' name='element_page' value='$page_name'>
                        <input type='hidden' name='element_type' value='$element_tag'>
                        <input type='submit' class='edit-entry' value='Edit'>
                        <a href='" . $directory_level . "/scripts/php/delete-element?page_name=" . $page_name . "&element_id=" . $element["element_id"] . "' onclick='return confirm(\"Delete this content?\");' class='edit-entry'>Delete</a></form>
                        <form action='$directory_level/scripts/php/edit-element' method='POST'>
                        <input type='hidden' name='element_id' value='" . $element["element_id"] . "'>
                        <input type='hidden' name='element_page' value='$page_name'>
                        <input type='hidden' name='element_type' value='$element_tag'>
                        <input type='submit' class='edit-entry' value='Add Content Here'>
                        </form></div>" : "";
                    echo "<div class='ww-content col-sm-$element_column $additional_classes'>$append</div>";
                    break;
            }
        }

        //If there are no custom content on the page.
        if ($results == 0) {
            if ($is_admin) {
                echo '<p>Click "Enable Edit Mode" and then "Add Content" on the navigation bar to begin adding content here!</p>';
            }
        }
    }

    echo "</div>";
}
//<button type='button' value='' class='edit-entry' onclick='return confirm(\"Delete this content?\");'>Delete</button>
?>