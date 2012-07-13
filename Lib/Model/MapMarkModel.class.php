<?php


class MapMarkModel extends RelationModel{
    //和yard的一一对应关系
     public $_link = array(
        "Yard" => array(
            "class_name" => "Yard", //class_name要关联的模型类名
            "mapping_name" => "yard", //关联的映射名称，用于获取数据用
            "foreign_key" => "target", //关联的外键名称
            "mapping_type" => HAS_ONE, //映射类型
        ),
    );
}
?>
