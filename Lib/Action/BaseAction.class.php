<?php

class BaseAction extends Action {
    //定义类常量，统一管理每页出现记录书目

    const RECORDS_ONE_PAGE = 25;

    public function _initialize() {
        if (!session("?truename"))
            $this->redirect("Login/login");
    }

    public function _empty() {

        if ($this->getActionName() == "Base") {
            $this->redirect("Login/login");
        } else {
            session("action_message", "非法操作！");
            $this->redirect($this->getActionName() . "/index");
        }
    }

    protected function getPagePlace($method, $action, $link = "index") {
        $at=$this->getActionName();
        return "<a href=__APP__/$at/$link>" . $action . "</a>--" . $method.
                "--<a href='#' onclick='javascript:window.history.back();return false;' />返回</a>";
    }

    public function search() {
        $searchField = $this->_post('searchField') ? $this->_post('searchField') : "name";
        $keyword = trim($this->_post('searchKey'));
        if ("" == $keyword) {
            session("action_message", "请输入查询关键字!");
            $this->redirect("index");
        }

        $action = $this->getActionName();
        switch ($action) {
            case "Ngo": $page_place = $this->getPagePlace("数据查询结果", "社会组织");
                break;
            case "Organization": $page_place = $this->getPagePlace("数据查询结果", "单位");
                break;
            case "Store": $page_place = $this->getPagePlace("数据查询结果", "商铺");
                break;
            case "Yard": $page_place = $this->getPagePlace("数据查询结果", "院落");
                break;
            case "Street": $page_place = $this->getPagePlace("数据查询结果", "街道");
                break;
            case "House": $page_place = $this->getPagePlace("数据查询结果", "房屋");
                break;
            case "Citizen": $page_place = $this->getPagePlace("数据查询结果", "居民");
                break;
            case "Camera": $page_place = $this->getPagePlace("数据查询结果", "监控点位");
                break;
            case "Old": $page_place = $this->getPagePlace("数据查询结果", "老人信息");
                break;
            case "Service": $page_place = $this->getPagePlace("数据查询结果", "服务信息");
                break;
            default:break;
        }

        $model = D($action);
        $condition[$searchField] = array('like', '%' . $keyword . '%');
        $result = $model->relation(true)->where($condition)->select();
        if (!empty($result)) {
            $page = '查询到相关数据 ' . count($result) . ' 条';
            $this->assign(array('page' => $page, 'list' => $result, "page_place" => $page_place));
            session("action_message", "查询数据成功");
            $this->display("index");
        } else {
            session("action_message", "无相关数据");
            $this->redirect("index");
        }
    }

}

?>
