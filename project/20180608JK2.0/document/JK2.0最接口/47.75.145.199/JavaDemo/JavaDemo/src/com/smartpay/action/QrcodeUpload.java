package com.smartpay.action;

import java.io.File;

import com.aliyun.oss.OSSClient;

public class QrcodeUpload {
	
	
	public static void main(String[] args) {
		
	    // endpoint以杭州为例，其它region请按实际情况填写
	    String endpoint = "oss-cn-shanghai.aliyuncs.com";
	    // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号
	    String accessKeyId = "LTAITeh9fCycyont";
	    String accessKeySecret = "kz5yn5VE1xyUZEJzWX3gWEWy6oeTmS";
	    // 创建OSSClient实例
	    OSSClient ossClient = new OSSClient(endpoint, accessKeyId, accessKeySecret);
	    // 上传文件
	    ossClient.putObject("smartpayqrcode", "abc", new File("D:\\今天任务.txt"));
	    // 关闭client
	    ossClient.shutdown();
		
		
	}
	

}
