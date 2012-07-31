<?php

session_start();

//检索功能的控制器，包括对居民的检索和对房屋的检索
class SearchAction extends BaseAction {

    const ACTION_NAME = "查询";

    public function citizen() {
        $this->assign("page_place", $this->getPagePlace("居民（人）信息查询", self::ACTION_NAME));
        $this->display();
    }

    public function house() {
        $this->assign("page_place", $this->getPagePlace("房屋（户）信息查询", self::ACTION_NAME));
        $this->display();
    }

    //接收客户端提交的表单，建立查询语句，查询后渲染模版，然后返回html数据
    public function dosearch() {
        $searchkey = $_POST;

        $tmp = "";  //该变量构建了一个sql语句的条件表达式
        foreach ($searchkey as $i => $value) {

            if (substr($i, 0, 8) == "sKeyName") {
                $y = "sValue" . substr($i, -1);

                $x = "sKeyRelation" . substr($i, -1);
                if ($searchkey[$x] !== "") {

                    switch ($searchkey[$i]) {
                        case "age":
                            $cunrrentYear = date("Y");
                            $b1 = $cunrrentYear - $searchkey[$y];
                            $b2 = $cunrrentYear - $searchkey[$y . '-1'];
                            $tmp = $tmp . "birthday between '" . $b2 . "-0-0' and '" . $b1 . "-0-0' " . $searchkey[$x] . " ";
                            break;
                        case "birthday":
                            $tmp = $tmp . $searchkey[$i] . " between '" . $searchkey[$y] . "' and '" . $searchkey[$y . '-1'] . "' " . $searchkey[$x] . " ";
                            break;
                        default :
                            $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' " . $searchkey[$x] . " ";
                    }

                    //对查询条件为“生日”或者“年龄”进行特别处理
                } 

            }
        }
        //dump($tmp);
        $citizen = D("Citizen");


        //$count=100;
        if ($tmp != "") {
            $_SESSION['sCitizenKey'] = $tmp;
        }

        $tmp1 = $_SESSION['sCitizenKey'];

        $count = $citizen->relation('house')->where($tmp1)->count();  //计算记录总数

        import("@.ORG.Pagea");
        $p = new Pagea($count, 50, 'type=1', 'searchResult', 'pages');
        $result = $citizen->relation('house')->where($tmp1)->limit($p->firstRow . ',' . $p->listRows)->select();

        $p->setConfig('header', '条数据');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');



        $page = $p->show();            //分页的导航条的输出变量
        $this->assign("page", $page);
        $this->assign("list", $result); //数据循环变量
        header("Content-Type:text/html; charset=utf-8");    //PHP header申明charset为utf8, Apache配置defaultcharst gbk,页面文件编码是utf8
        
        if ($this->isAjax()) {//判断ajax请求
            header("Content-Type:text/html; charset=utf-8");
            exit($this->fetch('_list'));
        }
        $this->display();
    }

    public function housesearch() {
        $searchkey = $_POST;
        $orflag = 0;
        $map = array();
        $where1 = array();
        $map['_logic'] = "or";
        $where1['_logic'] = "or";
        $tmp = "";  //该变量构建了一个sql语句的条件表达式
        foreach ($searchkey as $i => $value) {

            if (substr($i, 0, 8) == "sKeyName") {
                $y = "sValue" . substr($i, -1);
                $x = "sKeyRelation" . substr($i, -1);
                if ($searchkey[$x] == "AND" || $searchkey[$x] == "OR") {
                    $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' " . $searchkey[$x] . " ";
                } else {
                    $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' ";
                }
            }
        }

        $house = D("House");
        if ($tmp != "") {
            $_SESSION['sHouseKey'] = $tmp;
        }
        $tmp1 = $_SESSION['sHouseKey'];
        $count = $house->relation('house')->where($tmp1)->count();  //计算记录总数
        //$count=100;


        import("@.ORG.Pagea");
        $p = new Pagea($count, 50, 'type=1', 'searchResult', 'pages');
        $result = $house->relation('house')->where($tmp1)->limit($p->firstRow . ',' . $p->listRows)->select();

        $p->setConfig('header', '条数据');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');

        $page = $p->show();            //分页的导航条的输出变量
        $this->assign("page", $page);
        header("Content-Type:text/html; charset=utf-8");
        $this->assign("list", $result); //数据循环变量
        if ($this->isAjax()) {//判断ajax请求
            header("Content-Type:text/html; charset=utf-8");
            exit($this->fetch('_house'));
        }
        $this->display();
    }

    public function housetoexel() {
        $list = D("House")->where($_SESSION['sHouseKey'])->select();
        foreach ($list as &$list2) {
            $list2["id_card"] = "'" . $list2["id_card"];
        }
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=house.xls");
        $this->assign("list", $list);
        echo $this->fetch();
    }

    public function ctoexel() {
        $list = D("Citizen")->relation('house')->where($_SESSION['sCitizenKey'])->select();

        foreach ($list as &$list2) {
            $list2["id_card"] = "'" . $list2["id_card"];
        }
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=citizen.xls");
        $this->assign("list", $list);
        echo $this->fetch();
    }

}

?>
