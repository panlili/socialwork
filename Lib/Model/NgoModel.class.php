<?php

class NgoModel extends RelationModel {

    protected $_auto = array(
        array('create_time', 'getTime', Model::MODEL_INSERT, 'function'),
    );
    protected $_validate = array(
        array("name", "", "此社会组织已存在", Model::EXISTS_VAILIDATE, "unique", Model:: MODEL_BOTH),
        array("name", "require", "社会组织名不能为空", Model::EXISTS_VAILIDATE, "", Model:: MODEL_BOTH),
        array("chairman", "require", "负责人不能为空", Model::EXISTS_VAILIDATE, "", Model:: MODEL_BOTH),
    );
}
?>
