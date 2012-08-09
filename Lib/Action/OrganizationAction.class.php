<?php

class OrganizationAction extends BaseAction {

    const ACTION_NAME = "单位";

    public function index() {
        $Organization = D("Organization");
        //导入分页,每页10条记录
        import("ORG.Util.Page");
        $count = $Organization->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $list = $Organization->relation("street")->order("id desc,street_id desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        //router:'Organization/:id\d' => 'Organization/read',
        $Organization = D("Organization");
        $id = $this->_get("id");
        $data = $Organization->relation(true)->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Organization/index");
        }
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        $this->assign(array("page_place" => $this->getPagePlace("添加新数据", self::ACTION_NAME)));
        $this->display();
    }

    public function add() {
        $Organization = D("Organization");
        $streetid = $this->_post("street_id");
        if ($newdata = $Organization->create()) {
            $newdata["address"] = D("Street")->where("id='$streetid'")->getField("name");
            $newdata["address"].=$this->_post("address");
            $data = $Organization->add($newdata);
            if (false !== $data) {
                session("action_message", "添加新数据成功！");
                $this->redirect("Organization/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("Organization/newone");
            }
        } else {
            session("action_message", $Organization->getError());
            $this->redirect("Organization/newone");
        }
    }

    public function edit() {
        //router:'Organization/edit/:id\d'=>'Organization/edit',
        $id = $this->_get("id");
        $Organization = D("Organization");
        $data = $Organization->relation(true)->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Organization/index");
        }
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("编辑数据", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        //获取隐藏字段id,即要更新的数据id
        $id = $this->_post("id");
        $streetid = $this->_post("street_id");
        $Organization = D("Organization");
        if ($newdata = $Organization->create()) {
            $newdata["address"] = D("Street")->where("id='$streetid'")->getField("name");
            $newdata["address"].=$this->_post("address");
            $data = $Organization->save($newdata);
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Organization/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Organization/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Organization->getError());
            $this->redirect("/Organization/edit/$id"); //必须有第一个斜杠, i was confused!!!
        }
    }

    public function delete() {
        $id = $this->_get("id");
        $Organization = D("Organization");
        if ($Organization->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("Organization/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Organization/index");
        }
    }

}

?>
