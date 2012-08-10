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
//是否平房
function toggleAddress(element) {
    if(element.attr("value")=="是"){
        $(".addresspart").hide();
        $("#onefloor").show();
    }else{
        $(".addresspart").show();
        $("#onefloor").hide();
    }
}
//享受低保
function toggleDibao(element) {
    if(element.attr("value")=="是"){
        $(".dibao").show();
    }else{
        $(".dibao").hide();
    }
}
//享受廉租房
function toggleLianzu(element) {
    if(element.attr("value")=="是"){
        $(".lianzu").show();
    }else{
        $(".lianzu").hide();
    }
}
//人户一致
function toggleIsFit(element) {
    if(element.attr("value")=="是"){
        $(".isfit").hide();
        $(".isfree").hide();
    }else{
        $(".isfit").show();
        $(".isfree").show();
    }
}
//是否空闲
function toggleIsFree(element) {
    if(element.attr("value")=="是"){
        $(".rent").hide();
    }else{
        $(".rent").show();
    }
}
