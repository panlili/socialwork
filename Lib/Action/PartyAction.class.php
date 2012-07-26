<?php

class PartyAction extends BaseAction {

    const ACTION_NAME = "区域党建";

    public function index() {
        $Party = D("Party");
        import("ORG.Util.Page");
        $count = $Party->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $list = $Party->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();
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
        //router:'Party/:id\d' => 'Party/read',
        $Party = D("Party");
        $Parter = D("Parter");
        $id = $this->_get("id");
        $data = $Party->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Party/index");
        }
        $parter = $Parter->where(array("party_id" => $id))->select();
        $this->assign(array("data" => $data, "parter" => $parter, "page_place" => $this->getPagePlace("数据详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function add() {
        $Party = D("Party");
        if ($Party->create()) {
            $data = $Party->add($newdata);
            if (false !== $data) {
                session("action_message", "添加新数据成功！");
                $this->redirect("Party/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("Party/newone");
            }
        } else {
            session("action_message", $Party->getError());
            $this->redirect("Party/newone");
        }
    }

    public function edit() {
        //router:'Party/edit/:id\d'=>'Party/edit',
        $id = $this->_get("id");
        $Party = D("Party");
        $data = $Party->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Party/index");
        }

        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        //获取隐藏字段id,即要更新的数据id
        $id = $this->_post("id");
        $Party = D("Party");
        if ($newdata = $Party->create()) {
            $data = $Party->save();
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Party/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Party/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Party->getError());
            $this->redirect("/Party/edit/$id"); //必须有第一个斜杠, i was confused!!!
        }
    }

    public function delete() {
        $id = $this->_get("id");
        $Party = D("Party");
        if ($Party->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("Party/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Party/index");
        }
    }

    public function information() {
        $party = D("Party");
        $partlist = $party->relation("parter")->select();

        $this->assign(array("partylist" => $partlist, "page_place" => $this->getPagePlace("区域党建信息总览", self::ACTION_NAME)));
        $this->display();
    }

    public function partyInformation() {
        if ($this->isAjax()) {
            $partyid = $this->_get("id");
            $m_party = D("Party");
            $m_parter = D("Parter");
            $party = $m_party->where(array("id" => $partyid))->relation("parter")->find();
            $shuji = $m_parter->where(array("party_id" => $partyid, "position" => "党支部书记"))->find();
            $female = $m_parter->where(array("party_id" => $partyid, "sex" => "女"))->count();
            $this->assign(array("party" => $party, "shuji" => $shuji, "female" => $female));
            $content = $this->fetch("_right");
            header("content-type:text/html;charset=utf-8");
            echo $content;
        } else {
            $this->redirect("information");
        }
    }

    public function showParter() {
        if ($this->isAjax()) {
            $partyid = $this->_get("id");
            $category = $this->_get("category");
            $m_parter = D("Parter");
            if ($category == "all") {
                $parter = $m_parter->where(array("party_id" => $partyid))->select();
            } else {
                $parter = $m_parter->where(array("party_id" => $partyid, "category" => $category))->order("position asc")->select();
            }
            $this->assign("parter", $parter);
            $content = $this->fetch("_parterlist");
            header("content-type:text/html;charset=utf-8");
            echo $content;
        } else {
            $this->redirect("information");
        }
    }

}

?>
