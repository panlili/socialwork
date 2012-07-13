<?php

class CameraAction extends BaseAction {

    const ACTION_NAME = "监控点位";

    //index方法,展示数据列表
    public function index() {
        //在index模版中显示.
        $Camera = D("Camera");
        import("ORG.Util.Page");
        $count = $Camera->count();
        $p = new Page($count, self::RECORDS_ONE_PAGE);
        $page = $p->show();
        $list = $Camera->order("id desc")->relation(true)->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign(array("page" => $page, "list" => $list, "page_place" => $this->getPagePlace("数据浏览", self::ACTION_NAME)));
        $this->display();
    }
   public function newone() {
       $Yard = D("Yard");
        $list = $Yard->select();
        $this->assign(array("yardlist" => $list, "page_place" => $this->getPagePlace("添加新数据", self::ACTION_NAME)));
        $this->display();
        
    }
    public function read() {
        $Camera = D("Camera");
        $id = $this->_get("id");
        $data = $Camera->relation(true)->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Camera/index");
        }
        
        //$this->assign("yardlist",$list);
        $this->assign(array("data" => $data, "page_place" => $this->getPagePlace("详细信息", self::ACTION_NAME)));
        $this->display();
    }



    public function add() {
        $Camera = D("Camera");
        if ($Camera->create()) {
            $data = $Camera->add();
            if (false !== $data) {
                session("action_message", "添加数据成功");
                $this->redirect("Camera/index");
            } else {
                session("action_message", "添加新数据失败！");
                $this->redirect("Camera/add");
            }
        } else {
            session("action_message", $Camera->getError());
            $this->redirect("Yard/Camera");
        }
    }

    //delete方法，删除指定记录
    public function delete() {
        $id = $this->_get("id");
        $Camera = D("Camera");
        if ($Camera->where(array("id" => $id))->delete()) {
            session("action_message", "删除数据成功！");
            $this->redirect("Camera/index");
        } else {
            session("action_message", "删除数据失败！");
            $this->redirect("Camera/index");
        }
    }

    //edit方法，显示编辑信息的模板
    public function edit() {
        $id = $this->_get("id");
        $Camera = D("Camera");
        $data = $Camera->relation(true)->where(array("id" => $id))->find();
        if (empty($data)) {
            session("action_message", "数据不存在！");
            $this->redirect("Camera/index");
        }
        $Yard = D("Yard");
        $list = $Yard->select();

        $this->assign(array( "data" => $data,"yardlist"=>$list,
            "page_place" => $this->getPagePlace("数据编辑", self::ACTION_NAME)));
        $this->display();
    }

    //update方法，用于接收提交的修改信息
    public function update() {
        //注意修改的表单下面一定要有一个隐藏域提交id，这样save方法才能成功。
        //获取隐藏字段id,即要更新的数据id
        $id = $this->_post("id");
        $Camera = D("Camera");
        if ($newdata = $Camera->create()) {
            $data = $Camera->save();
            if (false !== $data) {
                session("action_message", "更新数据成功！");
                $this->redirect("Camera/$newdata[id]");
            } else {
                session("action_message", "更新数据时保存失败！");
                $this->redirect("/Camera/edit/$newdata[id]");
            }
        } else {
            session("action_message", $Camera->getError());
            $this->redirect("/Camera/edit/$id"); //必须有第一个斜杠, i was confused!!!
        }
    }
    public function opencam(){
        //采用老系统的监控控件，只需访问端口号和通道号，ip和登录信息封装在插件里面的。VIDEOOCX.StartRealPlay(8008,2)
        //$id = $this->_get("id");
        $id=$_GET["_URL_"][2];
        $channel=$_GET["_URL_"][3];
        //
        $cam=D("Camera");
        $result=$cam->where(array("id" => $id))->find();
        //$condition="where hdrid='".$result["hdr_id"]'';
        $condition['hdr_id']=array('eq',$result["hdr_id"]);
        $condition['number']=  array('eq',$channel);
        $ch=M("channel")->where($condition)->find();
        
        $title=$result["name"]."  ".$ch["name"];
        //$url=$result["ip"].":".$result["port"];
        $url=$result["port"].",".$channel;
        $this->assign("url",$url);
        $this->assign("title",$title);
        $this->display();
        
        //采用新的监控控件。 OCX.AddDVR('125.71.232.103','8001','guest','guest',4);
    }

}

?>

