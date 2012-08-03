<?php

class HouseModel extends RelationModel {

    protected $_auto = array(
        array('collection_date', 'getOnlyDate', Model::MODEL_INSERT, 'function'),
    );
    public $_link = array(
        //belongs to Yard 
        "Yard" => array(
            "class_name" => "Yard",
            "mapping_name" => "yard",
            "foreign_key" => "yard_id",
            "mapping_type" => BELONGS_TO,
        ),
        //has many citizen
        "Citizen" => array(
            "class_name" => "Citizen",
            "mapping_name" => "citizen",
            "foreign_key" => "house_id",
            "mapping_type" => HAS_MANY,
        ),
        "Youfu"=>array(
            "class_name" => "Youfu",
            "mapping_name" => "youfu",
            "foreign_key" => "youfu_id",
            "mapping_type" => BELONGS_TO,
//            "as_fields"=>"is_taishu",
        ),
    );

}

?>
