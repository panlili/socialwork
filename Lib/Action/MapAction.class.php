<?php

class MapAction extends BaseAction {

    public function index() {
        $mapset = D("map_set");
        $data = $mapset->select();
        $this->assign('mapset', $data[0]);
        $this->display();
    }

   

}

?>
