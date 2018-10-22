var userid = "";
var reg = /<[^>]+>/g;
var balance = $("#Balance").html();
$(function () {
    //每隔60秒刷新余额
    userid = $("#userid").val();
    if (userid != null && userid != "") {
        SetHits();
        setInterval("SetHits()", 60000);
    }
});

/**
 * [SetHits 读取余额]
 */
function SetHits() {
    $.ajax({
        type: "post",
        url: "/Common/RefreshBalance?BalanceType=sys&uid=" + userid,
        data: null,
        success: function (data) {
            if (data == -1) {
                $("#Balance").html(balance);
            } else if(data == 0) {
                balance=data;
                $("#Balance").html(balance);
            } else {
                if (!reg.test(data)) {
                    $("#Balance").html(data);
                    balance=data;
                } else {
                    $("#Balance").html(balance);
                }
            }
        }
    });
}

/**
 * [HotNewsHistory 公告信息弹窗]
 */
function HotNewsHistory() {
    window.open('HotNews', 'HotNewsHistory', 'height=600,width=800,top=0, left=0,scrollbars=yes,resizable=yes');
}
/**
 * [alert 提示框]
 * @param  {[type]} content [弹窗内容] 必填
 * @param  {[type]} title [弹窗标题] 可选填参数，不填默认为“温馨提示”
 * 不填：如没有width和top参数就可以写为alert('弹窗内容')，如果有必须写一个空值alert('弹窗内容','','360px','100px')
 * @param  {[type]} width [弹窗宽度] 可选填参数，不填默认为“360px”,符合大部分提示消息
 * 不填：如没有top参数可以alert('弹窗内容')或alert('弹窗内容','弹窗标题')，如果有必须写空值alert('弹窗内容','弹窗标题','','100px')
 * @param  {[type]} top [弹窗距顶部位置] 可选填参数，不填默认为“50%”,符合大部分提示消息
 * 不填：可以alert('弹窗内容')或alert('弹窗内容','弹窗标题')或alert('弹窗内容','弹窗标题','360px')
 * @return {[type]}        [弹窗]
 * 标准例子alert('澳门威尼斯人欢迎您!','欢迎光临','360px','100px');
 */
function alert(content,title,width,top) {
    if(content==undefined){
        content="";
    }
    if(title==undefined){
        title="温馨提示";
    }
    if(width==undefined){
        width="360px";
    }
    if(top==undefined){
        top="50%";
    }
    $(document).dialog({
        dialogWidth: width,
        dialogTop: top,
        titleText: title,
        content: content,
    });
}
/**
 * [alert 确认框]
 * @param  {[type]} content [弹窗内容] 必填
 * @param  {[type]} title [弹窗内容] 选填 用法同alert
 * @param  {[type]} width [弹窗宽度] 选填 用法同alert
 * @param  {[type]} top [弹窗距顶部位置] 选填 用法同alert
 * @return {[type]}        [弹窗]
 */
function confirm(content,title,width,top) {
    if(content==undefined){
        content="";
    }
    if(title==undefined){
        title="温馨提示";
    }
    if(width==undefined){
        width="360px";
    }
    if(top==undefined){
        top="50%";
    }
    $(document).dialog({
        type: 'confirm',
        dialogWidth: width,
        dialogTop: top,
        titleText: title,
        content: content,
    });
}
/**
 * [toGame 游戏中心弹窗]
 * @param  {[type]} name   [平台名称]
 * @param  {[type]} gameid [平台游戏ID(选填)]
 * @return {[type]}        [返回弹窗]
 */
function toGame(name,gameid) {
    if (userid == null || userid == "")
    {
        alert('您尚未登录，请先登录后再进行游戏！')
    } else {
        window.open('/Common/NewLive?uid='+userid+'&Gametype='+name+'&Gameid='+gameid, '游戏中心', 'height=' + $(window).height() + ', width=' + $(window).width() + ', top=0, left=0,toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no');
    }
}

/**
 * [toPage 非a标签的内链跳转和外链跳转
 * @param  {[type]} name 要跳转页面的名称或外链url地址
 * @param  {[type]} other 除了uid以外的其他链接参数
 * @param  {[type]} blank 打开新页面，如会员中心打开游戏 如果blank有值，other不能不填
 * @return {[type]}      跳转页面
 * <li onclick="toPage('GameLive')"></li>进入视讯
 * <li onclick="toPage('GameSlotGame','&GameType=AMB')"></li>进入对应电子页面
 * <li onclick="toPage('http://www.baidu.com/')"></li>打开外链
 * <li onclick="toPage('GameSlotGame','&GameType=AMB'，'_blank')"></li>弹窗新窗口打开
 * <li onclick="toPage('GameLive',''，'_blank')"></li>弹窗新窗口打开
 */
function toPage(name,other,blank) {
    if(name.indexOf("http")>-1){
        window.open(name,'_blank');
    }else{
        if(blank == "_blank"){
            if(other == undefined){
                window.open('\\'+name+'?uid='+userid, '_blank');
            }else{
                window.open('\\'+name+'?uid='+userid+other, '_blank');
            }
        }else{
            if(other == undefined || other==""){
                window.location.href=name+'?uid='+userid;
            }else{
                window.location.href=name+'?uid='+userid+other;
            }
        }

    }
}

/**
 * [winopen 弹窗--小窗口或自定义窗口]
 * 可用于会员中心、
 * @param  {[type]} url   [页面名称(必填参数)]
 * @param  {[type]} title [弹窗名称(必填参数)]
 * @param  {[type]} dir   [目录(有目录必填)]
 * @return {[type]}       [返回弹窗]
 * 例子：进会员中心winopen("Member#modifypwd","会员中心","NewCenter")
 */
