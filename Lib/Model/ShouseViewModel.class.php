<?php

class ShouseViewModel extends ViewModel {

    public $viewFields = array(
        'House' => array('id', 'yard_id', 'address', 'is_free', 'is_fit', 'is_lowrent',
            'is_floor', 'is_afford', 'is_taiwan', 'is_army', 'address_1', 'address_2', 'address_3', 'address_4'),
        'Yard' => array('street_id', 'name' => 'yard_name', 'address' => 'yard_address', '_on' => 'House.yard_id=Yard.id'),
        'Street' => array('name' => 'street_name', '_on' => 'Yard.street_id=Street.id'),
    );

}

?>
