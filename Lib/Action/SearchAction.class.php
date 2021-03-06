<?php

//检索功能的控制器，包括对居民的检索和对房屋的检索
class SearchAction extends BaseAction {

    const ACTION_NAME = "查询";

    public function citizen() {
        $this->assign("page_place", $this->getPagePlace("居民（人）信息查询", self::ACTION_NAME, "citizen"));
        $this->display();
    }

    public function house() {
        $this->assign("page_place", $this->getPagePlace("房屋（户）信息查询", self::ACTION_NAME, "house"));
        $this->display();
    }
    public function ngo() {
        $this->assign("page_place", $this->getPagePlace("社会组织信息查询", self::ACTION_NAME, "ngo"));
        $this->display();
    }

    //搜索居民表，接收客户端提交的表单，建立查询语句，查询后渲染模版，然后返回html数据
    public function dosearch() {
        $searchkey = $_POST;
        //该变量构建了一个sql语句的条件表达式
        $tmp = "";
        foreach ($searchkey as $i => $value) {
            if (substr($i, 0, 8) == "sKeyName") {
                $y = "sValue" . substr($i, -1);
                $x = "sKeyRelation" . substr($i, -1);
                if ($searchkey[$x] !== "") {
                    switch ($searchkey[$i]) {
                        case "address":
                            $new_address .= $searchkey[$y] ? $searchkey[$y] . "%" : "";
                            $new_address .= $searchkey[$y . "-1"] ? $searchkey[$y . "-1"] . "号%" : "";
                            $new_address .= $searchkey[$y . "-2"] ? $searchkey[$y . "-2"] . "栋%" : "";
                            $new_address .= $searchkey[$y . "-3"] ? $searchkey[$y . "-3"] . "单元%" : "";
                            $new_address .= $searchkey[$y . "-4"] ? $searchkey[$y . "-4"] . "楼%" : "";
                            $new_address .= $searchkey[$y . "-5"] ? $searchkey[$y . "-5"] . "号%" : "";
                            $new_address .= $searchkey[$y . "-6"] ? "附" . $searchkey[$y . "-6"] . "号%" : "";
                            $tmp = $tmp. $searchkey[$i] . " like '%" . $new_address . "' " . $searchkey[$x] . " ";
                            break;
                        case "age":
                            $cunrrentYear = date("Y");
                            $b1 = $cunrrentYear - $searchkey[$y];
                            $b2 = $cunrrentYear - $searchkey[$y . '-1'];
                            $tmp = $tmp . "birthday between '" . $b2 . "-0-0' and '" . $b1 . "-0-0' " . $searchkey[$x] . " ";
                            break;
                        case "birthday":
                            $tmp = $tmp . $searchkey[$i] . " between '" . $searchkey[$y] . "' and '" . $searchkey[$y . '-1'] . "' " . $searchkey[$x] . " ";
                            break;
                        case "cellphone":
                            if($searchkey[$y]==""){
                                $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' " . $searchkey[$x] . " or cellphone is null";
                            }  else {
                                $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' " . $searchkey[$x] . " ";
                            }
                            break;
                        default :
                            $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' " . $searchkey[$x] . " ";
                    }
                }
            }
        }

        //$citizen = D("Citizen");
        $citizen = D("View_citizen_youfu");
        if ($tmp != "") {
            $_SESSION['sCitizenKey'] = $tmp;
        }
        //echo $tmp;
        $tmp0 = $_SESSION['sCitizenKey'];
        $count = $citizen->relation('house')->where($tmp0)->count();
        $this->assign("totalcount", $count);
        //水井坊社区
        $tmp1 = $_SESSION['sCitizenKey'] . " and id<2000000";
        $count1 = $citizen->relation('house')->where($tmp1)->count();  //计算记录总数
        import("@.ORG.Pagea");
        $p1 = new Pagea($count1, 50, 'type=1', 'searchResult', 'pages1');
        $result1 = $citizen->relation('house')->where($tmp1)->limit($p1->firstRow . ',' . $p1->listRows)->select();
        $p1->setConfig('header', '人');
        $p1->setConfig('prev', "<");
        $p1->setConfig('next', '>');
        $p1->setConfig('first', '<<');
        $p1->setConfig('last', '>>');
        $page1 = $p1->show();
        $this->assign("page1", $page1);
        $this->assign("sjflist", $result1);

        //交子社区
        $tmp2 = $_SESSION['sCitizenKey'] . " and id>=2000000";
        $count2 = $citizen->relation('house')->where($tmp2)->count();  //计算记录总数
        import("@.ORG.Pagea");
        $p2 = new Pagea($count2, 50, 'type=1', 'searchResult', 'pages2');
        $result2 = $citizen->relation('house')->where($tmp2)->limit($p2->firstRow . ',' . $p2->listRows)->select();
        $p2->setConfig('header', '人');
        $p2->setConfig('prev', "<");
        $p2->setConfig('next', '>');
        $p2->setConfig('first', '<<');
        $p2->setConfig('last', '>>');
        $page2 = $p2->show();
        $this->assign("page2", $page2);
//        $this->assign("list", $result);
        $this->assign("jzlist", $result2);

        header("Content-Type:text/html; charset=utf-8");

        if ($this->isAjax()) {
            header("Content-Type:text/html; charset=utf-8");
            exit($this->fetch('_citizen'));
        }
        $this->display();
    }

//搜索房屋
    public function housesearch() {
        $searchkey = $_POST;
        
        $tmp = "";
        foreach ($searchkey as $i => $value) {
            if (substr($i, 0, 8) == "sKeyName") {
                $y = "sValue" . substr($i, -1);
                $x = "sKeyRelation" . substr($i, -1);
                if ($searchkey[$x] !== "") {
                    switch ($searchkey[$i]) {
                        case "address":
                            $new_address .= $searchkey[$y] ? $searchkey[$y] . "%" : "";
                            $new_address .= $searchkey[$y . "-1"] ? $searchkey[$y . "-1"] . "号%" : "";
                            $new_address .= $searchkey[$y . "-2"] ? $searchkey[$y . "-2"] . "栋%" : "";
                            $new_address .= $searchkey[$y . "-3"] ? $searchkey[$y . "-3"] . "单元%" : "";
                            $new_address .= $searchkey[$y . "-4"] ? $searchkey[$y . "-4"] . "楼%" : "";
                            $new_address .= $searchkey[$y . "-5"] ? $searchkey[$y . "-5"] . "号%" : "";
                            $new_address .= $searchkey[$y . "-6"] ? "附" . $searchkey[$y . "-6"] . "号%" : "";
                            $tmp = $tmp . $searchkey[$i] . " like '%" . $new_address . "' " . $searchkey[$x] . " ";
                            break;
                        default :
                            $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' " . $searchkey[$x] . " ";
                    }
                }
            }
        }
        //echo $tmp;
        
        $house = D("View_house_youfu");
        if ($tmp != "") {
            $_SESSION['sHouseKey'] = $tmp;
        }
        $tmp0 = $_SESSION['sHouseKey'];
        $count = $house->relation('house')->where($tmp0)->count();
        $this->assign("totalcount", $count);
        //水井坊社区
        $tmp1 = $_SESSION['sHouseKey'] . " and id<2000000";
        $count1 = $house->relation('house')->where($tmp1)->count();

        import("@.ORG.Pagea");
        $p1 = new Pagea($count1, 50, 'type=1', 'searchResult', 'pages1');
        $result1 = $house->relation('house')->where($tmp1)->limit($p1->firstRow . ',' . $p1->listRows)->select();

        $p1->setConfig('header', '户');
        $p1->setConfig('prev', "<");
        $p1->setConfig('next', '>');
        $p1->setConfig('first', '<<');
        $p1->setConfig('last', '>>');

        $page1 = $p1->show();
        $this->assign("page1", $page1);
        $this->assign("sjflist", $result1);
        //交子社区
        $tmp2 = $_SESSION['sHouseKey'] . " and id>=2000000";
        $count2 = $house->relation('house')->where($tmp2)->count();

        import("@.ORG.Pagea");
        $p2 = new Pagea($count2, 50, 'type=1', 'searchResult', 'pages2');
        $result2 = $house->relation('house')->where($tmp2)->limit($p2->firstRow . ',' . $p2->listRows)->select();

        $p2->setConfig('header', '户');
        $p2->setConfig('prev', "<");
        $p2->setConfig('next', '>');
        $p2->setConfig('first', '<<');
        $p2->setConfig('last', '>>');

        $page2 = $p2->show();
        $this->assign("page2", $page2);
        $this->assign("jzlist", $result2);

        header("Content-Type:text/html; charset=utf-8");
        //$this->assign("list", $result);
        if ($this->isAjax()) {
            header("Content-Type:text/html; charset=utf-8");
            exit($this->fetch('_house'));
        }
        $this->display();
    }
    
