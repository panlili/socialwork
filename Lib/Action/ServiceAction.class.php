<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ServiceAction extends BaseAction{
     const ACTION_NAME = "服务信息";

    //index方法,展示数据列表
    public function index() {
        $Service = D("Service");
        import("ORG.Util.Page");
        $count = $Service->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $Service->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        $Service = D("Service");
        $id = $this->_get("id");
        $data = $Service->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Service/index");
        }
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        if (is_numeric($this->_get("id"))) {
         
            $this->display();
        } else {
            $this->assign(array("page_place" => $this->getPagePlace("添加服务信息", self::ACTION_NAME)));
            $this->display();
        }
    }

    public function add() {
        $Service = D("Service");
        if ($newdata = $Service->create()) {
            $newdata["district"]="锦江区";
            $newdata["street_office"]="水井坊街道办事处";
            $newdata["community"]="水井坊社区";
            $data = $Service->add($newdata);
            if (FALSE !== $data) {
                //all redirect to House, because only add Service from house page
                session("action_message", "添加数据成功");
                $this->redirect("Service/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("Service/index");
            }
        } else {
            session("action_message", $Service->getError());
            $this->redirect("Service/newone");
        }
    }

    public function delete() {
        $id = $this->_get("id");
        $Service = D("Service");
        if ($Service->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("Service/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Service/index");
        }
    }

    public function edit() {
        $id = $this->_get("id");
        $Service = D("Service");
        $data = $Service->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Service/index");
        }
        $this->assign(array("data" => $data,
            "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        $id = $this->_post("id");
        $Service = D("Service");
        if ($newdata = $Service->create()) {
           $newdata["district"]="锦江区";
            $newdata["street_office"]="水井坊街道办事处";
            $newdata["community"]="水井坊社区";
            $data = $Service->save($newdata);
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Service/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Service/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Service->getError());
            $this->redirect("/Service/edit/$id");
        }
    }

    public function toexcel() {
        $houseid = $this->_get("id");
        $list = D("Old")->relation(array("house"))->where("house_id='$houseid'")->select();
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=Old_in_house_$houseid.xls");
        $this->assign("list", $list);
        echo $this->fetch();
    }
}
?>
