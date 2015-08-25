<?php
	class User extends Base_Controller {
	
		private $rolelist;
		
		function __construct()
		{
			parent::__construct();
			$this->load->model('users_model', 'users');
			$this->rolelist = array(3 => '群发用户', 2 => '公司内部用户', 1 => '管理员');
		}

		public function index()
		{
		}

		public function user_list()
		{
			$data = array();
			$data['list'] = $this->users->get_user_info(NULL, TRUE);

			$data['rolelist'] = $this->rolelist;
			$data['user_status'] = $this->config->item('USER_STATUS');
            $this->load->view('user_list_view', $data);
		}

		public function user_change($user_id = '')
		{
			$user_id = intval($user_id);

			if ($flag = $this->input->post('isPost') == 'TRUE')
			{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($username = $this->input->post('username'))
				{
					$change_info['username'] = $username;
				}
				else
				{
					echo '必须输入账号<br><a href="'.site_url('user/user_change').'">返回用户新增</a>';
					exit;
				}

				if ( ($pwd = $this->input->post('pwd')) == ($repwd = $this->input->post('repwd')))
				{
					if (!empty($pwd))
					{
						$change_info['password'] = $this->users->encode_user_pwd($pwd);
					}
				}
				else
				{
					echo '两次输入密码不一致<br><a href="'.site_url('user/user_change').'">返回用户新增</a>';
					exit;
				}

				if ($app_name = $this->input->post('app_name'))
				{
					$change_info['app_name'] = $app_name;
				}

				if ($company = $this->input->post('company'))
				{
					$change_info['company'] = $company;
				}

				if ($app_ext = $this->input->post('app_ext'))
				{
					$change_info['app_ext'] = $app_ext;
				}

				if ($app_phone = $this->input->post('app_phone'))
				{
					$change_info['app_phone'] = $app_phone;
				}

				if ($email = $this->input->post('email'))
				{
					$change_info['email'] = $email;
				}

				if ($add_app_name = $this->input->post('add_app_name'))
				{
					$change_info['add_app_name'] = $add_app_name;
				}

				if ($add_app_phone = $this->input->post('add_app_phone'))
				{
					$change_info['add_app_phone'] = $add_app_phone;
				}

				if ($this->access->is_admin() && $role_id = $this->input->post('role_id'))
				{
					$change_info['role_id'] = $role_id;
				}

				if ($this->access->is_admin() && empty($user_id))
				{
					$change_info['create_date'] = date('Y-m-d H:i:s');
					if ($user_id = $this->users->add_user($change_info))
					{
						redirect(site_url('user/user_list'));
					}
					elseif (0 == $user_id)
					{
						echo '用户已存在<br><a href="'.site_url('user/user_change').'">返回用户新增</a>';
					}
					else
					{
						echo '新增用户失败';
						exit;
					}
				}
				elseif(empty($user_id))
				{//hack
					$this->access->logout();
					exit;
				}
				else
				{
					if ($this->users->modify_user($change_info, $user_id))
					{
						redirect(site_url('user/user_list'));
					}
					else
					{
						echo '用户更新失败';
						exit;
					}
				}
			}
			else
			{
				if (empty($user_id))
				{
					$data = array(
								'user_info' => array(),
								'action' => array(
									'name' => '新增用户',
									'value' => site_url('user/user_change')
								)
							);
				}
				else
				{
					$user_info = $this->users->get_user_info($user_id, TRUE);
					$data = array(
								'user_info' => $user_info,
								'action' => array(
									'name' => '修改用户',
									'value' => site_url('user/user_change/'.$user_id)
								)
							);
				}

				$data['rolelist'] = $this->rolelist;
				$this->load->view('user_info_view', $data);
			}
		}

		public function delete()
		{
			$user_ids = $this->input->post('del_list');

			if (!empty($user_ids))
			{
				if ($this->users->del_user($user_ids))
				{
					redirect(site_url('user/userlist'));
				}
			}

			echo '<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=utf-8">';
			echo 'Delete Error';
		}

		public function user_status($status, $id)
		{
			if (!empty($id) && !empty($status))
			{
				$info['status'] = $status;
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				if ($this->users->modify_user($info, $id))
				{
					redirect(site_url('user/user_list'));
				}
				else
				{
					echo '用户状态更新失败';
				}
			}
		}

		public function user_uptpwd()
        {
            $id = $this->Global_user_id;

            $userinfo = $this->users->get_user_info($id);

            $oldpwd = $this->input->post('oldpwd');
            $newpwd = $this->input->post('newpwd');
            $repwd = $this->input->post('repwd');
            $error = '';

            if (!empty($oldpwd) && !empty($newpwd) && !empty($repwd))
            {
                if (strcmp($newpwd, $repwd) != 0)
                {
                    $error =  '两次输入的修改密码不一致';
                }
                else
                {
                    if ($this->access->valid_login($userinfo['username'], $oldpwd))
                    {
                        $data['password'] = $this->users->encode_user_pwd($newpwd);
                        if ($this->users->modify_user($data, $id))
                        {
                            $error = '密码修改成功';
                        }
                        else
                        {
                            $error = '密码修改失败，请稍后重试';
                        }
                    }
                    else
                    {
                        $error = '原始密码不正确';
                    }
                }
            }
            $data['errors'] = $error;
			$data['user_info'] = $userinfo;

            $this->load->view('user_uptpwd_view', $data);
        }
        public function user_search()
		{
			$info = array();
			if ($content = $this->input->post('content'))
			{
				$info['content'] = $content;
			}

			if ($this->access->is_admin())
			{
				$data['list'] = $this->users->get_info_from($info);
			}
			else
			{
				$data['list'] = $this->users->get_info_from($info, $this->Global_user_id);
			}
			
			$data['rolelist'] = $this->rolelist;
			$data['user_status'] = $this->config->item('USER_STATUS');
            $this->load->view('user_list_view', $data);

		}
		
	}