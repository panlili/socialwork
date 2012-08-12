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
//享受低保，与citizen.js中的重复
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
        $(".isfree2").hide();
    }else{
        $(".isfit").show();
        $(".isfree").show();
    }
}
//是否空闲
function toggleIsFree(element) {
    if(element.attr("value")=="是"){
        $(".isfree2").hide();
    }else{
        $(".isfree2").show();
    }
}
