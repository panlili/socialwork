<?php

class YoufuModel extends RelationModel {

    public $_link = array(
        "Citizen" => array(
            "class_name" => "Citizen",
            "mapping_name" => "citizen",
            "foreign_key" => "citizen_id",
            "mapping_type" => BELONGS_TO,
        ),
        "House" => array(
            "class_name" => "House",
            "mapping_name" => "house",
            "foreign_key" => "house_id",
            "mapping_type" => BELONGS_TO,
        ),
    );

}

?>
