<?php

class CitizenAction extends BaseAction {

    const ACTION_NAME = "居民";

    //index方法,展示数据列表
    public function index() {
        $m_citizen = D("Citizen");
        import("ORG.Util.Page");
        $count = $m_citizen->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $m_citizen->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        $m_citizen = D("Citizen");
        $id = $this->_get("id");
        $data = $m_citizen->relation(array("house","youfu"))->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Citizen/index");
        }
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        $house_id = $this->_get("id");
        if (isset($house_id)) {
            $yard_id = D("House")->where("id=$house_id")->getField("yard_id");
            //取得房屋的低保，廉租信息，只有房屋低保廉租了，居民才有低保廉租选项
            $is_dibao = D("Youfu")->where("house_id=$house_id")->getField("is_dibao");
            $is_lianzu = D("Youfu")->where("house_id=$house_id")->getField("is_lianzu");
            $this->assign(array("house_id" => $house_id, "yard_id" => $yard_id,
                "is_dibao" => $is_dibao, "is_lianzu" => $is_lianzu, "page_place" => $this->getPagePlace("在当前房屋下添加居民", self::ACTION_NAME)));
            $this->display();
        } else {
            session("action_message", "请在房屋下添加居民");
            $this->redirect("House/index");
        }
    }

    public function add() {
        $m_citizen = D("Citizen");
        $m_youfu = D("Youfu");
        $house_id = $_POST["house_id"];
        if ($m_citizen->create()) {
            if ($new_citizen_id = $m_citizen->add()) {
                $new_youfu = $m_youfu->create();
                $new_youfu["citizen_id"] = $new_citizen_id;
                //居民属于房屋，故表单中有house_id,需要在居民的youfu行中屏蔽
                $new_youfu["house_id"] = null;
                $new_youfu["citizen_id"] = $new_citizen_id;
                $m_youfu->add($new_youfu);

                session("action_message", "添加居民成功！");
                $this->redirect("House/$house_id");
            } else {
                session("action_message", "添加居民数据失败！");
                $this->redirect("House/index");
            }
        } else {
            session("action_message", $m_citizen->getError());
            $this->redirect("House/index");
        }
    }

    public function delete() {
        $m_citizen = D("Citizen");
        if ($m_citizen->relation("youfu")->delete($this->_get("id"))) {
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
