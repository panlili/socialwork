<?php

class StoreModel extends RelationModel {

    //自动验证,验证因子格式： array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        array("name", "", "此店铺已存在", Model::EXISTS_VAILIDATE, "unique", Model:: MODEL_BOTH),
        array("name", "require", "店铺名不能为空", Model::EXISTS_VAILIDATE, "", Model:: MODEL_BOTH),
        array("address", "require", "地址不能为空", Model::EXISTS_VAILIDATE, "", Model:: MODEL_BOTH),
    );
    public $_link = array(
        //belongs to street 
        "Street" => array(
            "class_name" => "Street", //class_name要关联的模型类名
            "mapping_name" => "street", //关联的映射名称，用于获取数据用
            "foreign_key" => "street_id", //关联的外键名称
            "mapping_type" => BELONGS_TO, //映射类型
        ),
    );
    protected $_auto = array(
        array('create_time', 'getTime', Model::MODEL_INSERT, 'function'),
    );

}

?>
