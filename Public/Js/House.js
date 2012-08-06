//点击是平房选项框时隐藏其他地址输入栏
function hideOtherAddress(element) {
    if(element.attr("checked")=="checked"){
        element.attr("value","是");
        $("#onefloor").show();
        element.parent().parent().nextUntil("#onefloor").hide();
    }else{
        $("#onefloor").hide();
        element.parent().parent().nextUntil("#onefloor").show();
    }        
}