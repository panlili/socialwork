<?php

class StatisticAction extends BaseAction {

    const ACTION_NAME = "民生民情统计";

    public function index() {
        $total = D("Citizen")->count();
        $this->assign("total", $total);
        $this->assign("page_place", $this->getPagePlace("民生民情统计", self::ACTION_NAME));
        $this->display();
    }

    public function marryinfo() {
        if ($this->isAjax()) {
            $citizen = D("Citizen");
            $arr = array();
            $i = 0;
            $marrytype = $citizen->field("marry_info")->group("marry_info")->select();
            foreach ($marrytype as $t) {
                if ($t["marry_info"] != "" && $t["marry_info"] != null) {
                    $arr[$t['marry_info']] = $citizen->where(array("marry_info" => $t['marry_info']))->count();
                }
                $i+= $arr[$t['marry_info']];
            }
            if (0 != intval($citizen->count()) - $i) {
            //    $arr["不详"] = intval($citizen->count()) - $i;
            }
            $this->assign("marryinfo", $arr);
            header("content-type:text/html;charset=utf-8");
            $content = $this->fetch("_statistic");
            echo $content;
        } else {
            $this->redirect("index");
        }
    }

    public function sexinfo() {
        if ($this->isAjax()) {
            $citizen = D("Citizen");
            $arr = array();
            $i = 0;
            $sextype = $citizen->field("sex")->group("sex")->select();
            foreach ($sextype as $t) {
                if ($t["sex"] != "" && $t["sex"] != null) {
                    $arr[$t['sex']] = $citizen->where(array("sex" => $t['sex']))->count();
                }
                $i+= $arr[$t['sex']];
            }
            if (0 != intval($citizen->count()) - $i) {
            //    $arr["不详"] = intval($citizen->count()) - $i;
            }
            $this->assign("sexinfo", $arr);
            header("content-type:text/html;charset=utf-8");
            $content = $this->fetch("_statistic");
            echo $content;
        } else {
            $this->redirect("index");
        }
    }

    public function nationinfo() {
        if ($this->isAjax()) {
            $citizen = D("Citizen");
            $arr = array();
            $i = 0;
            $nationtype = $citizen->field("nation")->group("nation")->select();
            foreach ($nationtype as $t) {
                if ($t["nation"] != "" && $t["nation"] != null) {
                    $arr[$t['nation']] = $citizen->where(array("nation" => $t['nation']))->count();
                }
                $i+= $arr[$t['nation']];
            }
            if (0 != intval($citizen->count()) - $i) {
            //    $arr["不详"] = intval($citizen->count()) - $i;
            }
            $this->assign("nationinfo", $arr);
            header("content-type:text/html;charset=utf-8");
            $content = $this->fetch("_statistic");
            echo $content;
        } else {
            $this->redirect("index");
        }
    }

    public function educationinfo() {
        if ($this->isAjax()) {
            $citizen = D("Citizen");
            $arr = array();
            $i = 0;
            $educationtype = $citizen->field("education")->group("education")->select();
            foreach ($educationtype as $t) {
                if ($t["education"] != "" && $t["education"] != null) {
                    $arr[$t['education']] = $citizen->where(array("education" => $t['education']))->count();
                }
                $i+= $arr[$t['education']];
            }
            if (0 != intval($citizen->count()) - $i) {
            //    $arr["不详"] = intval($citizen->count()) - $i;
            }
            $this->assign("educationinfo", $arr);
            header("content-type:text/html;charset=utf-8");
            $content = $this->fetch("_statistic");
            echo $content;
        } else {
            $this->redirect("index");
        }
    }

    public function politicalinfo() {
        if ($this->isAjax()) {
            $citizen = D("Citizen");
            $arr = array();
            $i = 0;
            $politicaltype = $citizen->field("political_status")->group("political_status")->select();
            foreach ($politicaltype as $t) {
                if ($t["political_status"] != "" && $t["political_status"] != null) {
                    $arr[$t['political_status']] = $citizen->where(array("political_status" => $t['political_status']))->count();
                }
                $i+= $arr[$t['political_status']];
            }
            if (0 != intval($citizen->count()) - $i) {
            //    $arr["不详"] = intval($citizen->count()) - $i;
            }
            $this->assign("politicalinfo", $arr);
            header("content-type:text/html;charset=utf-8");
            $content = $this->fetch("_statistic");
            echo $content;
        } else {
            $this->redirect("index");
        }
    }

    public function employeeinfo() {
        if ($this->isAjax()) {
            $citizen = D("Citizen");
            $arr = array();
            $i = 0;
            $employeetype = $citizen->field("employee")->group("employee")->select();
            foreach ($employeetype as $t) {
                if ($t["employee"] != "" && $t["employee"] != null) {
                    $arr[$t['employee']] = $citizen->where(array("employee" => $t['employee']))->count();
                }
                $i+= $arr[$t['employee']];
            }
            if (0 != intval($citizen->count()) - $i) {
            //    $arr["不详"] = intval($citizen->count()) - $i;
            }
            $this->assign("employeeinfo", $arr);
            header("content-type:text/html;charset=utf-8");
            $content = $this->fetch("_statistic");
            echo $content;
        } else {
            $this->redirect("index");
        }
    }

}

?>
