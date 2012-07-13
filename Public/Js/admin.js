
function delete_user(id) {
    //ajax删除用户
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

$(function(){
    //页面的奇数td文本向右靠齐
    $("#admin_table td:even").css("text-align", "right");
    $("#adduser td:even").css("text-align", "right");
});