//隐藏表单
function hideForm(formtitle) {
    return;
    $("li", $(formtitle).parent()).toggle();
}

//获取URL请求变量

function request(qsname) {
    if (location.search == "" || qsname == "") return "";
    qsname = qsname.toLowerCase();
    var queryString = location.search;
    if (queryString.substr(0, 1) == "?") queryString = queryString.substr(1, queryString.length - 1);
    var query = queryString.split("&");
    for (var i = 0; i < query.length; i++) {
        var q = query[i];
        if (q.length < qsname.length) continue;
        if (q.toLowerCase().substr(0, qsname.length) == qsname) {
            return q.split("=")[1];
        }
    }
    return "";
}

//通用的回车键切换焦点
document.onkeydown = function() {
    if (event.keyCode == 13) { //回车
        if (event.srcElement.type != 'button' && event.srcElement.type != 'submit' && event.srcElement.type != 'reset' && event.srcElement.type != 'textarea' && event.srcElement.type != 'password' && event.srcElement.type != '')
            event.keyCode = 9;
    }
    if (event.keyCode == 40) { //向下箭头
        if (event.srcElement.type != 'button' && event.srcElement.type != 'submit' && event.srcElement.type != 'reset' && event.srcElement.type != 'textarea' && event.srcElement.type != '')
            try { //选择下一行同一列文本框
                event.cancelBubble = true;
                event.returnvalue = false;
                var nextAmtTextBox = event.srcElement.parentNode.parentNode.nextSibling.cells[event.srcElement.parentNode.cellIndex].childNodes[0];
                nextAmtTextBox.focus();
                nextAmtTextBox.select();
            } catch(ex) {
                //单位控件
                try {
                    var nextAmtTextBox = event.srcElement.parentNode.parentNode.parentNode.nextSibling.cells[event.srcElement.parentNode.parentNode.cellIndex].childNodes[0].childNodes[0];
                    nextAmtTextBox.focus();
                    nextAmtTextBox.select();
                } catch(ex1) {
                }
            }
    }
    if (event.keyCode == 38) { //向上箭头
        if (event.srcElement.type != 'button' && event.srcElement.type != 'submit' && event.srcElement.type != 'reset' && event.srcElement.type != 'textarea' && event.srcElement.type != '') {
            try { //选择上一行同一列文本框
                event.cancelBubble = true;
                event.returnvalue = false;
                var previousAmtTextBox = event.srcElement.parentNode.parentNode.previousSibling.cells[event.srcElement.parentNode.cellIndex].childNodes[0];
                previousAmtTextBox.focus();
                previousAmtTextBox.select();
            } catch(ex) {
                //单位控件
                try {
                    var previousAmtTextBox = event.srcElement.parentNode.parentNode.parentNode.previousSibling.cells[event.srcElement.parentNode.parentNode.cellIndex].childNodes[0].childNodes[0];
                    previousAmtTextBox.focus();
                    previousAmtTextBox.select();
                } catch(ex1) {
                }
            }
        }
    }
    if (event.keyCode == 39) { //向右箭头
        if (event.srcElement.type != 'button' && event.srcElement.type != 'submit' && event.srcElement.type != 'reset' && event.srcElement.type != 'textarea' && event.srcElement.type != '')
            try { //选择同一列下一个文本框: 单位控件
                event.cancelBubble = true;
                event.returnvalue = false;
                var nextAmtTextBox = event.srcElement.nextSibling.nextSibling;
                nextAmtTextBox.focus();
                nextAmtTextBox.select();
            } catch(ex) {
            }
    }
    if (event.keyCode == 37) { //向左箭头
        if (event.srcElement.type != 'button' && event.srcElement.type != 'submit' && event.srcElement.type != 'reset' && event.srcElement.type != 'textarea' && event.srcElement.type != '')
            try { //选择同一列上一个文本框: 单位控件
                event.cancelBubble = true;
                event.returnvalue = false;
                var nextAmtTextBox = event.srcElement.previousSibling.previousSibling;
                nextAmtTextBox.focus();
                nextAmtTextBox.select();
            } catch(ex) {
            }
    }

};

