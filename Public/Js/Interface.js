//获取院落的基本信息
function getBasic(yardid,path) {
    $.get(path+"getBasic",{
        yardid:yardid
    },function(data){
        $("#getbasic").html(data);
    });
}
//根据院落id获取摄像头列表
function getCamlistByYardid(yardid,path) {
    $.get(path+"getCamlistByYardid",{
        yardid:yardid
    },function(data){
        $("#getCamlistByYardid").html(data);
    });
}

function showTable(table_id){
    $("#"+table_id).siblings().hide();
    $("#"+table_id).show();
}

function statisticsByAddress1(yardid,address1,path){
    $.get("addressone",{
        yardid:yardid,
        address_1:address1
    },function(data){
        $("#address1detail").html(data);
        $("#address2detail").html("");
        $("#address3detail").html("");
    });
}

function statisticsByAddress2(yardid,address1,address2,path){
    //点击单元的buttons，获取单元统计信息
    $.get(path+"addresstwo",{
        yardid:yardid,
        address_1:address1,
        address_2:address2
    },function(data){
        $("#address2detail").html(data);
        $("#address3detail").html("");
    });
}

function statisticsByAddress3(yardid,address1,address2,address3,path){
    //点击楼层的buttons，获取楼层统计信息和此楼层房屋列表
    $.get(path+"addressthree",{
        yardid:yardid,
        address_1:address1,
        address_2:address2,
        address_3:address3
    },function(data){
        $("#address3detail").html(data);
    });
}
