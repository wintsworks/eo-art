<?php
include("./php/includes/header.php");
$directory_level = populateHeader(".");
?>
<section class="col-sm-12">

    <?php renderContent($directory_level, $is_admin, $db); ?>

    <div class="mt-3 col-sm-12">

        <?php if ($is_admin) { ?>
            <div id="accordion">
                <div class="card-header" id="commissionEntries">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                            aria-controls="entries">
                            Commission Form Entries
                        </button>
                    </h5>
                </div>
                <div id="entries" class="col-sm-12 p-1 mt-3 collapse show" aria-labelledby="commissionEntries" data-parent="#accordion">
                    <div class="card-body">
                        <?php

                        $sql = "select * from commissions";
                        $statement = $db->prepare($sql);

                        if ($statement->execute()) {
                            $commissions_grab = $statement->fetchAll();
                            $statement->closeCursor();

                            $success = true;
                        } else {
                            $success = false;
                        }

                        if ($success) {
                            foreach ($commissions_grab as $commissions) { ?>

                                <div class="user-entries">
                                    <h5>Name:
                                        <?php echo $commissions["name"]; ?>
                                    </h5>
                                    <p>Email:
                                        <?php echo $commissions["email"]; ?>
                                    </p>
                                    <p>Commission Type:
                                        <?php echo $commissions["commission_type"]; ?>
                                    </p>

                                    <!-- Delete Button -->
                                    <form action="./scripts/php/process-form" method="POST">
                                        <input type="hidden" name="edit_type" value="delete">
                                        <input type="hidden" name="commissions_id"
                                            value="<?php echo $commissions["commissions_id"]; ?>">
                                        <input type="hidden" name="page" value="<?php echo $page_name; ?>">
                                        <input class="edit-entry" type="submit" value="Delete">
                                    </form>
                                    <!-- /Delete Button -->

                                    <!-- Take Commission Button -->
                                    <form action="./scripts/php/process-form" method="POST">
                                        <input type="hidden" name="edit_type" value="delete">
                                        <input type="hidden" name="commissions_id"
                                            value="<?php echo $commissions["commissions_id"]; ?>">
                                        <input type="hidden" name="page" value="<?php echo $page_name; ?>">
                                        <input class="edit-entry" type="submit" value="Take Commission">
                                    </form>
                                    <!-- /Take Commission Button -->

                                    <hr>

                                    <p>Details: <br><br>
                                        <?php echo $commissions["details"]; ?>
                                    </p>

                                </div>


                            <?php }
                        } ?>
                    </div>
                </div>
            </div>

        <?php } else { ?>

            <hr>

            <p><a href="tos">Click here</a> for my terms of service.</p>

            <form action="<?php echo $directory_level; ?>/scripts/php/process-form" method="POST">

                <label class="form-label">Your Name</label>
                <input type="text" name="name" class="form-control" placeholder="Name/Character Name" required>

                <label class="form-label">Your Email</label>
                <input type="email" name="email" class="form-control" placeholder="john@email.com" required>

                <label class="form-label">Type of Commission</label>
                <select name="commission_type" id="commission_type" class="form-select" required>
                    <?php
                    $sql = "select * from settings where settings_id = 0";
                    $statement = $db->prepare($sql);

                    if ($statement->execute()) {
                        $options = $statement->fetchAll();
                        $statement->closeCursor();
                        foreach ($options as $option) {
                            $values = explode(", ", $option["commission_select"]);
                            ?>
                            <script>
                                $(document).ready(function () {

                                    let values = <?php echo json_encode($values); ?>;

                                    for (value of values) {
                                        $("#commission_type").append(`<option value='${String(value)}'>${String(value)}</option>`);
                                        console.log(value);
                                    }
                                });
                            </script>
                            <?php
                        }
                    } ?>
                </select>


                <label class="form-label mt-3">Details</label>
                <textarea name="details" id="details" class="form-control"
                    placeholder="Information about your character, pose suggestions, free reign, any information you want me to know."
                    required></textarea>

                <input type="hidden" name="page" value="<?php echo $page_name; ?>">

                <input type="submit" value="Send Message" class="form-control mt-3">
            </form>

        <?php } ?>
    </div>

</section>
<?php include("./php/includes/footer.php"); ?>