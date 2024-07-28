<?php

function populateContainer($db, $container_id = "", $finished_content = "", $is_include = false) {
    $page_name = !$is_include ? (str_replace(".php", "", basename($_SERVER["PHP_SELF"]))) : "include";
    $sql = "select * from element where element_type = 'h3' and element_page = :element_page";

    $statement = $db->prepare($sql);
    $statement->bindValue(":element_page", $page_name);

    if ($statement->execute()) {
        $elements = $statement->fetchAll();
        $statement->closeCursor();

        foreach($elements as $element) {
            
        }
    }
}












// class NewClass {

//     //Properties and Methods goes here
//     public $info = "This is some info";

// }

// $object = new NewClass;
// var_dump($object);
