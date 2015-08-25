<?php
/************************************************
 ** @author diwei
 ** @class description 权限库
 ************************************************/

	class Access
	{
		private $CI;

		function __construct()
		{
			$this->CI =& get_instance();
		}

		/**
		* 验证用户登陆信息
		*
		* @access public
		* @param null
		* @return boolean
		*/
		public function valid_login($user, $pwd)
		{
			$this->CI->load->model('users_model', 'users');
               
			if ($info = $this->CI->users->get_user_info_from_username($user))
			{
				if (1 == $info['status'])
				{
					$encode_pwd = $this->CI->users->encode_user_pwd($pwd);
					if (strcmp($info['password'], $encode_pwd) == 0)
					{
						$this->CI->session->set_userdata('id', $info['id']);
						return TRUE;
					}
				}
			}
			
			return FALSE;
		}

		/**
		* 判断是否登陆
		*
		* @access public
		* @param null
		* @return boolean
		*/
		public function is_login()
		{
			$user_id = $this->CI->session->userdata('id');
			return (!empty($user_id) && intval($user_id) > 0) ? TRUE : FALSE;
		}

		/**
		* 用户注销登陆
		*
		* @access public
		* @param null
		* @return boolean
		*/
		public function logout()
		{
			$this->CI->session->unset_userdata('id');

			$check_id = $this->CI->session->userdata('id');
			return empty($check_id) ? TRUE : FALSE;
		}

		/**
		* 获取当前用户id
		*
		* @access public
		* @param null
		* @return numeric
		*/
		public function get_user_id()
		{
			$user_id = $this->CI->session->userdata('id');
			return (!empty($user_id) && intval($user_id) > 0) ? $user_id : FALSE;
		}
		
		/**
		* 判断是否管理员
		*
		* @access public
		* @param null
		* @return boolean
		*/
		
		public function is_admin()
		{
			$this->CI->load->model('users_model', 'users');
			
			$info = $this->CI->users->get_user_info($this->get_user_id(), TRUE);
			
			return (1 == $info['role_id']  || 2 == $info['role_id']) ? TRUE : FALSE;
			/*
			$this->CI->load->model('acl_model', 'acl');
			$role_list = $this->CI->acl->get_roles_from_user($this->get_user_id());
			if (count($role_list) > 0 && in_array(1, $role_list))
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}*/
		}

		/**
		* 判断当前用户是否对某个控制器的方法有访问权限
		*
		* @access public
		* @param mix
		* @return boolean
		*/
		public function acl_check($cur_class, $cur_method)
		{
			return TRUE;
			// gumeng: 今后再开放
			$user_id = $this->get_user_id();
			
			if ($this->is_admin($user_id) === FALSE)
			{
				$this->CI->load->model('Acl_model', 'acl');

				$res_list = $this->CI->acl->get_res_list_from_user($user_id);
				$res_id = $this->CI->acl->get_res_id_from_controller($cur_class, $cur_method);

				if (!in_array($res_id, $res_list))
				{
					return FALSE;
				}
			}
			return TRUE;
		}
	}