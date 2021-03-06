<?php

class NgoAction extends BaseAction {

    const ACTION_NAME = "社会组织";

    public function index() {
        $Ngo = D("Ngo");
        import("ORG.Util.Page");
        $count = $Ngo->count();
        //Records_one_page = 25
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $list = $Ngo->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show();

        $this->assign(array("page" => $page, "list" => $list,
            "page_place" => $this->getPagePlace("浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        $this->assign(array("page_place" => $this->getPagePlace("添加新数据", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        //router:'Ngo/:id\d' => 'Ngo/read',
        $Ngo = D("Ngo");
        $id = $this->_get("id");
        $data = $Ngo->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Ngo/index");
        }
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("数据详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function add() {
        $Ngo = D("Ngo");
        if ($Ngo->create()) {
            $data = $Ngo->add($newdata);
            if (false !== $data) {
                session("action_message", "添加新数据成功！");
                $this->redirect("Ngo/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("Ngo/newone");
            }
        } else {
            session("action_message", $Ngo->getError());
            $this->redirect("Ngo/newone");
        }
    }

    public function edit() {
        //router:'Ngo/edit/:id\d'=>'Ngo/edit',
        $id = $this->_get("id");
        $Ngo = D("Ngo");
        $data = $Ngo->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Ngo/index");
        }

        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        //获取隐藏字段id,即要更新的数据id
        $id = $this->_post("id");
        $Ngo = D("Ngo");
        if ($newdata = $Ngo->create()) {
            $data = $Ngo->save();
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Ngo/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Ngo/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Ngo->getError());
            $this->redirect("/Ngo/edit/$id"); //必须有第一个斜杠, i was confused!!!
        }
    }

    public function delete() {
        $id = $this->_get("id");
        $Ngo = D("Ngo");
        if ($Ngo->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("Ngo/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Ngo/index");
        }
    }

    public function toexcel() {
        if ($_SESSION["right"] == "9" || $_SESSION["right"] == "1") {
            $list = D("Ngo")->select();
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=ngo.xls");
            $this->assign("list", $list);
            echo $this->fetch();
        }  else {
            header("Content-Type:text/html; charset=utf-8");
            echo "你没有权限执行导出操作";
        }

    }

}

?>
