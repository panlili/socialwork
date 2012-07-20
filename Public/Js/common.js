//admin管理模块, 删除管理用户
function delete_user(id) {
    if(window.confirm("Are you Sure?")){
        $.post("delete", 
        {
            id:id
        }, 
        function(data){
            if(1==$.parseJSON(data).status) $('#'+id).fadeOut("slow");            
        });        
    }
}

//为表格每一行添加序号
function setNumber(parent) {
    var i=1;
    if(!parent){
        $(".number").each(function(){
            $(this).text(i);
            i++;
        });
    }else{
        $(parent+" .number").each(function(){
            $(this).text(i);
            i++;
        });
    }
}

//house模块, 是平方是单选按钮不同的反映
$(function(){
    $("#common_table td").css("height","15px");
    
    $(".onefloor").click(function(){
        if($(this).attr("checked")=="checked"){
            $(this).attr("value","是");
            $("#onefloor").show();
            $(this).parent().parent().nextUntil("#onefloor").hide();
        }else{
            $("#onefloor").hide();
            $(this).parent().parent().nextUntil("#onefloor").show();
        }        
    });
});

$(function(){
    setNumber();
    
    $("#footer").dblclick(function(){
        $("#menu").toggle();
    });
        
    if($.trim($("#action_message").text())==""){
        $("#action_message").remove();
    }else{
        $("#action_message").fadeOut(2500,function(){
            $(this).remove();
        //console.log("only chrome and firebug can use console to debug js");
        });
    }

    $(".main >a").click(function(event){
        if($(this).siblings("ul").length>0){
            event.preventDefault();
            var ulNode=$(this).next("ul");
            ulNode.slideToggle();
        }
    });

});
