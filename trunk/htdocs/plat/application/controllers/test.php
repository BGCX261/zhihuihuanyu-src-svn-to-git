<?php
	class test extends Base_Controller {
		function __construct()
		{
			parent::__construct();
			$this->load->model('test_model', 'gs');
		}

		function index()
		{
			$this->load->view('test_view');
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
					
					if ($ins_id = $this->gs->mms_add($change_info))
					{
						if (copy($mms_tmpfname, $mms_path.$ins_id.'.zip') && copy($tmpfname, $mms_path.$ins_id.'.txt'))
						{
							//echo '群发任务添加完成';
							redirect(site_url('group_send/mms_group_send_list'));
						}
						else
						{
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
					redirect(site_url('test/mms_group_send_list'));
				}
				else
				{
					echo '操作失败';
				}
			}
		}
	}