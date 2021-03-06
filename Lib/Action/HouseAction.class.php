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

        $id = $this->_get("id");
        $m_house = D("House");

        $data = $m_house->relation(array("yard", "youfu", "owner"))->find($id);
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("House/index");
        }

        //取得与此房屋相关的owner and youfu数据的主id,在edit.html中赋予表单才能在update中直接创建owner和youfu对象后更新
        $m_youfu = D("Youfu");
        $m_owner = D("Owner");
        $youfu_id = $m_youfu->where("house_id=$id")->getField("id");
        $owner_id = $m_owner->where("house_id=$id")->getField("id");
        //只有有对应产权人记录才赋值

        $m_addon = D("Addon");
        $addon = $m_addon->where("house_id=" . $id)->find();
        $this->assign("photo", $addon["filepath"]);
        $this->assign(array("data" => $data, "youfu_id" => $youfu_id,
            "owner_id" => $owner_id, "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        $house_id = $_POST["id"];

        $m_house = D("House");
        $m_owner = D("Owner");
        $m_youfu = D("Youfu");
        $m_addon = D("Addon");

        $new_house = $m_house->create();
        $new_youfu = $m_youfu->create();
        $new_youfu["id"] = $_POST["youfu_id"];
        //当新增house时is_fit选得否，owner_id表单数据存在，修改时is_fit选得否更新owner表,选是则不用管owner表
        if (!empty($_POST["owner_id"]) && $_POST["is_fit"] === "否") {
            $new_owner = $m_owner->create();
            $new_owner["id"] = $_POST["owner_id"];
            $result2 = $m_owner->where("house_id=$house_id")->save($new_owner);
        }
        //当新增house时is_fit选得是是，那么owner_id表单数据为空，那么如果修改是is_fit仍为是则owner数据不用管，如为否则需要新增一条owner数据
        if (empty($_POST["owner_id"]) && $_POST["is_fit"] === "否") {
            $new_owner = $m_owner->create();
            $new_owner["house_id"] = $house_id;
            $result2 = $m_owner->add($new_owner);
        }

        $result1 = $m_house->where("id=$house_id")->save($new_house);
        $result3 = $m_youfu->where("house_id=$house_id")->save($new_youfu);

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
            $result4 = $m_addon->where("house_id=" . $house_id)->save($new_addon);
            //dump($result4);
            if ($result4 === 0) {
                $new_addon["house_id"] = $house_id;
                $m_addon->add($new_addon);
            }
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
        if ($_SESSION["right"] == "9" || $_SESSION["right"] == "1") {
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=houses.xls");
            $this->assign("list", session("list"));
            echo $this->fetch();
        } else {
            header("Content-Type:text/html; charset=utf-8");
            echo "您没有权限执行导出操作";
        }
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
        if ($_SESSION["right"] == "9" || $_SESSION["right"] == "1") {
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=citizens.xls");
            $this->assign("list", session("clist"));
            echo $this->fetch();
        } else {
            header("Content-Type:text/html; charset=utf-8");
            echo "您没有权限执行导出操作";
        }
    }

}

?>
