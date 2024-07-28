<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ink&hyphen;Eo</title>

    <?php

    
    $db_dir = "";
    $is_admin = (array_key_exists("is_admin", $_SESSION) ? ($_SESSION["is_admin"] == 1 ? true : false) : false);
    $page_name = str_replace(".php", "", basename($_SERVER["PHP_SELF"]));

    $append_from_get = "";
    include("connect-db.php");
    include("grab-value.php");

    //takes the current directory level, using dots ./ to assume file path. i.e. populateHeader("."); one dot for root.
    function populateHeader($directory_level)
    {
        include("$directory_level/scripts/php/render-custom-content.php");
        include("connect-db.php");
        include("$directory_level/scripts/php/render-container-content.php");
        $is_admin = (array_key_exists("is_admin", $_SESSION) ? ($_SESSION["is_admin"] == 1 ? true : false) : false); //checking for admin status.
        $page_name = str_replace(".php", "", basename($_SERVER["PHP_SELF"])); //getting page's filename without extension; we're using htaccess to remove the extension.
        $do_not_edit = ["login-hidden", "edit-element", "constructPage", "process-login", "settings"]; //list of pages the site editor will ignore.
    
        include("trim-array.php");

        //loading page layout settings as defined by site admin.
        $fluid_load = "";
        $construction_mode = 0;
        $sql = "select * from settings where settings_id = 0";
        $statement = $db->prepare($sql);
        if ($statement->execute()) {
            $load_settings_results = $statement->fetchAll();
            $statement->closeCursor();

            foreach ($load_settings_results as $load) {

                $fluid_load = $load["fluid"];
                $construction_mode = $load["construction_mode"];
            }
        }

        if ($is_admin) {

            if ($_SERVER["REQUEST_METHOD"] == "GET" && array_key_exists("info", $_GET)) {
                $info = $_GET["info"];
            } else {
                $info = "";
            }
            switch ($info) {
                case "logout":
                    session_destroy();
                    $URL = "$directory_level/index";
                    $append_from_get = "<script type='text/javascript'>document.location.href='{$URL}';</script>";
                    break;
                case "contentDeleted":

                    $append_from_get = '<div id="alert" class="alert d-flex align-items-center p-3" role="alert">
                <img style="width: 44px;" class="me-4 ms-1" src="' . $directory_level . '/images/check-circle.svg" alt="">
                <div style="color: var(--titleColor);">Successfully Deleted Content.</div>
            </div>';

                    break;

                case "enabledEdit":

                    $_SESSION["edit"] = 1;
                    $append_from_get = '
                    <div id="alert" class="alert d-flex align-items-center p-3" role="alert">
                    <img style="width: 44px;" class="me-4 ms-1" src="' . $directory_level . '/images/check-circle.svg" alt="">
                <div style="color: var(--titleColor);">Enabled Editing Mode.</div>
            </div>';

                    break;

                case "disabledEdit":

                    $_SESSION["edit"] = 0;

                    $append_from_get = '<div id="alert" class="alert d-flex align-items-center p-3" role="alert">
                    <img style="width: 44px;" class="me-4 ms-1" src="' . $directory_level . '/images/check-circle.svg" alt="">
                <div style="color: var(--titleColor);">Disabled Editing Mode.</div>
            </div>';
                    break;

                case "failedDelete":

                    $append_from_get = '<div id="alert" name="error" class="alert d-flex align-items-center p-3" role="alert">
                <div style="color: var(--titleColor);">Failed to Delete Content.</div>
            </div>';
                    break;

                case "savedSettings":

                    $append_from_get = '<div id="alert" class="alert d-flex align-items-center p-3" role="alert">
                    <img style="width: 44px;" class="me-4 ms-1" src="' . $directory_level . '/images/check-circle.svg" alt="">
                <div style="color: var(--titleColor);">Saved Site Settings.</div>
            </div>';
                    break;

                case "failedSettings":

                    $append_from_get = '<div id="alert" name="error" class="alert d-flex align-items-center p-3" role="alert">
                <div style="color: var(--titleColor);">Failed to Save Settings.</div>
            </div>';
                    break;

                case "editSuccess":
                    $append_from_get = '<div id="alert" class="alert d-flex align-items-center p-3" role="alert">
                        <img style="width: 44px;" class="me-4 ms-1" src="' . $directory_level . '/images/check-circle.svg" alt="">
                    <div style="color: var(--titleColor);">Edited Content Successfully.</div>
                </div>';
                    break;

                case "addSuccess":
                    $append_from_get = '<div id="alert" class="alert d-flex align-items-center p-3" role="alert">
                            <img style="width: 44px;" class="me-4 ms-1" src="' . $directory_level . '/images/check-circle.svg" alt="">
                        <div style="color: var(--titleColor);">Added Content Successfully.</div>
                    </div>';
                    break;

                case "successDeleteEntry":
                    $append_from_get = '<div id="alert" class="alert d-flex align-items-center p-3" role="alert">
                    <img style="width: 44px;" class="me-4 ms-1" src="' . $directory_level . '/images/check-circle.svg" alt="">
                <div style="color: var(--titleColor);">Delete Entry Successful.</div>
            </div>';
                    break;

                case "failDeleteEntry":
                    $append_from_get = '<div id="alert" name="error" class="alert d-flex align-items-center p-3" role="alert">
                <div style="color: var(--titleColor);">Delete Entry Failed.</div>
            </div>';
                    break;
            }


            if (array_key_exists("edit", $_SESSION) && isset($_SESSION["edit"])) {
                if ($_SESSION["edit"] == 1) {
                    $edit_disabled = false;
                } else if ($_SESSION["edit"] == 0) {
                    $edit_disabled = true;
                }
            } else {
                $edit_disabled = true;
            }
        } else {

            if ($_SERVER["REQUEST_METHOD"] == "GET" && array_key_exists("info", $_GET)) {
                $info = $_GET["info"];

                switch ($info) {
                    case "logout":
                        session_destroy();
                        $URL = "$directory_level/index";
                        $append_from_get = "<script type='text/javascript'>document.location.href='{$URL}';</script>";
                        break;
                }
            } else {
                $info = "";
            }
        }

        //keeping user from editing critical pages.
        $protected_page = in_array($page_name, $do_not_edit);

        //allows user to access login page; while it shares if admin pages that are protected from edits as from above variable $protected_page, if user is not an admin, these pages will redirect to index anyhow.
        //$construction_mode = !$protected_page;
    
        if ($construction_mode && !$protected_page) {
            (!$is_admin ? header("Location: $directory_level/construct") : "");
        }
        ?>


        <link rel="icon" type="image/x-icon" href="<?php echo $directory_level; ?>/images/favicon.png">

        <!-- Bootstrap 5.2.3.min. -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>

        <!-- jQuery 3.6.1.min-->
        <script src="<?php echo $directory_level; ?>/scripts/js/jquery/jquery-3.6.1.min.js"></script>

        <?php if ($is_admin) { ?>

            <script>
                let directory_level = "<?php echo $directory_level; ?>";
            </script>

        <?php } ?>

        <!-- Javascript Sources-->
        <script src="<?php echo $directory_level; ?>/scripts/js/site-edit/ww-works.js"></script>
        <script src="<?php echo $directory_level; ?>/scripts/js/layout.js" defer></script>
        <?php if (!$protected_page) {
            echo ($is_admin ? "<script src=\"$directory_level/scripts/js/site-edit/edit-buttons.js\" defer></script>" : null);
        } ?>
        <script src="<?php echo $directory_level; ?>/scripts/js/animations.js"></script>

        <!-- Stylesheets -->
        <link rel="stylesheet" href="<?php echo $directory_level; ?>/styles/styles.css" type="text/css">
        <link rel="stylesheet" href="<?php echo $directory_level; ?>/styles/animations.css" type="text/css">
        <?php
        if (!$protected_page) {
            echo ($is_admin ? "<link rel=\"stylesheet\" href=\"$directory_level/styles/site-edit.css\" type=\"text/css\">" : null);
        }
        ?>


    </head>

    <body class="container<?php echo ($fluid_load == 1) ?? "-fluid"; ?>" id="<?php echo $page_name; ?>">
        <header class="row d-flex align-items-end">
            <div class="col-sm-12 p-0">
                <div id="headerContainerContainer">
                    <div class="d-flex" id="headerContainer">
                        <div class="align-self-end headerImage" id="navSpacer">
                            <p>Spacee</p>
                        </div>
                        <h1 class="text-center mt-2">Ink&hyphen;Eo</h1>
                        <div class="align-self-end headerImage" id="navSpacer2">
                            <p>Spacee</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 p-0">
                <div id="navContainer">
                    <nav class="navbar navbar-expand-lg" id="navDiv">
                        <div class="container-fluid ">
                            <a class="navbar-brand m-0" href="#"><span id="navbarBrand"></span></a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page"
                                            href="<?php echo $directory_level; ?>/index">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo $directory_level; ?>/gallery">Gallery</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="<?php echo $directory_level; ?>/commissions">Commissions</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo $directory_level; ?>/adoptables">Adoptables</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo $directory_level; ?>/contact">Contact</a>
                                    </li>


                                    <?php
                                    //below are statements to populate the rest of the menu.
                                    if (!array_key_exists("is_admin", $_SESSION)) {
                                        $_SESSION["is_admin"] = 0;
                                    }

                                    if (array_key_exists("logged_in", $_SESSION)) {

                                        if ($_SESSION["logged_in"] == 1) {
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link" href="<?php echo $page_name; ?>?info=logout">Log-out</a>
                                            </li>
                                        <?php }
                                    }
                                    if ($_SESSION["is_admin"] == 1 && !$edit_disabled && !$protected_page) { ?>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="<?php echo $directory_level; ?>/scripts/php/edit-element?add=<?php echo $page_name; ?>">Add
                                                Content</a>
                                        </li>

                                        <?php

                                        echo ($is_admin ? "<script src=\"$directory_level/scripts/js/site-edit/edit-buttons.js\" defer></script>" : null);

                                        ?>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="<?php echo "$page_name?edit=0&info=disabledEdit"; ?>">Disable Edit
                                                Mode</a>
                                        </li>
                                        <!-- disabled mode goes here -->
                                        <?php
                                    } else if ($_SESSION["is_admin"] == 1 && $edit_disabled && !$protected_page) { ?>

                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="<?php echo "$page_name?edit=1&info=enabledEdit"; ?>">Enable Edit Mode</a>
                                            </li>

                                    <?php }
                                    ?>

                                    <?php if (array_key_exists("is_admin", $_SESSION)) {

                                        if ($_SESSION["is_admin"] == 1) {
                                            ?>

                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="<?php echo $directory_level; ?>/scripts/php/settings">Settings</a>
                                            </li>

                                        <?php }
                                    } else {
                                        $_SESSION["is_admin"] = 0;
                                    } ?>

                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo $directory_level; ?>/about">About Me</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
        <main class="row mt-0">

            <?php

            echo "
        <script>
            addLink('headerContainer', '$directory_level/index');
        </script>";

            echo isset($append_from_get) ? $append_from_get : null;
            return $directory_level;
    }
    ?>