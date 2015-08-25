<?php
	class Acl extends Base_Controller {
		function __construct()
		{
			parent::__construct();
			$this->load->library('Access');
			$this->load->model('acl_model', 'acl');
		}

		public function index()
		{			
		}
		
		public function acl_list()
		{
			if ($role_list = $this->acl->get_role_info())
			{
				foreach ($role_list as $row)
				{
					$rolelist[$row['id']] = $row['role_name'];
				}
			}
			else
			{
				$rolelist = array();
			}
			
			if ($res_list = $this->acl->get_res_info())
			{
				foreach ($res_list as $row)
				{
					$reslist[$row['class']][] = $row;
				}
			}
			else
			{
				$reslist = array();
			}
			
			$data['rolelist'] = $rolelist;
			$data['reslist'] = $reslist;
			
			$this->load->view('acl_list_view', $data);
		}
		
		public function get_res_from_role($role_id = 0)
		{
			$res_list['resids[]'] = $this->acl->get_res_list_from_role($role_id);
			echo json_encode($res_list);
		}
		
		public function modify_acl()
		{
			$roleid = $this->input->post('roleids');
            $resids = $this->input->post('resids');
			
			if (!empty($roleid))
			{
				$oldlist = $this->acl->get_res_list_from_role($roleid);
				
				$dellist = array_diff($oldlist, $resids);
				$addlist = array_diff($resids, $oldlist);
				
				if ( count($dellist) > 0 )
				{
					foreach ($dellist as $res_ids)
					{
						$this->acl->del_acl(array('role_id' => $roleid, 'res_id' => $res_ids));
					}
				}

				if ( count($addlist) > 0 )
				{
					foreach ($addlist as $res_ids)
					{
						$this->acl->add_acl(array('role_id' => $roleid, 'res_id' => $res_ids));
					}
				}
				
				redirect('acl/acl_list');
			}
			else
			{
				echo "角色不能为空";
			}
		}

		public function add_role()
		{
			$role_name = $this->input->post('name');
			$role_desc = $this->input->post('desc');

			if (empty($role_name))
			{
				$data['role'] = array();
				$data['setting'] = array('button_name' => '增加角色', 'post_url' => site_url('acl/add_role'));
				$this->load->view('role_info_view', $data);
			}
			else
			{
				if (!empty($role_desc))
				{
					$data['role_desc'] = $role_desc;
				}

				$data['role_name'] = $role_name;
				$data['status'] = 1;

				if ($this->acl->add_role($data))
				{
					$msg = "角色新增成功";
				}
				else
				{
					$msg = "角色新增失败";
				}
				
				echo '<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=utf-8">';
				echo $msg;
			}
		}

		public function modify_role($role_id)
		{
			$role_id = intval($role_id);
			$role_name = $this->input->post('name');

			if (empty($role_name))
			{
				$data['role'] = $this->acl->get_role_info($role_id);
				$data['setting'] = array('button_name' => '修改角色', 'post_url' => site_url('acl/modify_role/'.$role_id));

				//$this->load->view('role_info_view', $data);
				$this->load->view('role_info_view_new', $data);
			}
			else
			{
				$role_name = $this->input->post('name');
				$role_desc = $this->input->post('desc');

				if (!empty($role_desc))
				{
					$data['role_desc'] = $role_desc;
				}

				$data['role_name'] = $role_name;

				if ($this->acl->modify_role($data, $role_id))
				{
					$msg = "角色修改成功";
				}
				else
				{
					$msg = "角色修改失败";
				}
				
				echo '<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=utf-8">';
				echo $msg;
			}
		}
		
		public function add_res()
		{
			$res_name = $this->input->post('name');
			$res_desc = $this->input->post('desc');
			$class = $this->input->post('class');
			$method = $this->input->post('method');
			$ext = $this->input->post('ext');

			if (empty($res_name) || empty($class) || empty($method))
			{
				$data['res'] = array();
				$data['setting'] = array('button_name' => '增加模块', 'post_url' => site_url('acl/add_res'));
				$this->load->view('res_info_view', $data);
			}
			else
			{
				if (!empty($ext))
				{
					$data['ext'] = $ext;
				}
				
				if (!empty($res_desc))
				{
					$data['res_desc'] = $res_desc;
				}

				$data['res_name'] = $res_name;
				$data['class'] = $class;
				$data['method'] = $method;
				
				$data['status'] = 1;

				if ($this->acl->add_res($data))
				{
					echo "模块新增成功";
				}
				else
				{
					echo "模块新增失败";
				}
			}
		}

		public function modify_res($res_id)
		{
			$res_id = intval($res_id);
			$res_name = $this->input->post('name');
			$class = $this->input->post('class');
			$method = $this->input->post('method');

			if (empty($res_name) || empty($class) || empty($method))
			{
				$data['res'] = $this->acl->get_res_info($res_id);
				$data['setting'] = array('button_name' => '修改模块', 'post_url' => site_url('acl/modify_res'));

				$this->load->view('res_info_view', $data);
			}
			else
			{
				$ext = $this->input->post('ext');
				$res_desc = $this->input->post('desc');
				
				if (!empty($ext))
				{
					$data['ext'] = $ext;
				}
				
				if (!empty($role_desc))
				{
					$data['role_desc'] = $role_desc;
				}

				$data['role_name'] = $role_name;
				$data['class'] = $class;
				$data['method'] = $method;

				if ($this->acl->modify_res($data, $res_id))
				{
					echo "模块修改成功";
				}
				else
				{
					echo "模块修改失败";
				}
			}
		}
	}