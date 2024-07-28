<?php

include("../../php/includes/header.php");
$directory_level = populateHeader("../..");
require_once("$directory_level/php/includes/connect-db.php");

//used to redirect non-admins back to the home page.
(!$is_admin ? header("Location: $directory_level/index") : "");
?>


<script src="../js/site-edit/select-content.js" defer></script>


<section class="col-sm-12">
    <?php

    $type = "";
    $header_title = "";

    //if an edit element request is called.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (array_key_exists("element_page", $_POST)) {
            $element_page = $_POST["element_page"];
        } else {
            $element_page = "";
        }

        //using element_columnS to set element_column [no S at the end]; doing so as the select menu uses the same information.
        if (array_key_exists("element_columns", $_POST)) {
            $element_column = $_POST["element_columns"];
        }

        if (array_key_exists("editing_mode", $_POST)) { //if the content is being processed.
            $type = "process";
            $editing_mode = trimEntry($_POST["editing_mode"]);
            (array_key_exists("element_id", $_POST) ? $element_id = $_POST["element_id"] : null);
            $element_body = $_POST["element_body"];

        } else { //if the content is being edited.
            $type = "edit";
            $element_id = $_POST["element_id"];

            ?>
            <script src="../js/site-edit/preview.js" defer></script>
            <?php
        }

        if ($type == "edit") {
            $sql = "select * from element where element_id = :element_id";
            $statement = $db->prepare($sql);
            $statement->bindValue(":element_id", $element_id);

            if ($statement->execute()) {
                $statement_results = $statement->fetchAll();
                $statement->closeCursor();

                foreach ($statement_results as $statement_entry) {
                    $element_body = htmlspecialchars($statement_entry["element_body"]);
                    $element_id = $statement_entry["element_id"];
                    $element_type = $statement_entry["element_type"];
                    $element_column = $statement_entry["element_column"];

                    ?>
                    <script src="../js/site-edit/preview.js" defer></script>
                    <?php
                }
            } else {
                $element_body = U_UNDEFINED_VARIABLE;
            }
        }
        //$element_body = trimEntry($_POST["element_body"]);
        $element_type = $_POST["element_type"];
        $success = true;

        $header_title = "Edit Content On Page";

        //other uses for adding content to page.
    } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $element_page = (array_key_exists("add", $_GET) ? trimEntry($_GET["add"]) : "error");
        $success = true;

        ?>
            <script src="../js/site-edit/preview.js" defer></script>
            <?php

            $type = "add";
            $header_title = "Add Content to Page";
    } else {
        $success = false;
        $type = "default";
    }

    isset($element_page) ? null : $element_page = "";
    isset($element_body) ? null : $element_body = "";

    //changing the header title to reflect processing the information to the user.
    if ($type == "process") {
        $header_title = "Processing Edit...";
    }

    ?>

    <h3 id="editHeaderLabel" class="pb-0">
        <?php echo $header_title; ?>
    </h3>
    <hr>
    <p id="message"></p>
    <form id="editElementForm" action="edit-element" method="POST">

        <input type="hidden" id="element_page" name="element_page" value="<?php echo $element_page; ?>">
        <input type="hidden" id="element_body" name="element_body" value="<?php echo $element_body; ?>">
        <input type="hidden" id="element_column" name="element_column" value="<?php echo $element_column; ?>">
        <?php

        //determining operation taking place for the page.
        switch ($type) {

            //adding a new element to page.
            case "add": ?>
                <label class="form-label">Type Of Content</label>
                <select name="element_type" id="element_type" class="form-select">
                    <option value="h3">Heading</option>
                    <option value="hr">Spacer</option>
                    <option value="p">Text</option>
                    <option value="img">Image</option>
                    <option value="div">Container</option>
                </select>

                <input type="hidden" name="editing_mode" value="add">
                <fieldset id="dynamicFieldset"></fieldset>

                <label class="form-label">Horizontal Space</label>
                <select name="element_columns" id="element_columns" class="form-select">
                    <option value="1">1/12th of Page Width</option>
                    <option value="2">1/10th of Page Width</option>
                    <option value="3">1/4th of Page Width</option>
                    <option value="4">1/3rd of Page Width</option>
                    <option value="6">1/2 of Page Width</option>
                    <option value="12" selected>Full Page Width</option>
                </select>

                <input id="submit" type="submit" value="Confirm Add Content" class="mt-3 form-control">

                <?php break;

            //editing an existing element on a page.
            case "edit": ?>

                <label class="form-label">Type Of Content</label>
                <select name="element_type" id="element_type" class="form-select">
                    <option value="h3">Heading</option>
                    <option value="hr">Spacer</option>
                    <option value="p">Text</option>
                    <option value="img">Image</option>
                    <option value="div">Container</option>
                </select>
                <input type="hidden" name="editing_mode" value="edit">
                <input type="hidden" name="element_id" value="<?php echo $element_id; ?>">
                <fieldset id="dynamicFieldset"></fieldset>

                <label class="form-label">Horizontal Space</label>
                <select name="element_columns" id="element_columns" class="form-select">
                    <option value="1">1/12th of Page Width</option>
                    <option value="2">1/10th of Page Width</option>
                    <option value="3">1/4th of Page Width</option>
                    <option value="4">1/3rd of Page Width</option>
                    <option value="6">1/2 of Page Width</option>
                    <option value="12">Full Page Width</option>
                </select>


                <input id="submit" type="submit" value="Confirm Edit" class="mt-3 form-control">
                <?php
                break;

            //processing add/edit content.
            case "process":

                switch ($editing_mode) {
                    case "add":

                        //echo "<p>Page: $element_page <br>Body: $element_body <br>Type: $element_type";
        
                        $sql = "insert into element
                        (element_page, element_type, element_body, element_column)
                        values
                        (:element_page, :element_type, :element_body, :element_column)";
                        $statement = $db->prepare($sql);

                        $statement->bindValue(":element_page", $element_page);
                        $statement->bindValue(":element_type", $element_type);
                        $statement->bindValue(":element_body", $element_body);
                        $statement->bindValue(":element_column", $element_column);

                        if ($statement->execute()) {
                            $statement->closeCursor();

                            $message = "Successfully added new content.";
                            echo '<script>
                                $("#editHeaderLabel").html("Success!");
                                document.location.href = "' . $directory_level . '/' . $element_page . '?info=addSuccess";
                        </script>';
                            ?>

                            <script>
                                $(document).ready(function () {
                                    $("#editHeaderLabel").html("Success!");
                                });
                            </script>

                            <?php
                        } else {
                            $message = "Failed to add new content.";
                            ?>
                            <script>
                                $(document).ready(function () {
                                    $("#editHeaderLabel").html("An Error Ocurred.");
                                });
                            </script>
                            <?php
                        }
                        break;

                    case "edit":

                        $sql = "update element
                        set element_page = :element_page, element_type = :element_type, element_body = :element_body, element_column = :element_column
                        where element_id = :element_id";

                        $statement = $db->prepare($sql);

                        $statement->bindValue(":element_id", $element_id);
                        $statement->bindValue(":element_page", $element_page);
                        $statement->bindValue(":element_type", $element_type);
                        $statement->bindValue(":element_body", $element_body);
                        $statement->bindValue(":element_column", $element_column);

                        if ($statement->execute()) {
                            $statement->closeCursor();

                            $message = "Successfully edited content.";

                            echo '<script>
                                    $("#editHeaderLabel").html("Success!");
                                    document.location.href = "' . $directory_level . '/' . $element_page . '?info=editSuccess";
                            </script>';
                            ?>





                            <?php
                        } else {
                            $message = "Failed to edit content.";
                            ?>
                            <script>
                                $(document).ready(function () {
                                    $("#editHeaderLabel").html("An Error Ocurred.");
                                });
                            </script>
                            <?php
                        }
                        break;
                }
                break;
        }
        ?>

    </form>

    <?php if (isset($message)) { ?>

        <button id="return-edit" class="form-control">Return To Page</button>
        <script>
            $(document).ready(function () {
                addLink("return-edit", "../../<?php echo $element_page; ?>");

                $("#message").html("<?php echo $message; ?><br><br>");
                $("#editElementForm").empty();
                addLink();
                
            });
        </script>
    <?php } ?>
