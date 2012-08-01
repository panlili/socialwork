//yard information 中显示yard统计的表格
//hasHeader判断是否需要院落信息和院落管理相关的信息
function showYardDetail(yardid,hasHeader) {
    $(".loading").show();
    $.get("detail",{
        id:yardid,
        hasHeader:hasHeader
    },function(data){
        $("#content_right").html("");
        $("#content_middle").html(data);
        $(".loading").hide();
    });
}

function showTable(table_id){
    $("#"+table_id).siblings().hide();
    $("#"+table_id).show();
}

function statisticsByAddress1(yardid,address1){
    //点击栋数的buttons，获取栋统计信息
    $(".loading").show();
    //id是yard的id，address_1是house的address_1
    $.get("addressone",{
        id:yardid,
        address_1:address1
    },function(data){
        $("#address1detail").html(data);
        $("#address2detail").html("");
        $("#address3detail").html("");
        $(".loading").hide();
    });
}

function statisticsByAddress2(yardid,address1,address2){
    //点击单元的buttons，获取单元统计信息
    $(".loading").show();
    $.get("addresstwo",{
        id:yardid,
        address_1:address1,
        address_2:address2
    },function(data){
        $("#address2detail").html(data);
        $("#address3detail").html("");
        $(".loading").hide();
    });
}

function statisticsByAddress3(yardid,address1,address2,address3){
    //点击楼层的buttons，获取楼层统计信息和此楼层房屋列表
    $(".loading").show();
    $.get("addressthree",{
        id:yardid,
        address_1:address1,
        address_2:address2,
        address_3:address3
    },function(data){
        $("#address3detail").html(data);
        $(".loading").hide();
    });
}

//information点击房屋数时获取房屋列表
function showHouse(yardid) {
    $("#content_right").html("");
    $(".loading").show();
    $.get("showHouse",{
        id:yardid
    },function(data){
        $("#content_middle").html(data);
        setNumber("#content_middle");
        $(".loading").hide();
    });
}

//information点击居民数时获取居民列表
function showCitizen(houseid){
    $(".loading").show();
    $.get("showCitizen",{
        id:houseid
    },function(data){
        $("#content_right").html(data);
        setNumber("#content_right");
        $(".loading").hide();
    });
}

//为院落添加管理，党支部等信息的ajax
$(function(){
    $("#common_table td").css("height","15px");
    $("#tabs").tabs();
    //添加院落管理信息
    $(".addadmin").click(function(){
        $.post("addAdmin",$("#yardadmin").serialize(),function(data){
            var tmp=$.parseJSON(data).data;
            var trid=$.parseJSON(data).id;
            var status=$.parseJSON(data).status;
            if(1==status){
                $("#addadminright").show().fadeOut(2000);
                var ht="<tr id='"+trid+"'>";
                ht+="<td>"+tmp.job+"</td>";
                ht+="<td>"+tmp.name+"</td>";
                ht+="<td>"+tmp.telephone+"</td>";
                ht+="<td><a class='delyardadmin' href='#'>删除</a></td></tr>";
                $("#addadmintr").before(ht);
            }
        });
    });
    //添加院落党支部信息
    $(".addparty").click(function(){
        $.post("addAdmin",$("#yardparty").serialize(),function(data){
            var tmp=$.parseJSON(data).data;
            var trid=$.parseJSON(data).id;
            var status=$.parseJSON(data).status;
            if(1==status){
                $("#addpartyright").show().fadeOut(2000);
                var ht="<tr id='"+trid+"'>";
                ht+="<td>"+tmp.job+"</td>";
                ht+="<td>"+tmp.name+"</td>";
                ht+="<td>"+tmp.telephone+"</td>";
                ht+="<td><a class='delyardadmin' href='#'>删除</a></td></tr>";
                $("#addpartytr").before(ht);
            }
        });
    });
    //添加院落环治工作信息
    $(".addclean").click(function(){
        $.post("addAdmin",$("#yardclean").serialize(),function(data){
            var tmp=$.parseJSON(data).data;
            var trid=$.parseJSON(data).id;
            var status=$.parseJSON(data).status;
            if(1==status){
                $("#addcleanright").show().fadeOut(2000);
                var ht="<tr id='"+trid+"'>";
                ht+="<td>"+tmp.job+"</td>";
                ht+="<td>"+tmp.name+"</td>";
                ht+="<td>"+tmp.telephone+"</td>";
                ht+="<td><a class='delyardadmin' href='#'>删除</a></td></tr>";
                $("#addcleantr").before(ht);
            }
        });
    });
    //ajax删除记录,因为动态添加了tr，并且具备删除链接，只能用live绑定事件
    $(".delyardadmin").live('click',function(event){
        event.preventDefault();
        var yardadminid=$(this).parent().parent("tr").attr("id");
        $.post("delAdmin",{
            id:yardadminid
        },function(data){
            var tmp=$.parseJSON(data).status;
            if(1==tmp)
                $("#"+yardadminid).fadeOut(500);
        });
    });

});