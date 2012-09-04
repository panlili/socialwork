<?php

class InterfaceAction extends BaseAction {

    public function _initialize() {
        parent::_initialize();
        header("content-type:text/html;charset=utf-8");
    }

    public function test() {

        $this->display();
    }

    public function getCamlistByYardid() {
        if ($this->isAjax()) {
            $yardid = $_GET["yardid"];
            $m_cam = D("Camera");
            $m_camlist = $m_cam->where("yard_id=" . $yardid)->select();
            $camlist = "";
            foreach ($m_camlist as $camvalue) {
                $camnumber = $camvalue["channels"] - $camvalue["remain"];
                $camlist = $camlist . "<p>";
                for ($i = 1; $i <= $camnumber; $i++) {
                    $camitem = '<a href="/socialwork/index.php/camera/opencam/' . $camvalue["id"] . '/' . $i . '" title="通道' . $i . '" target="_blank"><img id="camera"' . $i . '" src="/socialwork/public/image/map/camera1.jpg' . '"></img></a>';
                    $camlist = $camlist . $camitem;
                }
                $camlist = $camlist . "</p>";
            }
            echo $camlist;
        } else {
            echo "请用ajax调用方法";
        }
    }

    public function getBasic() {
        if ($this->isAjax()) {
            $yardid = $_GET["yardid"];
            $m_yard = D("yard");
            $m_yardadmin = D("Yardadmin");

            $data_yard = $m_yard->find($yardid);

            if (!empty($data_yard)) {
                //院落管理人员情况
                $admindata = $m_yardadmin->where(array("type" => "院落管理", "yard_id" => $yardid))->select();
                //院落党支部情况
                $partydata = $m_yardadmin->where(array("type" => "院落党支部", "yard_id" => $yardid))->select();
                //院落环治工作情况
                $cleandata = $m_yardadmin->where(array("type" => "环治工作", "yard_id" => $yardid))->select();

                $this->assign("data_yard", $data_yard);
                $this->assign(array("admindata" => $admindata, "partydata" => $partydata, "cleandata" => $cleandata));
                echo $this->fetch("_getbasic");
            } else {
                echo "无此id的院落";
            }
        } else {
            echo "请用ajax调用方法";
        }
    }

    public function addressone() {
        if ($this->isAjax()) {
            $yardid = $_GET["yardid"];
            $address_1 = $_GET["address_1"];
            $m_yard = D("yard");

            $data_yard = $m_yard->find($yardid);

            if (!empty($data_yard)) {
                $address_2 = $this->hasCollection($yardid, $address_1);
                $houses = $this->houseCollection($yardid, $address_1);
                $tongjitable = $this->statistics($houses, $yardid, $address_1, $address_2, "", $address_1 . "栋");
                echo $tongjitable;
            } else {
                echo "无此id的院落";
            }
        } else {
            echo "请用ajax调用方法";
        }
    }

    public function addresstwo() {
        if ($this->isAjax()) {
            $yardid = $_GET["yardid"];
            $address_1 = $_GET["address_1"];
            $address_2 = $_GET["address_2"];

            $address_3 = $this->hasCollection($yardid, $address_1, $address_2);
            $houses = $this->houseCollection($yardid, $address_1, $address_2);
            $tongjitable = $this->statistics($houses, $yardid, $address_1, $address_2, $address_3, $address_2 . "单元");
            echo $tongjitable;
        } else {
            echo "请用ajax调用方法";
        }
    }

    public function addressthree() {
        if ($this->isAjax()) {
            $yardid = $_GET["yardid"];
            $address_1 = $_GET["address_1"];
            $address_2 = $_GET["address_2"];
            $address_3 = $_GET["address_3"];

            $houses = $this->houseCollection($yardid, $address_1, $address_2, $address_3);
            $tongjitable = $this->statistics($houses, $yardid, $address_1, $address_2, $address_3, $address_3 . "楼");
            /*
              $url = __APP__ . "/House";
              $html = "";
              foreach ($houses as $house) {
              $str = $link = $houseid = "";
              $str = $house['address_4'] . "号";
              $houseid = $house["id"];
              $link = "<a target='_blank' href='$url/$houseid'>" . $str . "</a>  ";
              $html.= $link;
              }
              echo $tongjitable . $html;
             */
            //因为看数据需要权限，地图不集成在系统，点连接需要登陆，故放弃之
            echo $tongjitable;
        } else {
            echo "请用ajax调用方法";
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
        $housenumber = count($houses);
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
        $tongjiarray["housenumber"] = $housenumber;

        //各层次享受廉租福利
        $tongjiarray["houseislowrent"] = $houseislowrent;

        //各层次享受燃油补贴
        $tongjiarray["houseisfuel"] = $houseisfuel;

        //各层次台属
        $tongjiarray["housetaiwan"] = $housetaiwan;

        //各层次军属
        $tongjiarray["housearmy"] = $housearmy;

        //各层次居民
        $tongjiarray["citizensum"] = $citizensum;

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

}

?>
