<?php

class ParterAction extends BaseAction {

    const ACTION_NAME = "党员档案";

    public function _initialize() {
        if (!session("?truename"))
            $this->redirect("Login/login");

        switch ($this->_session("community")) {
            case 0: C("DB_PREFIX", "sum_");
                break;
            case 1: C("DB_PREFIX", "sjf_");
                break;
            case 2: C("DB_PREFIX", "sjf_");
                break;
        }
    }

    public function index() {
        $Parter = D("Parter");
        import("ORG.Util.Page");
        $count = $Parter->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $list = $Parter->relation(true)->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show();

        $this->assign(array("page" => $page, "list" => $list,
            "page_place" => $this->getPagePlace("浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        $this->assign(array("partylist" => D("Party")->select(), "page_place" => $this->getPagePlace("添加新数据", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        //router:'Parter/:id\d' => 'Parter/read',
        $Parter = D("Parter");
        $id = $this->_get("id");
        $data = $Parter->where(array("id" => $id))->relation("party")->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Parter/index");
        }
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("数据详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function add() {
        $Parter = D("Parter");
        if ($Parter->create()) {
            $data = $Parter->add($newdata);
            if (false !== $data) {
                session("action_message", "添加新数据成功！");
                $this->redirect("Parter/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("Parter/newone");
            }
        } else {
            session("action_message", $Parter->getError());
            $this->redirect("Parter/newone");
        }
    }

    public function edit() {
        //router:'Parter/edit/:id\d'=>'Parter/edit',
        $id = $this->_get("id");
        $Parter = D("Parter");
        $data = $Parter->where(array("id" => $id))->relation(true)->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Parter/index");
        }

        $this->assign(array("partylist" => D("Party")->select(), "data" => $data, "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        //获取隐藏字段id,即要更新的数据id
        $id = $this->_post("id");
        $Parter = D("Parter");
        if ($newdata = $Parter->create()) {
            $data = $Parter->save();
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Parter/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Parter/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Parter->getError());
            $this->redirect("/Parter/edit/$id"); //必须有第一个斜杠, i was confused!!!
        }
    }

    public function delete() {
        $id = $this->_get("id");
        $Parter = D("Parter");
        if ($Parter->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("Parter/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Parter/index");
        }
    }

}

?>
