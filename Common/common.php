<?php

//为所有模型datetime字段自动插入数据提供函数支持
//array('registertime', 'getTime', Model::MODEL_INSERT, 'function'),
function getTime() {
    return date("Y-m-d h:i:s");
}

//为所有模型data字段自动插入数据提供函数支持
function getOnlyDate() {
    return date("Y-m-d");
}

//计算年龄
function getAge($birthday) {
    $year = substr($birthday, 0, 4);
    if ($year == "")
        return "";
    else
        return date("Y") - $year;
}

//通过身份证号码计算生日
function getBirthdayByIdCard($idcard) {
    $idcard = trim($idcard);
    if (!empty($idcard)) {
        $birthday = "";
        if (18 == strlen($idcard)) {
            $birthday = substr($idcard, 6, 4);
            $birthday .= "-" . substr($idcard, 10, 2);
            $birthday .= "-" . substr($idcard, 12, 2);
        } else if (15 == strlen($idcard)) {
            $birthday = "19" . substr($idcard, 6, 2);
            $birthday .= "-" . substr($idcard, 8, 2);
            $birthday .= "-" . substr($idcard, 10, 2);
        }
        return $birthday;
    }
}

//根据身份证计算性别
function getSexByIdCard($idcard) {
    $idcard = trim($idcard);
    if (!empty($idcard)) {
        $sex = "";
        $idcard = trim($idcard);
        if (18 === strlen($idcard)) {
            $sex = substr($idcard, -2, 1) % 2 ? "男" : "女";
        } else if (15 === strlen($idcard)) {
            $sex = substr($idcard, -1) % 2 ? "男" : "女";
        }
        return $sex;
    }
}

//当街道用户community=0的时候，注释掉添加修改数据操作
function cs() {
    if (C("DB_PREFIX") === "sum_") {
        echo "<!--";
    }
}

function ce() {
    if (C("DB_PREFIX") === "sum_") {
        echo "-->";
    }
}

//根据用户社区的不同设定不同标题
function setHeader() {
    if (C("DB_PREFIX") === "sjf_") {
        if ($Think . MODULE_NAME === "Login" || $Think . MODULE_NAME === "Party" || $Think . MODULE_NAME === "Parter"
                || $Think . MODULE_NAME === "Admin" || $Think . MODULE_NAME == "Safe") {
            echo "街道办事处";
        } else {
            echo "水井坊社区";
        }
    } elseif (C("DB_PREFIX") === "jz_") {
        echo "交子社区";
    } elseif (C("DB_PREFIX") === "sum_") {
        echo "街道办事处";
    }
}

//根据数据id的大小判断数据所属的社区，设定不同的图标
function setStreetIcon($id) {
    $str = "";
    if ($id >= 280020000) {
        $str = "<img src=__IMAGE__/jiaozhi.gif />";
    } else {
        $str = "<img src=__IMAGE__/shuijingfang.gif />";
    }
    return $str . " ";
}

//根据数据id的大小判断数据所属的社区，设定不同的图标
function setYardIcon($id) {
    $str = "";
    if ($id >= 2000000) {
        $str = "<img src=__IMAGE__/jiaozhi.gif />";
    } else {
        $str = "<img src=__IMAGE__/shuijingfang.gif />";
    }
    return $str . " ";
}

//获取街道列表,并生成一串html的select
function getStreetSelect($id = "") {
    $m_street = D("Street");
    $streetlist = $m_street->select();

    $html = "<select name='street_id'>";
    if ("" != $id) {
        $name = $m_street->where("id=$id")->getField(name);
        $html.="<option value=$id selected=selected>$name</option>";
        $html.="<option value=$id>----请重新选择----</option>";
    }

    foreach ($streetlist as $s) {
        $html .= "<option value=$s[id]>$s[name]</option>";
    }
    return $html;
}

//获取yard列表,并生成一串html的select
function getYardSelect($id = "") {
    $m_yard = D("Yard");
    $yardlist = $m_yard->select();

    $html = "<select name='yard_id'>";
    if ("" != $id) {
        $name = $m_yard->where("id=$id")->getField(name);
        $html.="<option value=$id selected=selected>$name</option>";
        $html.="<option value=$id>----请重新选择----</option>";
    }

    foreach ($yardlist as $y) {
        $html .= "<option value=$y[id]>$y[name]</option>";
    }
    return $html;
}

function reverseIt($arg) {
    if ($arg === "是")
        return "否";
    if ($arg === "否")
        return "是";
}

//根据残疾号获取残疾等级
function getCanjiLevel($canji_no) {
    $last = substr($canji_no, -1);

    switch ($last) {
        case 1:
            return "一级";
            break;
        case 2:
            return "二级";
            break;
        case 3:
            return "三级";
            break;
        case 4:
            return "四级";
            break;
        default: "不详";
            break;
    }
}

function auto_charset($fContents, $from = 'gbk', $to = 'utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}

?>
