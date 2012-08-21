<?php

class YardAction extends BaseAction {

    const ACTION_NAME = "院落";

    //index方法,展示数据列表
    public function index() {
        //将表yard用M函数赋值给list变量，在index模版中显示.
        $Yard = D("Yard");
        import("ORG.Util.Page");
        $count = $Yard->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $Yard->relation("street")->order("street_id desc,id desc")->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function map() {
        $this->display();
    }

    public function read() {
        $Yard = D("Yard");
        $Yardadmin = D("Yardadmin");
        $id = $this->_get("id");
        $data = $Yard->relation("street")->where(array("id" => $id))->find();
        //院落管理人员情况
        $admindata = $Yardadmin->where(array("type" => "院落管理", "yard_id" => $id))->select();
        //院落党支部情况
        $partydata = $Yardadmin->where(array("type" => "院落党支部", "yard_id" => $id))->select();
        //院落环治工作情况
        $cleandata = $Yardadmin->where(array("type" => "环治工作", "yard_id" => $id))->select();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Yard/index");
        }

        //当前院落下的house列表
        $House = D("House");
        import("ORG.Util.Page");
        $housecount = $House->where(array("yard_id" => $id))->count();
        $p = new Page($housecount, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $House->where(array("yard_id" => $id))->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign(array("page" => $page, "list" => $list));

        $this->assign(array("data" => $data,
            "admindata" => $admindata, "partydata" => $partydata, "cleandata" => $cleandata,
            "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        $this->assign(array("page_place" => $this->getPagePlace("新建数据", self::ACTION_NAME)));
        $this->display();
    }

    public function add() {
        $Yard = D("Yard");
        $streetid = $this->_post("street_id");
        if ($newdata = $Yard->create()) {
            $newdata['address'] = D("Street")->where("id='$streetid'")->getField("name");
            $newdata['address'] .= $this->_post("address");
            $data = $Yard->add($newdata);
            if (false !== $data) {
                session("action_message", "添加数据成功");
                $this->redirect("Yard/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("Yard/newone");
            }
        } else {
            session("action_message", $Yard->getError());
            $this->redirect("Yard/newone");
        }
    }

    //delete方法，删除指定记录
    public function delete() {
        $id = $this->_get("id");
        $Yard = D("Yard");
        //关联删除yardadmin,其他的关联如房屋不删除
        //关联删除必须用delete(id)的方式
        if ($Yard->relation("yardadmin")->delete($id)) {
            session("action_message", "删除数据成功！");
            $this->redirect("Yard/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Yard/index");
        }
    }

    //edit方法，显示编辑院落信息的模板
    public function edit() {
        $id = $this->_get("id");
        $Yard = D("Yard");
        $data = $Yard->relation("street")->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Yard/index");
        }

        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    //update方法，用于接收提交的修改信息
    public function update() {
        //注意修改的表单下面一定要有一个隐藏域提交id，这样save方法才能成功。
        //获取隐藏字段id,即要更新的数据id
        $id = $this->_post("id");
        $streetid = $this->_post("street_id");
        $Yard = D("Yard");
        if ($newdata = $Yard->create()) {
            $newdata['address'] = D("Street")->where("id='$streetid'")->getField("name");
            $newdata['address'].=$this->_post("address");
            $data = $Yard->save($newdata);
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Yard/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Yard/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Yard->getError());
            $this->redirect("/Yard/edit/$id"); //必须有第一个斜杠, i was confused!!!
        }
    }

    //院落日常工作页面
    public function yardwork() {

        $yardid = $_GET["id"];
        $yardname = D("Yard")->where("id=$yardid")->getField("name");

        $m_yardwork = D("Yardwork");
        $worknormal = $m_yardwork->where(array("yard_id" => $yardid, "status" => 4))->order("into_date desc")->select();

        $map["status"] = array("neq", 4);
        $map["yard_id"] = $yardid;
        $workproblem = $m_yardwork->where($map)->order("into_date desc")->select();

        $this->assign("worknormal", $worknormal);
        $this->assign("workproblem", $workproblem);
        $this->assign(array("yardname" => $yardname, "yardid" => $yardid,
            "page_place" => $this->getPagePlace("院落工作数据", self::ACTION_NAME)));
        $this->display();
    }

    public function addwork() {
        $m_yardwork = D("Yardwork");
        $log_type = $_POST["log_type"];
        $yard_id = $_POST["yard_id"];

        if ($new_yardwork = $m_yardwork->create()) {
            if ($log_type === '1') {
                $new_yardwork["status"] = 4;
            } else {
                $new_yardwork["status"] = 1;
            }

            $new_yardwork["work_name"] = $_SESSION["truename"];
            $new_yardwork["work_uid"] = $_SESSION["username"];
            if (false !== $m_yardwork->add($new_yardwork)) {
                session("action_message", "添加数据成功！");
                $this->redirect("/Yard/yardwork/$yard_id");
            } else {
                session("action_message", "数据库写入失败！");
                $this->redirect("/Yard/yardwork/$yard_id");
            }
        } else {
            session("action_message", $m_yardwork->getError());
            $this->redirect("Yard/index");
        }
    }

    //ajax动态修改问题的状态
    public function changeYardworkStatus() {
        //yardworkid:yardworkid,
        //new_status:new_status
        if ($this->isAjax()) {
            $workid = $_GET["yardworkid"];
            $new_status = $_GET["new_status"];
            M("Yardwork")->where("id=$workid")->setField("status", $new_status);
        } else {
            $this->redirect("Yard/index");
        }
    }

    //information 页面的ajax操作
    public function information() {
        //水井坊院落列表
        $yard_list_sjf = D("Yard")->field("id,name,address")->where("id<2000000")->select();
        foreach ($yard_list_sjf as &$y) {
            $address1 = D("House")->field("address_1")->where(array("yard_id" => $y["id"]))->group("address_1")->select();
            $y["address1count"] = count($address1);
        }
        //交子院落列表
        $yard_list_jz = D("Yard")->field("id,name,address")->where("id>=2000000")->select();
        foreach ($yard_list_jz as &$y) {
            $address1 = D("House")->field("address_1")->where(array("yard_id" => $y["id"]))->group("address_1")->select();
            $y["address1count"] = count($address1);
        }

        if (!empty($yard_list_sjf)) {
            $this->assign("sjflist", $yard_list_sjf);
        }
        if (!empty($yard_list_jz)) {
            $this->assign("jzlist", $yard_list_jz);
        }
        $this->assign(array("page_place" => $this->getPagePlace("院落信息总览", self::ACTION_NAME)));
        $this->display();
    }

    //无用了
    public function showHouse() {
        if ($this->isAjax()) {
            $yardid = $this->_get("id");

            import("@.ORG.Page");
            $count = D("House")->where("yard_id='$yardid'")->count();
            //lib、org下修改过的分页类，可实现ajax分页。在文件的157行注入了setNumber方法，避免翻页后无序号的问题。
            $p = new Page($count, 30, 'type=1', 'content_middle', 'pages');
            $p->setConfig('header', '条');
            $p->setConfig('prev', "<");
            $p->setConfig('next', '>');
            $p->setConfig('first', '<<');
            $p->setConfig('last', '>>');
            $page = $p->show();

            $houselist = D("House")->where("yard_id='$yardid'")->relation("citizen")->order("address_1 asc")
                            ->limit($p->firstRow . ',' . $p->listRows)->select();
            //no pages
            //$houselist = D("House")->where("yard_id='$yardid'")->relation("citizen")->order("address_1 asc")->select();
            $this->assign(array("houselist" => $houselist, "page" => $page));
            $content = $this->fetch("_middle");
            header("content-type:text/html;charset=utf-8");
            echo $content;
        } else {
            $this->redirect("information");
        }
    }

    //无用了
    public function showCitizen() {
        if ($this->isAjax()) {
            $houseid = $this->_get("id");
            $citizenlist = D("Citizen")->where("house_id='$houseid'")->select();
            $this->assign("citizenlist", $citizenlist);
            $content = $this->fetch("_middle");
            header("content-type:text/html;charset=utf-8");
            echo $content;
        } else {
            $this->redirect("information");
        }
    }

    //添加管理信息,对应的数据插入到yardadmin表，与此表多对一关系
    public function addAdmin() {
        if ($this->isAjax()) {
            $yardadmin = D("Yardadmin");
            $newdata = $yardadmin->create();
            if ($this->id = $yardadmin->add())
                $this->ajaxReturn($newdata, "添加成功", 1);
        }
    }

    //下面这两个是把增加的数据id添加到返回结果里，这样才能使tr具备yardadmin
    //数据的id,才能在后面用删除命令ajax删除
    private $id;

    public function ajaxAssign(&$result) {
        $result['id'] = $this->id;
    }

    public function delAdmin() {
        if ($this->isAjax()) {
            $id = $this->_post("id");
            if (D("Yardadmin")->where(array("id" => $id))->delete())
                $this->ajaxReturn("", "删除记录成功！", 1);
        }
    }

    //information页面显示单个Yard详细统计的table生成方法
    //点击院落名，调用js的showYardDetail方法（同时传递yard id）
    //在js的showYardDetail方法中$.get这个函数，这个函数返回完整的table结构和信息
    //这个方法也有个detail.html。使用fetch方法获取渲染后的html，即将这个html返回给了js中的回调函数
    //showYardDetail函数负责将这个html显示在content_middle中，整个逻辑结束。
    public function detail() {
        if ($this->isAjax()) {
            $yardid = $this->_get("id");
            $header = $this->_get("hasHeader");
            $yard = D("Yard")->where("id='$yardid'")->find();

            if ($header == "has") {
                $manage = D("Yardadmin");
                //管理，党建，环卫查询和传输变量定义
                $yardadmin = $manage->where(array("yard_id" => $yardid, "type" => "院落管理"))->select();
                $yardparty = $manage->where(array("yard_id" => $yardid, "type" => "院落党支部"))->select();
                $yardclean = $manage->where(array("yard_id" => $yardid, "type" => "环治工作"))->select();
            }
            //detail页面直接显示院落的统计表，下面层次的需要点击button动态统计加载
            $address1 = $this->hasCollection($yardid);
            $houses = $this->houseCollection($yardid);
            $tongjitable = $this->statistics($houses, $yardid, $address1, "", "", $yard["name"]);

            //最后一步，返回完整html给调用的js函数showYardDetail
            if ($header == "has") {
                $this->assign(array("yard" => $yard, "yardadmin" => $yardadmin,
                    "yardparty" => $yardparty, "yardclean" => $yardclean,
                    "tongji" => $tongjitable, "header" => "has Header"));
            } else if ($header == "no") {
                $this->assign(array("yard" => $yard, "tongji" => $tongjitable));
            }
            $content = $this->fetch();
            header("content-type:text/html;charset=utf-8");
            echo $content;
        } else {
            $this->redirect("information");
        }
    }

    public function addressone() {
        if ($this->isAjax()) {
            $yardid = $this->_get("id");
            $address_1 = $this->_get("address_1");

            $address_2 = $this->hasCollection($yardid, $address_1);
            $houses = $this->houseCollection($yardid, $address_1);
            $tongjitable = $this->statistics($houses, $yardid, $address_1, $address_2, "", $address_1 . "栋");
            header("content-type:text/html;charset=utf-8");
            echo $tongjitable;
        } else {
            $this->redirect("information");
        }
    }

    public function addresstwo() {
        if ($this->isAjax()) {
            $yardid = $this->_get("id");
            $address_1 = $this->_get("address_1");
            $address_2 = $this->_get("address_2");

            $address_3 = $this->hasCollection($yardid, $address_1, $address_2);
            $houses = $this->houseCollection($yardid, $address_1, $address_2);
            $tongjitable = $this->statistics($houses, $yardid, $address_1, $address_2, $address_3, $address_2 . "单元");
            header("content-type:text/html;charset=utf-8");
            echo $tongjitable;
        } else {
            $this->redirect("information");
        }
    }

    public function addressthree() {
        if ($this->isAjax()) {
            $yardid = $this->_get("id");
            $address_1 = $this->_get("address_1");
            $address_2 = $this->_get("address_2");
            $address_3 = $this->_get("address_3");

            $houses = $this->houseCollection($yardid, $address_1, $address_2, $address_3);
            $tongjitable = $this->statistics($houses, $yardid, $address_1, $address_2, $address_3, $address_3 . "楼");
            $url = __APP__ . "/House";
            $html = "";
            foreach ($houses as $house) {
                $str = $link = $houseid = "";
                $str = $house['address_4'] . "号";
                $houseid = $house["id"];
                $link = "<a target='_blank' href='$url/$houseid'>" . $str . "</a>  ";
                $html.= $link;
            }
            header("content-type:text/html;charset=utf-8");
            echo $tongjitable . $html;
        } else {
            $this->redirect("information");
        }
    }

    //根据范围获取house集合
    protected function houseCollection($yardid, $address_1 = "", $address_2 = "", $address_3 = "") {
        $model = D("House");
        if ("" == $address_3) {
            if ("" == $address_2) {
                if ("" == $address_1) {
                    //院落范围
                    return $model->where(array("yard_id" => $yardid))->select();
                } else {
                    //院落，栋范围
                    return $model->where(array("yard_id" => $yardid,
                                "address_1" => $address_1))->select();
                }
            } else {
                //院落，栋，单元范围
                return $model->where(array("yard_id" => $yardid,
                            "address_1" => $address_1, "address_2" => $address_2))->select();
            }
        } else {
            //院落，栋，单元，楼层范围
            return $model->where(array("yard_id" => $yardid,
                        "address_1" => $address_1, "address_2" => $address_2, "address_3" => $address_3))->select();
        }
    }

    //获取下面一层的集合，如院落里面的栋数，栋里面的单元数，用来生成buttons用的
    protected function hasCollection($yardid, $address_1 = "", $address_2 = "") {
        $model = D("House");

        if ("" == $address_2) {
            if ("" == $address_1) {
                return $model->field("address_1")->where(array("yard_id" => $yardid))->group("address_1")->select();
            } else {
                return $model->field("address_2")->where(array("yard_id" => $yardid,
                            "address_1" => $address_1))->group("address_2")->select();
            }
        } else {
            return $model->field("address_3")->where(array("yard_id" => $yardid, "address_1" => $address_1,
                        "address_2" => $address_2))->group("address_3")->select();
        }
    }

    //传递房屋的列表，统计与居民和房屋信息相关的12个统计项的table html+下一层的buttons
    protected function statistics($houses, $yardid, $address_1 = "", $address_2 = "", $address_3 = "", $scope = "") {
        $tongjiarray = array();

        //获取此院落下居民人数, 暂住人口, 现有住户, 党员
        $citizensum = 0;
        $citizenzhanzhu = 0;
        $housenumber = 0;
        $citizenparty = 0;
        //低保, 享受廉租房(house中的is_lowrent), 享受燃煤补贴(house中的is_fuel), 享受长寿金
        $citizendibao = 0;
        $houseislowrent = 0;
        $houseisfuel = 0;
        $citizenislonglive = 0;
        //残疾人,特殊人员,台属,军属
        $citizendisable = 0;
        $citizenspecial = 0;
        $housetaiwan = 0;
        $housearmy = 0;

        $housenumber = count($houses);
        $citizen = D("Citizen");
        foreach ($houses as $house) {
            $house_id = $house["id"];
            $citizensum+=$citizen->where(array("house_id" => $house_id))->count();
            $citizenzhanzhu+=$citizen->where(array("house_id" => $house_id, "relation_with_householder" => "暂住人口"))->count();
            $citizenparty+=$citizen->where(array("house_id" => $house_id, "political_status" => "党员"))->count();
            $citizendibao+=$citizen->where(array("house_id" => $house_id, "is_low_level" => "是"))->count();
            $citizenislonglive+=$citizen->where(array("house_id" => $house_id, "is_long_live" => "是"))->count();
            $citizendisable+=$citizen->where(array("house_id" => $house_id, "is_disability" => "是"))->count();
            $citizenspecial+=$citizen->where(array("house_id" => $house_id, "is_special" => "是"))->count();

            $houseislowrent+=$house["is_lowrent"] == "是" ? 1 : 0;
            $houseisfuel+=$house["is_fuel"] == "是" ? 1 : 0;
            $housetaiwan+=$house["is_taiwan"] == "是" ? 1 : 0;
            $housearmy+=$house["is_army"] == "是" ? 1 : 0;
        }

        //各个层次房屋总数的链接
        $tongjiarray["housenumber"] = $this->link($housenumber, "table", $yardid, $address_1, $address_2, $address_3);

        //各层次享受廉租福利
        $tongjiarray["houseislowrent"] = $this->link($houseislowrent, "table/lowrent", $yardid, $address_1, $address_2, $address_3);

        //各层次享受燃油补贴
        $tongjiarray["houseisfuel"] = $this->link($houseisfuel, "table/fuel", $yardid, $address_1, $address_2, $address_3);

        //各层次台属
        $tongjiarray["housetaiwan"] = $this->link($housetaiwan, "table/taiwan", $yardid, $address_1, $address_2, $address_3);

        //各层次军属
        $tongjiarray["housearmy"] = $this->link($housearmy, "table/army", $yardid, $address_1, $address_2, $address_3);

        //各层次居民
        $tongjiarray["citizensum"] = $this->link($citizensum, "ctable", $yardid, $address_1, $address_2, $address_3);

        //居民是否是党员那些，因为可以导出excel，一下就能筛选，就不做了。
        $tongjiarray["citizenzhanzhu"] = $citizenzhanzhu;
        $tongjiarray["citizenparty"] = $citizenparty;
        $tongjiarray["citizendibao"] = $citizendibao;
        $tongjiarray["citizenislonglive"] = $citizenislonglive;
        $tongjiarray["citizendisable"] = $citizendisable;
        $tongjiarray["citizenspecial"] = $citizenspecial;

        //栋数的buttons
        if ("" == $address_3 && "" == $address_2 && "" != $address_1 && "" != $yardid)
            $this->assign(array("yardid" => $yardid, "address_1" => $address_1));
        //单元的buttons
        if ("" == $address_3 && "" != $address_2 && "" != $address_1 && "" != $yardid)
            $this->assign(array("yardid" => $yardid, "address_1" => $address_1, "address_2" => $address_2));
        //楼层的buttons
        if ("" != $address_3 && "" != $address_2 && "" != $address_1 && "" != $yardid)
            $this->assign(array("yardid" => $yardid, "address_1" => $address_1, "address_2" => $address_2, "address_3" => $address_3));

        $this->assign(array("tongji" => $tongjiarray, "scope" => $scope));
        $content = $this->fetch("_statistics");
        return $content;
    }

    protected function link($item, $action, $yardid, $address_1, $address_2, $address_3) {
        $link = "";
        if ($item != 0) {
            if (is_array($address_1)) {
                //院落链接
                //$link = <a target=_blank href="__APP__/House/$action_$yardid_$address1_$address2_address3>$item</a>
                $link = "<a target=_blank href=__APP__/House/" . $action . "_" . $yardid . ">" . $item . "</a>";
            } else {
                if (is_array($address_2)) {
                    //楼栋链接
                    $link = "<a target=_blank href=__APP__/House/" . $action . "_" . $yardid . "_" . $address_1 . ">" . $item . "</a>";
                } else {
                    if (is_array($address_3)) {
                        //单元链接
                        $link = "<a target=_blank href=__APP__/House/" . $action . "_" . $yardid . "_" . $address_1 . "_" . $address_2 . ">" . $item . "</a>";
                    } else {
                        //楼层链接
                        $link = "<a target=_blank href=__APP__/House/" . $action . "_" . $yardid . "_" . $address_1 . "_" . $address_2 . "_" . $address_3 . ">" . $item . "</a>";
                    }
                }
            }
        } else {
            $link = "<a href='#' onclick='javascript:return false'>0</a>";
        }
        return $link;
    }

}

?>
