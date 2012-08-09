<?php

class StoreAction extends BaseAction {

    const ACTION_NAME = "商铺";

    public function index() {
        $Store = D("Store");
        import("ORG.Util.Page");
        $count = $Store->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $list = $Store->relation("street")->order("id desc, street_id desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        //$list = $Store->relation("street")->limit(self::RECORDS_ONE_PAGE)->page(empty($_GET['p']) ? 1 : $_GET['p'])->order('id desc')->select();
        $page = $p->show();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        //router:'Store/:id\d' => 'Store/read',
        $Store = D("Store");
        $id = $this->_get("id");
        $data = $Store->relation("street")->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Store/index");
        }
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        $this->assign(array("page_place" => $this->getPagePlace("添加新数据", self::ACTION_NAME)));
        $this->display();
    }

    public function add() {
        $Store = D("Store");
        if ($Store->create()) {
            $data = $Store->add();
            if (false !== $data) {
                session("action_message", "添加新数据成功！");
                $this->redirect("Store/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("Store/newone");
            }
        } else {
            session("action_message", $Store->getError());
            $this->redirect("Store/newone");
        }
    }

    public function edit() {
        //router:'Store/edit/:id\d'=>'Store/edit',
        $id = $this->_get("id");
        $Store = D("Store");
        $data = $Store->relation(true)->where(array("id" => $id))->find();
        if (is_null($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Store/index");
        }

        $this->assign(array("data" => $data,"page_place" => $this->getPagePlace("修改数据", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        //获取隐藏字段id,即要更新的数据id
        $id = $this->_post("id");
        $Store = D("Store");
        if ($newdata = $Store->create()) {
            $data = $Store->save();
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Store/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Store/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Store->getError());
            $this->redirect("/Store/edit/$id"); //必须有第一个斜杠, i was confused!!!
        }
    }

    public function delete() {
        $id = $this->_get("id");
        $Store = D("Store");
        if ($Store->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("Store/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Store/index");
        }
    }

}

?>
