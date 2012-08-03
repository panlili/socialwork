<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YoufuModel
 *
 * @author Administrator
 */
class YoufuModel extends RelationModel{
    //put your code here
    public $_link = array(
         "Citizen" => array(
            "class_name" => "Citizen",
            "mapping_name" => "citizen",
            "foreign_key" => "youfu_id",
            "mapping_type" => HAS_ONE,
        ),
        "House" => array(
            "class_name" => "House",
            "mapping_name" => "house",
            "foreign_key" => "youfu_id",
            "mapping_type" => HAS_ONE,
        ),
    );
}

?>
