<html>

<head>
	<title>Bank_coder_1.1</title>
	<!--
20180925 Bank_coder_1.0 
20180927 Bank_coder_1.1 新增功能；滚到目标位置，使用记录log 
	-->
	<style>
		body {
			color: #555555;

		}

		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
		}

		td,
		th {
			border: 0px solid #535353;
			text-align: left;
			padding: 4px;
		}

		tr:nth-child(even) {
			background-color: #eeeeee;
		}

		tr:nth-child(odd) {
			background-color: #ffffff;
		}

		.tabelBottom {
			vertical-align: top;
		}

		.tabelBottom table {
			width: 100%;
		}

		.submit {
			background-color: #ffffff;
			border: none;
			color: #888888;
			border-radius: 4px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			border: 1px solid #888888;

			-webkit-transition-duration: 0.4s;
			/* Safari */
			transition-duration: 0.4s;
		}

		.submit:hover {
			background-color: #888888;
			color: #ffffff;
			border: 1px solid #888888;


			-webkit-transition-duration: 0.2s;
			/* Safari */
			transition-duration: 0.2s;
		}

		.submit:active {
			background-color: #555555;
			color: #cccccc;
			border: 1px solid #cccccc;
		}

		#main {
			width: 900px;
			margin: 0 auto;
		}

		body::-webkit-scrollbar {
			display: none;
		}

		.relative2 {
			position: relative;
			margin: 40 auto;
			background-color: rgb(197, 197, 197);
			width: 500px;
		}
	</style>
</head>

<body id='body'>
	<div id="main">
		<div id="part_0" style="text-align:center;">
			<p style="visibility: hidden;">错误提示区域</p>
		</div>
		<table style="width:884px;">
			<tr>
				<td>
					<div>
						第三方名称：
						<input id="pay_name" type="text" value="" style="width:170px; height:30px;" placeholder="范例付" />
					</div>
				</td>
				<td>
					<div>
						资料夹名称：
						<input id="file_name" type="text" value="" style="width:170px; height:30px;" placeholder="fanlifu" />
					</div>
				</td>
				<td>
					<div>
						<input class="submit" type="submit" onclick="split()" value="产生 ↓" style="width:294px; height:30px;" />
					</div>
				</td>
			</tr>
		</table>
		<br/>
		<div style="display: none;">
			<textarea id="common_Bank_name_textarea" cols="15" rows="25">
北京银行
渤海银行
东亚银行
工商银行
光大银行
广发银行
华夏银行
建设银行
交通银行
民生银行
南京银行
宁波银行
农业银行
平安银行
上海银行
上海农商银行
浦发银行
深圳发展银行
兴业银行
邮政储蓄银行
招商银行
中国银行
中信银行</textarea>
		</div>

		<table id="t0">
			<tr>
				<td id="td0">
					银行名称
				</td>
				<td id="td1">
					银行编号
				</td>
				<td style="width:600x;" id="td2">
					输出SQL
				</td>
			</tr>
			<tr>
				<td>
					<textarea valign="top" id="Bank_name_textarea" cols="14" rows="24" onfocus="this.select()" style="font-size:14px;resize: none;">
范例

中国邮政储蓄银行
中国工商银行
中国农业银行
中国银行
中国建设银行
交通银行
中信银行
中国光大银行
华夏银行
中国民生银行
广东发展银行
平安银行
招商银行
兴业银行
上海浦东发展银行
渤海银行
北京银行
深圳发展银行
北京农村商业银行
</textarea>
				</td>
				<td>
					<textarea valign="top" id="Bank_code_textarea" cols="14" rows="23" onfocus="this.select()" style="font-size:15px;resize: none;">
ex

