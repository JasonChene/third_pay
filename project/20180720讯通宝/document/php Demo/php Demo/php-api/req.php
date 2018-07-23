<?php

/*
 * @Description API֧����Ʒͨ��֧���ӿڷ���
 */

include 'payCommon.php';

#	�̼������û�������Ʒ��֧����Ϣ.
##API֧��ƽ̨ͳһʹ��GBK/GB2312���뷽ʽ,�������õ����ģ���ע��ת��

#	�̻�������,ѡ��.
##����Ϊ""���ύ�Ķ����ű����������˻�������Ψһ;Ϊ""ʱ��API֧�����Զ������������̻�������.
$p2_Order					= $_REQUEST['p2_Order'];

#	֧������,����.
##��λ:Ԫ����ȷ����.
$p3_Amt						= $_REQUEST['p3_Amt'];

#	���ױ���,�̶�ֵ"CNY".
$p4_Cur						= "CNY";

#	��Ʒ����
##����֧��ʱ��ʾ��API֧�����������Ķ�����Ʒ��Ϣ.
$p5_Pid						= $_REQUEST['p5_Pid'];

#	��Ʒ����
$p6_Pcat					= $_REQUEST['p6_Pcat'];

#	��Ʒ����
$p7_Pdesc					= $_REQUEST['p7_Pdesc'];

#	�̻�����֧���ɹ����ݵĵ�ַ,֧���ɹ���API֧�������õ�ַ�������γɹ�֪ͨ.
$p8_Url						= $_REQUEST['p8_Url'];

#	�̻���չ��Ϣ
##�̻�����������д1K ���ַ���,֧���ɹ�ʱ��ԭ������.
$pa_MP						= $_REQUEST['pa_MP'];

#	֧��ͨ������
##Ĭ��Ϊ""����API֧������.��������ʾAPI֧����ҳ�棬ֱ����ת�������С�������֧��������һ��ͨ��֧��ҳ�棬���ֶο����ո�¼:�����б����ò���ֵ.
$pd_FrpId					= $_REQUEST['pd_FrpId'];

#	Ӧ������
##Ĭ��Ϊ"1": ��ҪӦ������;
$pr_NeedResponse	= "1";

#����ǩ����������ǩ����
$sign = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);

     //var_dump($hmac);
     //die();
?>
<html>
<head>
<title>To API Page</title>
</head>
<body onLoad="document.API.submit();">
<form name='API' action='<?php echo $reqURL_onLine; ?>' method='post'>
<input type='hidden' name='p0_Cmd'					value='<?php echo $p0_Cmd; ?>'>
<input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
<input type='hidden' name='p2_Order'				value='<?php echo $p2_Order; ?>'>
<input type='hidden' name='p3_Amt'					value='<?php echo $p3_Amt; ?>'>
<input type='hidden' name='p4_Cur'					value='<?php echo $p4_Cur; ?>'>
<input type='hidden' name='p5_Pid'					value='<?php echo $p5_Pid; ?>'>
<input type='hidden' name='p6_Pcat'					value='<?php echo $p6_Pcat; ?>'>
<input type='hidden' name='p7_Pdesc'				value='<?php echo $p7_Pdesc; ?>'>
<input type='hidden' name='p8_Url'					value='<?php echo $p8_Url; ?>'>
<input type='hidden' name='p9_SAF'					value='<?php echo $p9_SAF; ?>'>
<input type='hidden' name='pa_MP'						value='<?php echo $pa_MP; ?>'>
<input type='hidden' name='pd_FrpId'				value='<?php echo $pd_FrpId; ?>'>
<input type='hidden' name='pr_NeedResponse'	value='<?php echo $pr_NeedResponse; ?>'>
<input type='hidden' name='sign'						value='<?php echo $sign; ?>'>
</form>
</body>
</html>
