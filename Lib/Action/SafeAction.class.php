<?php

class SafeAction extends BaseAction {

    const ACTION_NAME = "公共安全";

    public function hydrant() {
        $this->assign("page_place", $this->getPagePlace("消防栓位置示意图", self::ACTION_NAME));
        $this->display();
    }

    public function shelter() {
        $this->assign("page_place", $this->getPagePlace("应急避难点示意图示意图", self::ACTION_NAME));
        $this->display();
    }

    public function safer() {
        $this->assign("page_place", $this->getPagePlace("安全员网络图", self::ACTION_NAME));
        $this->display();
    }

    public function plans() {
        $m_addon = M("Addon");
        $files = $m_addon->where("status=1 AND house_id is null AND citizen_id is null And yard_id is null")->order("uptime desc")->select();
        $this->assign("files", $files);
        $this->assign("page_place", $this->getPagePlace("应急预案文档", self::ACTION_NAME));
        $this->display();
    }

    public function upload() {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'doc,docx,xls,xlsx,pdf,jpeg,png,jpg,gif');
        $upload->savePath = './Public/Uploads/safe/';
        $upload->saveRule = uniqid;
        if (!$upload->upload()) {
            session("action_message", $upload->getErrorMsg());
            $this->redirect("plans");
        } else {
            $uploadList = $upload->getUploadFileInfo();
            $filename = $uploadList[0]['name'];
            $filepath = $uploadList[0]['savename'];
        }
        $model = M('Addon');
        //下载的时候用filename作为浏览器的保存名时不认识中文开头的字符串，故处理之
        $data['filename'] = " " . $filename;
        $data['filenote'] = $_POST["filenote"];
        $data['filepath'] = $filepath;
        $data['uptime'] = time();
        $data["releasedate"] = $_POST["releasedate"];
        $data['status'] = 1;
        $data['upuser'] = $_SESSION["truename"];
        $list = $model->add($data);
        if ($list !== false) {
            session("action_message", "上传成功！");
            $this->redirect("plans");
        } else {
            session("action_message", "上传失败！");
            $this->redirect("plans");
        }
    }

    public function download() {
        $id = intval($_GET['id']);
        $m_addon = M("Addon");
        $map['id'] = $id;
        if ($m_addon->where($map)->find()) {
            $filepath = './Public/Uploads/safe/' . $m_addon->filepath;
            if (is_file($filepath)) {
                $showname = auto_charset($m_addon->filename, 'utf-8', 'gbk');
                import("ORG.Net.Http");
                Http::download($filepath, $showname);
            } else {
                $this->error('附件不存在或者已经删除！');
            }
        } else {
            $this->error('附件不存在或者已经删除！');
        }
    }

    public function delete() {
        $addon_id = $_GET["id"];
        $m_addon = M("Addon");
        $m_addon->where("id=$addon_id")->setField("status", 0);
        session("action_message", "删除成功！");
        $this->redirect("plans");
    }

}

?>