    //社会组织搜索
     public function ngosearch() {
        $searchkey = $_POST;
       
        $tmp = "";
        foreach ($searchkey as $i => $value) {
            if (substr($i, 0, 8) == "sKeyName") {
                $y = "sValue" . substr($i, -1);
                $x = "sKeyRelation" . substr($i, -1);
                if ($searchkey[$x] !== "") {
                    switch ($searchkey[$i]) {
                        case "register_date":
                            $tmp = $tmp . $searchkey[$i] . " between '" . $searchkey[$y] . "' and '" . $searchkey[$y . '-1'] . "' " . $searchkey[$x] . " ";
                            break;
                        default :
                            $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' " . $searchkey[$x] . " ";
                    }
                }
            }
        }
        //echo $tmp;
        
        $ngo = D("Ngo");
        if ($tmp != "") {
            $_SESSION['sNgoKey'] = $tmp;
        }
        $tmp0 = $_SESSION['sNgoKey'];
        $count = $ngo->where($tmp0)->count();
        $this->assign("totalcount", $count);
        //水井坊社区
        $tmp1 = $_SESSION['sNgoKey'] . " and id<2000000";
        $count1 = $ngo->where($tmp1)->count();

        import("@.ORG.Pagea");
        $p1 = new Pagea($count1, 50, 'type=1', 'searchResult', 'pages1');
        $result1 = $ngo->where($tmp1)->limit($p1->firstRow . ',' . $p1->listRows)->select();

        $p1->setConfig('header', '个');
        $p1->setConfig('prev', "<");
        $p1->setConfig('next', '>');
        $p1->setConfig('first', '<<');
        $p1->setConfig('last', '>>');

        $page1 = $p1->show();
        $this->assign("page1", $page1);
        $this->assign("sjflist", $result1);
        //交子社区
        $tmp2 = $_SESSION['sNgoKey'] . " and id>=2000000";
        $count2 = $ngo->where($tmp2)->count();

        import("@.ORG.Pagea");
        $p2 = new Pagea($count2, 50, 'type=1', 'searchResult', 'pages2');
        $result2 = $ngo->where($tmp2)->limit($p2->firstRow . ',' . $p2->listRows)->select();

        $p2->setConfig('header', '个');
        $p2->setConfig('prev', "<");
        $p2->setConfig('next', '>');
        $p2->setConfig('first', '<<');
        $p2->setConfig('last', '>>');

        $page2 = $p2->show();
        $this->assign("page2", $page2);
        $this->assign("jzlist", $result2);

        header("Content-Type:text/html; charset=utf-8");
        
        if ($this->isAjax()) {
            header("Content-Type:text/html; charset=utf-8");
            exit($this->fetch('_ngo'));
        }
        $this->display();
    }