</section>

<?php

//disabling the preview content section if processing data.
if ($type != "process") { ?>

    <section class="col-sm-12">
        <h3 id="previewHeader">Preview of Content</h3>
        <hr>

        <div class="row">
            <div id="previewArea" class="mt-4"></div>
        </div>
    </section>

<?php } ?>

<!-- <div class="col-sm-6 sectionDiv p-3">
</div> -->

<?php include("$directory_level/php/includes/footer.php"); ?>

<?php

echo '<script>
$(document).ready(function() {

    switch (' . json_encode($type) . ') {
        case "edit":
            firstEdit = false;

            let element_type = "' . (isset($element_type) ? $element_type : json_encode(null)) . '";

            if (element_type != "") {
                switch (element_type) {
                    case "h3":
                        previousData[0] = `' . (isset($element_body) ? $element_body : json_encode(null)) . '`;
                        break;
                    case "p":
                        previousData[1] = `' . (isset($element_body) ? $element_body : json_encode(null)) . '`;
                        break;
                    case "img":
                        previousData[2] = `' . (isset($element_body) ? $element_body : json_encode(null)) . '`;
                        break;
                }

                renderFields(element_type);
                renderPreview(element_type);
                $("#element_type").val("' . (isset($element_type) ? $element_type : json_encode(null)) . '");
                $("#element_columns").val("' . (isset($element_column) ? $element_column : json_encode(null)) . '");
            }
            break;
    }
});
</script>';

?>