<?php
/***************************************************************
 ** @author diwei  20110301
 ** @class description 通道控制器 实现通道列表、增加、修改、删除
 ***************************************************************/

	class Task extends Base_Controller {

		private $_task_type;

		function __construct()
		{
			parent::__construct();
			$this->load->model('task_model', 'task');
			$this->load->library('access');

			$this->_task_type = $this->config->item('CHANNEL_TYPE');
		}

		public function index()
		{
			$this->task_list();
		}

		public function task_list()
		{
			$data = array();

			/*$user_id = $this->acl->get_user_id();
			if ($this->acl->isadmin()){{$this->task->get_info($pid = NULL, $user_id)}
			else{$data['c_list'] = $this->task->get_info(NULL, $user_id);}
			*/
			$this->load->model('project_model', 'pj');
			if ($this->access->is_admin())
			{
				$data['p_list'] = $this->task->get_info();
				$data['pj_info'] = $this->pj->get_info_key_project_no();
			}
			else
			{				
				$data['pj_info'] = $this->pj->get_info_key_project_no(NULL, $this->Global_user_id);
				if (!empty($data['pj_info']))
				{
					foreach ($data['pj_info'] as $k => $v)
					{
						$tmp_array[] = $k;
					}
					$data['p_list'] = $this->task->get_info_from(array('channel_no' => $tmp_array));
				}
				else
				{
					$data['p_list'] = array();
				}
			}	
			$data['task_status'] = $this->config->item('TASK_STATUS');
						
            $this->load->view('task_list_view', $data);
		}

		public function task_change($p_id = '')
		{
			$p_id = intval($p_id);

			if (!empty($p_id))
			{
				$task_info = $this->task->get_info($p_id);

				if ($task_info['status'] != 0 && $task_info['status'] != 1)
				{
					exit;
				}
			}

			if ($flag = $this->input->post('isPost') == 'TRUE')
			{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($task_name = $this->input->post('task_name'))
				{
					$change_info['task_name'] = $task_name;
				}
				else
				{
					echo '必须填写任务名称';
					exit;
				}

				if ($task_type = $this->input->post('task_type'))
				{
					$change_info['task_type'] = $task_type;
				}

				if ($task_popular = $this->input->post('task_popular'))
				{
					$change_info['task_popular'] = htmlspecialchars($task_popular);
				}

				if ($task_msg = $this->input->post('task_msg'))
				{
					$change_info['task_msg'] = htmlspecialchars($task_msg);
				}
				
				if ($project_no = $this->input->post('project_no'))
				{
					$change_info['project_no'] = $project_no;
				}

				$validdate_s_y = $this->input->post('validdate_s_y');
				$validdate_s_m = $this->input->post('validdate_s_m');
				$validdate_s_d = $this->input->post('validdate_s_d');
				$validdate_e_y = $this->input->post('validdate_e_y');
				$validdate_e_m = $this->input->post('validdate_e_m');
				$validdate_e_d = $this->input->post('validdate_e_d');

				$change_info['task_start'] = sprintf('%d%02d%02d', $validdate_s_y, $validdate_s_m, $validdate_s_d);
				$change_info['task_end'] = sprintf('%d%02d%02d', $validdate_e_y, $validdate_e_m, $validdate_e_d);

				$change_info['app_date'] = date('YmdHis');

				if (empty($p_id))
				{
					if ($p_id = $this->task->add($change_info))
					{
						redirect(site_url('task/task_list'));
					}
					else
					{
						echo '新增任务失败';
						exit;
					}
				}
				else
				{
					$change_info['status'] = 0;
					if ($this->task->update($change_info, $p_id))
					{
						redirect(site_url('task/task_list'));
					}
					else
					{
						echo '任务更新失败';
						exit;
					}
				}
			}
			else
			{
				if (empty($p_id))
				{
					$data = array(
								'task_info' => array(),
								'action' => array(
									'name' => '新增任务',
									'value' => site_url('task/task_change')
								)
							);
				}
				else
				{
					$data = array(
								'task_info' => $task_info,
								'action' => array(
									'name' => '修改任务',
									'value' => site_url('task/task_change/'.$p_id)
								)
							);
				}
				$data['task_type'] = $this->_task_type;
				
				$this->load->model('project_model', 'pj');
				if ($this->access->is_admin())
				{
					$data['pj_info'] = $this->pj->get_info();
				}
				else
				{
					$data['pj_info'] = $this->pj->get_info(NULL, $this->Global_user_id);
				}
				

				$this->load->view('task_info_view', $data);
			}
		}


		public function task_status($status, $id)
		{
			if (!empty($id) && !empty($status))
			{
				$info['status'] = $status;
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($this->task->update($info, $id))
				{
					//echo '项目状态更新成功';
					redirect(site_url('task/task_list'));
				}
				else
				{
					echo '任务状态更新失败';
				}
			}
		}

		public function task_search()
		{
			$info = array();
			if ($content = $this->input->post('content'))
			{
				$info['content'] = $content;
			}

			if (($task_status = $this->input->post('task_status')) != '')
			{
				$info['task_status'] = $task_status;
			}

			$data['p_list'] = $this->task->get_info_from($info);
			$data['task_status'] = $this->config->item('PROJECT_STATUS');
			$this->load->view('project_list_view', $data);
		}

		public function project_task()
		{

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
	}