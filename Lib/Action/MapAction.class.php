<?php

class MapAction extends BaseAction {

    public function index() {
        $mapset = D("map_set");
        $data = $mapset->select();
        $this->assign('mapset', $data[0]);
        $this->display();
    }

    //获取院落列表的方法
    public function getYardList() {
        $yard = D("yard");
        $yardlist = $yard->getField("id,name");
        echo json_encode($yardlist);
    }

    //获取摄像头列表的方法
    public function getCamList() {
        $cam = D("camera");
        $camlist = $cam->getField("id,name");
        echo json_encode($camlist);
    }

    //添加地图标记的方法
    public function addMapMark() {
        $MapMark = D("map_mark");
        if ($MapMark->create()) {
            $data = $MapMark->add();
            if (FALSE !== $data) {
                //session("action_message", "添加数据成功");
                //$this->redirect("map/index");
            } else {
                //session("action_message", "添加新数据失败！");
                //$this->redirect("House/newone");
            }
        } else {
            //session("action_message", $House->getError());
            //$this->redirect("House/newone");
        }
    }

    //根据客户端发回的tag的target和tag的type，从相应的表取回内容，返回给客户端
    public function getItem() {
        $tagType = $_GET["_URL_"][2];
        $tagTarget = $_GET["_URL_"][3];
        $tablename = "";
        switch ($tagType) {
            case 1:
                $tablename = "yard";
                break;
            case 2:
                $tablename = "street";
                break;
            case 3:
                $tablename = "camera";
                break;
            default :
                $tablename = "";
        }
        if ($tablename != "") {
            $table = D($tablename);
            $condition['id'] = $tagTarget;
            $list = $table->where($condition)->select();
            echo json_encode($list);
        }
    }

//根据客户端发回的地图基点坐标和地图区域大小，从数据库选择范围内的标签，以json的形式发送给客户端    
    public function getTag() {
        $baseX = $_GET["_URL_"][2];
        $baseY = $_GET["_URL_"][3];
        $mapW = $_GET["_URL_"][4];
        $mapH = $_GET["_URL_"][5];

        $tagtmp = D("map_mark");
        $xtmp = $baseX + $mapW - 1;
        $ytmp = $baseY + $mapH - 1;
        $tmp['x'] = array('between', "$baseX,$xtmp");
        $tmp['y'] = array('between', "$baseY,$ytmp");
        $taglist = $tagtmp->where($tmp)->select();
        echo json_encode($taglist);
    }

    //对地图进行切割的方法
    public function splitmap() {
        $SplitHeight = 200;              //切片高度
        $SplitWidth = 200;              //切片宽度
        $MapSourcePath = "\\wamp\\www\\socialwork\\Public\\Image\\map\\map.jpg";    //需要切割的大图路径
        $piecePath = "\\wamp\\www\\socialwork\\Public\\Image\\map\\1\\";           //切片输出路径
        echo "分割图片中……";
        //打开文件
        $im = @imagecreatefromjpeg($MapSourcePath);
        //获取文件的高度和宽度
        $map = getimagesize($MapSourcePath);
        //$mapHeight = $map[1] / $SplitHeight + 1;
        //$mapWidth = $map[0] / $SplitWidth + 1;
        $mapHeight = $map[1];
        $mapWidth = $map[0];
        //按给定大小将图片分割
        for ($y = 0; $y < $mapHeight; $y+=$SplitHeight) {
            for ($x = 0; $x < $mapWidth; $x+=$SplitWidth) {
                $exportFile = $piecePath . ($y / $SplitHeight + 1) . "-" . ($x / $SplitWidth + 1) . ".jpg";
                echo $exportFile;
                echo '<br>';
                $ex = @imagecreatetruecolor($SplitWidth, $SplitHeight);
                imagecopy($ex, $im, 0, 0, $x, $y, $SplitWidth, $SplitHeight);
                //imagecopy($ex,$im,1,1,1,1,200,200);
                imagejpeg($ex, $exportFile);
                imagedestroy($ex);
            }
        }
    }

    //修改设置的方法
    public function mapset() {
        $mapset = D("map_set");
        $data = $mapset->select();
        $this->assign('mapsetting', $data);
        $this->display();
    }

    public function setupdate() {
        $mapset = D("map_set");
        $data = $mapset->create();
        $mapset->save();
        $this->redirect("map/mapset");
    }

    public function getmapset() {
        $mapset = D("map_set");
        $data = $mapset->select();
        echo json_encode($data);
    }

}

?>
