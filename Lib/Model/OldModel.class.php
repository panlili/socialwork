<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class OldModel extends RelationModel{
    protected $_auto = array(
        array('collection_date', 'getOnlyDate', Model::MODEL_INSERT, 'function'),
    );

    protected function _before_write(&$data) {
        parent::_before_write($data);
        $data["birthday"] = getBirthdayByIdCard($data["id_card"]);
        $data["sex"] = getSexByIdCard($data["id_card"]);
    }
    
}
?>
