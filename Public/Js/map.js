
var mapAreaX=0;                       //地图容器在left方向的值
var mapAreaY=0;                       //地图容器在top方向的值
var oldoffsetX=0;                     //按下鼠标对应点的X位置
var oldoffsetY=0;                     //按下鼠标对应点的Y位置
var newoffsetX=0;                     //鼠标释放时对应点的X位置
var newoffsetY=0;                     //鼠标释放时对应点的Y位置
var mapoffsetX=0;                     //地图在X方向移动的偏移值
var mapoffsetY=0;                     //地图在Y方向移动的偏移值

var zoomlevel=4;                      //地图缩放标准

var isrightdrag=false;               //判断是否处于右键拖动状态
var mousepress=false;                 //判断鼠标是否处于按下状态

$(function()
{
    //getmapset();
    setmap();
    var tagAddress=methodeAddress+"getTag";                    //获取tag的地址
    var itemAddress=methodeAddress+"getitem";
    var mct=$("div#mapcontainer").offset();
    mapAreaX=mct.left;
    mapAreaY=mct.top;
    //初始化添加院落标记
    $("div#addYardMark").hide();
    $("div#addCamMark").hide();
    
    //初始化提示信息
    $("div#tip").hide();
    $("div#tip1").hide();
    //初始化弹出菜单
    $("div#popmenu").hide();
    $("pre#menu_markyard").click(function(){
        //alert("baby");
        $("div#addYardMark").show();
        $("div#popmenu").hide();
    })
    $("pre#menu_markcam").click(function(){
        $("div#addCamMark").show();
        $("div#popmenu").hide();
    })
  
    $("div#mapcontrol").css("top",mapAreaY);
    $("div#mapcontrol").css("left",mapAreaX);

    fillmap();
    //为地图移动控件的上下左右按钮添加操作
    $("img#map_up").click(function(){
        //        mapbaseX=mapbaseX+parseInt(mapoffsetX/pieceWidth)*200;
        mapbaseY=mapbaseY-pieceHeight;
        fillmap();
    })
    $("img#map_down").click(function(){
        mapbaseY=mapbaseY+pieceHeight;
        fillmap();
    })
    $("img#map_left").click(function(){
        mapbaseX=mapbaseX-pieceWidth;
        fillmap();
    })
    $("img#map_right").click(function(){
        mapbaseX=mapbaseX+pieceWidth;
        fillmap();
    })
    //屏蔽地图容器中浏览器默认上下文菜单
    $("div#container").contextmenu(function(e){
        return false;
    })
    //为地图添加鼠标滚轮事件的支持。该代码依赖于html中引入的jquery.mousewheel.min.js,
    //通过参数delta可以获取鼠标滚轮的方向和速度。如果delta的值是负的，那么滚轮就是向下滚动，正的就是向上。
    
    $('div#content').bind('mousewheel', function(event, delta) {
        //事件的回调函数第一个参数为event，第二个参数为delta，代表鼠标滚轮的变化值.滚轮向上，则地图放大，滚轮向下，则地图缩小。
        //alert(delta);
        if(delta>0){
            //放大地图
            if(zoomlevel>1){
                zoomlevel=zoomlevel-1;
                
                mapbaseX=parseInt(mapbaseX*1.7);
                mapbaseY=parseInt(mapbaseY*2);
                //从最小放大来的时候，由于基准点为左上角，会导致以左角为中心放大。因此在第二级设置一个坐标，让地图缩放更平滑。
                if(zoomlevel==3){
                    mapbaseX=400;
                    mapbaseY=300;
                }
            }
            //alert(zoomlevel);
            setmap();
            fillmap();
        }else{
            //缩小地图
            if(zoomlevel<4){
                zoomlevel=zoomlevel+1;
                mapbaseX=parseInt(mapbaseX/1.7);
                mapbaseY=parseInt(mapbaseY/2);
            }
            //alert(zoomlevel);
            setmap();
            fillmap();
        }
        
        return false;
    });
    
    $("div#dragmap").mousedown(function(e){
        //alert(e.which);e.which判断鼠标按下的键，1是左键，3是右键，2是中键
        switch(e.which){
            case 1:
                mousepress=true;
        
                //获取当前点的坐标，保存在oldoffsetX和oldoffsetY中。
                //oldoffsetX=e.pageX;
                oldoffsetX=getX(e);
                oldoffsetY=getY(e);
                //$("div#tip").text(oldoffsetX+","+oldoffsetY);
                $("div#tip").hide();
                $("div#tip1").hide();
                $("div#map").fadeTo("fast", 0.25);
                break;
            case 3:
                mousepress=true;
                break;
            default:
                ;
        }
        

    }).mouseup(function(e){
        mousepress=false;
        //获取鼠标释放点的坐标，计算拖动操作给地图带来的偏移值。
        newoffsetX=getX(e);
        newoffsetY=getY(e);
        //        mapoffsetX=newoffsetX-oldoffsetX;
        //        mapoffsetY=newoffsetY-oldoffsetY;
        mapoffsetX=oldoffsetX-newoffsetX;
        mapoffsetY=oldoffsetY-newoffsetY;
        switch(e.which){
            case 1:
                mapbaseX=mapbaseX+parseInt(mapoffsetX/pieceWidth)*200;
                mapbaseY=mapbaseY+parseInt(mapoffsetY/pieceHeight)*200;
                fillmap();
                break;
            
            case 3:
                if(isrightdrag)
                {
                    //根据范围统计信息
                    isrightdrag=false;
                    alert("drag_anaasys");
                    
                }
                else if(zoomlevel==1)
                {
                    //alert("popmenu");弹出右键菜单
                        
                    $("div#popmenu").css("left",newoffsetX);
                    $("div#popmenu").css("top",newoffsetY);
                    $("input[name='x']").attr("value",newoffsetX+mapbaseX-mapAreaX);
                    $("input[name='y']").attr("value",newoffsetY+mapbaseY-mapAreaY);
                    $("div#popmenu").show();
                    $("div#popmenu").mouseleave(function(){
                               
                        $("div#popmenu").hide();
                               
                    })
                    return false;
                        
                }
                break;
            default:
                ;
        }
        
    })
    $("div#dragmap").mousemove(function(e){
        if(mousepress){  
            switch(e.which){
                case 1:
                    $("div#dragmap").css("cursor","move");
                    $("div#map").css("top",e.pageY-oldoffsetY);
                    $("div#map").css("left",e.pageX-oldoffsetX);
                    $("div#dragmap").css("top",mapAreaY+e.pageY-oldoffsetY);
                    $("div#dragmap").css("left",mapAreaX+e.pageX-oldoffsetX);
                    break;
                case 3:
                    isrightdrag=true;
                    break;
                default:
                    ;
            }
            
        }
        
    })
    //填充地图
    function fillmap(){
        //            mapbaseX=mapbaseX+parseInt(mapoffsetX/pieceWidth)*200;
        //            mapbaseY=mapbaseY+parseInt(mapoffsetY/pieceHeight)*200;
        //防止拖拉过界
        if(mapbaseX<0)mapbaseX=0;
        if(mapbaseY<0)mapbaseY=0;
        if(mapbaseX>mapWidth-mapAreaWidth)mapbaseX=mapWidth-mapAreaWidth;
        if(mapbaseY>mapHeight-mapAreaHeight)mapbaseY=mapHeight-mapAreaHeight;
        //将地图容器归回原位,由于dragmap是绝对定位，所以需要偏移到和mapcontainer重合
        //var mpt=$("div#mapcontainer").offset();
        $("div#map").fadeTo("fast", 1);
        $("div#dragmap").css("cursor","pointer");
        $("div#map").css("top",0);
        $("div#map").css("left",0);
        $("div#dragmap").css("top",mapAreaY);
        $("div#dragmap").css("left",mapAreaX);
        //根据地图基准和偏移加载相应的切片
        $("div#map").empty();
        $("div#tag").empty();
        var x1=parseInt(mapbaseX/pieceWidth);
        var y1=parseInt(mapbaseY/pieceHeight);
        for(var y=1;y<=mapAreaHeight/pieceHeight;y++){
            for(var x=1;x<=mapAreaWidth/pieceWidth;x++){
                var fn=piecePath+zoomlevel+"/"+(y+y1)+"-"+(x+x1)+".jpg";
                
                var tmp="<img src=\""+fn+"\"></img>";
                $("div#map").append(tmp);  
            }
        }
        //由于地图切片以piece的长高为单位进行移动，所以在添加标签的时候，基准点应是地图切片长高的整数倍，不然标签位置会出现误差
        mapbaseX=parseInt(mapbaseX/pieceWidth)*pieceWidth;
        mapbaseY=parseInt(mapbaseY/pieceHeight)*pieceHeight;
        if(zoomlevel==1)addTag();
    }
    //通过ajax方式获取json数据，生成tag标签，指定位置，并添加到tag。
    function addTag(){
        //        var jsonurl=tagAddress+"?baseX="+mapbaseX+"&baseY="+mapbaseY+"&mapW="+mapAreaWidth+"&mapH="+mapAreaHeight;
        var jsonurl=tagAddress+"/"+mapbaseX+"/"+mapbaseY+"/"+mapAreaWidth+"/"+mapAreaHeight;
        //alert(jsonurl);
        $.getJSON(jsonurl, function(data){
            //alert(data);
            //var mxx=$("div#mapcontainer").offset();
            
            $.each(data,function(i,item){
                //alert(item.id);
                var tag="";
                switch(item.type){
                    case "1":
                        tag="<img id=\"tag"+item.id+"\" src=\""+piecePath+"tag.png"+"\" class=\"tag\"></img>";
                        break;
                    case "3":
                        tag="<img id=\"tag"+item.id+"\" src=\""+piecePath+"tag_cam.png"+"\" class=\"tag\"></img>";
                        break;
                }
                //var tag="<img id=\"tag"+item.id+"\" src=\""+piecePath+"tag.png"+"\" class=\"tag\"></img>"; 
                var tagtmp="img#tag"+item.id;
                $("div#tag").append(tag);
                var x=parseInt(item.x)-mapbaseX+parseInt(mapAreaX);
                var y=parseInt(item.y)-mapbaseY+parseInt(mapAreaY);
                //alert(x);alert(y);
                $(tagtmp).css("left",x);
                $(tagtmp).css("top",y);
                //鼠标移到标签上显示标签内容名字，移开消失
                $(tagtmp).hover(function(){
                    var url=itemAddress+"/"+item.type+"/"+item.target;
                    //alert(url);
                    $.getJSON(url, function(data){
                        $.each(data,function(i,item){
                            //alert(item.name);
                            $("div#tip1").empty();
                            $("div#tip1").append(item.name);
                            $("div#tip1").css("left",x+20);
                            $("div#tip1").css("top",y-20);
                            //$("div#tip").show();
                            $("div#tip1").fadeTo(100,1);
                            $(tagtmp).css("width",40);
                            $(tagtmp).css("height",40);
                        })
                    //alert(data.name);
                    })
                }, function(){
                    $("div#tip1").hide();
                    $("div#tip1").empty();
                    $(tagtmp).css("width",30);
                    $(tagtmp).css("height",30);
                });
                //点击鼠标显示具体内容
                $(tagtmp).click(function(){
                    //alert(x);alert(y);alert(jsonurl);
                    //获取tag对应的内容
                    var url=itemAddress+"/"+item.type+"/"+item.target;
                    //alert(url);
                    $.getJSON(url, function(data){
                        $.each(data,function(i,item1){
                            //alert(item.name);
                            $("div#tip").empty();
                            switch(item.type){
                                case "3":
                                    //生成摄像机通道列表
                                    var camnumber=item1.channels-item1.remain; 
                                    var camlist="<p>";
                                    for(var i=1;i<=camnumber;i++){
                                        var camitem='<a href="/socialwork/index.php/camera/opencam/'+item1.id+'/'+i+'" title="通道'+i+'" target="_blank"><img id="camera"'+i+'" src="'+piecePath+'camera1.jpg'+'"></img></a>';
                                        camlist=camlist+camitem;
                                    }
                                    camlist=camlist+"</p>";
                                    
                                    var html1='<p>'+item1.name+'</p><p>'+item1.comment+'</p><p>'+item1.ip+':'+item1.port+'</p>'+camlist+'<p>点击摄像头图标查看监控图像</p>';
                                    //alert (html1);
                                    $("div#tip").append(html1);
                                    break;
                                case "1":
                                    var html1='<p>'+item1.name+'</p><p>'+item1.address+'</p><button onclick="openyard('+item1.id+')">查看院落详细信息</button>'
                                    $("div#tip").append(html1);
                                    break;
                                default:
                                    $("div#tip").append(item1.name);
                            }
                            //$("div#tip").append(item.name);
                            $("div#tip").css("left",x+10);
                            $("div#tip").css("top",y+10);
                            $("div#tip").show();
                            $("div#tip").click(function(){
                               
                                $("div#tip").hide();
                                $("div#tip").empty();
                            })
                        })
                    //alert(data.name);
                    })
                })
            })
        });
    //        var tag="<img id=\"tag1\" src=\"\" class=\"tag\"></img>";
    //        $("div#tag").append(tag);
    //        $("img#tag1").css("top",250);
    //        $("img#tag1").css("left",250);
    }
    //将院落列表添加到下拉列表
    var url=methodeAddress+"getYardList";
    $.getJSON(url, function(data){
        //alert(data);
        $.each(data,function(i,item){
            var txt="<option value=\""+i+"\">"+item+"</option>";
            //alert(i);
            $("form#YardMark > select[name='target']").append(txt);
        }) 
    })
    $("input#addyardok").click(function(){
        $.post(methodeAddress+"addMapMark", $("form#YardMark").serialize(),function(data){
            $("div#addYardMark").hide();
            fillmap();  
        });
        
    })
    $("input#addyardcancel").click(function(){
        $("div#addYardMark").hide();
    })
    //将摄像头列表添加到下拉列表
    var url1=methodeAddress+"getCamList";
    $.getJSON(url1,function(data){
        $.each(data,function(i,item){
            var txt="<option value=\""+i+"\">"+item+"</option>";
            $("form#CamMark > select").append(txt);
        })
    })
    $("input#addcamok").click(function(){
        $.post(methodeAddress+"addMapMark", $("form#CamMark").serialize(),function(data){
            $("div#addCamMark").hide();
            fillmap();
        });
        
    })
    $("input#addcamcancel").click(function(){
        $("div#addCamMark").hide();
    })
    
    //获取鼠标位置的方法
    function GetPostion(e) {
        var x = getX(e);
        var y = getY(e);
    }
    function getX(e) {
        e = e || window.event;
    
        return e.pageX || e.clientX + document.body.scroolLeft;
    }

    function getY(e) {
        e = e|| window.event;
        return e.pageY || e.clientY + document.boyd.scrollTop;
    }
})
//缩放的时候根据缩放级别设置地图的参数
function setmap(){
    switch(zoomlevel){
        case 1:
            mapWidth=3573;
            mapHeight=2471;
            //mapbaseX=800;
           
            //alert(mapbaseX);
            //mapbaseY=800;
            
            break;
        case 2:
            mapWidth=2600;
            mapHeight=1798;
            //            mapbaseX=600;
            //            mapbaseY=600;
            
            break;
        case 3:
            mapWidth=1800;
            mapHeight=1245;
            //            mapbaseX=400;
            //            mapbaseY=400;
           
            break;
        case 4:
            mapWidth=1000;
            mapHeight=692;
            //            mapbaseX=0;
            //            mapbaseY=0;
            break;
    }
}
function getmapset(){
    //从服务器获取地图设置参数
    $.getJSON(methodeAddress+"getmapset",function(data){
        //alert(data[0]["id"]);
        mapWidth=parseInt(data[0]["mapWidth"]);                   //地图原始图宽
        mapHeight=parseInt(data[0]["mapHeight"]);                  //地图原始图高
        mapAreaHeight=parseInt(data[0]["mapAreaHeight"]);                //地图区域高度
        mapAreaWidth=parseInt(data[0]["mapAreaWidth"]);                 //地图区域宽度
        pieceHeight=parseInt(data[0]["pieceHeight"]);                  //地图切片高度
        pieceWidth=parseInt(data[0]["pieceWidth"]);                   //地图切片宽度
        piecePath=data[0]["piecePath"];            //地图切片在服务器的路径
        methodeAddress=data[0]["methodeAddress"];       //地图方法的完整地址
        mapbaseX=parseInt(data[0]["mapbaseX"]);                       //地图基准X值
        //mapbaseX=100;   
        mapbaseY=parseInt(data[0]["mapbaseY"]);                       //地图基准Y值
    })   
}

function opencam(id,channel) { 
    window.open ("/socialwork/index.php/camera/opencam/"+id+"/"+channel, "newwindow", "height=480, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");

//写成一行 
} 
function openyard(id){
    window.open("/socialwork/index.php/yard/"+id,"newwindow");
}





