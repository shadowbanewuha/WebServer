function $(elementId) {
    return document.getElementById(elementId).value;
}

function divId(elementId) {
    return document.getElementById(elementId);
}

/* 用户名验证 */
function  checkUsrName() {
    var user = $("usr");
    var userId = divId("usr_prompt");
    userId.innerHTML = "";
    var reg = /^[a-zA-Z][a-zA-Z0-9_]{4,15}$/;
    if (reg.test(user) == false) {
        userId.innerHTML = "用户名由字母、数字、下划线组成，必须以字母开头，长度5-16位";
        return false;
    }
    return true;
}

/* 密码验证 */
function  checkPwd() {
    var pwd = $("pwd");
    var pwdId = divId("pwd_prompt");
    pwdId.innerHTML = "";
    var reg = /^[a-zA-Z0-9_.]{5,15}$/;
    if (reg.test(pwd) == false) {
        pwdId.innerHTML = "密码由字母、数字和常用字符组成，长度6-16位";
        return false;
    }
    return true;
}

function checkRepwd() {
    var repwd = $("repwd");
    var pwd = $("pwd");
    var repwdId = divId("repwd_prompt");
    repwdId.innerHTML = "";
    if (repwd != pwd) {
        repwdId.innerHTML = "两次输入的密码不一致";
        return false;
    }
    return true;
}

/*验证邮箱*/
function checkEmail(){
    var email=$("email");
    var email_prompt=divId("email_prompt");
    email_prompt.innerHTML="";
    var reg=/^\w+@\w+(\.[a-zA-Z]{2,3}){1,2}$/;
    if (reg.test(email) == false) {
        email_prompt.innerHTML = "邮箱格式不正确";
        return false;
    }
    return true;
}

/*验证手机号码*/
function checkMobile(){
    var mobile = $("mobile");
    var mobileId = divId("mobile_prompt");
    mobileId.innerHTML = "";
    var regMobile = /^0?(1[3-9][0-9])[0-9]{8}$/;
    if (regMobile.test(mobile) == false) {
        mobileId.innerHTML = "手机号码不正确，请重新输入";
        return false;
    }
    return true;
}