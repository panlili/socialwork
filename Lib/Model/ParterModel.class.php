<?php

class ParterModel extends RelationModel {

    public $_link = array(
        //belongs to party 
        "Party" => array(
            "class_name" => "Party", 
            "mapping_name" => "party",
            "foreign_key" => "party_id", 
            "mapping_type" => BELONGS_TO, 
        ),
    );
}
?>