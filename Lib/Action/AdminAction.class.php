<?php

//controller of table:sjf_admin
//显示所有用户列表,并在列表后出现删除用户按钮(超级管理员right=1才有)

class AdminAction extends BaseAction {

    public function _initialize() {
        parent::_initialize();
        //TODO: if not administrator, set the first page
        if (9 != $this->_session("right") && $Think . ACTION_NAME != "edit" && $Think . ACTION_NAME != "update")
            $this->redirect("Map/index");

        switch ($this->_session("community")) {
            case 0: C("DB_PREFIX", "sjf_");
                break;
            case 1: C("DB_PREFIX", "sjf_");
                break;
            case 2: C("DB_PREFIX", "sjf_");
                break;
        }
    }

    public function index() {
        $User = D('User');
        $userlist = $User->order("id desc")->select();
        $this->assign("userlist", $userlist);
        $this->display();
    }

    public function add() {
        $User = D("User");
        if ($User->create()) {
            $data = $User->add();
            if (false !== $data) {
                session("action_message", "添加用户成功！");
                $this->redirect("Admin/index");
            } else {
                session("action_message", "数据保存到数据库错误！");
                $this->redirect("Admin/index");
            }
        } else {
            session("action_message", $User->getError());
            $this->redirect("Admin/index");
        }
    }

    public function delete() {
        $id = $this->_post("id");
        $User = D("User");
        if (!isset($id)) {
            //如果不是通过点击连接，而是url传递，则$id为null
            $this->redirect("Admin/index");
        } else {
            $condition["id"] = $id;
            $condition["right"] = array("neq", 9);
            if ($User->where($condition)->delete()) {
                $this->ajaxReturn($id, "deleted!", 1);
            } else {
                $this->ajaxReturn(0, "something wrong!", 0);
            }
        }
    }

    public function edit() {
        $User = D("User");
        $truename = $this->_session("truename");
        $community = $this->_session("community");
        $current = $User->where(array("truename" => $truename, "community" => $community))->find();
        $this->assign("user", $current);
        $this->display();
    }

    public function update() {
        $id = $this->_post("id");
        $User = D("User");
        if ($newdata = $User->create()) {
            $newdata["password"] = md5($this->_post("password"));
            $data = $User->save($newdata);
            if (false !== $data) {
                $this->redirect('Login/logout');
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("Map/index");
            }
        } else {
            session("action_message", $User->getError());
            $this->redirect("Map/index");
        }
    }

}

?>
