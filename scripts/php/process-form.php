<?php
include("../../php/includes/header.php");
$directory_level = populateHeader("../..");
?>

<section class="col-sm-12">

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $page_origination = $_POST["page"] ?? trimEntry($_POST["page"]);

        if (isset($page_origination)) {

            switch ($page_origination) {
                case "contact":
                    if ($is_admin) {
                        $contact = getValue(["contact_id", "edit_type"]);

                        if (!$contact["missing_variables"]) {
                            $sql = "delete from contact where contact_id = :contact_id";
                            $statement = $db->prepare($sql);

                            $statement->bindValue(":contact_id", $contact["contact_id"]);

                            if ($statement->execute()) {
                                $statement->closeCursor();

                                echo "<script type='text/javascript'>document.location.href='$directory_level/$page_origination?info=successDeleteEntry';</script>";
                            } else {
                                echo "<script type='text/javascript'>document.location.href='$directory_level/$page_origination?info=failDeleteEntry';</script>";
                            }
                        }
                    } else {
                        $contact = getValue(["name", "email", "message"]);
                        if (!$contact["missing_variables"]) {

                            if (strlen($contact["message"]) <= 1000 && strlen($contact["name"]) <= 75 && strlen($contact["email"]) <= 75) {
                                $sql = "insert into contact (name, email, message)
                            values (:name, :email, :message)";

                                $statement = $db->prepare($sql);
                                $statement->bindValue(":name", $contact["name"]);
                                $statement->bindValue(":email", $contact["email"]);
                                $statement->bindValue(":message", $contact["message"]);

                                if ($statement->execute()) {
                                    $statement->closeCursor();
                                    
                                    echo "<h3>Thank you.</h3> <hr> <p>Message received, expect a response shortly!</p>";
                                } else {
                                    $success = false;

                                }
                            } else {
                                echo "<h3>Error</h3> <hr> <p style='color: darkred;'>Too many characters in a field.</p>";
                            }
                        } else {
                            echo "<h3>Error</h3>
                        <hr><p style='color: darkred;'>Error processing contact request.</p></section>";
                        }
                    }
                    break;

                case "commissions":

                    if ($is_admin) {
                        $commissions = getValue(["commissions_id", "edit_type"]);

                        if (!$commissions["missing_variables"]) {
                            $sql = "delete from commissions where commissions_id = :commissions_id";
                            $statement = $db->prepare($sql);

                            $statement->bindValue(":commissions_id", $commissions["commissions_id"]);

                            if ($statement->execute()) {
                                $statement->closeCursor();

                                echo "<script type='text/javascript'>document.location.href='$directory_level/$page_origination?info=successDeleteEntry';</script>";
                            } else {
                                echo "<script type='text/javascript'>document.location.href='$directory_level/$page_origination?info=failDeleteEntry';</script>";
                            }
                        }
                    } else {
                        $commissions = getValue(["name", "email", "commission_type", "details"]);
                        if (!$commissions["missing_variables"]) {
                            if (strlen($commissions["name"]) <= 75 && strlen($commissions["email"]) <= 75 && strlen($commissions["commission_type"]) <= 75 && strlen($commissions["details"]) <= 500) {

                                $sql = "insert into commissions (name, email, commission_type, details)
                                    values (:name, :email, :commission_type, :details)";

                                $statement = $db->prepare($sql);

                                $statement->bindValue(":name", $commissions["name"]);
                                $statement->bindValue(":email", $commissions["email"]);
                                $statement->bindValue(":commission_type", $commissions["commission_type"]);
                                $statement->bindValue(":details", $commissions["details"]);

                                if ($statement->execute()) {
                                    $statement->closeCursor();
                                    
                                    echo "<h3>Thank you.</h3> <hr> <p>Commission request received, expect a response shortly!</p>";
                                } else {
                                    $success = false;
                                }
                            } else {
                                echo "<h3>Error</h3> <hr> <p style='color: darkred;'>Too many characters in a field.</p>";
                            }
                        }
                    }
                    break;

                default:
                    echo "<h3>Error</h3>
                        <hr><p style='color: darkred;'>Error processing request for: $page_origination.</p></section>";
                    break;
            }
        } else {
        }
    } else {
        header("Location: $directory_level/index");
    }
    ?>

</section>

<?php include("$directory_level/php/includes/footer.php"); ?>