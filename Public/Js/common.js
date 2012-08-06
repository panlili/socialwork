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

//layout.html中一些全局js效果的实现
function init() {
    setNumber();
    $("#common_table td").css("height","15px");
    
    //footer双击隐藏menu的实现
    $("#footer").dblclick(function(){
        $("#menu").toggle();
    });
        
    //action_message区块
    if($.trim($("#action_message").text())==""){
        $("#action_message").remove();
    }else{
        $("#action_message").fadeOut(2500,function(){
            $(this).remove();
        });
    }

    //menu的收缩
    $(".main >a").click(function(event){
        if($(this).siblings("ul").length>0){
            event.preventDefault();
            var ulNode=$(this).next("ul");
            ulNode.slideToggle();
        }
    });
}

function checkpassword(){
    var pass1=$("#pass1").val().trim();
    var pass2=$("#pass2").val().trim();
    if(pass1!=pass2 || pass1==""){
        alert("密码不一致！");
        return false;
    }
    else{
        return true;
    }
}