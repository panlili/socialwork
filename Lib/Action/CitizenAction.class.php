<?php

class CitizenAction extends BaseAction {

    const ACTION_NAME = "居民";

    //index方法,展示数据列表
    public function index() {
        $Citizen = D("Citizen");
        import("ORG.Util.Page");
        $count = $Citizen->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $Citizen->relation("house")->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        $Citizen = D("Citizen");
        $id = $this->_get("id");
        $data = $Citizen->relation("house")->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Citizen/index");
        }
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        if (is_numeric($this->_get("id"))) {
            $house_id = $this->_get("id");
            $yard_id = D("House")->where("id='$house_id'")->getField("yard_id");
            $this->assign(array("house_id" => $house_id, "yard_id" => $yard_id, "page_place" => $this->getPagePlace("在当前房屋下添加居民", self::ACTION_NAME)));
            $this->display();
        } else {
            $this->assign(array("page_place" => $this->getPagePlace("新建居民", self::ACTION_NAME)));
            $this->display();
        }
    }

    public function add() {
        $newdata = array();

        $newdata["house_id"] = $_POST["house_id"];
        $newdata["yard_id"] = $_POST["yard_id"];
        $newdata["name"] = $_POST["name"];
        $newdata["relation_with_householder"] = $_POST["relation_with_householder"];
        $newdata["id_card"] = $_POST["id_card"];
        $newdata["nation"] = $_POST["nation"];
        $newdata["is_fertility"] = $_POST["is_fertility"];
        $newdata["is_special"] = $_POST["is_special"];
        $newdata["employee"] = $_POST["employee"];
        $newdata["household"] = $_POST["household"];
        $newdata["telephone"] = $_POST["telephone"];
        $newdata["education"] = $_POST["education"];
        $newdata["political_status"] = $_POST["political_status"];
        $newdata["is_long_live"] = $_POST["is_long_live"];
        $newdata["marry_info"] = $_POST["marry_info"];

        if ($_POST["dibao_jine"] == "") {
            $dibao_jine = 0;
        } else {
            $dibao_jine = $_POST["dibao_jine"];
        }
        $newdata["youfu"] = array(
            'sp_status' => $_POST["sp_status"],
            'sp_is_xfww' => $_POST["sp_is_xfww"],
            'sp_xfww_desc' => $_POST["sp_xfww_desc"],
            'sp_sqjz' => $_POST["sp_sqjz"],
            'sp_sqjz_desc' => $_POST["sp_sqjz_desc"],
            'sp_xsjj' => $_POST["sp_xsjj"],
            'sp_xsjj_desc' => $_POST["sp_xsjj_desc"],
            'sp_flgcy' => $_POST["sp_flgcy"],
            'sp_flgcy_desc' => $_POST["sp_flgcy_desc"],
            'sp_qtry' => $_POST["sp_qtry"],
            'sp_qtry_desc' => $_POST["sp_qtry_desc"],
            'is_dibao' => $_POST["is_dibao"],
            'dibao_jine' => $dibao_jine,
            'dibao_start_date' => $_POST["dibao_start_date"],
            'is_canji' => $_POST["is_canji"],
            'canji_type' => $_POST["canji_type"],
            'canji_lvl' => $_POST["canji_lvl"],
            'canji_no' => $_POST["canji_no"],
            'canji_jine' => $_POST["canji_jine"],
            'is_lianzu' => $_POST["is_lianzu"],
            'lianzu_address' => $_POST["lianzu_address"],
        );






        $Citizen = D("Citizen");

       // dump($newdata);
        $data = $Citizen->relation(true)->add($newdata);
         session("action_message", "添加数据成功");
         $this->redirect("House/newone");
//        if ($newdata = $Citizen->create()) {
//            dump($newdata);
//            //$data = $Citizen->add($newdata);
//            if (FALSE !== $data) {
//                //all redirect to House, because only add citizen from house page
//                session("action_message", "添加数据成功");
//                $this->redirect("House/index");
//            } else {
//                session("action_message", "添加新数据失败！");
//                $this->redirect("House/index");
//            }
//        } else {
//            session("action_message", $Citizen->getError());
//            $this->redirect("House/newone");
//        }
    }

    public function delete() {
        $id = $this->_get("id");
        $Citizen = D("Citizen");
        if ($Citizen->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("Citizen/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Citizen/index");
        }
    }

    public function edit() {
        $id = $this->_get("id");
        $Citizen = D("Citizen");
        $data = $Citizen->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Citizen/index");
        }
        $this->assign(array("data" => $data,
            "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        $id = $this->_post("id");
        $Citizen = D("Citizen");
        if ($newdata = $Citizen->create()) {
            //the boolean fields' check
            $newdata['is_fertility'] = null == $this->_post('is_fertility') ? '否' : '是';
            $newdata['is_special'] = null == $this->_post('is_special') ? '否' : '是';
            $newdata['is_low_level'] = null == $this->_post('is_low_level') ? '否' : '是';
            $newdata['is_disability'] = null == $this->_post('is_disability') ? '否' : '是';
            $newdata['is_low_rent'] = null == $this->_post('is_low_rent') ? '否' : '是';
            $newdata['is_long_live'] = null == $this->_post('is_long_live') ? '否' : '是';

            $data = $Citizen->save($newdata);
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Citizen/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Citizen/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Citizen->getError());
            $this->redirect("/Citizen/edit/$id");
        }
    }

    public function toexcel() {
        if ($_SESSION["right"] == "9") {
            $houseid = $this->_get("id");
            $list = D("Citizen")->relation(array("house"))->where("house_id='$houseid'")->select();
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=citizen_in_house_$houseid.xls");
            $this->assign("list", $list);
            echo $this->fetch();
        } else {
            header("Content-Type:text/html; charset=utf-8");
            echo "您没有权限执行导出操作";
        }
    }

}

?>
