<?php

class YardModel extends RelationModel {

    public $_link = array(
        //belongs to street 
        "Street" => array(
            "class_name" => "Street",
            "mapping_name" => "street",
            "foreign_key" => "street_id",
            "mapping_type" => BELONGS_TO,
        ),
        //has many house
        "House" => array(
            "class_name" => "House",
            "mapping_name" => "house",
            "foreign_key" => "yard_id",
            "mapping_type" => HAS_MANY,
        ),
       
         //has many yardadmin
        "Yardadmin" => array(
            "class_name" => "Yardadmin",
            "mapping_name" => "yardadmin",
            "foreign_key" => "yard_id",
            "mapping_type" => HAS_MANY,
        ),
    );

}

?>
