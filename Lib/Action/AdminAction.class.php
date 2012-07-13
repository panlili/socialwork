<?php

//controller of table:sjf_admin
//显示所有用户列表,并在列表后出现删除用户按钮(超级管理员right=1才有)

class AdminAction extends BaseAction {

    public function _initialize() {
        parent::_initialize();
        //TODO: if not administrator, set the first page
        if (1 != $this->_session("right"))
            $this->redirect("Map/index");
    }

    public function index() {

        $Admin = D('Admin');
        $userlist = $Admin->order("id desc")->select();
        $this->assign("userlist", $userlist);
        $this->display();
    }

    public function add() {
        $Admin = D("Admin");
        if ($Admin->create()) {
            $data = $Admin->add();
            if (false !== $data) {
                session("action_message", "添加用户成功！");
                $this->redirect("Admin/index");
            } else {
                session("action_message", "数据保存到数据库错误！");
                $this->redirect("Admin/index");
            }
        } else {
            session("action_message", $Admin->getError());
            $this->redirect("Admin/index");
        }
    }

    public function delete() {
        $id = $this->_post("id");
        $Admin = D("Admin");
        if (!isset($id)) {
            //如果不是通过点击连接，而是url传递，则$id为null
            $this->redirect("Admin/index");
        } else {
            if ($Admin->where("id='$id'")->delete()) {
                $this->ajaxReturn($id, "deleted!", 1);
            } else {
                $this->ajaxReturn(0, "something wrong!", 0);
            }
        }
    }

}

?>