PSBC
ICBC
ABC
BOC
CCB
BOCOM
CITIC
CEB
HXB
CMBC
GDB
PAB
CMB
FIB
SPDB
CBHB
BOB
SDB
BRCB
</textarea>
				</td>
				<td valign="top" style="width:590x; position:relative;">
					<div style="width:590px;"></div>
					<font size="1">
						<p>
							<div id="part_1"></div>
						</p>
						<p>
							<div id="part_5"></div>
						</p>
					</font>

					<form action="./Bank_coder_log.php" method="post">
						<textarea type="text" style="display: none;" id="SQL" name="SQL" value="" /></textarea>
						<textarea type="text" style="display: none;" id="sand_file_name" name="file_name" value="" /></textarea>
						<textarea type="text" style="display: none;" id="sand_pay_name" name="pay_name" value="" /></textarea>
						<input class="submit" type="submit" id="submitSQL" onclick="submit_SQL()" value="送出 →" style="width:585; height:30px;display: none; position: absolute; bottom: 5px;"
						/>
					</form>

				</td>
			</tr>
		</table>
		<br/>
		<table id="table_2" style="display: none;">
			<tr>
				<td style="position:relative;">
					<div class="tabelBottom" style="width:277px; display:inline-block;" onclick="getselecttext()">
						<span id="part_2"></span>
						<!-- <input type="submit" onclick="ok()" style="visibility:hidden;" value="加入" /> -->
						<br/>
						<br/>
					</div>
					<div class="tabelBottom" style="width:294px; display:inline-block;">
						<span id="part_4"></span>
						<input class="submit" type="submit" onclick="ok()" id="ok" style="visibility:hidden;" value="加入 ↑" />
					</div>
					<div class="tabelBottom" style="width:294px; display:inline-block;" onclick="getselecttext_2()">
						<span id="part_3"></span>
						<input class="submit" type="submit" onclick="addnull()" id="nullsubmit" style="visibility:hidden;" value="空值 ←" />

						<!-- <form action="./Bank_coder_log.php" method="post">
							<textarea type="text" style="display: none;" id="SQL" name="SQL" value="" /></textarea>
							<textarea type="text" style="display: none;" id="sand_file_name" name="file_name" value="" /></textarea>
							<input class="submit" type="submit" id="submitSQL" onclick="submit_SQL()" value="送出 →" style="width:294px; height:30px;display: none; position: absolute; bottom: 10px;"
							/>
						</form> -->
					</div>
				</td>
			</tr>
		</table>
		<br/>
		<p>
			<div>
				<p>说明</p>
				<p id="p_1"> * 第三方名称必填，资料夹名称为空或不存在时，档案会生成再此路径的bker资料夹中，若档案已存在则会直接覆盖上去</p>
				<p id="p_2"> * 银行名称和银行编号以换行切割不同银行，且数量必须相等</p>
				<p id="p_3"> * 点选 " 产生 ↓ " 下方手动选取相同银行，反白可寻找有相同文字的银行，勾选checkbox加入银行或点选 " 空值 ← " 跳过</p>
				<p id="p_4"> * 点选 " 加入 ↑ " 输出SQL区块为最终结果，确认无误后点选 " 送出 → " 后即生成至ftp资料夹中</p>
				<p id="p_5"> * 目前不会直接汇入到db，还是要手动添加</p>
			</div>
		</p>
		<!-- <?php 
	// if (isset($_REQUEST['msg'])) {
	// 					echo $_REQUEST['msg'];
	// 				} 
						?> -->

	</div>
</body>