function checkAll(checked, lstName) {
    var input = document.getElementsByTagName("INPUT");
    for (var i = 0; i < input.length; i++) {
        if (lstName == null) {
            if (input[i].disabled != true) {
                input[i].checked = checked;
            }
        } else if (input[i].type == "checkbox") {
            if (input[i].name == lstName || input[i].name.lastIndexOf(lstName) >= 0) {
                if (input[i].disabled != true) {
                    input[i].checked = checked;
                }
            }
        }
    }
}

// 递归选择树节点

function checkChildNode() {
    if (window.event.srcElement.tagName != "INPUT" || window.event.srcElement.type != "checkbox") return;
    checkChildNodeRecursive(window.event.srcElement);
    checkParentNodeRecursive(window.event.srcElement);
}

// 递归选择树子节点

function checkChildNodeRecursive(node) {
    //当前节点层
    var div = window.document.getElementById(node.id.replace("CheckBox", "Nodes"));
    if (div == null) return;

    //当前节点下所有子节点
    var childNodes = div.getElementsByTagName("input");
    for (var i = 0; i < childNodes.length; i++) {
        if (childNodes[i].type == "checkbox") {
            childNodes[i].checked = node.checked;
        }
    }
}

//递归选择树父节点

function checkParentNodeRecursive(node) {
    if (node.id == "form1") return;
    //父层
    var parentDiv = node.parentElement.parentElement.parentElement.parentElement.parentElement;
    //父节点
    var parentNode = document.getElementById(parentDiv.id.replace("Nodes", "CheckBox"));
    if (parentNode == null) return;

    //子节点被选中父节点也要被选中
    if (node.checked) {
        parentNode.checked = true;
    } else {
        var noChildChecked = true;
        var childNodes = parentDiv.getElementsByTagName("input");
        for (var i = 0; i < childNodes.length; i++) {
            if (childNodes[i].type == "checkbox" && childNodes[i].checked) {
                noChildChecked = false;
                break;
            }
        }
        //没有一个儿子被选中的父节点取消选中
        if (noChildChecked) {
            parentNode.checked = false;
        }
    }

    //向上递归
    checkParentNodeRecursive(parentNode);
}

//设置选中的gridview行的样式  ：单选

function setGridViewRowClass(index) {
    var src = event.srcElement ? event.srcElement : event.target;
    if (src.tagName != "TD" && src.tagName != "TR") return;
    var rows;
    if (src.tagName == "TD") {
        rows = src.parentNode.parentNode.childNodes;
    } else {
        rows = src.parentNode.childNodes;
    }
    for (var i = 1; i < rows.length; i++) {
        if (parseInt(index) + 1 == i) {
            rows[i].className = "gridrowselected";
        } else {
            rows[i].className = "gridrow";
        }
    }
}

//设置点击gridview行时，将第0列的checkbox选中/不选中  ：多选

function chooseGridViewRow(index) {
    var src = event.srcElement ? event.srcElement : event.target;
    if (src.tagName != "TD" && src.tagName != "TR") return;
    var rows;
    if (src.tagName == "TD") {
        rows = src.parentNode.parentNode.childNodes;
    } else {
        rows = src.parentNode.childNodes;
    }
    var chk = rows[parseInt(index) + 1].cells[0].childNodes[0];
    if (chk.type == "checkbox") {
        chk.checked = !chk.checked;
    }
}

//批量操作(多选)：检查是否选中记录
//containerID:checkbox的容器ID
//hideID:隐藏控件ID，用来存储选中Checkbox的值

function checkSelected(containerID, hideID) {
    checkSelected(containerID, hideID, true);
}

//hideID:隐藏控件ID，用来存储选中Checkbox的值，showMsg:是否需要显示确认消息

function checkSelected(containerID, hideID, showMsg) {
    if (showMsg == true) {
        if (!confirm("确认要执行操作吗？")) {
            return false;
        }
    }
    var nos = "";
    $('input:checkbox', '#' + containerID).each(function() {
        var qty = this.checked ? 1 : 0;
        if (this.value != null && this.value != "" && qty > 0) {
            nos += this.value + ",";
        }
    });
    if (nos.length > 0) {
        nos = nos.substr(0, nos.length - 1);
        document.getElementById(hideID).value = nos;
        return true;
    } else {
        alert('请至少选择一条记录');
        return false;
    }
}

