<?php

class OwnerModel extends RelationModel {

    public $_link = array(
        "House" => array(
            "mapping_type" => BELONGS_TO,
            "mapping_name" => "house",
            "class_name" => "House",
            "foreign_key" => "house_id",
        ),
    );

    protected function _before_insert(&$data, $options) {
        parent::_before_insert($data, $options);
        $data["birthday"] = getBirthdayByIdCard($data["idcard"]);
        $data["sex"] = getSexByIdCard($data["idcard"]);
    }

    protected function _before_update(&$data, $options) {
        parent::_before_update($data, $options);
        $data["birthday"] = getBirthdayByIdCard($data["idcard"]);
        $data["sex"] = getSexByIdCard($data["idcard"]);
    }

}

?>
