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
        if ($_SESSION["right"] == "9" || $_SESSION["right"] == "1") {
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
        } else {
            header("Content-Type:text/html; charset=utf-8");
            echo "您没有权限查看监控";
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
                $tongjitable = $this->statistics($yardid, $address_1, $address_2, "", $address_1 . "栋");
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
            $tongjitable = $this->statistics($yardid, $address_1, $address_2, $address_3, $address_2 . "单元");
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
            $tongjitable = $this->statistics($yardid, $address_1, $address_2, $address_3, $address_3 . "楼");

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

    //从sjf_house_youfu,sjf_citizen_youfu表中进行统计
    protected function statistics($yardid, $address_1 = "", $address_2 = "", $address_3 = "", $scope = "") {
        $tongjiarray = array();
        $v_house_youfu = M("HouseYoufu");
        $v_citizen_youfu = M("CitizenYoufu");

        $query = "yard_id=$yardid";
        //如果address_1是数组，说明是返回hasCollection的集合，当前查询是院落层次
        if (!is_array($address_1)) {
            $query .= " AND address_1=$address_1";
            if (!is_array($address_2)) {
                $query.=" AND address_2=$address_2";
                if (!is_array($address_3)) {
                    $query.=" AND address_3=$address_3";
                }
            }
        }

        //第一行，房屋相关统计
        $tongjiarray["housenumber"] = $v_house_youfu->where($query)->count();
        $tongjiarray["houseislianzu"] = $v_house_youfu->where($query . " AND is_lianzu='是'")->count();
        $tongjiarray["houseisdibao"] = $v_house_youfu->where($query . " AND is_dibao='是'")->count();
        $tongjiarray["houseranmei"] = $v_house_youfu->where($query . " AND ranmei='是'")->count();
        $tongjiarray["houseistaishu"] = $v_house_youfu->where($query . " AND is_taishu='是'")->count();
        $tongjiarray["houseisjunshu"] = $v_house_youfu->where($query . " AND is_junshu='是'")->count();
        $tongjiarray["houseisjjsyf"] = $v_house_youfu->where($query . " AND is_jjsyf='是'")->count();

        //第二行，居民相关统计
        $tongjiarray["citizensum"] = $v_citizen_youfu->where($query)->count();
        $tongjiarray["citizenzhanzhu"] = $v_citizen_youfu->where($query . " AND relation_with_householder='流动人口_暂住'")->count();
        $tongjiarray["citizenparty"] = $v_citizen_youfu->where($query . " AND political_status='党员'")->count();
        $tongjiarray["citizenislonglive"] = $v_citizen_youfu->where($query . " AND is_long_live='是'")->count();
        $tongjiarray["citizenisdibao"] = $v_citizen_youfu->where($query . " AND is_dibao='是'")->count();
        $tongjiarray["citizeniscanji"] = $v_citizen_youfu->where($query . " AND is_canji='是'")->count();
        $tongjiarray["citizenspecial"] = $v_citizen_youfu->where($query . " AND sp_status!='不是'")->count();

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

    //根据多个yardid, address_1 统计总数，输出列表
    public function Getmore() {
        if ($_SESSION['right'] != '9') {
            exit("无权限");
        }

        if ($this->isAjax()) {
            //解析json数据到数组
            $yardid_array = json_decode($_POST['query_id'], true);
            //$yardid_array=json_decode($aaa, true);
            //exit(print_r($yardid_array));	
            $m_yard = D("yard");

            $Alltongjiarray = array();
            $scope = '';
            $elm_sum = count($yardid_array);
            for ($i = 0; $i < $elm_sum; $i++) {
                $yardid = $yardid_array[$i]['yard_id'];
                $name = $yardid_array[$i]['name'];
                $address_1 = $yardid_array[$i]['address_1'];

                $data_yard = $m_yard->find($yardid);

                $dot = $j++ != 0 ? '<br>' : '';
                if ($i < 25) { //防止输出过长信息
                    $scope.=$dot . $name;
                } elseif ($i == 25) {
                    $scope.=$dot . $name . "<br>...省略";
                }

                if (!empty($data_yard)) {
                    $address_2 = $this->hasCollection($yardid, $address_1);
                    $houses = $this->houseCollection($yardid, $address_1);
                    $tongjiarray = $this->statistics_getmore($houses, $yardid, $address_1, $address_2, "", $address_1 . "栋");

                    //累加到一起
                    $Alltongjiarray['housenumber']+=$tongjiarray['housenumber'];
                    $Alltongjiarray['houseislianzu']+=$tongjiarray['houseislianzu'];
                    $Alltongjiarray['houseisdibao']+=$tongjiarray['houseisdibao'];
                    $Alltongjiarray['houseranmei']+=$tongjiarray['houseranmei'];
                    $Alltongjiarray['houseistaishu']+=$tongjiarray['houseistaishu'];
                    $Alltongjiarray['houseisjunshu']+=$tongjiarray['houseisjunshu'];
                    $Alltongjiarray['houseisjjsyf']+=$tongjiarray['houseisjjsyf'];

                    $Alltongjiarray['citizensum']+=$tongjiarray['citizensum'];
                    $Alltongjiarray['citizenzhanzhu']+=$tongjiarray['citizenzhanzhu'];
                    $Alltongjiarray['citizenparty']+=$tongjiarray['citizenparty'];
                    $Alltongjiarray['citizenislonglive']+=$tongjiarray['citizenislonglive'];
                    $Alltongjiarray['citizenisdibao']+=$tongjiarray['citizenisdibao'];
                    $Alltongjiarray['citizeniscanji']+=$tongjiarray['citizeniscanji'];
                    $Alltongjiarray['citizenspecial']+=$tongjiarray['citizenspecial'];
                } else {
                    echo "<br>无此id的院落";
                }
            }

            $this->assign(array("tongji" => $Alltongjiarray, "scope" => $scope, "elm_sum" => $elm_sum));
            $content = $this->fetch("_getmore");
            echo $content;
            return $content;
        } else {
            echo "请用ajax调用方法";
        }
    }

    //为Getmore返回统计数组
    protected function statistics_getmore($houses, $yardid, $address_1 = "", $address_2 = "", $address_3 = "", $scope = "") {
        $tongjiarray = array();
        $v_house_youfu = M("HouseYoufu");
        $v_citizen_youfu = M("CitizenYoufu");

        $query = "yard_id=$yardid";
        //如果address_1是数组，说明是返回hasCollection的集合，当前查询是院落层次
        if (!is_array($address_1)) {
            $query .= " AND address_1=$address_1";
            if (!is_array($address_2)) {
                $query.=" AND address_2=$address_2";
                if (!is_array($address_3)) {
                    $query.=" AND address_3=$address_3";
                }
            }
        }

        //第一行，房屋相关统计
        $tongjiarray["housenumber"] = $v_house_youfu->where($query)->count();
        $tongjiarray["houseislianzu"] = $v_house_youfu->where($query . " AND is_lianzu='是'")->count();
        $tongjiarray["houseisdibao"] = $v_house_youfu->where($query . " AND is_dibao='是'")->count();
        $tongjiarray["houseranmei"] = $v_house_youfu->where($query . " AND ranmei='是'")->count();
        $tongjiarray["houseistaishu"] = $v_house_youfu->where($query . " AND is_taishu='是'")->count();
        $tongjiarray["houseisjunshu"] = $v_house_youfu->where($query . " AND is_junshu='是'")->count();
        $tongjiarray["houseisjjsyf"] = $v_house_youfu->where($query . " AND is_jjsyf='是'")->count();

        //第二行，居民相关统计
        $tongjiarray["citizensum"] = $v_citizen_youfu->where($query)->count();
        $tongjiarray["citizenzhanzhu"] = $v_citizen_youfu->where($query . " AND relation_with_householder='流动人口_暂住'")->count();
        $tongjiarray["citizenparty"] = $v_citizen_youfu->where($query . " AND political_status='党员'")->count();
        $tongjiarray["citizenislonglive"] = $v_citizen_youfu->where($query . " AND is_long_live='是'")->count();
        $tongjiarray["citizenisdibao"] = $v_citizen_youfu->where($query . " AND is_dibao='是'")->count();
        $tongjiarray["citizeniscanji"] = $v_citizen_youfu->where($query . " AND is_canji='是'")->count();
        $tongjiarray["citizenspecial"] = $v_citizen_youfu->where($query . " AND sp_status!='不是'")->count();

        //栋数的buttons
        if ("" == $address_3 && "" == $address_2 && "" != $address_1 && "" != $yardid)
            $this->assign(array("yardid" => $yardid, "address_1" => $address_1));
        //单元的buttons
        if ("" == $address_3 && "" != $address_2 && "" != $address_1 && "" != $yardid)
            $this->assign(array("yardid" => $yardid, "address_1" => $address_1, "address_2" => $address_2));
        //楼层的buttons
        if ("" != $address_3 && "" != $address_2 && "" != $address_1 && "" != $yardid)
            $this->assign(array("yardid" => $yardid, "address_1" => $address_1, "address_2" => $address_2, "address_3" => $address_3));

        return $tongjiarray;
    }

}

?>
