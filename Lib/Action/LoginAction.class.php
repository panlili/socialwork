<?php

class LoginAction extends Action {

    public function login() {
        //如果用户已登录，避免其再次访问登陆页面，直接跳到显示登陆后第一个页面
        //否则进入login页面
        if (session("?truename")) {
            //TODO: modify the first page to be enter
            $this->redirect("Admin/index");
        }

        //如果验证码错误
        if (session("?loginMessage")) {
            $this->assign("loginMessage", $this->_session("loginMessage"));
            session("loginMessage", null);
        }
        $this->display();
    }

    public function verify() {
        import('ORG.Util.Image');
        Image::buildImageVerify(4, 1, "png", 60, 32);
    }

    public function checkUser() {
        //首先进行验证码验证,不符合直接返回登陆页面,不需要对数据库进行访问
        if ($this->_session("verify") != md5($this->_post("verify"))) {
            session("loginMessage", "验证码错误");
            $this->redirect("Login/login");
        }

        //其次对用户验证, 最开始使用的数据库是config.php中定义的sjf_为前缀的user库
        $User = D('User');
        $username = $this->_post("username");
        $password = md5($this->_post("password"));
        //use array for where will be more safe
        $condition["username"] = $username;
        $condition["password"] = $password;
        $condition["_logic"] = "AND";
        $result = $User->where($condition)->find();
        if ($result) {
            session("username", $result["username"]);
            session("truename", $result["truename"]);
            session("right", $result["right"]);
            session("community", $result["community"]);
            //TODO: modify the first page to be enter
            $this->redirect("Map/index");
        } else {
            session("loginMessage", "用户验证失败");
            $this->redirect("Login/login");
        }
    }

    public function logout() {
        session(null);
        $this->redirect("Login/login");
    }

    public function _empty() {
        $this->redirect("Login/login");
    }

}

?>
