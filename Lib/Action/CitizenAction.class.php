<?php

class CitizenAction extends BaseAction {

    const ACTION_NAME = "居民";

    //index方法,展示数据列表
    public function index() {
        $m_citizen = D("Citizen");
        import("ORG.Util.Page");
        $count = $m_citizen->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $m_citizen->order("id desc")->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }

    public function read() {
        $m_citizen = D("Citizen");
        $id = $this->_get("id");
        $data = $m_citizen->relation(array("house", "youfu"))->where(array("id" => $id))->find();

        $m_addon = D("Addon");
        $addon = $m_addon->where("citizen_id=" . $id)->find();

        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Citizen/index");
        }
        $this->assign("photo", $addon["filepath"]);
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }

    public function newone() {
        $house_id = $this->_get("id");
        if (isset($house_id)) {
            $m_house = D("House");
            $m_youfu = D("Youfu");
            $yard_id = $m_house->where("id=$house_id")->getField("yard_id");
            //取得房屋的低保，廉租信息，只有房屋低保廉租了，居民才有低保廉租选项
            $is_dibao = $m_youfu->where("house_id=$house_id")->getField("is_dibao");
            $is_lianzu = $m_youfu->where("house_id=$house_id")->getField("is_lianzu");
            //取得房屋人户是否一致，是否空闲，如果两个都为否，这人口的计划生育指标为流动人口生育指标选项
            $is_fit = $m_house->where("id=$house_id")->getField("is_fit");
            $is_free = $m_house->where("id=$house_id")->getField("is_free");
            $use_ldrksyzb = "no";
            if ($is_fit === "否" && $is_free === "否") {
                $use_ldrksyzb = "yes";
            }

            $this->assign(array("house_id" => $house_id, "yard_id" => $yard_id,
                "is_dibao" => $is_dibao, "is_lianzu" => $is_lianzu, "use_ldrksyzb" => $use_ldrksyzb,
                "page_place" => $this->getPagePlace("在当前房屋下添加居民", self::ACTION_NAME)));
            $this->display();
        } else {
            session("action_message", "请在房屋下添加居民");
            $this->redirect("House/index");
        }
    }

    public function add() {

        $m_citizen = D("Citizen");
        $m_youfu = D("Youfu");
        $m_addon = D("Addon");
        $house_id = $_POST["house_id"];
        if ($m_citizen->create()) {
            if ($new_citizen_id = $m_citizen->add()) {
                $new_youfu = $m_youfu->create();
                $new_youfu["citizen_id"] = $new_citizen_id;
                //居民属于房屋，故表单中有house_id,需要在居民的youfu行中屏蔽
                $new_youfu["house_id"] = null;
                $new_youfu["citizen_id"] = $new_citizen_id;
                $m_youfu->add($new_youfu);
                if ($_FILES["addon"]["name"] != NULL) {
                    //添加附件数据
                    //导入图片上传类
                    import("ORG.Net.UploadFile");
                    //实例化上传类
                    $upload = new UploadFile();
                    $upload->maxSize = 3145728;
                    //设置文件上传类型
                    $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
                    //设置文件上传位置
                    $upload->savePath = "./Public/Uploads/citizen/"; //这里说明一下，由于ThinkPHP是有入口文件的，所以这里的./Public是指网站根目录下的Public文件夹
                    //设置文件上传名(按照时间)
                    $upload->saveRule = "time";
                    if (!$upload->upload()) {
                        $this->error($upload->getErrorMsg());
                    } else {
                        //上传成功，获取上传信息
                        $info = $upload->getUploadFileInfo();
                    }
                    $new_addon = $m_addon->create();
                    $new_addon["citizen_id"] = $new_citizen_id;
                    $new_addon["yard_id"] = null;
                    $new_addon["house_id"] = null;
                    $new_addon["filepath"] = "citizen/" . $info[0]['savename'];    //文件路径
                    $m_addon->add($new_addon);
                }

                session("action_message", "添加居民成功！");
                $this->redirect("House/$house_id");
            } else {
                session("action_message", "添加居民数据失败！");
                $this->redirect("House/index");
            }
        } else {
            session("action_message", $m_citizen->getError());
            $this->redirect("House/index");
        }
    }

    public function delete() {
        $m_citizen = D("Citizen");
        $m_addon = D("Addon");
        $id = $this->_get("id");
        if ($m_citizen->relation("youfu")->delete($this->_get("id"))) {
            $m_addon->where("citizen_id=" . $id)->delete();
            session("action_message", "删除数据成功！");
            $this->redirect("Citizen/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Citizen/index");
        }
    }

    public function edit() {
        $id = $this->_get("id");
        $m_citizen = D("Citizen");
        $data = $m_citizen->relation("youfu")->find($id);
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Citizen/index");
        }

        $m_addon = D("Addon");
        $addon = $m_addon->where("citizen_id=" . $id)->find();

        //取得他所在房屋是否是低保与廉租
        $house_id = $m_citizen->getField("house_id");
        $m_youfu = D("Youfu");
        $house_is_dibao = $m_youfu->where("house_id=$house_id")->getField("is_dibao");
        $house_is_lianzu = $m_youfu->where("house_id=$house_id")->getField("is_lianzu");

        //取得居民对应的优抚数据的主id,赋值给edit.html页面，才能在update中直接根据表单数据创建优抚对象，否则优抚对象无id无法更新
        $youfu_id = $m_youfu->where("citizen_id=$id")->getField("id");

        $this->assign("photo", $addon["filepath"]);
        $this->assign(array("data" => $data, "house_is_dibao" => $house_is_dibao, "house_is_lianzu" => $house_is_lianzu,
            "youfu_id" => $youfu_id, "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        $citizen_id = $this->_post("id");
        $m_citizen = D("Citizen");
        $m_youfu = D("Youfu");

        $new_youfu = $m_youfu->create();
        $new_citizen = $m_citizen->create();
        //从edit.html获取对应优抚对象的youfu_id作为主键才能更新优抚表数据，这样能避免对每个字段手工赋值
        $new_youfu["citizen_id"] = $citizen_id;
        $new_youfu["id"] = $_POST["youfu_id"];

        $result1 = $m_citizen->save($new_citizen);
        $result2 = $m_youfu->save($new_youfu);

        //俊，图像更新的代码我也弄过来了。
        //if下面是判断提交上的文件是否是空，如果不为空就新传一个文件，同时在数据库中更新附件路径
        if ($_FILES["addon"]["name"] != NULL) {
            $m_addon = D("Addon");
            //添加附件数据
            //导入图片上传类
            import("ORG.Net.UploadFile");
            //实例化上传类
            $upload = new UploadFile();
            $upload->maxSize = 3145728;
            //设置文件上传类型
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');
            //设置文件上传位置
            $upload->savePath = "./Public/Uploads/citizen/"; //这里说明一下，由于ThinkPHP是有入口文件的，所以这里的./Public是指网站根目录下的Public文件夹
            //设置文件上传名(按照时间)
            $upload->saveRule = "time";
            if (!$upload->upload()) {
                $this->error($upload->getErrorMsg());
            } else {
                //上传成功，获取上传信息
                $info = $upload->getUploadFileInfo();
            }
            $new_addon = $m_addon->create();

            $new_addon["filepath"] = "citizen/" . $info[0]['savename'];    //文件路径
            $result4 = $m_addon->where("citizen_id=" . $citizen_id)->save($new_addon);
            if ($result4 === 0) {
                $new_addon["citizen_id"] = $citizen_id;
                $m_addon->add($new_addon);
            }
        }

        if ($result1 !== false && $result2 !== false) {
            session("action_message", "更新数据成功！");
            $this->redirect("Citizen/$citizen_id");
        } else {
            session("action_message", "更新数据失败！");
            $this->redirect("Citizen/$citizen_id");
        }
    }

    public function toexcel() {
        if ($_SESSION["right"] == "9" || $_SESSION["right"] == "1") {
            $houseid = $this->_get("id");
            $list = D("Citizen")->relation(array("house"))->where("house_id='$houseid'")->select();
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=citizen_in_house_$houseid.xls");
            $this->assign("list", $list);
            echo $this->fetch();
        } else {
            header("Content-Type:text/html; charset=utf-8");
            echo "您没有权限执行导出操作";
        }
    }

}

?>
