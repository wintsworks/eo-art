<?php
include("../../php/includes/header.php");
$directory_level = populateHeader("../..");

//checking if the keys exist, and the server request method.
if ($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("username", $_POST) && array_key_exists("password", $_POST) && array_key_exists("type", $_POST)) {
    $username = trimEntry($_POST["username"]);
    $password = trimEntry($_POST["password"]);
    $type = trimEntry($_POST["type"]);

    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    $success = true;

    $sql_test = "select * from user where username = :username";
    $statement_test = $db->prepare($sql_test);

    $statement_test->bindValue(":username", $username);

    if ($statement_test->execute()) {
        $results = $statement_test->fetchAll();
        $statement_test->closeCursor();

        $success = true;
    } else {
        $success = false;
    }

    $amount = 0;
    foreach ($results as $result) {
        $amount += 1;
    }
} else {
    $success = false;
    $type = "An Error Ocurred.";
    $info = "Please try again.";
}
?>

<section class="col-sm-12">

    <h3 id="processH3">Processing Login...</h3>

    <p><?php
        if (!is_bool($success)) {
        } else if ($success) {

            switch ($type) {
                case "create":

                    //checking the amount of results returned from sql query to check if username exists already.
                    if ($amount > 0) {
                        $success = false;
                        $type = "Username Already Exists.";
                        $info = "Please try another username that is not in use.";
                    } else {

                        $sql = "insert into user (username, password)
                            values
                            (:username, :password)";
                        $statement = $db->prepare($sql);
                        $statement->bindValue(":username", $username);
                        $statement->bindValue(":password", $hashed_pass);

                        if ($statement->execute()) {
                            $statement->closeCursor();

                            $type = "Account Creation Successful.";
                            $info = "Please contact webmaster to grant administrator privileges to your new account.";
                            $success = true;
                        } else {
                            $success = false;
                            $type = "Account Creation Unsuccessful.";
                            $info = "Could not create the account. Please contact administrator.";
                        }
                    }
                    break;
                case "login":

                    //Checking Login, 0 results == no database entry with that username. 
                    $sql = "select * from user where username = :username";

                    $statement = $db->prepare($sql);

                    $statement->bindValue(":username", $username);

                    if ($statement->execute()) {
                        $userGrab = $statement->fetchAll();
                        $statement->closeCursor();

                        $resultss = 0;

                        foreach ($userGrab as $user) {
                            $password_success = password_verify($password, $user["password"]);
                            $resultss = 1;
                            if ($password_success) {
                                $_SESSION["is_admin"] = ($user["is_admin"] == "1") ? true : false;
                                $_SESSION["username"] = $user["username"];
                                $_SESSION["logged_in"] = true;

                                $type = "Login Successful.";
                                $info = "You'll be redirected automatically within 3 seconds...";
                                $success = true;
                            } else {
                                $success = false;
                                $type = "Unsuccessful Login.";
                                $info = "Incorrect Password.";
                            }
                        }

                        //Displaying the amount of entries that returned for the result set. 
                        if ($resultss == 0) {
                            $success = false;
                            $type = "Unsuccessful Login.";
                            $info = "That username does not exist.";
                        }
                    } else {
                        echo '<p style="color: red;">Error occurred. Please try again later.</p>';
                    }
                    break;
            }
        }

        ?></p>

    <p id="info"></p>

    <script>
        //Changing document information based on data entered. 
        $(document).ready(function() {
            let success = <?php echo json_encode($success); ?>;
            let type = <?php echo json_encode($type); ?>;
            let info = <?php echo json_encode($info); ?>;

            $("#processH3").css("font-weight", "bolder");

            if (success) {
                $("#processH3").html(type);
                $("#processH3").css("color", "darkgreen");

                setTimeout(() => {
                    window.location.replace("<?php echo $directory_level; ?>/index");
                }, 3000);

            } else {
                $("#processH3").html(type);
                $("#processH3").css("color", "darkred");

                setTimeout(() => {
                    window.location.replace("<?php echo $directory_level; ?>/index");
                }, 3000);
            }

            $("#info").html(info);
            $("#info").html()

        });
    </script>
</section>

<?php include("$directory_level/php/includes/footer.php"); ?>