//hideID:隐藏控件ID，用来存储选中Checkbox的值，showMsg:是否需要显示确认消息, lstName:控件name

function checkSelected(containerID, hideID, showMsg, lstName) {
    if (showMsg == true || showMsg == null) {
        if (!confirm("确认要执行操作吗？")) {
            return false;
        }
    }
    var nos = "";
    $('input:checkbox', '#' + containerID).each(function() {
        var qty = this.checked ? 1 : 0;
        if (lstName == null) {
            if (this.value != null && this.value != "" && qty > 0) {
                nos += this.value + ",";
            }
        } else if (this.name == lstName || this.name.lastIndexOf(lstName) >= 0) {
            if (this.value != null && this.value != "" && qty > 0) {
                nos += this.value + ",";
            }
        }
    });
    if (nos.length > 0) {
        nos = nos.substr(0, nos.length - 1);
        document.getElementById(hideID).value = nos;
        return true;
    } else {
        alert('请至少选择一条记录');
        return false;
    }
}

//添加重载方法

function insertState(msg) {

    var len = arguments.length;
    if (len == 0)
        msg = "操作成功";
    alert(msg);
    var url = unescape(window.location.href);
    var newMemuId = url.substr(url.indexOf("menuId=") + 7);
    //如果主键中存在日期参数，将日期中的+替换成空格，进行日期格式还原
    parent.closePage(newMemuId.replace("+", " "));


}

function Close_Page() {
    var newMemuId = window.location.href.substr(window.location.href.indexOf("menuId=") + 7);
    parent.closePage(unescape(newMemuId));

}

function Close_Page(newMemuId) {
    parent.closePage(newMemuId);

}

//数字框输入改变事件(数字)

function numericTextChangeDigit(txt) {
    txt.value = txt.value.replace(/[^\d]/g, '');
    if (window.event.keyCode == 9) txt.select();
}

//数字框输入改变事件（小数）

function numericTextChangeDecimal(txt, min, max, precision) {
    txt.value = txt.value.replace(/[^\d\.]/g, '').replace(/[\.]+/g, '.').replace(/^0+/g, '0');
    if (txt.value == "") txt.value = "0";
    var point = txt.value.indexOf(".");
    if (point > 0) {
        if (point == txt.value.length - 1) {
            //            txt.value = txt.value.substring(0, txt.value.length - 1);
        } else {
            txt.value = txt.value.substring(0, point + 1 + precision);
        }
    }
    if (txt.value == ".") txt.value = "";
    numericTextChangeLimit(txt, min, max);
}

//数字框输入改变事件（负小数）

function numericTextChangeNegativeDecimal(txt, min, max, precision) {
    if (txt.value.substr(0, 1) == "-") {
        txt.value = "-" + txt.value.replace(/[^\d\.]/g, '').replace(/[\.]+/g, '.').replace(/^0+/g, '0');
    } else {
        txt.value = txt.value.replace(/[^\d\.]/g, '').replace(/[\.]+/g, '.').replace(/^0+/g, '0');
    }
    if (txt.value == "") txt.value = "0";
    var point = txt.value.indexOf(".");
    if (point > 0) {
        if (point == txt.value.length - 1) {
            //            txt.value = txt.value.substring(0, txt.value.length-1);
        } else {
            txt.value = txt.value.substring(0, point + 1 + precision);
        }
    }
    if (txt.value == ".") txt.value = "";
    numericTextChangeLimit(txt, min, max);
}

//numericTextChangeNegativeInteger（负整数）

function numericTextChangeNegativeInteger(txt, min, max) {
    if (txt.value.substr(0, 1) == "-") {
        txt.value = "-" + txt.value.replace(/[^\d]/g, '').replace(/^0+/g, '');
    } else {
        txt.value = txt.value.replace(/[^\d]/g, '').replace(/^0+/g, '');
    }
    if (txt.value == "") txt.value = "0";

    numericTextChangeLimit(txt, min, max);
}

//数字框输入改变事件（整数）

function numericTextChangeInteger(txt, min, max) {
    txt.value = txt.value.replace(/[^\d]/g, '').replace(/^0+/g, '');
    if (txt.value == "") txt.value = "0";
    numericTextChangeLimit(txt, min, max);
}