    public function htoexcel() {
        if ($_SESSION["right"] == "9" || $_SESSION["right"] == "1") {
            $list = D("View_house_youfu")->where($_SESSION['sHouseKey'])->select();
            foreach ($list as &$list2) {
                $list2["id_card"] = "'" . $list2["id_card"];
            }
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=house.xls");
            $this->assign("list", $list);
            echo $this->fetch("htoexcel");
        } else {
            header("Content-Type:text/html; charset=utf-8");
            echo "你没有权限执行导出操作";
        }
    }

    public function ctoexcel() {
        if ($_SESSION["right"] == "9" || $_SESSION["right"] == "1") {
            $list = D("View_citizen_youfu")->relation('house')->where($_SESSION['sCitizenKey'])->select();

            foreach ($list as &$list2) {
                $list2["id_card"] = "'" . $list2["id_card"];
            }
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=citizen.xls");
            $this->assign("list", $list);
            echo $this->fetch("ctoexcel");
        } else {
            header("Content-Type:text/html; charset=utf-8");
            echo "你没有权限执行导出操作";
        }
    }
    
     public function ntoexcel() {
        if ($_SESSION["right"] == "9" || $_SESSION["right"] == "1") {
            $list = D("ngo")->where($_SESSION['sCitizenKey'])->select();

            foreach ($list as &$list2) {
                $list2["id_card"] = "'" . $list2["id_card"];
            }
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=citizen.xls");
            $this->assign("list", $list);
            echo $this->fetch("ntoexcel");
        } else {
            header("Content-Type:text/html; charset=utf-8");
            echo "你没有权限执行导出操作";
        }
    }

    public function getStreetSelect() {
        $m_street = D("Street");
        $streetlist = $m_street->select();

        $html = '<option value="">----请选择----</option>';

        foreach ($streetlist as $y) {
            $html .= '<option value=' . $y[name] . '>' . $y[name] . '</option>';
        }
        header("Content-Type:text/html; charset=utf-8");
        echo $html;
    }

}

?>
