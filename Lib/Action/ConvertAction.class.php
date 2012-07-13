<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ConvertAction extends BaseAction {

    protected function getStreetNameById($id) {
        $street = D("Street");
        $result = $street->where("id=" . $id)->find();
        if (!is_null($result)) {
            return $result["name"];
        } else {
            return null;
        }
    }

    protected function getYardIdByAddress($address) {
        $yard = D("Yard");
        $result = $yard->where("address ='" . $address . "'")->find();
        if (!is_null($result)) {
            return $result["id"];
        } else {
            return null;
        }
    }

    protected function getYardNameById($id) {
        $yard = D("Yard");
        $result = $yard->where("id =" . $id)->find();
        if (!is_null($result)) {
            return $result["name"];
        } else {
            return null;
        }
    }

    protected function getStrByBool($a) {
        if ($a == 1) {
            return "是";
        } else {
            return "否";
        }
    }

    protected function getHouseIdByOldId($oldid) {
        $house = D("House");
        $result = $house->where("house_id='" . $oldid . "'")->find();
        if (!is_null($result)) {

            return $result["id"];
        } else {
            return null;
        }
    }

    public function index() {
        //$x= $this->getStreetNameby(280010001);
//        $y = $this->getYardIdByAddress("青龙正街101号");
//        //$x="fuck";
//        //echo $x;
//        echo $y;
        $this->display();
    }
    
    public function convertstreet(){
        $street1=M("address",null)->select();
         echo "<p>街区道路信息表转换，转换address到street</p>";
        $street=D("Street");
        foreach($street1 as $streettmp){
            $data["id"]=(int)$streettmp["a_street_id"];
            $data["name"]=$streettmp["a_alias_name"];
            dump($data);
            $street->create($data);
            $street->add();
        }
        echo "<p>转换完成</p>";
    }

    public function convertyard() {
        $yard1 = M("yuanluo_base", null)->select();
        echo "<p>院落表转换，转换yuanluo_base到yard……</p>";

        $yard = D("Yard");
        foreach ($yard1 as $yardtmp) {
            $data["id"] = $yardtmp["id"];
            $data["streed_id"] = $yardtmp["h_address_jlx"];
            $data["name"] = $yardtmp["y_name"];
            $data["building_count"] = $yardtmp["y_ld_count"];
            $data["building_age"] = $yardtmp["y_build_year"];
            $data["area"] = $yardtmp["y_build_area"];
            $data["admin_org"] = $yardtmp["y_jiegou"];
            $data["street_id"] = (int) $yardtmp["h_address_jlx"];
            if ($yardtmp["h_address_mlh"] == '0' || $yardtmp["h_address_mlh"] == "") {
                $data["address"] = $this->getStreetNameById((int) ($yardtmp["h_address_jlx"]));
            } else {
                $data["address"] = $this->getStreetNameById((int) ($yardtmp["h_address_jlx"])) . $yardtmp["h_address_mlh"] . "号";
            }
            dump($data);
            $yard->create($data);
            $yard->add();
        }
        echo "<p>院落表转换，转换yuanluo_base到yard完毕</p>";
    }

    public function converthouse() {
        $house1 = M("house_base", null)->select();
        echo "<p>房屋（户）表转换，转换house_base到house</p>";
        $house = D("House");
        foreach ($house1 as $housetmp) {
            $data["id"] = $housetmp["id"];
            $data["house_id"] = $housetmp["house_id"]; //houseid是增加的一个临时字段
            $x = "";
            if ($housetmp["h_address_mlh"] == '0' || $housetmp["h_address_mlh"] == "") {
                $x = $this->getStreetNameById((int) ($housetmp["h_address_jlx"]));
            } else {
                $x = $this->getStreetNameById((int) ($housetmp["h_address_jlx"])) . $housetmp["h_address_mlh"] . "号";
            }
            $data["yard_id"] = $this->getYardIdByAddress($x);

            $data["address_1"] = $housetmp["h_address_ldh"];
            $data["address_2"] = $housetmp["h_address_dyh"];
            $data["address_3"] = $housetmp["h_address_step"];
            $data["address_4"] = $housetmp["h_address_mph"];
            $data["contactor"] = $housetmp["h_contact_name"];
            $data["telephone"] = $housetmp["h_contact_tel"];
            $data["address"] = $this->getYardNameById($data["yard_id"]) . $data["address_1"] . "栋" . $data["address_2"] . "单元" . $data["address_3"] . "楼" . $data["address_4"] . "号";
            dump($data);
            $house->create($data);
            $house->add();
        }
        echo "<p>转换完成</p>";
    }

    public function convertcitizen() {
        $people1 = M("people", null)->select();
        echo "<p>居民表转换，转换people到citizen";
        $citizen = D("Citizen");
        foreach ($people1 as $peopletmp) {
//            $x="";
//            if($peopletmp["h_address_mlh"]=='0' || $peopletmp["h_address_mlh"]==""){
//                $x=  $this->getStreetNameById((int)($peopletmp["h_address_jlx"]));
//            }else{
//                $x=  $this->getStreetNameById((int)($peopletmp["h_address_jlx"])).$peopletmp["h_address_mlh"]."号";
//            }
            $data["id"] = $peopletmp["id"];
            $data["house_id"] = $this->getHouseIdByOldId($peopletmp["house_id"]);
            $data["name"] = $peopletmp["p_name"];
            $data["relation_with_householder"] = $this->get_guanxi($peopletmp["p_guanxi"]);
            $data["id_card"]=$peopletmp["p_sfzid"];
            $data["sex"]=  $this->get_sex($peopletmp["p_sex"]);
            $data["nation"]="汉族";
            $data["birthday"]=  getBirthdayByIdCard($peopletmp["p_sfzid"]);
            $data["is_fertility"]=  $this->getStrByBool($peopletmp["p_syzb"]);
            $data["marry_info"]=  $this->get_marry($peopletmp["p_marry_info"]);
            $data["education"]=$this->get_education($peopletmp["p_edu_info"]);
            $data["political_status"]=  $this->get_politic($peopletmp["p_zzmm_info"]);
            $data["is_special"]=  $this->getStrByBool($peopletmp["p_tp"]);
            $data["employee"]=  $this->get_employee($peopletmp["p_work"]);
            $data["household"]=$peopletmp["p_huji_address"];
            $data["telephone"]=$peopletmp["p_contact_tel"];
            dump($data);
            $citizen->create($data);
            $citizen->add();    
            echo "<p>转换完成</p>";
        }
    }

    function get_guanxi($guanxi) {
        switch ($guanxi) {
            case '1':
                return "户主";
                break;
            case '2':
                return "配偶";
                break;
            case '3':
                return "父亲";
                break;
            case '4':
                return "母亲";
                break;
            case '5':
                return "儿子";
                break;
            case '6':
                return "女儿";
                break;
            case '7':
                return "儿媳";
                break;
            case '8':
                return "女婿";
                break;
            case '98':
                return "非亲属";
                break;
            case '9':
                return "孙子";
                break;
            case '10':
                return "孙女";
                break;
            case '11':
                return "兄弟";
                break;
            case '12':
                return "姐妹";
                break;
            case '13':
                return "侄儿";
                break;
            case '14':
                return "侄女";
                break;
            case '99':
                return "流动人口_暂住";
                break;
        }
    }
    function get_sex($sex){
        switch ($sex){
            case "1":
                return "男";
                break;
            case "2":
                return "女";
                break;
        }
        
    }
    function get_marry($marry){
        switch ($marry){
            case "1":
                return "未婚";
                break;
            case "2":
                return "已婚";
                break;
            case "3":
                return "离异";
                break;
            case "4":
                return "丧偶";
                break;
        }
    }
    function get_education($edu){
        switch ($edu){
            case "1":
                return "文盲";
                break;
            case "2":
                return "小学";
                break;
            case "3":
                return "初中";
                break;
            case "4":
                return "高中";
                break;
            case "5":
                return "技校";
                break;
            case "6":
                return "中专";
                break;
            case "7":
                return "大专";
                break;
            case "8":
                return "本科";
                break;
            case "9":
                return "硕士";
                break;
            case "10":
                return "博士";
                break;
            case "11":
                return "博士后";
                break;
            case "12":
                return "教授";
                break;
            case "13":
                return "院士";
                break;
        }
    }
    function get_politic($politic){
        switch ($politic){
            case "1":
                return "群众";
                break;
            case "2":
                return "团员";
                break;
            case "3":
                return "党员";
                break;
        }
    }
    function get_employee($emp){
      switch ($emp){
            case "1":
                return "就业";
                break;
            case "2":
                return "未就业";
                break;
            case "3":
                return "灵活就业";
                break;
            case "4":
                return "领取失业保证金";
                break;
            case "5":
                return "在校生";
                break;
            case "6":
                return "低保";
                break;
            case "7":
                return "退休";
                break;
        }  
    }
    

}

?>
