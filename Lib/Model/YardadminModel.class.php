<?php

class YardadminModel extends Model {

    //belongs to yard
    public $_link = array(
        "Yard" => array(
            "class_name" => "Yard",
            "mapping_name" => "yard",
            "foreign_key" => "yard_id",
            "mapping_type" => BELONGS_TO,
        ),
    );

}

?>