<script>
	common_Bank_code = Array();

	//自动产生完全吻合银行
	function split() {
		if (document.getElementById("pay_name").value == '') {
			document.getElementById("part_0").innerHTML = '<p style="color: #ffffff; background-color: #d10000;">第三方名称不能为空</p>';
			return false;
		}
		document.getElementById("ok").style = "visibility: hidden;";
		document.getElementById("part_1").innerHTML = null;
		document.getElementById("part_2").innerHTML = null;
		document.getElementById("part_3").innerHTML = null;
		document.getElementById("part_4").innerHTML = null;
		document.getElementById("part_5").innerHTML = null;
		common_Bank_code = Array();
		arr1 = Array();
		arr2 = Array();
		arr3 = Array();
		arr4 = Array();
		arr5 = Array();
		document.getElementById("part_2").innerHTML = '<table id="t1"></table>';
		document.getElementById("part_3").innerHTML = '<table id="t2"></table>';
		document.getElementById("part_4").innerHTML = '<table id="t3"></table>';
		document.getElementById("nullsubmit").style = "width:294px; height:30px; visibility: visible;";
		document.getElementById("table_2").style = "display: block;";
		pay_name = document.getElementById("pay_name").value;
		document.getElementById("submitSQL").style = "visibility: hidden;";

		common_Bank_name = (document.getElementById("common_Bank_name_textarea")).value.split("\n");
		Bank_name = (document.getElementById("Bank_name_textarea")).value.split("\n");
		Bank_code = document.getElementById("Bank_code_textarea").value.split("\n");

		document.getElementById('ok').disabled = false;
		document.getElementById('nullsubmit').disabled = false;

		if (Bank_name.length != Bank_code.length) {
			document.getElementById("part_0").innerHTML = '<p style="color: #ffffff; background-color: #d10000;">银行名称与银行编号数量必须相同</p>';
			return false;
		}

		common_Bank_name.forEach(function (vi, i) {
			Bank_name.forEach(function (vj, j) {
				if (vj == '') {
					Bank_name.splice(j, 1);
					Bank_code.splice(j, 1);
				}
				if (vi == vj) {
					common_Bank_code[i] = Bank_code[j];
					Bank_code[j] = null;
					document.getElementById("part_1").innerHTML += "INSERT INTO `bank_code`(pay_name,bank_name,bank_code) VALUES ('" + pay_name + "','" + vi + "','" + common_Bank_code[i] + "');" + "\n<br/>";
					// document.getElementById("td2").innerHTML = '输出SQL';
				}
			});
		});

		document.getElementById("td0").innerHTML = '银行名称 (' + Bank_name.length + ')';
		document.getElementById("td1").innerHTML = '银行编号 (' + Bank_code.length + ')';

		common_Bank_name.forEach(function (vi, i) {
			if (common_Bank_code[i] == null) {
				// arr5.push('<tr><td style="text-align: right;"><span id="common_span_' + i + '"><input type="checkbox" id="common_checkbox_' + i + '" style="visibility: hidden; value="' + vi + '">' + vi + '</span></td></tr>');
				arr5.push('<tr><td style="text-align: right;"><span id="common_span_' + i + '"><input type="checkbox" id="common_checkbox_' + i + '" style="visibility: hidden; name="dif_Bank_name" value="' + vi + '">' + vi + '</span></td></tr>');
			}
			document.getElementById("t1").innerHTML = arr5.join('');
		});
		Bank_name.forEach(function (vi, i) {
			if (Bank_code[i] != null) {
				arr3.push('<tr><td><span id="span_' + i + '"><input type="checkbox" id="checkbox_' + i + '" onclick="jumpin(' + i + ')" name="dif_Bank_name" value="' + vi + '">' + vi + '</span></td></tr>');
				arr4.push(i);
				document.getElementById("t2").innerHTML = arr3.join('');
			}
		});
		now_scrollTop = document.body.scrollTop;
		window.scrollTo(0, document.body.scrollHeight);
		tog_scrollTop = document.body.scrollTop;
		window.scrollTo(0, now_scrollTop);
		toTop(tog_scrollTop);
	}

	//加入到选取银行
	function jumpin(i) {
		if (document.getElementById('checkbox_' + i).checked && arr1.length < arr5.length) {
			arr3.splice(arr4.indexOf(i), 1);
			arr4.splice(arr4.indexOf(i), 1);

			arr1.push('<tr><td><span id="span2_' + i + '"><input type="checkbox" style="visibility: hidden;" id="checkbox2_' + i + '" onclick="jumpin_back(' + i + ')" name="dif_Bank_name2" value="' + Bank_name[i] + '">' + Bank_name[i] + '</span></td></tr>');
			arr2.push(i);
			document.getElementById("t2").innerHTML = arr3.join('');
			document.getElementById("t3").innerHTML = arr1.join('');
			if (arr1.length == arr5.length) {
				document.getElementById("ok").style = "width:294px; height:30px; visibility: visible;";
			}
		} else {
			document.getElementById('checkbox_' + i).checked = false;
			document.getElementById("ok").style = "width:294px; height:30px; visibility: visible;";
		}
	}

	//移除从选取银行
	// function jumpin_back(i) {
	// 	if (document.getElementById('checkbox2_' + i).checked) {
	// 		if (i < 10000) {
	// 			arr3.push('<tr><td><span id="span_' + i + '"><input type="checkbox" id="checkbox_' + i + '" onclick="jumpin(' + i + ')" name="dif_Bank_name" value="' + Bank_name[i] + '">' + Bank_name[i] + '</span></td></tr>');
	// 			arr4.push(i);
	// 			common_Bank_code[i] = null;
	// 		}

	// 		arr1.splice(arr2.indexOf(i), 1);
	// 		arr2.splice(arr2.indexOf(i), 1);
	// 		document.getElementById("t2").innerHTML = arr3.join('');
	// 		document.getElementById("t3").innerHTML = arr1.join('');
	// 		document.getElementById("ok").style = "visibility: hidden;";
	// 	}
	// }

	//加入没有银行
	function addnull() {
		if (arr1.length < arr5.length) {
			i = (new Date()).valueOf();
			arr1.push('<tr><td><span id="span2_' + i + '"><input type="checkbox" style="visibility: hidden; id="checkbox2_' + i + '" onclick="jumpin_back(' + i + ')" name="dif_Bank_name2" value="无">无</span></td></tr>');
			arr2.push(i);
			document.getElementById("t3").innerHTML = arr1.join('');
			if (arr1.length == arr5.length) {
				document.getElementById("ok").style = "width:294px; height:30px; visibility: visible;";
			}
		}
	}

	//加入手动比对到SQL区块
	function ok() {
		toTop('0');
		document.getElementById("ok").style = "width:294px; height:30px; visibility:visible; opacity:0.5; cursor:not-allowed;";
		document.getElementById('ok').disabled = true;
		document.getElementById("nullsubmit").style = "width:294px; height:30px; visibility:visible; opacity:0.5; cursor:not-allowed;";
		document.getElementById('nullsubmit').disabled = true;
		j = 0;
		if (arr1.length == 0) {
			document.getElementById("part_5").innerHTML = null;
		}
		common_Bank_name.forEach(function (vi, i) {
			if (common_Bank_code[i] == null) {
				common_Bank_code[i] = Bank_code[arr2[j]];
				j++;
				if (common_Bank_code[i] != undefined) {
					document.getElementById("part_5").innerHTML += "INSERT INTO `bank_code`(pay_name,bank_name,bank_code) VALUES ('" + pay_name + "','" + vi + "','" + common_Bank_code[i] + "');" + "\n<br/>";
				}
			}
		});
		common_Bank_code = null;
		document.getElementById("submitSQL").style = "display:block;width:585; height:30px;position: absolute; bottom: 5px;";
	}

	//送出内容到PHP档生成
	function submit_SQL() {
		document.getElementById("SQL").value = document.getElementById("part_1").innerHTML + document.getElementById("part_5").innerHTML;
		document.getElementById("sand_file_name").value = document.getElementById("file_name").value;
		document.getElementById("sand_pay_name").value = document.getElementById("pay_name").value;
	}

	// 萤光笔 查左标右
	function getselecttext() {
		var t = '';
		if (window.getSelection) { t = window.getSelection(); }
		else if (document.getSelection) { t = document.getSelection(); }
		else if (window.document.selection) { t = window.document.selection.createRange().text; }
		// if (t != '') alert(t);

		Bank_name.forEach(function (vi, i) {
			if (document.getElementById("checkbox_" + i)) {
				// alert(i);
				// if (typeof myObj == "undefined") {
				if ((document.getElementById("checkbox_" + i).value.search(t) != -1) && (t != '')) {
					document.getElementById("span_" + i).style = "background-color:hsla(50, 100%, 50%,0.4)";
				} else {
					document.getElementById("span_" + i).style = "background-color:hsla(0, 0%, 0%,0)";
				}
				// }
			}
		});
	}

	// 萤光笔 查右标左
	function getselecttext_2() {
		var u = '';
		if (window.getSelection) { u = window.getSelection(); }
		else if (document.getSelection) { u = document.getSelection(); }
		else if (window.document.selection) { u = window.document.selection.createRange().text; }
		// if (u != '') alert(u);

		common_Bank_name.forEach(function (vi, i) {
			if (document.getElementById("common_checkbox_" + i)) {
				// alert(i);
				// if (typeof myObj == "undefined") {
				if ((document.getElementById("common_checkbox_" + i).value.search(u) != -1) && (u != '')) {
					document.getElementById("common_span_" + i).style = "background-color:hsla(50, 100%, 50%,0.4)";
				} else {
					document.getElementById("common_span_" + i).style = "background-color:hsla(0, 0%, 0%,0)";
				}
				// }
			}
		});
	}
	//滚动到目标
	function toTop(tog_h) {
		gotoTop = function () {
			now_h = document.body.scrollTop;
			if (Math.abs(now_h - tog_h) * 0.05 > 0) {
				window.scrollTo(0, now_h + (tog_h - now_h) * 0.05 + Math.sign(tog_h - now_h));
			} else {
				window.scrollTo(0, tog_h);
				clearInterval(timer);
				timer = null;
			}
		}
		timer = setInterval(gotoTop, 10);
	}

	// function getInfo() {
	// 	var s = "";
	// 	s += " 网页可见区域宽：" + document.body.clientWidth;
	// 	s += " 网页可见区域高：" + document.body.clientHeight;
	// 	s += " 网页可见区域宽：" + document.body.offsetWidth + " (包括边线和滚动条的宽)";
	// 	s += " 网页可见区域高：" + document.body.offsetHeight + " (包括边线的宽)";
	// 	s += " 网页正文全文宽：" + document.body.scrollWidth;
	// 	s += " 网页正文全文高：" + document.body.scrollHeight;
	// 	s += " 网页被卷去的高(ff)：" + document.body.scrollTop;
	// 	s += " 网页被卷去的高(ie)：" + document.documentElement.scrollTop;
	// 	s += " 网页被卷去的左：" + document.body.scrollLeft;
	// 	s += " 网页正文部分上：" + window.screenTop;
	// 	s += " 网页正文部分左：" + window.screenLeft;
	// 	s += " 屏幕分辨率的高：" + window.screen.height;
	// 	s += " 屏幕分辨率的宽：" + window.screen.width;
	// 	s += " 屏幕可用工作区高度：" + window.screen.availHeight;
	// 	s += " 屏幕可用工作区宽度：" + window.screen.availWidth;
	// 	s += " 你的屏幕设置是 " + window.screen.colorDepth + " 位彩色";
	// 	s += " 你的屏幕设置 " + window.screen.deviceXDPI + " 像素/英寸";
	// 	alert(s);
	// }
</script>

</html>