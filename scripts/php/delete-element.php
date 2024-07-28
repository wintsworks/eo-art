<?php

include("../../php/includes/header.php");
$directory_level = populateHeader("../..");
require_once("$directory_level/php/includes/connect-db.php");

//used to redirect non-admins back to the home page.
(!$is_admin ? header("Location: $directory_level/index") : "");

if($_SERVER["REQUEST_METHOD"] == "GET") {
    $element_id = (array_key_exists("element_id", $_GET) ? trimEntry($_GET["element_id"]) : null);
    $page_name = (array_key_exists("page_name", $_GET) ? trimEntry($_GET["page_name"]) : null);

    $sql = "delete from element where element_id = :element_id";
    $statement = $db->prepare($sql);

    $statement->bindValue(":element_id", $element_id);

    if ($statement->execute()) {
        $statement->closeCursor();

        $success = true;
    } else {
        $success = false;
    }

    if (!$success) {
        echo "Failed to delete component, redirecting...";

        ?>
        
        <script>
            $(document).ready(function () {
                setTimeout(() => {
                    window.location.replace("<?php echo $directory_level/$page_name; ?>?info=failedDelete");
                }, 3000);
            });
        </script>
        
        <?php
    } else {
        ?>
        
        <script>window.location.replace("<?php echo "$directory_level/$page_name?info=contentDeleted"; ?>");</script>
        
        <?php
    }
}

?>
