/**
 * 为表格每一行添加序号
 */
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
