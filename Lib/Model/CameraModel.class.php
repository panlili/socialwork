<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class CameraModel extends RelationModel{
    public $_link = array(
        //belongs to House
        "Yard" => array(
            "class_name" => "Yard",
            "mapping_name" => "yard",
            "foreign_key" => "yard_id",
            "mapping_type" => BELONGS_TO,
        ),
    );

}
?>
