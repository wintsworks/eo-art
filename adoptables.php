<?php
include("./php/includes/header.php");
$directory_level = populateHeader(".");
?>
<section class="col-sm-12">

    <h3>Adoptables</h3><br>
    <p><a href="tos">Click here</a> for my terms of service.</p>
    <hr>

    <?php renderContent($directory_level, $is_admin, $db); ?>


</section>
<?php include("./php/includes/footer.php"); ?>