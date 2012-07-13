<?php

class EmptyAction extends Action {

    public function _empty() {
        session("action_message", "非法操作！");
        $this->redirect("Login/login");
    }

}

?>
