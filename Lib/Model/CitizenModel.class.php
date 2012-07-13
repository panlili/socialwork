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
    );

}

?>
