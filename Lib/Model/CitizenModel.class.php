<?php

class CitizenModel extends RelationModel {

    protected $_auto = array(
        array('collection_date', 'getOnlyDate', Model::MODEL_INSERT, 'function'),
    );
    public $_link = array(
        //belongs to House
        "House" => array(
            "class_name" => "House",
            "mapping_name" => "house",
            "foreign_key" => "house_id",
            "mapping_type" => BELONGS_TO,
        ),
        //has_one youfu
        "Youfu"=>array(
            "class_name" => "Youfu",
            "mapping_name" => "youfu",
            "foreign_key" => "citizen_id",
            "mapping_type" => HAS_ONE,
        ),
    );

}

?>
