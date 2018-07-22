<?php

/*
 * @Description API֧��B2C����֧���ӿڷ���
 */

include 'payCommon.php';

#	ֻ��֧���ɹ�ʱAPI֧���Ż�֪ͨ�̻�.
##֧���ɹ��ص������Σ�����֪ͨ������֧�����������е�p8_Url�ϣ��������ض���;���������Ե�ͨѶ.

#	�������ز���.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$sign);

#	�жϷ���ǩ���Ƿ���ȷ��True/False��
$bRet = CheckSign($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$sign);
#	���ϴ����ͱ�������Ҫ�޸�.

#	У������ȷ.
if($bRet){
	if($r1_Code=="1"){

	#	��Ҫ�ȽϷ��صĽ������̼����ݿ��ж����Ľ����Ƿ����ȣ�ֻ�����ȵ������²���Ϊ�ǽ��׳ɹ�.
	#	������Ҫ�Է��صĴ��������������ƣ����м�¼�������Դ������ڽ��յ�֧������֪ͨ�����ж��Ƿ����й�ҵ���߼���������Ҫ�ظ�����ҵ���߼���������ֹ��ͬһ�������ظ���������������.

		if($r9_BType=="1"){
			echo "SUCCESS";
			echo  "<br />����֧��ҳ�淵��";
		}elseif($r9_BType=="2"){
			#������ҪӦ��������������д��,��success��ͷ,��Сд������.
			echo "success";
			echo "<br />���׳ɹ�";
			echo  "<br />����֧������������";
		}
	}

}else{
	echo "������Ϣ���۸�";
}

?>
<html>
<head>
<title>Return from API Page</title>
</head>
<body>
</body>
</html>
