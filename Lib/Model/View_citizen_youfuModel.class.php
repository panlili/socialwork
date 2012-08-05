<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View_citizen_youfuModel
 *
 * @author Administrator
 */
class View_citizen_youfuModel extends RelationModel {

    //put your code here
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
