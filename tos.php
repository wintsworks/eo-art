<?php
include("./php/includes/header.php");
$directory_level = populateHeader(".");
?>
<section class="col-sm-12">

    <?php renderContent($directory_level, $is_admin, $db); ?>

</section>
<?php include("./php/includes/footer.php"); ?>