//限制输入数字大小

function numericTextChangeLimit(txt, min, max) {
    var val = parseFloat(txt.value);
    if (min != "") {
        if (val < min) txt.value = min;
    }
    if (max != "") {
        if (val > max) txt.value = max;
    }
    if (window.event.keyCode == 9) txt.select();
}

//批量操作

function plancheckSelected() {
    var nos = "";
    $('input:checkbox', '#headDiv').each(function() {
        var qty = this.checked ? 1 : 0;
        if (this.value != null && this.value != "" && qty > 0) {
            nos += this.value.substr(0, this.value.length - 2) + ",";
        }
    });
    if (nos.length > 0) {
        nos = nos.substr(0, nos.length - 1);
        var url = $("#listToolbarModule1_PrintButton1").attr('href');
        var newurl = url.replace(/(pkid)[^&]+/g, 'pkid=' + nos);
        $("#listToolbarModule1_PrintButton1").attr('href', newurl);
        return true;
    } else {
        alert('请至少选择一条订单');
        return false;
    }
}

//行单击事件

function oneclick(id) {

    if ($('#' + id).is(':checked')) {
        $('#' + id).attr('checked', false);
    } else {
        $('#' + id).attr('checked', true);
    }
}

//GV双击行事件

function dbclick(pkid, state, url, menuid, urlname, bool) {
    if (state == 1 && bool == 'True') {
        parent.showPage(menuid, urlname + "编辑", url + "?Action=1&pkid=" + pkid + "&menuId=" + menuid);
    } else {
        parent.showPage(menuid, urlname + "查看", url + "?Action=3&pkid=" + pkid + "&menuId=" + menuid);
    }
}

////回车焦点改变
//jQuery(function () {
//    var $inp = jQuery('ksy：input:text');
//    $inp.bind('keydown', function (e) {
//        var key = e.which;
//        if (key == 13) {
//            e.preventDefault();
//            //下一个TEXT的索引
//            var nxtIdx = $inp.index(this) + 1;

//            //判断下一个TEXT是否可用
//            var tempHidden = jQuery(":input:text:eq(" + nxtIdx + ")").is(":hidden");//是否隐藏 
//            var tempVisible = jQuery(":input:text:eq(" + nxtIdx + ")").is(":visible");//是否可见 
//            var tempEnabled = jQuery(":input:text:eq(" + nxtIdx + ")").is(":Enabled");//是否可用 
//            var i = 0;
//            //不可用索引加1
//            while ((tempHidden == true || tempVisible == false || tempEnabled == false) && i < 30) {
//                i = i + 1;
//                nxtIdx = nxtIdx + 1;
//                tempHidden = jQuery(":input:text:eq(" + nxtIdx + ")").is(":hidden");//是否隐藏 
//                tempVisible = jQuery(":input:text:eq(" + nxtIdx + ")").is(":visible");//是否可见 
//                tempEnabled = jQuery(":input:text:eq(" + nxtIdx + ")").is(":Enabled");//是否可用 
//            }
//            //设定下一个TEXT选中
//            jQuery(":input:text:eq(" + nxtIdx + ")").focus();
//            jQuery(":input:text:eq(" + nxtIdx + ")").select();
//        }
//    });
//});

//GRIDVIEW分页后调用，不然回车只能在第一页起作用

function EnterFunction() {
    //回车焦点改变
    jQuery(function() {
        var $inp = jQuery('input:text');
        $inp.bind('keydown', function(e) {
            var key = e.which;
            if (key == 13) {
                e.preventDefault();
                //下一个TEXT的索引
                var nxtIdx = $inp.index(this) + 1;

                //判断下一个TEXT是否可用
                var tempHidden = jQuery(":input:text:eq(" + nxtIdx + ")").is(":hidden"); //是否隐藏 
                var tempVisible = jQuery(":input:text:eq(" + nxtIdx + ")").is(":visible"); //是否可见 
                var tempEnabled = jQuery(":input:text:eq(" + nxtIdx + ")").is(":Enabled"); //是否可用 
                var i = 0;
                //不可用索引加1
                while ((tempHidden == true || tempVisible == false || tempEnabled == false) && i < 30) {
                    i = i + 1;
                    nxtIdx = nxtIdx + 1;
                    tempHidden = jQuery(":input:text:eq(" + nxtIdx + ")").is(":hidden"); //是否隐藏 
                    tempVisible = jQuery(":input:text:eq(" + nxtIdx + ")").is(":visible"); //是否可见 
                    tempEnabled = jQuery(":input:text:eq(" + nxtIdx + ")").is(":Enabled"); //是否可用 
                }
                //设定下一个TEXT选中
                jQuery(":input:text:eq(" + nxtIdx + ")").focus();
                jQuery(":input:text:eq(" + nxtIdx + ")").select();
            }
        });
    });

}

