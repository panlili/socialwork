function getAgeByBirthday(birthday) {
    var   now=new   Date();
    var   old=new   Date(birthday);
    var age=now.getFullYear()-old.getFullYear();
    return age;

}

function getAgeByIdcard(idcard) {
    var birthday=getBirthdayByIdcard(idcard);
    var   now=new   Date();
    var   old=new   Date(birthday);
    var age=now.getFullYear()-old.getFullYear();
    return age;

}

function getSexByIdcard(idCard){
    idCard = trim(idCard.replace(/ /g, ""));// 对身份证号码做处理。包括字符间有空格。
    if(len(idCard)==15){
        if(idCard.substring(14,15)%2==0){
            return '女';
        }else{
            return '男';
        }
    }else if(len(idCard) ==18){
        if(idCard.substring(14,17)%2==0){
            return '女';
        }else{
            return '男';
        }
    }else{
        return null;
    }
}
/**
 * Comment
 */
function getBirthdayByIddard(idcard) {
    var birthday = "";
    if (18 == len(idcard)) {
        birthday = idcard.substring(6,10);
        birthday += "-" +idcard.substring(10,12);
        birthday += "-" + idcard.substring(12,14);
    } else if (15 == len(idcard)) {
        birthday = "19" + idcard.substring(6,8);
        birthday += "-" + idcard.substring(8,10);
        birthday += "-" + idcard.substring(10,12);
    }
    return birthday;

}
function len(s) {
    var l = 0;
    var a = s.split("");
    for (var i=0;i<a.length;i++) {
        if (a[i].charCodeAt(0)<299) {
            l++;
        } else {
            l+=2;
        }
    }
    return l;
}


function toggleDibao(element) {
    if(element.attr("checked")=="checked"){
        element.attr("value","是");
        element.parent().after('<td>低保金额：<input type="text" name="dibao_jine"/>开始享受低保时间：<input type=text name="dibao_start_date"/></td>');
    }else{
        element.parent().nextUntil("tr").remove();
    }
}
function toggleLianzhu(element) {
    if(element.attr("checked")=="checked"){
        element.attr("value","是");
        element.parent().after('<td>廉租地址：<input type=text name="lianzu_address" /></td>');
    }else{
        element.parent().nextUntil("tr").remove();
    }
}
function toggleCanji(element) {
    if(element.attr("checked")=="checked"){
        element.attr("value","是");
//        element.parent().after("<td>残疾：</td><td><input type=text /></td>");
        $("#canji_ext").show();
    }else{
//        element.parent().nextUntil("tr").remove();
        $("#canji_ext").hide();
    }
}

function toggleSp(element){
     if(element.attr("checked")=="checked"){
        element.attr("value","是");
//        element.parent().after("<td>残疾：</td><td><input type=text /></td>");
        $("#sp_renqun").show();
    }else{
//        element.parent().nextUntil("tr").remove();
        $("#sp_renqun").hide();
    }
}

function switch_hide_show(id_name, type)
{
  if(type==2){
    type='block';
  }else{
    type='inline';
  }
  if(document.getElementById(id_name).style.display==type){
    document.getElementById(id_name).style.display='none';
	document.getElementById(id_name).disabled = true;
  }else{
    document.getElementById(id_name).style.display=type;
	document.getElementById(id_name).disabled = false;
  }
}
