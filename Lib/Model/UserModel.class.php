<?php

//model of table:sjf_admin
class UserModel extends Model {

    //自动验证,验证因子格式： array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
    protected $_validate = array(
        array("username", "", "用户名已存在", Model::EXISTS_VAILIDATE, "unique", Model:: MODEL_BOTH),
        array("username", "require", "用户名不能为空", Model::EXISTS_VAILIDATE, "", Model:: MODEL_BOTH), //当用户输入空格是怎么办?只能放在页面验证?
        array("password", "require", "密码不能为空", Model::EXISTS_VAILIDATE, "", Model:: MODEL_BOTH),
        array("truename", "require", "姓名不能为空", Model::EXISTS_VAILIDATE, "", Model:: MODEL_BOTH),
    );
    //自动填充相关字段, array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
        array('password', 'md5', Model:: MODEL_INSERT, 'function'),
        array('registertime', 'getTime', Model::MODEL_INSERT, 'function'),
    );

}

?>
