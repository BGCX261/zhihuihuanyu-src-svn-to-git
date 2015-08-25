<?php
/*
 * Copyright (c) 2013, zhihuihuanyu.
 * All rights reserved.
 * 
 * file	name		send_sms.php?mobile=13800138000&msg=urlencode('test')&lcode=10690090&gsendid=123&pno=98105
 *	不支持长短信。msg超过70个字，将被截断。
 *	pno = project_no, from.
 *	gsendid = 群发id。对web页面群发时，必填。若是合作方调用我们的接口进行群发，则无此参数。
 * 	isinter=1表示内部使用，将略过部分检查
 * 	lcode = 高级用户才可以设置此参数。默认下，客户不需要填写此参数。系统将按照map_project_channel中配置的lcode进行下发。
 * 			若用户填写了此参数，则系统将会与map_project_channel配置的lcode比对，只有匹配前N个字符成功的lcode才可下发。
 * 
 *	return_val:
 * 	成功：router.rpcid >0
 * 	
 *	失败：
 * 		-1: ip非法
 * 		-2: 用户名密码非法
 * 		-3: 手机号非法
 * 		-4: 黑名单
 * 		-5: 该项目不允许下发这类运营商的手机号码。
 * 		-6: 含有关键字
 * 		-7: lcode错误。该项目不允许以这个lcode下发。应该与配置好的lcode一致。一般来说，用户可不输入lcode参数。
 * 		-8：该项目已暂停/删除，不允许再使用该pno进行下发。
 * 		-9: 该群发ID状态非法，不允许使用该群发ID进行下发。如未审核、删除状态。
 * 		
 * 		-200 -- -400: 内部错误
 * 		-200: 访问内部DB错误
 * 		-201：调用smsd错误
 * 		-202：读取conf文件错误，conf未找到或json_decode失败
 * 
 * 
 * description		接收合作方的下发请求。含基本权限、参数检查、黑名单、关键字。
 * 	处理流程：
 * 		检查该号码是否11位数字，是合法cm、cu、ct。isinter=1时不检查本项。
 * 		号码是否为黑名单- 将来再做。
 * 		检查关键字 - 将来再做。isinter=1时不检查本项。
 * 		读取project.conf文件，json_decode.根据pno，也就是project_no值，查看配置文件中的该项目能否下发。
 * 		检查配置文件中的ip配置. isinter=1时不检查本项。
 *		用户名+密码的验证放在将来再做。本接口的用户名密码与web页面的不能一致。以防密码窃取。isinter=1时不检查本项。
 * 		查找该项目可使用的通道。按照号码的运营商类型，选择适当的通道。

 *		查询db，获取该号码的省市，检查是否在通道的限制下发的地区中。
 * 		对于查找出的通道，调用smsd下发接口。http://221.179.190.34:8612/mo?/101001/15801564398/10690090/test/123456linkid

 * 		调用之前，先生成一个rpcid，用于返回给客户。
 * 		然后开始调用smsd接口，并把linkid=project_no,gsend_id,rpcid,ips_type[cm|cu|ct],province_id,city_id,msgfee_in_proj,msglen
 * 
 * date			author		changes
 * 2013-05-28	gumeng		create.
 */

































?>