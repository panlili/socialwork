<?php

class StreetModel extends RelationModel {

    //自动验证,验证因子格式： array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        array("name", "", "街道已存在", Model::EXISTS_VAILIDATE, "unique", Model:: MODEL_BOTH),
        array("name", "require", "街道名不能为空", Model::EXISTS_VAILIDATE, "", Model:: MODEL_BOTH), //当用户输入空格是怎么办?只能放在页面验证?
    );
    protected $_link = array(
        //has many organization
        "Organization" => array(
            "class_name" => "Organization",
            "mapping_name" => "organization",
            "foreign_key" => "street_id",
            "mapping_type" => HAS_MANY,
        ),
        //has many store
        "Store" => array(
            "class_name" => "Store",
            "mapping_name" => "store",
            "foreign_key" => "street_id",
            "mapping_type" => HAS_MANY,
        ),
        //has many yard
        "Yard" => array(
            "class_name" => "Yard",
            "mapping_name" => "yard",
            "foreign_key" => "street_id",
            "mapping_type" => HAS_MANY,
        ),
    );

}

?>
