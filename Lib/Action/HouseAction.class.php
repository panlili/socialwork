<?php

class HouseAction extends BaseAction {

    const ACTION_NAME = "房屋";

    //index方法,展示数据列表
    public function index() {
        $House = D("House");
        import("ORG.Util.Page");
        $count = $House->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $House->relation(array("yard", "citizen"))->order("id desc,yard_id desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        $House = D("House");
        $id = $this->_get("id");
        $data = $House->relation(array("yard", "citizen"))->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("House/index");
        }

        //当前房屋下的居民列表
        $Citizen = D("Citizen");
        $list = $Citizen->where(array("house_id" => $id))->order("id desc")->select();
        $this->assign(array("list" => $list));

        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        $this->assign(array("page_place" => $this->getPagePlace("新建数据", self::ACTION_NAME)));
        $this->display();
    }

    public function add() {
//        $House = D("House");
//        if ($House->create()) {
//            $data = $House->add();
//            if (FALSE !== $data) {
//                session("action_message", "添加数据成功");
//                $this->redirect("House/index");
//            } else {
//                session("action_message", "添加新数据失败！");
//                $this->redirect("House/newone");
//            }
//        } else {
//            session("action_message", $House->getError());
//            $this->redirect("House/newone");
//        }
        dump($_POST);
    }

    public function delete() {
        $id = $this->_get("id");
        $House = D("House");
        if ($House->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("House/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("House/index");
        }
    }

    //edit方法，显示编辑房屋信息的模板
    public function edit() {
        $id = $this->_get("id");
        $House = D("House");
        $data = $House->relation("yard")->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("House/index");
        }

        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        $id = $this->_post("id");
        $House = D("House");
        if ($newdata = $House->create()) {

            $data = $House->save($newdata);
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("House/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/House/edit/$newdata[id]");
            }
        } else {
            session("action_message", $House->getError());
            $this->redirect("/House/edit/$id");
        }
    }

    private function Houses($yardid, $address_1, $address_2, $address_3, $lowrent, $fuel, $taiwan, $army) {
        $house = D("House");
        $list = null;
        if ($lowrent != 1 && $fuel != 1 && $taiwan != 1) {
            if ($address_3) {
                $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                            "address_2" => $address_2, "address_3" => $address_3))->select();
            } else {
                if ($address_2) {
                    $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                                "address_2" => $address_2))->select();
                } else {
                    if ($address_1) {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1))->select();
                    } else {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid))->select();
                    }
                }
            }
        }

        if ($lowrent == 1) {
            //廉租房条件存在
            if ($address_3) {
                $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                            "address_2" => $address_2, "address_3" => $address_3,
                            "is_lowrent" => "是"))->select();
            } else {
                if ($address_2) {
                    $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                                "address_2" => $address_2,
                                "is_lowrent" => "是"))->select();
                } else {
                    if ($address_1) {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid,
                                    "address_1" => $address_1, "is_lowrent" => "是"))->select();
                    } else {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid,
                                    "is_lowrent" => "是"))->select();
                    }
                }
            }
        }

        if ($fuel == 1) {
            //燃煤补贴条件存在
            if ($address_3) {
                $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                            "address_2" => $address_2, "address_3" => $address_3,
                            "is_fuel" => "是"))->select();
            } else {
                if ($address_2) {
                    $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                                "address_2" => $address_2,
                                "is_fuel" => "是"))->select();
                } else {
                    if ($address_1) {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid,
                                    "address_1" => $address_1, "is_fuel" => "是"))->select();
                    } else {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid,
                                    "is_fuel" => "是"))->select();
                    }
                }
            }
        }

        if ($taiwan == 1) {
            //台属房屋
            if ($address_3) {
                $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                            "address_2" => $address_2, "address_3" => $address_3,
                            "is_taiwan" => "是"))->select();
            } else {
                if ($address_2) {
                    $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                                "address_2" => $address_2,
                                "is_taiwan" => "是"))->select();
                } else {
                    if ($address_1) {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid,
                                    "address_1" => $address_1, "is_taiwan" => "是"))->select();
                    } else {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid,
                                    "is_taiwan" => "是"))->select();
                    }
                }
            }
        }

        if ($army == 1) {
            //军属房屋
            if ($address_3) {
                $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                            "address_2" => $address_2, "address_3" => $address_3,
                            "is_army" => "是"))->select();
            } else {
                if ($address_2) {
                    $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid, "address_1" => $address_1,
                                "address_2" => $address_2,
                                "is_army" => "是"))->select();
                } else {
                    if ($address_1) {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid,
                                    "address_1" => $address_1, "is_army" => "是"))->select();
                    } else {
                        $list = $house->relation(array("yard", "citizen"))->where(array("yard_id" => $yardid,
                                    "is_army" => "是"))->select();
                    }
                }
            }
        }

        session("list", $list);
        return $list;
    }

    public function table() {
        header("content-type:text/html;charset=utf-8");
        $yardid = $this->_get("yardid");
        $address_1 = $this->_get("address_1");
        $address_2 = $this->_get("address_2");
        $address_3 = $this->_get("address_3");
        $lowrent = $this->_get("lowrent");
        $fuel = $this->_get("fuel");
        $taiwan = $this->_get("taiwan");
        $army = $this->_get("army");
        //调用私有方法后设置了session
        $this->assign("list", $this->Houses($yardid, $address_1, $address_2, $address_3, $lowrent, $fuel, $taiwan, $army));
        $this->display();
    }

    //统计表格中不同层次房屋的列表
    public function toexcel() {
        //$yardid = $this->_get("id");
        //$list = D("House")->relation(array("yard", "citizen"))->where("yard_id='$yardid'")->select();
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=houses.xls");
        $this->assign("list", session("list"));
        echo $this->fetch();
    }

    public function ctable() {
        header("content-type:text/html;charset=utf-8");
        $yardid = $this->_get("yardid");
        $address_1 = $this->_get("address_1");
        $address_2 = $this->_get("address_2");
        $address_3 = $this->_get("address_3");
        $houses = $this->Houses($yardid, $address_1, $address_2, $address_3);
        $citizens = array();
        foreach ($houses as $house) {
            foreach ($house["citizen"] as $c) {
                $c["houseaddress"] = $house["address"];
                array_push($citizens, $c);
            }
        }
        session("clist", $citizens);
        $this->assign("list", $citizens);
        $this->display();
    }

    //统计表格中不同层次居民的列表
    public function toexcel2() {
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=citizens.xls");
        $this->assign("list", session("clist"));
        echo $this->fetch();
    }

}

?>
