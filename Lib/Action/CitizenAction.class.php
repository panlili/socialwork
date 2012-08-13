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
            $yard_id = D("House")->where("id=$house_id")->getField("yard_id");
            //取得房屋的低保，廉租信息，只有房屋低保廉租了，居民才有低保廉租选项
            $is_dibao = D("Youfu")->where("house_id=$house_id")->getField("is_dibao");
            $is_lianzu = D("Youfu")->where("house_id=$house_id")->getField("is_lianzu");
            $this->assign(array("house_id" => $house_id, "yard_id" => $yard_id,
                "is_dibao" => $is_dibao, "is_lianzu" => $is_lianzu, "page_place" => $this->getPagePlace("在当前房屋下添加居民", self::ACTION_NAME)));
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
        $Citizen = D("Citizen");
        $data = $Citizen->where(array("id" => $id))->find();
        $m_addon = D("Addon");
        $addon = $m_addon->where("citizen_id=" . $id)->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Citizen/index");
        }
        $this->assign("photo", $addon["filepath"]);
        $this->assign(array("data" => $data,
            "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    public function update() {
        $id = $this->_post("id");
        $Citizen = D("Citizen");
        if ($newdata = $Citizen->create()) {
            //the boolean fields' check
            $newdata['is_fertility'] = null == $this->_post('is_fertility') ? '否' : '是';
            $newdata['is_special'] = null == $this->_post('is_special') ? '否' : '是';
            $newdata['is_low_level'] = null == $this->_post('is_low_level') ? '否' : '是';
            $newdata['is_disability'] = null == $this->_post('is_disability') ? '否' : '是';
            $newdata['is_low_rent'] = null == $this->_post('is_low_rent') ? '否' : '是';
            $newdata['is_long_live'] = null == $this->_post('is_long_live') ? '否' : '是';

            $data = $Citizen->save($newdata);
            
            //俊，图像更新的代码我也弄过来了。
            //if下面是判断提交上的文件是否是空，如果不为空就新传一个文件，同时在数据库中更新附件路径
            if ($_FILES["addon"]["name"] != NULL) {
                 $m_addon=D("Addon");
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
                $m_addon->where("citizen_id=" . $id)->save($new_addon);
            }



            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Citizen/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Citizen/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Citizen->getError());
            $this->redirect("/Citizen/edit/$id");
        }
    }

    public function toexcel() {
        if ($_SESSION["right"] == "9") {
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
