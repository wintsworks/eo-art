<?php

include("../../php/includes/header.php");
$directory_level = populateHeader("../..");
require_once("../../php/includes/connect-db.php");
?>

<section class="col-sm-12">
    <?php
    $settings = getValue(["commission_select"]);

    if (!$settings["missing_variables"]) {
        $sql = "update settings set commission_select = :commission_select where settings_id = 0";

        $statement = $db->prepare($sql);
        $statement->bindValue(":commission_select", htmlspecialchars($settings["commission_select"]));

        if ($statement->execute()) {
            $statement->closeCursor();
            echo "<script type='text/javascript'>document.location.href='$page_name?info=savedSettings';</script>";
        }
    }
    $fluid_result = "";
    $sql = "select * from settings where settings_id = 0";
    $statement = $db->prepare($sql);

    if ($statement->execute()) {
        $settings_results = $statement->fetchAll();

        foreach ($settings_results as $settings) {
            $fluid_result = $settings["fluid"];
            $commissions_populate = $settings["commission_select"];
        }
    }
    ?>
    <h3>Site Settings</h3>
    <hr>

    <form action="settings" method="POST" class="row">

        <fieldset class="col-sm-6">
            <legend id="commissionsHeader">Commission Types</legend>
            <textarea name="commission_select" id="commission_select" class="form-control"><?php echo $commissions_populate; ?></textarea>
        </fieldset>

        <fieldset disabled class="col-sm-6">
            <legend id="stretchHeader">Fit site to edges?</legend>
           
            <select name="fluid" id="fluid" class="form-select">
                <option value="0">Do Not Stretch</option>
                <option value="1">Stretch</option>
            </select>
        </fieldset>

        <input type="hidden" name="info" value="">
        <input type="submit" class="form-control mt-3" value="Save Settings">
    </form>

</section>

<?php //echo "<script>$('#fluid').val('$fluid_result');</script>"; ?>

<?php include("../../php/includes/footer.php"); ?>