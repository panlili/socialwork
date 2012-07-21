<?php

class PartyModel extends RelationModel {

    public $_link = array(
        //has many parter 
        "Parter" => array(
            "class_name" => "Parter",
            "mapping_name" => "parter", 
            "foreign_key" => "party_id",
            "mapping_type" => HAS_MANY,
        ),
    );

}

?>