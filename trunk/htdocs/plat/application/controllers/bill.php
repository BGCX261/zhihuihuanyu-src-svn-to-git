<?php
	class Bill extends Base_Controller {

		function __construct()
		{
			parent::__construct();
			$this->load->model('bill_model', 'bill');
		}

		public function index()
		{
		}

		public function bill_list()
		{
			if ($this->access->is_admin())
			{
				$data['list'] = $this->bill->get_info();
			}
			else
			{
				$data['list'] = $this->bill->get_info(NULL, $this->Global_user_id);
			}

			$data['pj_bill_type'] = $this->config->item('BILL_TYPE');
			$this->load->view('bill_list_view', $data);
		}
		
		
		public function delete( $id,$mobilefile=NULL)
		{
			if (!empty($id) )
			{
				
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($this->gs->del( $id))
				{
					if (!empty($mobilefile))
					{
						$path = $this->config->item('groupsend_path');
						exec("rm $path$mobilefile");
					}
					//echo '项目状态更新成功';
					$this->load->model('users_model', 'user');
					$userdata = $this->user->get_user_info($this->Global_user_id);
					$username = $userdata['username'];
					$this->writeLog('OP', "$username,$id,delete group ok");
					
					redirect(site_url('group_send/group_send_list'));
				}
				else
				{
					echo '操作失败';
				}
			}
		}

		public function group_send_test()
		{
			if ($this->input->post('isPost')  == 'TRUE') {
  				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

  				if (!($sms_msg = $this->input->post('sms_msg')) ) {
					echo "下发内容为空，请重试";
					exit;
				}
				
  				if (!($dest_mobile = $this->input->post('dest_mobile')) ) {
					echo "请输入下发给哪个手机号码";
					exit;
				}
				
				$mobile_ok = false;
				if(is_numeric($dest_mobile) && strlen($dest_mobile) == 11) {
					$cm = $this->config->item('CM');
					$cu = $this->config->item('CU');
					$ct = $this->config->item('CT');
					$prefix = substr($dest_mobile, 0, 3);
					if(in_array($prefix, $cm) ||in_array($prefix, $cu) ||in_array($prefix, $ct) ) {
						$mobile_ok = true;
					}
				}
				if(!$mobile_ok) {
					echo "手机号码非法，请重试";
					exit;
				}
				
				if ( !($project_no = $this->input->post('project_no')) ) {
					echo "请选择本次测试属于哪个项目";
					exit;
				}
				
				if ($sms_msg = $this->input->post('sms_msg'))
				{
					$change_info['sms_msg'] = htmlspecialchars($sms_msg);
				}
				else
				{
					echo "内容为空，请重试";
					exit;
				}

				// send msg, then show result.
				
			}
			else
			{
				$this->load->model('project_model', 'pj');
				if ($this->access->is_admin()) {
					$total_pj = $this->pj->get_info(null, null, true);
				}else {
					$total_pj = $this->pj->get_info(null, $this->Global_user_id, true);
				}
				
				$data = array(
							'total_pj' =>$total_pj,
							'action' => array(
								'title' => '短信测试',
								'name' => '下发短信',
								'value' => site_url('group_send/group_send_test')
							)
						);
					
				$this->load->view('group_send_test_view' ,$data);
			}
			
		
		}
		
		public function group_send_change($gs_id = NULL)
		{
			if ($this->input->post('isPost')  == 'TRUE') 
			{
  				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

  				if ($gsend_name = $this->input->post('gsend_name'))
				{
					$change_info['gsend_name'] = htmlspecialchars($gsend_name);
				}
				else
				{
					echo "群发名称为空，请重试";
					exit;
				}
				
				if ($project_no = $this->input->post('project_no'))
				{
					$change_info['project_no'] = $project_no;
				}
				else
				{
					echo "您必须选择一个项目";
					exit;
				}
				
				if ($sms_msg = $this->input->post('sms_msg'))
				{
					$change_info['sms_msg'] = htmlspecialchars($sms_msg);
				}
				else
				{
					echo "内容为空，请重试";
					exit;
				}
				
				$sendtime_y = $this->input->post('sendtime_y');
				$sendtime_m = $this->input->post('sendtime_m');
				$sendtime_d = $this->input->post('sendtime_d');
				$sendtime_h = $this->input->post('sendtime_h');
				$sendtime_i = $this->input->post('sendtime_i');
				$change_info['mt_date'] = sprintf('%d%02d%02d%02d%02d00', $sendtime_y, $sendtime_m, $sendtime_d, $sendtime_h, $sendtime_i);
				
				$change_info['apply_user_id'] = $this->Global_user_id;
				
				$this->load->model('users_model', 'users');
				$tmp = $this->users->get_user_info($this->Global_user_id);
				$change_info['apply_user_name'] = $tmp['app_name'];
				$change_info['apply_date'] = date("Y-m-d H:i:s");
				
				$this->load->model('project_model', 'pj');
				$projects = $this->pj->get_info();
				foreach($projects as $one_pj) {
					if($one_pj['project_no'] == $project_no  ) {
						$change_info['pj_name'] = $one_pj['pj_name'];
						break;
					}else {
						$change_info['pj_name'] = '';					
					}
				}
				
				$change_info['gs_status'] = 0;

				if (is_null($gs_id)) {
					if ($gs_id = $this->gs->add($change_info)) {
						// 只有获取了gs-id后，用户上传的文件名中，才会有 gs-id
						$this->writeLog('OP', $change_info['apply_user_name'].",$project_no,0,add group send ok");
						$config['upload_path'] = $this->config->item('groupsend_path');
						$config['allowed_types'] = 'txt';
						$config['file_name'] = $gs_id.'_'.$this->Global_user_id.'_'.$project_no.'_'.date("Ymd_His").'.txt';
						$config['overwrite'] = false;
						$config['remove_space'] = true;
		
						$this->load->library('upload', $config);
						if ( ! $this->upload->do_upload('upload_phone')) {
							print_r($this->upload->display_errors());
							exit;
						}
						else {
							$data = $this->upload->data();
							$change_info['upload_file_fullname'] = $data['full_path'];
							$change_info['upload_client_filename'] = $data['client_name'];
		  				}
						//$this->group_mail();
					}else {
						echo '群发任务添加失败';
						redirect(site_url('group_send/group_send_list'));
					}
				}
				if ($this->gs->update($change_info,$gs_id)) {
					//echo '群发任务添加完成';
					$this->writeLog('OP', $change_info['apply_user_name'].",$project_no,0,update group send ok");
				}else {
					echo '群发任务修改失败';
				}
								
				redirect(site_url('group_send/group_send_list'));
			}
			else
			{
				$this->load->model('project_model', 'pj');
				if ($this->access->is_admin()) {
					$total_pj = $this->pj->get_info(null, null, true);
				}else {
					$total_pj = $this->pj->get_info(null, $this->Global_user_id, true);
				}
				
				if (empty($gs_id)) {
					$data = array(
								'total_pj' =>$total_pj,
								'gsend_list' => array(),
								'action' => array(
									'title' => '新增群发',
									'name' => '新增',
									'value' => site_url('group_send/group_send_change')
								)
							);
				}else {
					$gsend_list = $this->gs->get_info($gs_id, $this->Global_user_id); // only one gsend_id returned.
					$data = array(
									'total_pj' =>$total_pj,
									'gsend_list' => $gsend_list,
									'action' => array(
										'title' => '修改群发',
										'name' => '修改',
										'value' => site_url('group_send/group_send_change/'.$gs_id)
									)
								); 
				}
					
				$this->load->view('group_send_view' ,$data);
			}
		}
			
		public function gsend_detail($gsend_id = '')
		{
			$gsend_id = intval($gsend_id);
			$gsend_info = $this->gs->get_info($gsend_id);

			$gs_status_list = $this->config->item('GROUP_SEND_STATUS');
			$data = array(
							'gsend_info' => $gsend_info,
							'gs_status_list' => $gs_status_list,
							'action' => array(
								'title' => '群发详情',
								'name' => '返回',
								'value' => site_url('group_send/group_send_list')
							)
						);

			$this->load->view('group_send_detail_readonly_view', $data);
		}
		
		public function group_send_special($gs_id = NULL)
		{
			if ($this->input->post('isPost')  == 'TRUE') 
			{
				
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($project_no = $this->input->post('project_no'))
				{
					$change_info['project_no'] = $project_no;
				}
				else
				{
					echo "FROM为空，请重试";
					exit;
				}
				
				if ($longnum = $this->input->post('longnum'))
				{
					$change_info['longnum'] = $longnum;
					
					$longnum_db = $this->gs->get_longnum($project_no) ;
					if ($longnum_db == -1)
					{
						echo "长号码获取错误，请查看from值是否正确!";
						exit;
					}
					else if ( substr($longnum,0,strlen($longnum_db)) !== $longnum_db )
					{
						echo "长号码填写错误，分配基号为：$longnum_db!";
						exit;
					}
				}
				else
				{
					echo "长号码为空，请重试";
					exit;
				}
				
				if ($sms_msg = $this->input->post('sms_msg'))
				{
					$change_info['sms_msg'] = htmlspecialchars($sms_msg);
				}
				else
				{
					echo "内容为空，请重试";
					exit;
				}
				
				$sendtime_y = $this->input->post('sendtime_y');
				$sendtime_m = $this->input->post('sendtime_m');
				$sendtime_d = $this->input->post('sendtime_d');
				$sendtime_h = $this->input->post('sendtime_h');
				$sendtime_i = $this->input->post('sendtime_i');

				$change_info['gs_start'] = sprintf('%d%02d%02d%02d%02d00', $sendtime_y, $sendtime_m, $sendtime_d, $sendtime_h, $sendtime_i);
				
				if ($input_phone = $this->input->post('input_phone'))
				{
					$phone_array1 = preg_split("/\r\n|\r|\n/", $input_phone);
					$phone_str1 = $this->gs->filter_phone($phone_array1);
				}

				if ($upload_phone_special = $this->input->post('upload_phone_special'))
				{
					
					$path = $this->config->item('groupsend_path');
					$tmpfname = tempnam($path."tmp", "group_send_");
					
					$qlw_user_id = $this->access->get_user_id(); 
					$qlw_user_info = $this->users->get_user_info($qlw_user_id); 
					$dir = '/data0/apache/rsyncdata/'.$qlw_user_info['username'];
					if(!is_dir($dir))
  				{
						exit('no directory!');
				  }			
					else
					{	
										
					if ( copy("$dir/$upload_phone_special", $tmpfname) )
						{
							$phone_array2 = preg_split("/\r\n|\r|\n/", file_get_contents($tmpfname));
							$phone_str2 = $this->gs->filter_phone($phone_array2);
						}

						$savename = sprintf('M%s_%s_%s', $project_no, $longnum, $change_info['gs_start']);
	
						file_put_contents("$path$savename", $phone_str2, LOCK_EX);

					
				}										
			}
				
				if (!empty($phone_str2))
				{
					$change_info['phone_list'] = $savename;
				}
				else if  (!empty($phone_str1))
				{
					$change_info['phone_list'] = trim($phone_str1, ',');
				}

				$change_info['user_id'] = $this->Global_user_id;


					$change_info['status'] = 0;
					
					if (is_null($gs_id))
					{

						if ($this->gs->add($change_info))
						{
							//echo '群发任务添加完成';
							$this->load->model('users_model', 'user');
							$userdata = $this->user->get_user_info($this->Global_user_id);
							$username = $userdata['username'];
							$this->writeLog('OP', "$username,$longnum,$project_no,0,add group ok");
							
							$this->group_mail();
							
							redirect(site_url('group_send/group_send_list'));
						}
						else
						{
							echo '群发任务添加失败';
						}
					}
					else
					{
						if ($this->gs->update($change_info,$gs_id))
						{
							//echo '群发任务添加完成';
							$this->load->model('users_model', 'user');
							$userdata = $this->user->get_user_info($this->Global_user_id);
							$username = $userdata['username'];
							$this->writeLog('OP', "$username,$longnum,$project_no,0,update group ok");
							redirect(site_url('group_send/group_send_list'));
						}
						else
						{
							echo '群发任务修改失败';
						}
						
					}
				
			}
			else
			{
				if (empty($gs_id))
				{
					$data = array(
								'pj_list' => array(),
								'action' => array(
									'name' => '新增',
									'value' => site_url('group_send/group_send_special/')
								)
							);
				}
				else
				{
					$this->load->model('group_send_model', 'pj');
	
					$pj_list = $this->pj->get_info($gs_id);
					$data = array(
									'pj_list' => $pj_list,
									'action' => array(
										'name' => '修改',
										'value' => site_url('group_send/group_send_special/'.$gs_id)
										)
								); 
					
				}
				if ($this->access->is_admin())
				{
					$data['from_list'] = $this->gs->get_from();
				}
				else      
				{
					$data['from_list'] = $this->gs->get_from( $this->Global_user_id);
				}
	
				$this->load->view('group_send_special_view' ,$data);
			}
		}
		
		public function group_send_blacklist_new($gs_id = NULL)
		{
			$data = array();	
			$phone_array1 = array();
			$phone_array2 = array();
			$phone_array3 = array();
			$phone_array4 = array();			
			if ($this->input->post('isPost')  == 'TRUE') 
			{
				
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
								
				if ($input_phone = $this->input->post('input_phone'))
				{
					$phone_array1 = preg_split("/\r\n|\r|\n/", $input_phone);

				}
				
				if (!empty($_FILES['upload_phone']['name']))
				{
					$path = $this->config->item('groupsend_path');
					$tmpfname = tempnam($path."tmp", "group_send_");

					if ($_FILES['upload_phone']['error'] == 0 )
					{
						if ( move_uploaded_file($_FILES['upload_phone']['tmp_name'], $tmpfname) )
						{
							$phone_array3 = preg_split("/\r\n|\r|\n/", file_get_contents($tmpfname));

						}
					}
					else
					{
						echo '上传手机号码失败，错误代码是:'.$_FILES['upload_phone']['error'];
						exit;
					}
				}
				
				if (!empty($phone_array3))
				{
					foreach ($phone_array3 as $v)
					{
						$change_info = array();
						$tmpphone = trim($v);
						if (preg_match ("/^$/", $tmpphone))
						{
							
						}
						else
						{
							$phone_array4 = preg_split("/\s+/", $tmpphone);
							
							if ($change_info['mobile'] = $phone_array4[0])
							{
				
							}
							else
							{
								echo "$tmpphone\n";
								echo "mobile $phone_array4[0] input error";
								exit;
							}
					
							if ($change_info['msgfrom'] = $phone_array4[1])
							{
							}
							else
							{
								echo "from $phone_array4[1] input error";
								exit;
							}
					
							if ($change_info['mark'] = $phone_array4[2])
							{
							}
							else
							{
								echo "mark $phone_array4[2] is empty";
								exit;
							}
							
							$this->load->model('users_model', 'user');
							$userdata = $this->user->get_user_info($this->Global_user_id);
							$username = $userdata['username'];
							$change_info['user'] = $username;
					
					
							if ($this->gs->addblack_new($change_info))
							{
								$mobile = $change_info['mobile'];
								$from = $change_info['msgfrom'];
								$this->writeLog('OP', "$mobile,$from,$username,0,add black ok");
		
							}
							else
							{
								$mobile1 = $change_info['mobile'];
								echo "添加黑名单失败:$mobile1\n";
							}
						}
															
					}

				}
				else if  (!empty($phone_array1))
				{
					foreach ($phone_array1 as $v)
					{
						$change_info = array();
						$tmpphone = trim($v);
						if (preg_match ("/^$/", $tmpphone))
						{
							
						}
						else
						{
							$phone_array2 = preg_split("/\s+/", $tmpphone);
							
							if ($change_info['mobile'] = $phone_array2[0])
							{
				
							}
							else
							{
								echo "$tmpphone\n";
								echo "mobile $phone_array2[0] input error";
								exit;
							}
					
							if ($change_info['msgfrom'] = $phone_array2[1])
							{
							}
							else
							{
								echo "from $phone_array2[1] input error";
								exit;
							}
					
							if ($change_info['mark'] = $phone_array2[2])
							{
							}
							else
							{
								echo "mark $phone_array2[2] is empty";
								exit;
							}
							$this->load->model('users_model', 'user');
							$userdata = $this->user->get_user_info($this->Global_user_id);
							$username = $userdata['username'];
							$change_info['user'] = $username;
					
					
							if ($this->gs->addblack_new($change_info))
							{
								$mobile = $change_info['mobile'];
								$from = $change_info['msgfrom'];
								$this->writeLog('OP', "$mobile,$from,$username,0,add black ok");
		
							}
							else
							{
								$mobile1 = $change_info['mobile'];
								echo "添加黑名单失败:$mobile1\n";
							}
						}									
					}
				}
				
																								
				$this->load->view('group_send_blacklist_new_view' ,$data);		
									
			}
			else
			{
				
				$data = array(
								'pj_list' => array(),
								'action' => array(
									'name' => '新增',
									'value' => site_url('group_send/group_send_blacklist_new/')
								)
							);
							
				
				$this->load->view('group_send_blacklist_new_view' ,$data);
			}
		}
		
		public function group_send_blacklist($gs_id = NULL)
		{
			$data = array();
			
			
			$this->load->view('group_send_blacklist.php' ,$data);
		}
		public function add_black()
		{
			$data = array();
			
			$change_info = array();
			if ($change_info['mobile'] = $this->input->post('mobile'))
			{
		
			}
			else
			{
				echo "mobile input error";
				exit;
			}
			
			if ($change_info['msgfrom'] = $this->input->post('from'))
			{
			}
			else
			{
				echo "from input error";
				exit;
			}
			
			if ($change_info['mark'] = $this->input->post('mark'))
			{
			}
			else
			{
				echo "mark is empty";
				exit;
			}
			
			$this->load->model('users_model', 'user');
			$userdata = $this->user->get_user_info($this->Global_user_id);
			$username = $userdata['username'];
			$change_info['user'] = $username;
			
			
			if ($this->gs->addblack($change_info))
			{
				echo '添加完成黑名单成功';
				$mobile = $change_info['mobile'];
				$from = $change_info['msgfrom'];
				$this->writeLog('OP', "$mobile,$from,$username,0,add black ok");

			}
			else
			{
				echo '添加黑名单失败';
			}
			$this->load->view('group_send_blacklist.php' ,$data);
		}
		
		public function del_black($mobile,$from)
		{
			$data = array();
			
			$change_info = array();
			if ($mobile)
			{
		
			}
			else
			{
				echo '删除失败';
				exit;
			}
			
			if ($from)
			{
			}
			else
			{
				echo '删除失败';
				exit;
			}
			
			
			$this->load->model('users_model', 'user');
			$userdata = $this->user->get_user_info($this->Global_user_id);
			$username = $userdata['username'];

			
			
			if ($this->gs->delblack($mobile,$from))
			{
				echo '删除黑名单成功';

				$this->writeLog('OP', "$mobile,$from,$username,0,del black ok");

			}
			else
			{
				echo '删除黑名单失败';
			}
			$this->load->view('group_send_blacklist.php' ,$data);
		}
		
		public function blacklist($gs_id = NULL)
		{
			$data = array();
	
			
			if (false === $this->input->post('phone')) 
			{

				$this->load->view('group_send_blacklist.php' ,$data);
				return;
			}else 
			{
				$data['phone'] = $this->input->post('phone');
				$tmp = $data['phone'] ;
				
				if (strlen($tmp) > 6)
				$data['list'] = $this->gs->get_black_info($tmp);
			}
			
			$this->load->view('group_send_blacklist.php' ,$data);
		}
		
		public function upt_status($status, $id)
		{
			if (!empty($id) && !empty($status))
			{
				$info['gs_status'] = $status;

				$this->load->model('users_model', 'user');
				$userdata = $this->user->get_user_info($this->Global_user_id);
				$username = $userdata['app_name'];
				if($status == 2) { // 通过审核
					$info['audit_user_id'] = $this->Global_user_id;
					$info['audit_user_name'] = $username;
					$info['audit_date'] = date('Y-m-d H:i:s');
				}
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($this->gs->update($info, $id))
				{
					//echo '项目状态更新成功';
					$this->writeLog('OP', "$username,$id,$status,update group send status ok");
					redirect(site_url('group_send/group_send_list'));
				}
				else
				{
					echo '操作失败';
				}
			}
		}
		
		public function group_mail()
		{
			$this->load->library('email');

			$this->email->from('qxtgateway@staff.sina.com.cn', 'gateway');
			$this->email->to('muzi1@staff.sina.com.cn'); 
			$this->email->cc('jianqiang1@staff.sina.com.cn'); 
			
			$this->load->model('users_model', 'user');
			$userdata = $this->user->get_user_info($this->Global_user_id);
			$username = $userdata['username'];
			
			$this->email->subject("申请群发业务--$username");
			
			
			
			$this->email->message("
您好：
	用户 $username 申请企信通群发业务请查看！！
"); 

			$this->email->send();

			echo $this->email->print_debugger();
			echo $pj_name;
		}
		
		
		
		public function mms_group_send_list()
		{
			if ($this->access->is_admin())
			{
				$data['list'] = $this->gs->get_mms_info();
			}
			else
			{
				$data['list'] = $this->gs->get_mms_info(NULL, $this->Global_user_id);
			}

			$this->load->model('users_model', 'users');
			$data['user_list'] = $this->users->get_user_info_key();
			$data['gs_status'] = $this->config->item('GROUP_SEND_STATUS');
			$this->load->view('mms_group_send_list_view', $data);
		}

		public function mms_group_send_change($gs_id = NULL)
		{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			if (($this->input->post('input_phone') === FALSE) && empty($_FILES['upload_phone']['name']))
			{
				$this->load->model('project_model', 'pj');

				if ($this->access->is_admin())
				{
					$data['pj_list'] = $this->pj->get_info_key_project_no();
				}
				else
				{
					$data['pj_list'] = $this->pj->get_info_key_project_no($this->Global_user_id);
				}

				$data['map_pj'] = $this->pj->get_info_key_project_no();

				if (empty($data['pj_list']))
				{
					echo '没有项目可以进行群发，请先申请一个项目';
				}
				else
				{
					$this->load->view('mms_group_send_view' ,$data);
				}
			}
			else
			{
				if ($project_no = $this->input->post('project_no'))
				{
					$change_info['project_no'] = $project_no;
				}
				else
				{
					echo "项目编号为空，请重试";
					exit;
				}

				if (!empty($_FILES['upload_mmsfile']['name']))
				{
					$mms_path = $this->config->item('mms_groupsend_path');
					$mms_tmpfname = tempnam("/tmp", "mms_group_send_");

					if ($_FILES['upload_mmsfile']['error'] == 0 && $_FILES['upload_mmsfile']['size'] <= 38000)
					{
						if ( move_uploaded_file($_FILES['upload_mmsfile']['tmp_name'], $mms_tmpfname) )
						{
							if (($checkflag = $this->gs->check_mmsfile($mms_tmpfname)) !== TRUE)
							{
								$mms_err = array();
								echo '彩信包格式错误,错误原因是:'.$mms_err[$checkflag];
								exit;
							}
						}
						else
						{
							echo '转移彩信包失败，请联系管理员';
							exit;
						}
					}
					else
					{
						echo '上传彩信包失败，错误代码是:'.$_FILES['upload_mmsfile']['error'];
						exit;
					}
				}

				if (!empty($_FILES['upload_phone']['name']))
				{
					$path = $this->config->item('mms_groupsend_path');
					$tmpfname = tempnam("/tmp", "group_send_");

					if ($_FILES['upload_phone']['error'] == 0 && $_FILES['upload_phone']['size'] <= 150000)
					{
						if ( move_uploaded_file($_FILES['upload_phone']['tmp_name'], $tmpfname) )
						{
							if (($checkflag2 = $this->gs->check_phonefile($tmpfname)) !== TRUE)
							{
								$phone_err = array();
								echo '手机号文件格式错误,错误原因是:'.$phone_err[$checkflag2];
								exit;
							}
						}
						else
						{
							echo '转移手机号文件失败，请联系管理员';
							exit;
						}
					}
					else
					{
						echo '上传手机号码失败，错误代码是:'.$_FILES['upload_phone']['error'];
					}
				}

				if (is_null($gs_id))
				{
					$change_info['app_date'] = date('YmdHis');
					$change_info['user_id'] = $this->Global_user_id;
					$change_info['status'] = 2;

					if ($ins_id = $this->gs->mms_add($change_info))
					{
						if (copy($mms_tmpfname, $mms_path.$ins_id.'.zip') && copy($tmpfname, $mms_path.$ins_id.'.txt'))
						{
							//echo '群发任务添加完成';
							redirect(site_url('group_send/mms_group_send_list'));
						}
						else
						{
							echo "mms_tmpfname:$mms_tmpfname<br>tmpfname:$tmpfname<br>";
							echo '保存文件失败，请联系管理员';
						}
					}
					else
					{
						echo '群发任务添加失败';
					}
				}
				else
				{
					echo '不能修改';
				}
			}
		}

		public function upt_mms_status($status, $id)
		{
			if (!empty($id) && !empty($status))
			{
				$info['status'] = $status;
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($this->gs->mms_update($info, $id))
				{
					//echo '项目状态更新成功';
					redirect(site_url('group_send/mms_group_send_list'));
				}
				else
				{
					echo '操作失败';
				}
			}
		} 
		public function writeLog($pre, $logInfo)
		{
			//global $LOG_DIR;
			
			$logInfo = str_replace("\n", " ", $logInfo);
			$logInfo = str_replace("\r", " ", $logInfo);
			
			$dateNow = date('Ymd');
			$timeNow = date('H:i:s');
			$fp = @fopen("/data0/apache/htdocs/qxt_plat/log/".$pre.$dateNow.".log", "a+");
			if ($fp)
			{
				fwrite($fp, "[$timeNow] $logInfo\n");
				fclose($fp);
			}
		}
		
	
		public function gsend_search()
		{
			$info = array();
			if ($content = $this->input->post('content'))
			{
				$info['content'] = $content;
			}

			if (($gs_status = $this->input->post('gs_status')) != '')
			{
				$info['gs_status'] = $gs_status;
			}

			if ($this->access->is_admin())
			{
				$data['list'] = $this->gs->get_info_from($info);
			}
			else
			{
				$data['list'] = $this->gs->get_info_from($info, $this->Global_user_id);
			}

			$data['gs_status'] = $this->config->item('GROUP_SEND_STATUS');
			$this->load->view('group_send_list_view', $data);
		}
	}
