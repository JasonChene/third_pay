<html>

<head>
	<title>Bank_coder_1.0</title>
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
	</style>
</head>

<body>
	<div id="main">
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
				<td valign="top" style="width:590x;">
					<div style="width:590px;"></div>
					<font size="1">
						<p>
							<div id="part_1">
								<!-- <img src="tst.png"/> -->
								<!-- 1)填入第三方名称跟资料夹名称<br/>
2)复制贴上WORD上银行列表的名称跟编号<br/>
3)按下自动产生,银行名称一样的会自动填入<br/>
4)下面的银行名称不同,要人工判断,勾选checkbox加到左方,可反白寻找有相同文字的名称<br/>
5)按下加入,检查是否正确
6)按下送出,产生SQL -->
							</div>
						</p>
						<p>
							<div id="part_5">
								<!-- <br/> * 资料夹名称为空或不存在时,档案会生成再此路径-bker资料夹中
							<br/> * 银行名称和银行编号以换行切割不同银行,不能有空格
							<br/> * 点选 "产生 ↓" 下方手动选取相同银行,反白寻找有相同文字的银行,勾选checkbox加入银行或点选 "空值 ←" 跳过
							<br/> * 点选 "加入 ↑" 输出SQL区块为最终结果,确认无误后点选 "送出 →" 后即生成至ftp资料夹中 -->
							</div>
						</p>
					</font>
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

						<form action="./Bank_coder_log.php" method="post">
							<textarea type="text" style="display: none;" id="SQL" name="SQL" value="" /></textarea>
							<textarea type="text" style="display: none;" id="sand_file_name" name="file_name" value="" /></textarea>
							<input class="submit" type="submit" id="submitSQL" onclick="submit_SQL()" value="送出 →" style="width:294px; height:30px;display: none; position: absolute; bottom: 10px;"
							/>
						</form>
					</div>
				</td>
			</tr>
		</table>
		<br/>
		<p>
			<div>
				<p>说明</p>
				<p> * 资料夹名称为空或不存在时，档案会生成再此路径-bker资料夹中，若档案已存在则会直接覆盖上去</p>
				<p> * 银行名称和银行编号以换行切割不同银行，不能有空格</p>
				<p> * 点选 " 产生 ↓ " 下方手动选取相同银行，反白寻找有相同文字的银行，勾选checkbox加入银行或点选 " 空值 ← " 跳过</p>
				<p> * 点选 " 加入 ↑ " 输出SQL区块为最终结果，确认无误后点选 " 送出 → " 后即生成至ftp资料夹中</p>
				<p> * 目前不会直接汇入到db，还是要手动添加
				</p>
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
	function split() {
		if (document.getElementById("pay_name").value == '') {
			alert('第三方名称不能为空');
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
	}

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

	function ok() {
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
		document.getElementById("submitSQL").style = "display:block;width:294px; height:30px;position: absolute; bottom: 10px;";
	}

	//送出内容到PHP
	function submit_SQL() {
		document.getElementById("SQL").value = document.getElementById("part_1").innerHTML + document.getElementById("part_5").innerHTML;
		document.getElementById("sand_file_name").value = document.getElementById("file_name").value;
	}

	// 萤光笔 左
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

	// 萤光笔 右
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

</script>

</html>