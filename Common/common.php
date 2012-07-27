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

function comment_start() {
    if (C("DB_PREFIX") == "sum_") {
        echo "<!--";
    }
}

function comment_end() {
    if (C("DB_PREFIX") == "sum_") {
        echo "-->";
    }
}

function setHeader() {
    if (C("DB_PREFIX") === "sjf_") {
        if ($Think . MODULE_NAME === "Login" || $Think . MODULE_NAME === "Ngo"
                || $Think . MODULE_NAME === "Party" || $Think . MODULE_NAME === "Parter"
                || $Think . MODULE_NAME === "Admin") {
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

?>
