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

    //接收客户端提交的表单，建立查询语句，查询后返回json数据
    public function dosearch() {
        $searchkey = $_POST;

        $tmp = "";  //该变量构建了一个sql语句的条件表达式
        foreach ($searchkey as $i => $value) {
           
            if (substr($i, 0, 8) == "sKeyName") {
                $y = "sValue" . substr($i, -1);
                
                $x = "sKeyRelation" . substr($i, -1);
                if ($searchkey[$x] !== "") {
                    
                    //对查询条件为“生日”或者“年龄”进行特别处理
                    $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' " . $searchkey[$x] . " ";
                } else {
                    $tmp = $tmp . $searchkey[$i] . " like '%" . $searchkey[$y] . "%' ";
                }
            }
        }
       
        $citizen = D("Citizen");
       
        $result = $citizen->relation('house')->where($tmp)->limit(50,1)->select();  //只输出50条，避免浏览器假死
       $_SESSION['sCitizenKey']=$tmp;
        echo json_encode($result);
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
        
        $result=$house->where($tmp)->limit(50,1)->select();    //只输出50条，避免浏览器假死
        $_SESSION['sHouseKey']=$tmp;
        echo json_encode($result);
    }
    public function housetoexel(){
        $list=  D("House")->where($_SESSION['sHouseKey'])->select();
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=house.xls");
        $this->assign("list",$list);
        echo $this->fetch();
    }
    public function ctoexel(){
        $list=  D("Citizen")->relation('house')->where($_SESSION['sCitizenKey'])->select();
        //$list=  D("citizen")->select();
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=citizen.xls");
        $this->assign("list",$list);
        echo $this->fetch();
    }

}

?>
