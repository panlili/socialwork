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
        $Citizen = D("Citizen");
        if ($newdata = $Citizen->create()) {
            $newdata["birthday"] = $this->getBirthdayByIdCard(trim($this->_post("id_card")));
            $data = $Citizen->add($newdata);
            if (FALSE !== $data) {
                //all redirect to House, because only add citizen from house page
                session("action_message", "添加数据成功");
                $this->redirect("House/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("House/index");
            }
        } else {
            session("action_message", $Citizen->getError());
            $this->redirect("House/newone");
        }
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

            $newdata["birthday"] = $this->getBirthdayByIdCard(trim($this->_post("id_card")));

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

    private function getBirthdayByIdCard($idcard) {
        if (!empty($idcard)) {
            $birthday = "";
            if (18 == strlen($idcard)) {
                $birthday = substr($idcard, 6, 4);
                $birthday .= "-" . substr($idcard, 10, 2);
                $birthday .= "-" . substr($idcard, 12, 2);
            } else if (15 == strlen($idcard)) {
                $birthday = "19" . substr($idcard, 6, 2);
                $birthday .= "-" . substr($idcard, 8, 2);
                $birthday .= "-" . substr($idcard, 10, 2);
            }
            return $birthday;
        }
    }

    public function toexcel() {
        $houseid = $this->_get("id");
        $list = D("Citizen")->relation(array("house"))->where("house_id='$houseid'")->select();
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=citizen_in_house_$houseid.xls");
        $this->assign("list", $list);
        echo $this->fetch();
    }

}

?>
