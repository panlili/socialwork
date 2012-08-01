<?php

class StreetAction extends BaseAction {

    const ACTION_NAME = "街道";

    public function index() {
        $Street = D("Street");
        import("ORG.Util.Page");
        $count = $Street->count();
        //street页面比较简单，删除修改都在一个页面完成，故将分页数量弄大，基本就不需要分页处理了。
        //但代码还先保留
        $p = new Page($count, 50);
        //$list = $Street->relation(array("organization", "store"))->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $list = $Street->order("id asc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function insert() {
        $Street = D("Street");
        if ($Street->create()) {
            $data = $Street->add();
            if (FALSE !== $data) {
                session("action_message", "添加数据成功!");
                $this->redirect("Street/index");
            } else {
                $this->error("添加街道信息不能写入数据库");
            }
        } else {
            session("action_message", $Street->getError());
            $this->redirect("Street/index");
        }
    }

    public function edit() {
        $id = $this->_get("id");
        $Street = D("Street");
        if (is_numeric($id)) {
            $list = $Street->where("id='$id'")->find();
            if (isset($list)) {
                $this->assign('list', $list);
                $this->display();
            } else {
                $this->redirect("Street/index");
            }
        } else {
            $this->redirect("Street/index");
        }
    }

    public function update() {
        //$id = $this->_post("id");
        $Street = D("Street");
        if ($Street->create()) {
            $data = $Street->save();
            if (false !== $data) {
                $this->ajaxReturn("", "修改数据成功", 1);
            } else {
                $this->ajaxReturn("", "修改数据失败", 0);
            }
        } else {
            $this->ajaxReturn("", $Street->getError(), 0);
        }
    }

    public function delete() {
        $id = $this->_post("id");
        $Street = D("Street");
        if (!isset($id)) {
            $this->redirect("Street/index");
        } else {
            if ($Street->where("id='$id'")->delete()) {
                $this->ajaxReturn($id, "deleted!", 1);
            } else {
                $this->ajaxReturn(0, "something wrong!", 0);
            }
        }
    }

    //下面是information使用的ajax方法
    public function information() {
        $org = D("Organization");
        $street_list = D("Street")->relation(array("yard", "organization", "store"))->select();
        foreach ($street_list as &$list) {
            $list["govcount"] = $org->where(array("street_id" => $list["id"], "type" => "国家机关"))->count();

            $condition["street_id"] = $list["id"];
            $condition["type"] = array("neq", "国家机关");
            $list["other"] = $org->where($condition)->count();
        }
        $this->assign(array("list" => $street_list, "page_place" => $this->getPagePlace("街道信息总览", self::ACTION_NAME)));
        $this->display();
    }

    public function showYard() {
        if ($this->isAjax()) {
            $streetid = $this->_get("id");
            $yardlist = D("Yard")->where(array("street_id" => $streetid))->select();
            $this->assign("yardlist", $yardlist);
            $content = $this->fetch("_right");
            header("content-type:text/html;charset=utf-8");
            echo $content;
        } else {
            $this->redirect("information");
        }
    }

    public function showOrganization() {
        if ($this->isAjax()) {
            $streetid = $this->_get("id");
            $type = $this->_get("type");
            if ($type == "gov") {
                $orglist = D("Organization")->where(array("street_id" => $streetid, "type" => "国家机关"))->select();
                $this->assign("orglist", $orglist);
                $content = $this->fetch("_right");
                header("content-type:text/html;charset=utf-8");
                echo $content;
            } else if ($type == "other") {
                $condition["street_id"] = $streetid;
                $condition["type"] = array("neq", "国家机关");
                $orglist = D("Organization")->where($condition)->select();
                $this->assign("orglist", $orglist);
                $content = $this->fetch("_right");
                header("content-type:text/html;charset=utf-8");
                echo $content;
            }
        } else {
            $this->redirect("information");
        }
    }

    public function showStore() {
        if ($this->isAjax()) {
            $streetid = $this->_get("id");
            $storelist = D("Store")->where(array("street_id" => $streetid))->select();
            $this->assign("storelist", $storelist);
            $content = $this->fetch("_right");
            header("content-type:text/html;charset=utf-8");
            echo $content;
        } else {
            $this->redirect("information");
        }
    }

    //ajax分页的实验函数
    public function pagea() {
        $Street = D("Street");
        import("@.ORG.Pagea");
        $count = $Street->count();
        $p = new Pagea($count, 5, 'type=1', 'test', 'pages');
        $list = $Street->limit($p->firstRow . ',' . $p->listRows)->select();

        $p->setConfig('header', '条数据');
        $p->setConfig('prev', "<");
        $p->setConfig('next', '>');
        $p->setConfig('first', '<<');
        $p->setConfig('last', '>>');
        $page = $p->show();            //分页的导航条的输出变量
        $this->assign("page", $page);
        $this->assign("list", $list); //数据循环变量
        if ($this->isAjax()) {//判断ajax请求
            exit($this->fetch('_list'));
        }
        $this->display();
    }

}

?>
