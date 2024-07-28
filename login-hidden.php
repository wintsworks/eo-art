<?php

include("./php/includes/header.php");
$directory_level = populateHeader(".");
require_once("./php/includes/connect-db.php");


if ($_SERVER["REQUEST_METHOD"] == "GET" && array_key_exists("create", $_GET)) {
    $type = "create";
} else {
    $type = "login";
}
?>

<section class="col-sm-12">
    <?php if ($type == "login") { ?>

        <h3 class="pb-0">Login</h3>
        <hr>

        <form action="<?php echo $directory_level; ?>/scripts/php/process-login" method="POST">

            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" class="form-control" name="username" required>

            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>

            <input type="hidden" name="type" value="login">

            <input class="btn-primary col-sm-12 ww-button" type="submit" value="Login">

        </form>

    <?php } else if ($type == "create") { ?>

        <h3 class="pb-0">Create Account</h3>
        <hr>

        <form action="<?php echo $directory_level; ?>/scripts/php/process-login" method="POST">

            <!-- <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" class="form-control" name="email" required> -->

            <label for="username" class="form-label">Username</label>
            <input id="username" type="text" class="form-control" name="username" required>

            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>

            <input type="hidden" name="type" value="create">

            <input class="btn-primary col-sm-12 ww-button" type="submit" value="Create Account">

        </form>

    <?php } ?>
</section>

<?php include("$directory_level/php/includes/footer.php"); 

?>