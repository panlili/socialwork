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

function getBirthdayByIdcard(idcard) {
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

//残疾
function toggleDisable(element) {
    if(element.attr("value")=="是"){
        $(".canji").show();
    }else{
        $(".canji").hide();
    }
}

//享受低保,与house.js中的重复
function toggleDibao(element) {
    if(element.attr("value")=="是"){
        $(".dibao").show();
    }else{
        $(".dibao").hide();
    }
}

//享受廉租房,与house.js中的重复
function toggleLianzu(element) {
    if(element.attr("value")=="是"){
        $(".lianzu").show();
    }else{
        $(".lianzu").hide();
    }
}

//是否特殊人群
function toggleSpecial(element) {
    if(element.attr("value")=="是"){
        $(".special").show();
    }else{
        $(".special").hide();
    }
}
//是否特殊人群
function toggleJhsy(element) {
    if(element.attr("value")=="是"){
        $(".jhsy").show();
    }else{
        $(".jhsy").hide();
    }
}

function checkIdCard(idcard) {
    var message="";
    message=vertifyIdCard(idcard);
    if("验证通过!"!=message){
        alert(message);
    }else{
        
        if(getAgeByIdcard(idcard)<56 || getAgeByIdcard(idcard)>17 || getSexByIdcard(idcard)=="女"){
            $("#is_jhsy").show(); 
                
        }
    }
        
}