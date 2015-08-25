<?php
/***************************************************************
 ** @author diwei  20110301
 ** @class description 通道控制器 实现通道列表、增加、修改、删除
 ***************************************************************/

	class Projects extends Base_Controller {

		private $_pj_type;
		private $_pj_status;
		private $_longs = array('10657000' => '10657000', '12398765' => '12398765', '106900292929' => '106900292929');
		
		function __construct()
		{
			parent::__construct();
			$this->load->model('project_model', 'pj');

			$this->_pj_type = $this->config->item('CHANNEL_TYPE');
		}

		public function index()
		{
			$this->project_list();
		}

		public function project_list()
		{
			$data = array();

			if ($this->access->is_admin())
			{
				$data['p_list'] = $this->pj->get_info();
			}
			else
			{
				$data['p_list'] = $this->pj->get_info(NULL, $this->Global_user_id);
			}
			
			$data['pj_status'] = $this->config->item('PROJECT_STATUS');
			$data['pj_bill_type'] = $this->config->item('BILL_TYPE');
			$data['isp_type'] = $this->config->item('ISP_TYPE');
			
			$this->load->view('project_list_view', $data);
		}

		public function project_app_change($p_id = '')
		{
			
			$userid = $this->Global_user_id;
			if (empty($userid))
			{
				echo '没有用户信息<br><a href="'.site_url('projects/project_app_change/'.$p_id).'">返回</a>';
				exit;
			}
			
			$p_id = intval($p_id);
			if (!empty($p_id))
			{
				$project_app_info = $this->pj->get_info($p_id);
				if ($project_app_info['status'] != 0 && $project_app_info['status'] != 1)
				{
					exit;
				}
			}

			if ($flag = $this->input->post('isPost') == 'TRUE')
			{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($pj_name = $this->input->post('pj_name'))
				{
					$change_info['pj_name'] = $pj_name;
				}
				else
				{
					echo '必须填写项目名称<br><a href="'.site_url('projects/project_app_change/'.$p_id).'">返回</a>';
					exit;
				}
				
				if ($industry_type = $this->input->post('industry_type'))
				{
					$change_info['industry_type'] = $industry_type;
				}
				if ($emergent_user_email = $this->input->post('emergent_user_email'))
				{
					$change_info['emergent_user_email'] = $emergent_user_email;
				}				
				if ($emergent_user_name = $this->input->post('emergent_user_name'))
				{
					$change_info['emergent_user_name'] = $emergent_user_name;
				}

				if ($emergent_user_phone = $this->input->post('emergent_user_phone'))
				{
					$change_info['emergent_user_phone'] = $emergent_user_phone;
				}

				$validdate_s_y = $this->input->post('validdate_s_y');
				$validdate_s_m = $this->input->post('validdate_s_m');
				$validdate_s_d = $this->input->post('validdate_s_d');
				$validdate_e_y = $this->input->post('validdate_e_y');
				$validdate_e_m = $this->input->post('validdate_e_m');
				$validdate_e_d = $this->input->post('validdate_e_d');

				$change_info['pj_start'] = sprintf('%d%02d%02d', $validdate_s_y, $validdate_s_m, $validdate_s_d);
				$change_info['pj_end'] = sprintf('%d%02d%02d', $validdate_e_y, $validdate_e_m, $validdate_e_d);

				if ($avg_cnt = $this->input->post('avg_cnt'))
				{
					$change_info['avg_cnt'] = $avg_cnt;
				}
				if ($pj_desc = $this->input->post('pj_desc'))
				{
					$change_info['pj_desc'] = htmlspecialchars($pj_desc);
				}
				if ($pj_restrict = $this->input->post('pj_restrict'))
				{
					$change_info['pj_restrict'] = htmlspecialchars($pj_restrict);
				}
				if ($pj_memo = $this->input->post('pj_memo'))
				{
					$change_info['pj_memo'] = htmlspecialchars($pj_memo);
				}

				$change_info['apply_user_id'] = $userid;
				$this->load->model('users_model', 'users');
				$tmp = $this->users->get_user_info($userid);
				$change_info['apply_user_name']  = $tmp['app_name'];
				$change_info['apply_company'] = $tmp['company'];
				$change_info['apply_user_phone'] = $tmp['app_phone'];
				$change_info['apply_date'] = date('Y-m-d H:i:s');
				
				if (empty($p_id))
				{
					if ($p_id = $this->pj->add($change_info))
					{
                        //echo '新增项目申请成功';
						redirect(site_url('projects/project_list'));
					}
					else
					{
						echo '新增项目申请失败';
						exit;
					}
				}
				else
				{
					$change_info['status'] = 0;
					if ($this->pj->update($change_info, $p_id))
					{
						//echo '项目申请更新成功';
						redirect(site_url('projects/project_list'));
					}
					else
					{
						echo '项目申请更新失败';
						exit;
					}
				}
			}
			else
			{
				if (empty($p_id))
				{
					$data = array(
								'pj_apv_info' => array(),
								'action' => array(
									'name' => '提交本项目',
									'value' => site_url('projects/project_app_change/'.$p_id)
								)
							);
				}
				else
				{
					$data = array(
								'pj_apv_info' => $project_app_info,
								'action' => array(
									'name' => '提交本项目',
									'value' => site_url('projects/project_app_change/'.$p_id)
								)
							);
				}

				$data['industry_type'] = $this->config->item('INDUSTRY_TYPE');
				$this->load->view('project_app_info_view', $data);
			}
		}

		// project 审核。
		public function project_apv_change($p_id = '')
		{
			$p_id = intval($p_id);

			if ($flag = $this->input->post('isPost') == 'TRUE')
			{
				if ($project_no = $this->input->post('project_no'))
				{
					$change_info['project_no'] = $project_no;
				}else {
					echo '必须填写项目from <br><a href="'.site_url('projects/project_apv_change/'.$p_id).'">返回</a>';
					exit;
				}
				
				$projects = $this->pj->get_info();
				foreach($projects as $one_pj) {
					if($one_pj['project_no'] == $project_no  ) {
						echo '已有 “'.$one_pj['pj_name'].'” 项目使用了该from值 '.$project_no.'，请重写。<br><a href="'.site_url('projects/project_apv_change/'.$p_id).'">返回</a>';
						exit;
					}
				}

				if ($pj_fee = $this->input->post('pj_fee')) {
					$change_info['pj_fee'] = $pj_fee;
				}else {
					echo '必须填写此项目每条短信的收费金额，精确到厘。<br><a href="'.site_url('projects/project_apv_change/'.$p_id).'">返回</a>';
					exit;
				}
				
				if ($isp_type = $this->input->post('isp_type'))
				{
					$change_info['isp_type'] = $isp_type;
				}
				
				// 检查用户是否设置了相应运营商的通道
				$channel_info = array();
				$this->load->model('channel_model', 'channels');
				if(false !== strpos($isp_type, 'CM') ) {
					$cm_channel = $this->input->post('cm_channel');
					if ($cm_channel != 0 ) {
						if($cm_longcode = $this->input->post('cm_longcode') ) {
							$tmp = $this->channels->get_info($cm_channel);
							$channel_info[] = array('pj_id'=>$p_id, 'project_no'=>$project_no,'channel_id'=>$cm_channel,'channel_name'=>$tmp['channel_name'],
												'gateway'=>$tmp['gateway'], 'isp_type'=>'CM', 'longcode'=>$cm_longcode);
						}else {
							echo '您打算让客户使用移动通道，但并未设置该通道的长号码。请重新设置。<br><a href="'.site_url('projects/project_apv_change/'.$p_id).'">返回</a>';
							exit;
						}
					}else {
						echo '您打算让客户使用移动通道，但并未选择使用哪个移动通道。请重新设置。<br><a href="'.site_url('projects/project_apv_change/'.$p_id).'">返回</a>';
						exit;
					}
				}

				if(false !== strpos($isp_type, 'CU') ) {
					$cu_channel = $this->input->post('cu_channel');
					if ($cu_channel != 0 ) {
						if($cu_longcode = $this->input->post('cu_longcode') ) {
							$tmp = $this->channels->get_info($cu_channel);
							$channel_info[] = array('pj_id'=>$p_id, 'project_no'=>$project_no,'channel_id'=>$cu_channel, 'channel_name'=>$tmp['channel_name'],
												'gateway'=>$tmp['gateway'], 'isp_type'=>'CU', 'longcode'=>$cu_longcode);
						}else {
							echo '您打算让客户使用联通通道，但并未设置该通道的长号码。请重新设置。<br><a href="'.site_url('projects/project_apv_change/'.$p_id).'">返回</a>';
							exit;
						}
					}else {
						echo '您打算让客户使用联通通道，但并未选择使用哪个联通通道。请重新设置。<br><a href="'.site_url('projects/project_apv_change/'.$p_id).'">返回</a>';
						exit;
					}
				}
							
				if(false !== strpos($isp_type, 'CT') ) {
					$ct_channel = $this->input->post('ct_channel');
					if ($ct_channel != 0 ) {
						if($ct_longcode = $this->input->post('ct_longcode') ) {
							$tmp = $this->channels->get_info($ct_channel);
							$channel_info[] = array('pj_id'=>$p_id, 'project_no'=>$project_no,'channel_id'=>$ct_channel, 'channel_name'=>$tmp['channel_name'],
												'gateway'=>$tmp['gateway'], 'isp_type'=>'CT', 'longcode'=>$ct_longcode);
						}else {
							echo '您打算让客户使用电信通道，但并未设置该通道的长号码。请重新设置。<br><a href="'.site_url('projects/project_apv_change/'.$p_id).'">返回</a>';
							exit;
						}
					}else {
						echo '您打算让客户使用电信通道，但并未选择使用哪个电信通道。请重新设置。<br><a href="'.site_url('projects/project_apv_change/'.$p_id).'">返回</a>';
						exit;
					}
				}
				
				if ($pj_bill_type = $this->input->post('pj_bill_type'))
				{
					$change_info['pj_bill_type'] = $pj_bill_type;
				}
				
				if ($industry_type = $this->input->post('industry_type'))
				{
					$change_info['industry_type'] = $industry_type;
				}
								
				if ($pj_restrict = $this->input->post('pj_restrict'))
				{
					$change_info['pj_restrict'] = htmlspecialchars($pj_restrict);
				}
				else
				{
					$change_info['pj_restrict'] = "122.0.67.155;127.0.0.1";
				}

				if ($pj_memo = $this->input->post('pj_memo'))
				{
					$change_info['pj_memo'] = htmlspecialchars($pj_memo);
				}
				
				$change_info['audit_user_id'] = $this->Global_user_id;
				$change_info['audit_date'] = date("Y-m-d H:i:s");
				$this->load->model('users_model', 'users');
				$tmp = $this->users->get_user_info($this->Global_user_id);
				$change_info['audit_user_name'] = $tmp['app_name'];
				
				$change_info['status'] = 2;
				
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

				if ($flag = $this->pj->update($change_info, $p_id))
				{
					//echo '项目更新成功';
					foreach($channel_info as $one_channel) {
						$this->pj->add_pj_channels($one_channel);
					}			
					
					$userdata = $this->users->get_user_info($this->Global_user_id);
					$username = $userdata['username'];
					$this->writeLog('OP', "$username,$project_no,update ok");	
					redirect(site_url('projects/project_list'));
				}
				else
				{
					if ($flag === 0)
					{
						echo '项目编号已经被分配，请重新分配';
						exit;
					}
					else
					{
						echo '项目更新失败';
						exit;
					}
				}
			}
			else
			{
				$project_apv_info = $this->pj->get_info($p_id);

				$data = array(
								'pj_apv_info' => $project_apv_info,
								'action' => array(
									'title' => '项目审核',
									'name' => '通过审核',
									'value' => site_url('projects/project_apv_change/'.$p_id)
								)
							);

				$this->load->model('channel_model', 'channels');
				$data['channels_group_by_ips'] = $this->channels->get_channels_group_by_ips();

				$data['channels_in_proj'] = $this->pj->get_channels_in_proj($p_id);
				
				$data['cm_longcode'] = $this->get_longcode('CM', $data['channels_in_proj'], $data['channels_group_by_ips']);
				$data['cu_longcode'] = $this->get_longcode('CU', $data['channels_in_proj'], $data['channels_group_by_ips']);
				$data['ct_longcode'] = $this->get_longcode('CT', $data['channels_in_proj'], $data['channels_group_by_ips']);
				
				$data['pj_bill_type'] = $this->config->item('BILL_TYPE');
				$data['mo_mt'] = $this->config->item('MO_MT');
				$data['isp_type'] = $this->config->item('ISP_TYPE');
				$data['industry_type'] = $this->config->item('INDUSTRY_TYPE');
				$this->load->view('project_apv_info_view', $data);
			}
		}
		
		private function get_longcode($isp_type, $channels_in_proj, $channels_group_by_ips)
		{
			$setting = false;
			foreach($channels_group_by_ips[$isp_type] as $k => $v) {
				foreach($channels_in_proj as $one_channel_in_proj) {
					if ($one_channel_in_proj['channel_id'] == $v['id'] 
					&& $one_channel_in_proj['isp_type'] == $isp_type) {
						return empty($one_channel_in_proj['longcode'])?'':$one_channel_in_proj['longcode'];
					}
				}
			}
			
       		return '';
		}
		
		public function project_detail($p_id = '')
		{
			$p_id = intval($p_id);
			$project_apv_info = $this->pj->get_info($p_id);
			$data = array(
							'pj_apv_info' => $project_apv_info,
							'action' => array(
								'title' => '项目审核后的详情',
								'name' => '返回',
								'value' => site_url('projects/project_list')
							)
						);

			$this->load->model('channel_model', 'channels');
			$data['channels_group_by_ips'] = $this->channels->get_channels_group_by_ips();
		
			$data['channels_in_proj'] = $this->pj->get_channels_in_proj($p_id);
			
			$data['cm_longcode'] = $this->get_longcode('CM', $data['channels_in_proj'], $data['channels_group_by_ips']);
			$data['cu_longcode'] = $this->get_longcode('CU', $data['channels_in_proj'], $data['channels_group_by_ips']);
			$data['ct_longcode'] = $this->get_longcode('CT', $data['channels_in_proj'], $data['channels_group_by_ips']);
			
			$data['pj_bill_type'] = $this->config->item('BILL_TYPE');
			$data['mo_mt'] = $this->config->item('MO_MT');
			$data['isp_type'] = $this->config->item('ISP_TYPE');
			$data['industry_type'] = $this->config->item('INDUSTRY_TYPE');
						
			$this->load->view('project_apv_info_readonly_view', $data);
		}
		
		public function project_update($status, $id)
		{
			if (!empty($id) && !empty($status))
			{
				$info['status'] = $status;
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($this->pj->update($info, $id))
				{
					//echo '项目状态更新成功';
					$this->load->model('users_model', 'user');
					$userdata = $this->user->get_user_info($this->Global_user_id);
					$username = $userdata['username'];
					$this->writeLog('OP', "$username,$id,$status,update status ok");				
					redirect(site_url('projects/project_list'));
				}
				else
				{
					echo '项目状态更新失败';
				}
			}
		}
		public function project_delete($id)
		{
			if (!empty($id) )
			{
		
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($this->pj->del( $id))
				{
					//echo '项目状态更新成功';
					$this->load->model('users_model', 'user');
					$userdata = $this->user->get_user_info($this->Global_user_id);
					$username = $userdata['username'];
					$this->writeLog('OP', "$username,$id,$status,delete ok");
					
					redirect(site_url('projects/project_list'));
				}
				else
				{
					echo '项目删除失败';
				}
			}
		}

		public function project_search()
		{
			$info = array();
			if ($content = $this->input->post('content'))
			{
				$info['content'] = $content;
			}

			if (($pj_status = $this->input->post('pj_status')) != '')
			{
				$info['pj_status'] = $pj_status;
			}

			if ($this->access->is_admin())
			{
				$data['p_list'] = $this->pj->get_info_from($info);
			}
			else
			{
				$data['p_list'] = $this->pj->get_info_from($info, $this->Global_user_id);
			}
			$data['pj_status'] = $this->config->item('PROJECT_STATUS');
			$data['pj_bill_type'] = $this->config->item('BILL_TYPE');
			$data['isp_type'] = $this->config->item('ISP_TYPE');
			
			$this->load->view('project_list_view', $data);
		}

		public function project_task()
		{

		}
		
		public function project_mail($id)
		{
			$this->load->library('email');
			
			
			$data = $this->pj->get_info($id);
			
			$pj_name = 	$data['pj_name'];
			$project_no  = 	$data['project_no'];
			$longcode  = 	$data['longcode'];
			$limit_D  = 	$data['limit_D'];
			$limit_M  = 	$data['limit_M'];
			$limit_pho_D  = 	$data['limit_pho_D'];
			$app_email  = $data['app_email'];
			
			
			$this->email->from('qxtgateway@staff.sina.com.cn', 'gateway');
			$this->email->to($app_email); 
			$this->email->cc('jianqiang1@staff.sina.com.cn'); 

			
			
			
			$this->email->subject("企信通通道开通FROM=$project_no");
			$this->email->message("
接口地址：
外网地址: http://qxt.mobile.sina.cn/cgi-bin/qxt/sendSMS.cgi
内网地址: http://qxt.intra.mobile.sina.cn/cgi-bin/qxt/sendSMS.cgi
参数:
	{unwrap}msg[必填]:短信内容[GB2312URL编码]，不超过200字，超过XX个字按多条计费(英文字符汉字一字){/unwrap}
	{unwrap}usernumber[必填]:手机号码：可以填多个，用英文逗号隔开，最大不要超过50个群发推手机{/unwrap}
	count[必填]：手机号码个数： usernumber含有的手机号码个数
	from[必填]:         渠道值 ： $project_no
	longnum[必填]：  长号码 ：   $longcode
返回值: 
大于0  提交成功； 
-99 参数错误；
-102  from值错误；
-103  ip没有权限；
-104  longnum错误
-105  关键字禁止；
		
{unwrap}必须先提供IP，开下权限.公司内部建议调用内网域名{/unwrap}

限制措施(数值为0标识不做限制):
每天最多下发量:$limit_D;
月最多下发量: $limit_M;
每天单个号码最多下发量:$limit_pho_D;
	
			"); 

			$this->email->send();

			echo $this->email->print_debugger();
			echo $pj_name;
		}
		
		public function delete()
		{
			$channel_ids = $this->input->post('del_list');

			if (!empty($channel_ids))
			{
				if ($this->users->del_user($channel_ids))
				{
					redirect(site_url('projects/project_list'));
				}
			}

			echo 'Delete channel Error';
		}
		
		function writeLog($pre, $logInfo)
		{
			$logInfo = str_replace("\n", " ", $logInfo);
			$logInfo = str_replace("\r", " ", $logInfo);
			
			$dateNow = date('Ymd');
			$timeNow = date('H:i:s');
			$log = $this->config->item('PLAT_LOG');
			$fp = @fopen($log.$pre.$dateNow.".log", "a+");
			if ($fp)
			{
				fwrite($fp, "[$timeNow] $logInfo\n");
				fclose($fp);
			}
		}
	}