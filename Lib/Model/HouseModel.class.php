<?php

class HouseModel extends RelationModel {

    protected $_auto = array(
        array('collection_date', 'getOnlyDate', Model::MODEL_INSERT, 'function'),
    );

    protected function _before_insert(&$data) {
        parent::_before_insert($data);

        if (!empty($data["is_floor"])) {
            $data["address"] = D("Yard")->where("id='$data[yard_id]'")->getField('name');
            $data["address"] .= $data["address_other"] ? "附" . $data["address_other"] . "号" : "";
        } else {
            $data["address"] = D("Yard")->where("id='$data[yard_id]'")->getField("name");
            $data['address'] .= $data['address_1'] ? $data['address_1'] . "栋" : "";
            $data['address'] .= $data['address_2'] ? $data['address_2'] . "单元" : "";
            $data['address'] .= $data['address_3'] ? $data['address_3'] . "楼" : "";
            $data['address'] .= $data['address_4'] ? $data['address_4'] . "号" : "";
        }
    }

    protected function _before_update(&$data, $options) {
        parent::_before_update($data, $options);
        $data['is_free'] = null == $data['is_free'] ? '否' : '是';
        $data['is_lowrent'] = null == $data['is_lowrent'] ? '否' : '是';
        $data['is_afford'] = null == $data['is_afford'] ? '否' : '是';
        $data['is_taiwan'] = null == $data['is_taiwan'] ? '否' : '是';
        $data['is_army'] = null == $data['is_army'] ? '否' : '是';
        $data['is_fuel'] = null == $data['is_fuel'] ? '否' : '是';
        $data['is_fit'] = null == $data['is_fit'] ? '否' : '是';

        if (!empty($data["is_floor"])) {
            //if is floor
            $data["address"] = D("Yard")->where("id='$data[yard_id]'")->getField('name');
            $data["address"] .= $data["address_other"] ? "附" . $data["address_other"] . "号" : "";
            $data["address_1"] = $data["address_2"] = $data["address_3"] = $data["address_4"] = "";
        } else {
            //not floor
            $data["address"] = D("Yard")->where("id='$data[yard_id]'")->getField('name');
            $data['address'] .= $data['address_1'] ? $data['address_1'] . "栋" : "";
            $data['address'] .= $data['address_2'] ? $data['address_2'] . "单元" : "";
            $data['address'] .= $data['address_3'] ? $data['address_3'] . "楼" : "";
            $data['address'] .= $data['address_4'] ? $data['address_4'] . "号" : "";
            $data['address_other'] = "";
            $data['is_floor'] = '否';
        }
    }

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
        //has one owner
        "Owner" => array(
            "mapping_type" => HAS_ONE,
            "class_name" => "Owner",
            "foreign_key" => "house_id",
            "mapping_name" => "owner",
        ),
        //has one youfu
        "Youfu" => array(
            "class_name" => "Youfu",
            "mapping_name" => "youfu",
            "foreign_key" => "house_id",
            "mapping_type" => HAS_ONE,
        ),
    );

}

?>
