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

function toggleDibao(element) {
    if(element.attr("checked")=="checked"){
        element.attr("value","是");
        element.parent().after("<td>低保金额：</td><td><input type=text /></td><td>开始享受低保时间：</td><td><input type=text /></td>");
    }else{
        element.parent().nextUntil("tr").remove();
    }
}

function toggleLianzhu(element) {
    if(element.attr("checked")=="checked"){
        element.attr("value","是");
        element.parent().after("<td>廉租地址：</td><td><input type=text /></td>");
    }else{
        element.parent().nextUntil("tr").remove();
    }
}

function toggleIsFit(element){
    if(element.attr("checked")=="checked"){
        $(".isfit").hide();
    }else{
        $(".isfit").show();
    }
}