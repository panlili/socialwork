<?php

class HouseAction extends BaseAction {

    const ACTION_NAME = "房屋";

    //index方法,展示数据列表
    public function index() {
        $m_house = D("House");
        import("ORG.Util.Page");
        $count = $m_house->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $m_house->relation(array("yard", "citizen", "youfu"))->order("id desc,yard_id desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        $m_house = D("House");
        $id = $this->_get("id");
        $data = $m_house->relation(array("yard", "citizen", "owner", "youfu"))->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("House/index");
        }

        //当前房屋下的居民列表
        $m_citizen = D("Citizen");
        $list = $m_citizen->where(array("house_id" => $id))->order("id desc")->select();
        $this->assign(array("list" => $list));

        $m_addon = D("Addon");
        $addon = $m_addon->where("house_id=" . $id)->find();
        $this->assign("photo", $addon["filepath"]);

        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        $this->assign(array("page_place" => $this->getPagePlace("新建数据", self::ACTION_NAME)));
        $this->display();
    }

    public function add() {

        $m_house = D("House");
        $m_youfu = D("Youfu");
        $m_addon = D("Addon");

        if ($m_house->create()) {
            if ($new_house_id = $m_house->add()) {
                //人户不一致时产权人owner表的数据
                if ($_POST["is_fit"] === "否") {
                    $m_owner = D("Owner");
                    $new_owner = $m_owner->create();
                    $new_owner["house_id"] = $new_house_id;
                    $m_owner->add($new_owner);
                }
                //房屋优抚表的数据
                $new_youfu = $m_youfu->create();
                $new_youfu["house_id"] = $new_house_id;
                $m_youfu->add($new_youfu);
                if ($_FILES["addon"]["name"] != NULL) {
                    //添加附件数据
                    //导入图片上传类   
                    import("ORG.Net.UploadFile");
                    //实例化上传类   
                    $upload = new UploadFile();
                    $upload->maxSize = 3145728;
                    //设置文件上传类型   
                    $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
                    //设置文件上传位置   
                    $upload->savePath = "./Public/Uploads/house/"; //这里说明一下，由于ThinkPHP是有入口文件的，所以这里的./Public是指网站根目录下的Public文件夹   
                    //设置文件上传名(按照时间)   
                    $upload->saveRule = "time";
                    if (!$upload->upload()) {
                        $this->error($upload->getErrorMsg());
                    } else {
                        //上传成功，获取上传信息   
                        $info = $upload->getUploadFileInfo();
                    }
                    $new_addon = $m_addon->create();
                    $new_addon["house_id"] = $new_house_id;
                    $new_addon["yard_id"] = null;
                    $new_addon["citizen_id"] = null;
                    $new_addon["filepath"] = "house/" . $info[0]['savename'];    //文件路径
                    $m_addon->add($new_addon);
                }

                //所有数据添加正确
                session("action_message", "添加房屋数据成功！");
                $this->redirect("House/index");
            } else {
                session("action_message", "添加房屋数据失败！");
                $this->redirect("House/newone");
            }
        } else {
            session("action_message", $m_house->getError());
            $this->redirect("House/newone");
        }
    }

    public function delete() {
        $m_house = D("House");
        $m_addon = D("Addon");
        $id = $this->_get("id");
        if ($m_house->relation(array("youfu", "owner"))->delete($this->_get("id"))) {
            $m_addon->where("house_id=" . $id)->delete();
            session("action_message", "删除数据成功！");
            $this->redirect("House/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("House/index");
        }
    }

    //edit方法，显示编辑房屋信息的模板
    public function edit() {
        $m_house = D("House");
        $id=$this->_get("id");
        $data = $m_house->relation(array("yard", "youfu", "owner"))->find($this->_get("id"));
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("House/index");
        }
        $m_addon = D("Addon");
        $addon = $m_addon->where("house_id=" . $id)->find();
        $this->assign("photo", $addon["filepath"]);
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        //关联更新的bug在框架中一直存在，开发者也没有解决这个问题
        //自己根据逻辑手动关联操作效率更高
        $house_id = $_POST["id"];
        $m_house = D("House");
        $m_owner = D("Owner");
        $m_youfu = D("Youfu");
        $m_addon = D("Addon");
        //house自身数据，13fields
        $house["yard_id"] = $_POST["yard_id"];
        $house["contactor"] = $_POST["contactor"];
        $house["telephone"] = $_POST["telephone"];
        $house["is_floor"] = $_POST["is_floor"];
        $house["address_1"] = $_POST["address_1"];
        $house["address_2"] = $_POST["address_2"];
        $house["address_3"] = $_POST["address_3"];
        $house["address_4"] = $_POST["address_4"];
        $house["address_other"] = $_POST["address_other"];
        $house["is_fit"] = $_POST["is_fit"];
        $house["is_free"] = $_POST["is_free"];
        $house["rent"] = $_POST["rent"];
        $house["rent_tax"] = $_POST["rent_tax"];
        //owner表, 6fields
        $owner["name"] = $_POST["name"];
        $owner["idcard"] = $_POST["idcard"];
        $owner["marry_info"] = $_POST["marry_info"];
        $owner["education"] = $_POST["education"];
        $owner["mobile"] = $_POST["mobile"];
        $owner["nowaddress"] = $_POST["nowaddress"];
        //优抚表, 9fields
        $youfu["is_dibao"] = $_POST["is_dibao"];
        $youfu["dibao_jine"] = $_POST["dibao_jine"];
        $youfu["dibao_start_date"] = $_POST["dibao_start_date"];
        $youfu["is_jjsyf"] = $_POST["is_jjsyf"];
        $youfu["is_taishu"] = $_POST["is_taishu"];
        $youfu["is_junshu"] = $_POST["is_junshu"];
        $youfu["ranmei"] = $_POST["ranmei"];
        $youfu["is_lianzu"] = $_POST["is_lianzu"];
        $youfu["lianzu_address"] = $_POST["lianzu_address"];



        $result1 = $m_house->where("id=$house_id")->save($house);
        $result2 = $m_owner->where("house_id=$house_id")->save($owner);
        $result3 = $m_youfu->where("house_id=$house_id")->save($youfu);
        //dump($_FILES["addon"]["name"]);
        //if下面是判断提交上的文件是否是空，如果不为空就新传一个文件，同时在数据库中更新附件路径
        if ($_FILES["addon"]["name"] != NULL) {
            //添加附件数据
            //导入图片上传类   
            import("ORG.Net.UploadFile");
            //实例化上传类   
            $upload = new UploadFile();
            $upload->maxSize = 3145728;
            //设置文件上传类型   
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
            //设置文件上传位置   
            $upload->savePath = "./Public/Uploads/house/"; //这里说明一下，由于ThinkPHP是有入口文件的，所以这里的./Public是指网站根目录下的Public文件夹   
            //设置文件上传名(按照时间)   
            $upload->saveRule = "time";
            if (!$upload->upload()) {
                $this->error($upload->getErrorMsg());
            } else {
                //上传成功，获取上传信息   
                $info = $upload->getUploadFileInfo();
            }
            $new_addon = $m_addon->create();
            
            $new_addon["filepath"] = "house/" . $info[0]['savename'];    //文件路径
            $m_addon->where("house_id=".$house_id)->save($new_addon);
        }

        if ($result1 !== false && $result2 !== false && $result3 !== false) {
            session("action_message", "更新数据成功！");
            $this->redirect("House/$house_id");
        } else {
            session("action_message", "更新数据失败！");
            $this->redirect("House/$house_id");
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
