<?php
include("./php/includes/header.php");
$directory_level = populateHeader(".");
?>
<section class="col-sm-12">

    <?php renderContent($directory_level, $is_admin, $db); ?>

    <div class="mt-3 col-sm-12">
        <?php if ($is_admin) { ?>

            <h3>Contact Form Entries</h3>
            <hr>

            <div class="col-sm-12 p-1 mt-3">
                <?php

                $sql = "select * from contact";
                $statement = $db->prepare($sql);

                if ($statement->execute()) {
                    $contacts = $statement->fetchAll();
                    $statement->closeCursor();

                    $success = true;
                } else {
                    $success = false;
                }

                if ($success) {
                    foreach ($contacts as $contact) { ?>

                        <div class="user-entries">
                            <h5>Name: <?php echo $contact["name"]; ?></h5>
                            <p>Email: <?php echo $contact["email"]; ?></p>
                            <form action="./scripts/php/process-form" method="POST">
                                <input type="hidden" name="edit_type" value="delete">
                                <input type="hidden" name="contact_id" value="<?php echo $contact["contact_id"]; ?>">
                                <input type="hidden" name="page" value="<?php echo $page_name; ?>">
                                <input class="edit-entry" type="submit" value="Delete">
                            </form>
                            <hr>

                            <p>Message:<br><br> <?php echo $contact["message"]; ?></p>

                        </div>


                <?php }
                } ?>
            </div>

        <?php } else { ?>
            
            <hr>

            <form action="<?php echo $directory_level; ?>/scripts/php/process-form" method="POST">

                <label class="form-label">Your Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Name/Character Name">

                <label class="form-label">Your Email</label>
                <input type="email" name="email" class="form-control" required placeholder="john@email.com">

                <label class="form-label">Your Message</label>
                <textarea name="message" id="message" class="form-control" required placeholder="Type message here."></textarea>

                <input type="hidden" name="page" value="<?php echo $page_name; ?>">

                <input type="submit" value="Send Message" class="form-control mt-3">
            </form>

        <?php } ?>
    </div>

</section>
<?php include("./php/includes/footer.php"); ?>