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

    protected function getYoufuIdByOldId($oldid) {
        $youfu = D("youfu");
        $result = $youfu->where("youfu_id='" . $oldid . "'")->find();
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

    public function convertstreet() {
        $street1 = M("address", null)->select();
        echo "<p>街区道路信息表转换，转换address到street</p>";
        $street = D("Street");
        foreach ($street1 as $streettmp) {
            $data["id"] = (int) $streettmp["a_street_id"];
            $data["name"] = $streettmp["a_alias_name"];
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
            //$data["id"] = $yardtmp["id"];
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

    public function convertyoufu() {
        //转换优抚表内的数据
        $youfu1 = M("youfu", null)->select();
        echo "<p>优抚表转换，转换youfu到youfu……</p>";
        $youfu = D("Youfu");
        foreach ($youfu1 as $youfutmp) {
            $data["youfu_id"] = $youfutmp["p_youfu_id"];
            $data["is_dibao"] = $this->getStrByBool($youfutmp["p_bdibao"]);
            $data["dibao_jine"] = $youfutmp["p_dibao_jine"];
            $data["dibao_start_date"] = $youfutmp["p_dibao_start_date"];
            $data["is_lianzu"] = $this->getStrByBool($youfutmp["p_blianzu"]);
            $data["lianzu_address"] = $youfutmp["p_lianzu_address"];
            $data["is_lzbt"] = $this->getStrByBool($youfutmp["p_blianzu_btjine"]);
            $data["lzbt_jine"] = $youfutmp["p_lianzu_btjine"];
            $data["is_lzzj"] = $this->getStrByBool($youfutmp["p_blianzu_zhjine"]);
            $data["lzzj_jine"] = $youfutmp["p_lianzu_zhjine"];
            $data["is_lianzu_swpz"] = $this->getStrByBool($youfutmp["p_blianzu_swpz"]);
            $data["lianzu_swpz"] = $youfutmp["p_lianzu_swpz"];
            $data["is_jjsyf"] = $this->getStrByBool($youfutmp["p_bjjsyf"]);
            $data["is_taishu"] = $this->getStrByBool($youfutmp["p_btaishu"]);
            $data["is_junshu"] = $this->getStrByBool($youfutmp["p_bjunshu"]);
            $data["is_canji"] = $this->getStrByBool($youfutmp["p_bcanji"]);
            $data["canji_type"] = $this->get_canji_type($youfutmp["p_canji_type"]);
            $data["canji_lvl"] = $this->get_canji_lvl($youfutmp["p_canji_lvl"]);
            $data["canji_no"] = $youfutmp["p_canji_no"];
            $data["canji_jine"] = $youfutmp["p_canji_jine"];
            $data["ranmei"] = $this->getStrByBool($youfutmp["p_ranmei"]);
            $data["jzqk"] = $this->get_jzqk($youfutmp["p_jzqk"]);
            $data["srly"] = $this->get_srly($youfutmp["p_srly"]);
            $data["stzk"] = $this->get_stzk($youfutmp["p_stzk"]);
            $data["ylfs"] = $this->get_ylfs($youfutmp["p_ylfs"]);
            $data["xuqiu"] = $this->get_xuqiu($youfutmp["p_xuqiu"]);
            $data["sp_status"] = $this->get_sp_stat($youfutmp["sp_status"]);
            $data["sp_is_xfww"] = $this->getStrByBool($youfutmp["sp_xfww"]);
            $data["sp_xfww_desc"] = $youfutmp["sp_xfww_desc"];
            $data["sp_sqjz"] = $this->getStrByBool($youfutmp["sp_sqjz"]);
            $data["sp_sqjz_desc"] = $youfutmp["sp_sqjz_desc"];
            $data["sp_xsjj"] = $this->getStrByBool($youfutmp["sp_xsjj"]);
            $data["sp_xsjj_desc"] = $youfutmp["sp_xsjj_desc"];
            $data["sp_flgcy"] = $this->getStrByBool($youfutmp["sp_flgcy"]);
            $data["sp_flgcy_desc"] = $youfutmp["sp_flgcy_desc"];
            $data["sp_qtry"] = $this->getStrByBool($youfutmp["sp_qtry"]);
            $data["sp_qtry_desc"] = $youfutmp["sp_qtry_desc"];
            $data["last_date"] = $youfutmp["last_date"];
            $data["rec_date"] = $youfutmp["rec_date"];
            $data["update_use"] = $youfutmp["update_use"];
            $data["status"] = $youfutmp["status"];
            $youfu->create($data);
            $youfu->add();
            dump($data);
        }
        echo "<p>优抚表转换完成</p>";
    }

    public function converthouse() {
        $house1 = M("house_base", null)->select();
        echo "<p>房屋（户）表转换，转换house_base到house</p>";
        $house = D("House");
        foreach ($house1 as $housetmp) {
            //$data["id"] = $housetmp["id"];
            $data["house_id"] = $housetmp["house_id"]; //houseid是增加的一个临时字段
            $x = "";
            if ($housetmp["h_address_mlh"] == '0' || $housetmp["h_address_mlh"] == "") {
                $x = $this->getStreetNameById((int) ($housetmp["h_address_jlx"]));
            } else {
                $x = $this->getStreetNameById((int) ($housetmp["h_address_jlx"])) . $housetmp["h_address_mlh"] . "号";
            }
            $data["yard_id"] = $this->getYardIdByAddress($x);
            $data["youfu_id"] = $this->getYoufuIdByOldId($housetmp["p_youfu_id"]);
            $data["address_1"] = $housetmp["h_address_ldh"];
            $data["address_2"] = $housetmp["h_address_dyh"];
            $data["address_3"] = $housetmp["h_address_step"];
            $data["address_4"] = $housetmp["h_address_mph"];
            $data["contactor"] = $housetmp["h_contact_name"];
            $data["telephone"] = $housetmp["h_contact_tel"];
            $data["is_fit"] = $this->getStrByBool($housetmp["h_perdoor_accord"]);
            $data["is_free"] = $this->getStrByBool($housetmp["h_bidle"]);
            $data["address"] = $this->getYardNameById($data["yard_id"]) . $data["address_1"] . "栋" . $data["address_2"] . "单元" . $data["address_3"] . "楼" . $data["address_4"] . "号";
            dump($data);
            $house->create($data);
            $house->add();
        }
        echo "<p>房屋表转换完成</p>";
    }

    public function convertcitizen() {
        $people1 = M("people", null)->select();
        echo "<p>居民表转换，转换people到citizen";
        $citizen = D("Citizen");
        foreach ($people1 as $peopletmp) {

            $data["house_id"] = $this->getHouseIdByOldId($peopletmp["house_id"]);
            $data["youfu_id"] = $this->getYoufuIdByOldId($peopletmp["p_youfu_id"]);
            $data["name"] = $peopletmp["p_name"];
            $data["relation_with_householder"] = $this->get_guanxi($peopletmp["p_guanxi"]);
            $data["id_card"] = $peopletmp["p_sfzid"];
            $data["sex"] = $this->get_sex($peopletmp["p_sex"]);
            $data["nation"] = "汉族";
            $data["birthday"] = getBirthdayByIdCard($peopletmp["p_sfzid"]);
            $data["is_fertility"] = $this->getStrByBool($peopletmp["p_syzb"]);
            $data["marry_info"] = $this->get_marry($peopletmp["p_marry_info"]);
            $data["education"] = $this->get_education($peopletmp["p_edu_info"]);
            $data["political_status"] = $this->get_politic($peopletmp["p_zzmm_info"]);
            $data["is_special"] = $this->getStrByBool($peopletmp["p_tp"]);
            $data["employee"] = $this->get_employee($peopletmp["p_work"]);
            $data["household"] = $peopletmp["p_huji_address"];
            $data["telephone"] = $peopletmp["p_contact_tel"];
            $data["is_long_live"] = $this->getStrByBool($peopletmp["p_csj"]);
            dump($data);
            $citizen->create($data);
            $citizen->add();
        }
        echo "<p>居民表转换完成</p>";
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

    function get_sex($sex) {
        switch ($sex) {
            case "1":
                return "男";
                break;
            case "2":
                return "女";
                break;
        }
    }

    function get_marry($marry) {
        switch ($marry) {
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

    function get_education($edu) {
        switch ($edu) {
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

    function get_politic($politic) {
        switch ($politic) {
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

    function get_employee($emp) {
        switch ($emp) {
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

    function get_canji_type($x) {
        switch ($x) {
            case "1":
                return "视力";
                break;
            case "2":
                return "听力语言";
                break;
            case "3":
                return "精神";
                break;
            case "4":
                return "智力";
                break;
            case "5":
                return "肢体";
                break;
        }
    }

    function get_canji_lvl($x) {
        switch ($x) {
            case "1":
                return "一级";
                break;
            case "2":
                return "二级";
                break;
            case "3":
                return "三级";
                break;
            case "4":
                return "四级";
                break;
        }
    }

    function get_jzqk($emp) {
        switch ($emp) {
            case "1":
                return "独居";
                break;
            case "2":
                return "偶居";
                break;
            case "3":
                return "孤老";
                break;
            case "4":
                return "与子女居住";
                break;
        }
    }

    function get_srly($emp) {
        switch ($emp) {
            case "1":
                return "享受低保";
                break;
            case "2":
                return "其他来源";
                break;
           
        }
    }
     function get_stzk($emp){
      switch ($emp){
            case "1":
                return "完全自理";
                break;
            case "2":
                return "部分自理";
                break;
            case "3":
                return "依赖照顾";
                break;
           
        }  
    }
     function get_ylfs($emp){
      switch ($emp){
            case "1":
                return "居家养老____享受政府购买";
                break;
            case "2":
                return "居家养老____自己购买服务";
                break;
            case "3":
                return "居家养老____还未购买服务";
                break;
            case "4":
                return "机构养老";
                break;
            
        }  
    }
    function get_xuqiu($x) {
        switch ($x) {
            case "1":
                return "家政服务";
                break;
            case "2":
                return "代缴费（水、电、气电话）";
                break;
            case "3":
                return "物品代购";
                break;
            case "4":
                return "生活护理";
                break;
            case "5":
                return "社区文娱活动";
                break;
            case "6":
                return "老年餐厅";
                break;
            case "7":
                return "老年大学";
                break;
            case "8":
                return "陪伴服务";
                break;
            case "9":
                return "其他";
                break;
            
        }
    }
    function get_personal_stat($emp){
      switch ($emp){
            case "1":
                return "正常";
                break;
            case "2":
                return "删除/迁出";
                break;
            case "3":
                return "死亡";
                break;
           
        }  
    }
     function get_sp_stat($emp){
      switch ($emp){
            case "0":
                return "不是";
                break;
            case "1":
                return "正在处理";
                break;
            case "2":
                return "息诉息访";
                break;
           
        }  
    }

}

?>