function winopen(url, title, dir) {
    if(dir==undefined){
        dir="";
    }
    if(title=undefined){
        title="";
    }
    if(dir.indexOf("NewCenter")>-1){
        
        if (userid == null || userid == "") {
            alert("您尚未登录，请先登录后再进行游戏");
            return false;
        } else {
            if(url.indexOf('#')>-1){
                var urldata=url.split("#");
                window.open('/'+dir +'/'+ urldata[0] + '?uid=' + userid +'#'+ urldata[1]+'', title, 'height=700, width=1200, top=90, left=200,toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no');
            }else{
                window.open('/'+dir +'/'+ url + '?uid=' + userid +'', title, 'height=700, width=1200, top=90, left=200,toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no');
            }
            
        }  
    }else{
        if(url.indexOf("http")>-1){
            window.open(url, title, 'height=700, width=1200, top=90, left=200,toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no');
        }else{
            if(dir==""){
                window.open('/'+url + '?uid=' + userid + '', title, 'height=700, width=1200, top=90, left=200,toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no');
            }else{
                window.open('/'+dir + '/'+url + '?uid=' + userid + '', title, 'height=700, width=1200, top=90, left=200,toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no');
            }
            
        }
        
    }
}

/**
 * [change_zc_yzm 获取验证码]
 * @param  {[type]} id [绑定验证码的图片ID]
 * @return {[type]}    [返回验证码]
 */
function change_zc_yzm(id) {
    document.getElementById(id).src = "/Common/ValidateCode?id=" + Math.random();
}

/**
 * [setFirst 设为首页]
 * @param {[type]} sURL [当前网址]
 */
function setFirst(sURL) {
    try {
        document.body.style.behavior = 'url(#default#homepage)';
        document.body.setHomePage(sURL);
    } catch (e) {
        if (window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            } catch (e) {
                alert("抱歉，此操作被浏览器拒绝！\n\r请在浏览器地址栏输入“about:config”并回车\n\r然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
            }
        } else {
            alert("抱歉，您的浏览器不支持，请按照下面步骤操作：\n\r1.打开浏览器设置。\n\r2.点击设置网页。\n\r3.输入：" + sURL + "点击确定。");
        }
    }
}

/**
 * [bookMarksite 加入收藏]
 * @param  {[type]} sURL   [当前网址]
 * @param  {[type]} sTitle [网站名称]
 * @return {[type]}        [收藏成功]
 */
function bookMarksite(sURL, sTitle) {
    try {
        window.external.addFavorite(sURL, sTitle);
    } catch (e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "");
        } catch (e) {
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\r加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}

/**
 * [login 登录]
 * @return {[type]} [登录成功或失败]
 */
function Login() {
    var us = $("#LoginName").val();
    var pwd = $("#LoginPass").val();
    var code = $("#Code").val();
    if (us == "" || us == null) {
        alert("请输入用户名！");
        return false;
    }
    if (pwd == "" || pwd == null) {
        alert("请输入密码！");
        return false;
    }
    if (code == "" || code == null) {
        alert("请输入验证码！");
        return false;
    }
    $.ajax({
        url: "/Common/Login",
        data: { LoginName: us, LoginPass: pwd, Code: code },
        type:"POST",
        dataType:"JSON",
        success:function(data){
            if (data.Code == 1) {
                window.location.href = "/NewWeb/Welcome?uid=" + data.uid;
          }
          else{
                alert(data.Msg);
          }

        }
    });
}

/**
 * [toggleColor 文字闪烁效果]
 * 可以页面单独使用new toggleColor('#j-promotions', ['#FF0','#FF0000'] , 500 )
 * 也可以使用下面更简单的方式 <span tabcolor="#ff0000|#ffff00" tabtime="300">我最闪</span>
 * @param  {[type]} el  [包裹要闪烁文字的元素]
 * @param  {[type]} arr [传入一个颜色值数组，例如['#FFFF00','#FF0000']]
 * @param  {[type]} s   [闪烁间隔毫秒数 1000毫秒=1秒]
 * @return {[type]}     [文字闪烁效果]
 */
function toggleColor( el , arr , s ){
    var self = this;
    self._i = 0;
    self._timer = null;
    
    self.run = function(){
        if(arr[self._i]){
            $(el).css('color', arr[self._i]);
            self._i++;
        }else{
            self._i = 0;
        }
        self._timer = setTimeout(function(){
            self.run( el , arr , s);
        }, s);
    };
    self.run();
}
$(function(){
    /**
     * 闪烁文字：使用方法 任意标签加上属性tabcolor="颜色值|颜色值" tabtime="200"
     * 颜色值可以是十六进制颜色#ff0000,也可以用red,yellow等   必写属性
     * tabtime为毫秒数 1000毫秒=1秒   可选属性（不写默认为200毫秒）
     * 例子：<span tabcolor="#ff0000|#ffff00" tabtime="300">我最闪</span>
     */
    var tabel=$("*[tabcolor]");
    $(tabel).each(function (i) {
        var color = tabel.eq(i).attr("tabcolor");
        var s = tabel.eq(i).attr("tabtime");
        if(!s){
            s="200";
        }
        color = color.split("|");
        new toggleColor(tabel.eq(i),color,s);
    });
    /**
     * 按钮波纹效果
     * 使用方法，在需要波纹效果的元素上加个属性ripple
     * 例子：<button ripple></button>
     */
    var ripple_el=$("*[ripple]");
    $(ripple_el).each(function (i) {
        ripple_el.eq(i).css({"overflow":"hidden","position":"relative"});
        ripple_el.eq(i).children().css("pointer-events","none");
        ripple_el.eq(i).ripple();
    });
});