//身份证号码验证程序
function vertifyIdCard(idcard){ 
    var Errors=new Array("验证通过!","身份证号码位数不对!","身份证号码出生日期超出范围或含有非法字符!","身份证号码校验错误!","身份证地区非法!"); 
    var area={
        11:"北京",
        12:"天津",
        13:"河北",
        14:"山西",
        15:"内蒙古",
        21:"辽宁",
        22:"吉林",
        23:"黑龙江",
        31:"上海",
        32:"江苏",
        33:"浙江",
        34:"安徽",
        35:"福建",
        36:"江西",
        37:"山东",
        41:"河南",
        42:"湖北",
        43:"湖南",
        44:"广东",
        45:"广西",
        46:"海南",
        50:"重庆",
        51:"四川",
        52:"贵州",
        53:"云南",
        54:"西藏",
        61:"陕西",
        62:"甘肃",
        63:"青海",
        64:"宁夏",
        65:"xinjiang",
        71:"台湾",
        81:"香港",
        82:"澳门",
        91:"国外"
    } 
    var idcard,Y,JYM; 
    var S,M; 
    var idcard_array = new Array(); 
    idcard_array = idcard.split(""); 
    if(area[parseInt(idcard.substr(0,2))]==null) return Errors[4]; 
    switch(idcard.length){ 
        case 15:
            if ((parseInt(idcard.substr(6,2))+1900) % 4 == 0 || ((parseInt(idcard.substr(6,2))+1900) % 100 == 0 && (parseInt(idcard.substr(6,2))+1900) % 4 == 0 )){ 
                ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/;//测试出生日期的合法性 
            } 
            else{ 
                ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/;//测试出生日期的合法性 
            } 
            if(ereg.test(idcard)) 
                return Errors[0]; 
            else 
                return Errors[2]; 
            break; 
        case 18:
            if( parseInt(idcard.substr(6,4)) % 4 == 0 || ( parseInt(idcard.substr(6,4)) % 100 == 0 && parseInt(idcard.substr(6,4))%4 == 0 )){ 
                ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/;//闰年出生日期的合法性正则表达式 
            } 
            else{ 
                ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/;//平年出生日期的合法性正则表达式 
            } 
            if(ereg.test(idcard)){ 
                S = (parseInt(idcard_array[0]) + parseInt(idcard_array[10])) * 7 + (parseInt(idcard_array[1]) + parseInt(idcard_array[11])) * 9 + (parseInt(idcard_array[2]) + parseInt(idcard_array[12])) * 10 + (parseInt(idcard_array[3]) + parseInt(idcard_array[13])) * 5 + (parseInt(idcard_array[4]) + parseInt(idcard_array[14])) * 8 + (parseInt(idcard_array[5]) + parseInt(idcard_array[15])) * 4 + (parseInt(idcard_array[6]) + parseInt(idcard_array[16])) * 2 + parseInt(idcard_array[7]) * 1 + parseInt(idcard_array[8]) * 6 + parseInt(idcard_array[9]) * 3 ; 
                Y = S % 11; 
                M = "F"; 
                JYM = "10X98765432"; 
                M = JYM.substr(Y,1); 
                if(M == idcard_array[17]) 
                    return Errors[0]; 
                else 
                    return Errors[3]; 
            } 
            else 
                return Errors[2]; 
            break; 
        default:
            return Errors[1]; 
            break; 
    } 
} 
/**
 * Comment
 */
function getAgeByBirthday(birthday) {
    var   now=new   Date(); 
    var   old=new   Date(birthday); 
    var age=now.getFullYear()-old.getFullYear();
    return age;
    
}
/**
 * 
 */
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

function checkIdCard(idcard) {
    var message="";
    message=vertifyIdCard(idcard);
    if("验证通过!"!=message){
        alert(message);
    }
    
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