//控制Textarea输入字数，超出此次数，则不让输入。onkeypress

function TextareaMaxLengthInput(obj, maxNums) {
    if (obj.value.length == maxNums) {
        return false;
    } else {
        return true;
    }
}

//超出这个数子，则截断

function TextareaMaxLength(obj, maxNums) {
    if (obj.value.length > maxNums) {
        obj.value = obj.value.substring(0, maxNums);
        return false;
    } else {
        return true;
    }
}

function getDateDiffString(dateStart, dateEnd) {
    var diff = dateEnd - dateStart;
    if (diff / 1000 / 3600 / 24 / 30 / 12 >= 1) {
        return Math.round(diff / 1000 / 3600 / 24 / 30 / 12).toString() + "年";
    } else if (diff / 1000 / 3600 / 24 / 30 >= 1) {
        return Math.round(diff / 1000 / 3600 / 24 / 30).toString() + "月";
    } else if (diff / 1000 / 3600 / 24 / 7 >= 1) {
        return Math.round(diff / 1000 / 3600 / 24 / 7).toString() + "周";
    } else if (diff / 1000 / 3600 / 24 >= 1) {
        return Math.round(diff / 1000 / 3600 / 24).toString() + "天";
    } else if (diff / 1000 / 3600 >= 1) {
        return Math.round(diff / 1000 / 3600).toString() + "小时";
    } else if (diff / 1000 / 60 >= 1) {
        return Math.round(diff / 1000 / 60).toString() + "分钟";
    } else if (diff / 1000 >= 1) {
        return "刚才";
    } else {
        return "现在";
    }
}

function getBodyWidth() {
    if (window.outerWidth != window.undifined) //Firefox
        return window.outerWidth;
    else                                    //IE
        return document.body.offsetWidth;
    //return window.screen.availWidth-window.screenLeft;
}

function getBodyHeight() {
    if (window.outerHeight != window.undifined) //Firefox
        return window.outerHeight;
    else                                    //IE
        //return document.body.offsetHeight;
        //return document.body.scrollHeight;
        return window.screen.availHeight - window.screenTop;
}

function printImage(url) {
    if (document.getElementById("printIframe") == null) {
        var frm = document.createElement("iframe");
        frm.id = "printIframe";
        frm.style.display = "none";
        document.body.appendChild(frm);
    }

    var str = "";
    str += '<html><head></head><body onload="window.print();">';
    str += '<img src="' + url + '" border="1" />';
    str += '</body></html>';
    window.frames["printIframe"].document.writeln(str);
    window.frames["printIframe"].document.close();
}

//折叠模块详细列表 

function showDetail(imgobj, detailTableId) {
    $('#' + detailTableId).toggle();
    var trObj = $(imgobj).parent().parent();
    var offset = trObj.offset();
    $('#' + detailTableId).css("left", offset.left - 1);
    if ($("#" + detailTableId).is(":visible")) {
        $(imgobj).attr("src", "Images/expand-1.gif");
    } else {
        $(imgobj).attr("src", "Images/expand-0.gif");
    }
}

//鼠标划过行变背景色

function set_mOver(obj) {
    obj.style.backgroundColor = '#e8eef4';
    obj.style.cursor = 'hand';
    obj.style.filter = 'alpha(opacity=50)';
}

//鼠标划开清空行背景色

function set_mOut(obj) {
    obj.style.backgroundColor = "transparent";
    obj.style.filter = '';
    obj.style.cursor = 'hand';
}