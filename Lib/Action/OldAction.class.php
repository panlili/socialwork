
<?php

// put your code here
class OldAction extends BaseAction {

    const ACTION_NAME = "养老服务";

    //index方法,展示数据列表
    public function index() {
        $Old = D("Old");
        import("ORG.Util.Page");
        $count = $Old->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $Old->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        $Old = D("Old");
        $id = $this->_get("id");
        $data = $Old->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Old/index");
        }
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        if (is_numeric($this->_get("id"))) {

            $this->display();
        } else {
            $this->assign(array("page_place" => $this->getPagePlace("添加老人信息", self::ACTION_NAME)));
            $this->display();
        }
    }

    public function add() {
        $Old = D("Old");
        if ($newdata = $Old->create()) {
            $newdata["birthday"] = $this->getBirthdayByIdCard(trim($this->_post("id_card")));
            $data = $Old->add($newdata);
            if (FALSE !== $data) {
                //all redirect to House, because only add Old from house page
                session("action_message", "添加数据成功");
                $this->redirect("Old/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("Old/index");
            }
        } else {
            session("action_message", $Old->getError());
            $this->redirect("Old/newone");
        }
    }

    public function delete() {
        $id = $this->_get("id");
        $Old = D("Old");
        if ($Old->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("Old/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Old/index");
        }
    }

    public function edit() {
        $id = $this->_get("id");
        $Old = D("Old");
        $data = $Old->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Old/index");
        }
        $this->assign(array("data" => $data,
            "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        $id = $this->_post("id");
        $Old = D("Old");
        if ($newdata = $Old->create()) {
            //the boolean fields' check


            $newdata["birthday"] = $this->getBirthdayByIdCard(trim($this->_post("id_card")));

            $data = $Old->save($newdata);
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Old/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Old/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Old->getError());
            $this->redirect("/Old/edit/$id");
        }
    }

    public function life() {
        $this->assign(array("page_place" => $this->getPagePlace("来宾刷卡")));
        $this->display();
    }

    //通过身份证号获取老人信息
    public function getoldinfo() {
        header("content-type:text/html;charset=utf-8");
        $idcard = $_GET["_URL_"][2];
        $condition["id_card"] = array("eq", $idcard);
        $old = D("old");
        $result = $old->where($condition)->find();
        if (!is_null($result)) {
            $html = "身份证号：" . $result["id_card"] . " || 姓名：" . $result["name"] . " || 居住地：" . $result["address"];
            echo $html;
        } else {
            echo '老人信息库中没有该身份证信息，<a href="/socialwork/index.php/old/newone">新增老人信息</a>';
        }
    }

    //
    public function getservicebytype() {
        $type = $_GET["_URL_"][2];
        $condition["type"] = array("eq", $type);
        $result = D("service")->where($condition)->select();
        $items = '<option value="0" selected="selected">-----------选择服务-----------</option>';
        foreach ($result as $tmp) {
            $items = $items . '<option value="' . $tmp["id"] . '">' . $tmp['id'] . "." . $tmp["name"] . '</option>';
        }
        header("content-type:text/html;charset=utf-8");
        echo $items;
    }

    //根据提交上来的身份证号和服务的编号，扣除对应的点券值。并返回提示信息。
    public function expense() {
        header("content-type:text/html;charset=utf-8");
        $idcard = $_GET["_URL_"][2];
        $sid = $_GET["_URL_"][3];
        if ($idcard == "" || $sid == "0") {
           echo '身份证号为空或者没选择对应的服务，刷卡操作未能完成<br>'; 
        } else {
            $condition1["id"] = array("eq", $sid);
            $result1 = D("service")->where($condition1)->find();
            $cost = $result1["cost"];
            $sname = $result1["name"];
            $result2 = D("old")->where("id_card='" . $idcard . "'")->find();
            $ticket = $result2["ticket"];
            $old = D("old");
            $condition2["id_card"] = array("eq", $idcard);
            //$old->where($condition2)->setDec('ticket',$cost);

            if ($ticket > $cost) {
                $data["ticket"] = $ticket - $cost;
                $old->where($condition2)->save($data);
                $newdata["id_card"] = $idcard;
                $newdata["service"] = $sid;
                $newdata["optime"] = date("Y-m-d H:i:s", time());
                $newdata["expense"] = $cost;
                D("service_log")->add($newdata);
                echo '身份证号为' . $idcard . "的老人享受了服务:" . $sname . "，扣除点券" . $cost . "点<br>";
            } else {
                echo '身份证号为' . $idcard . "的老人点券值不够，不能完成扣除点券值操作<br>";
            }
        }
    }

    private function getBirthdayByIdCard($idcard) {
        if (!empty($idcard)) {
            $birthday = "";
            if (18 == strlen($idcard)) {
                $birthday = substr($idcard, 6, 4);
                $birthday .= "-" . substr($idcard, 10, 2);
                $birthday .= "-" . substr($idcard, 12, 2);
            } else if (15 == strlen($idcard)) {
                $birthday = "19" . substr($idcard, 6, 2);
                $birthday .= "-" . substr($idcard, 8, 2);
                $birthday .= "-" . substr($idcard, 10, 2);
            }
            return $birthday;
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
