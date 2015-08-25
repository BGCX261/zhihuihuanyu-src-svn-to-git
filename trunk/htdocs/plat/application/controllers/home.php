<?php
	class Home extends CI_Controller {

		private $user_info;

		function __construct() 
		{
			parent::__construct();		

			$this->load->model('acl_model', 'acl');
			$this->load->model('users_model', 'users');
			$this->load->library('access');
			if (!$this->access->is_login())
			{
				redirect('welcome');
				exit;
			}
			$this->Global_user_id = $this->access->get_user_id();
			$this->user_info = $this->users->get_user_info($this->Global_user_id);
		}

		public function index()
		{
			$data = array();

			$data['title'] = $this->config->item('WEBSITE_TITLE');
			$data['user_info'] = $this->user_info;
			$this->load->view('home_view', $data);
		}

		public function menu()
		{
			/* 这些是参考lzlibrary的代码，没有用，但是很有参考价值。
			 * 获取用户的role-id后，通过role-id和acl，可获取用户的所有可用的资源resource，然后构造 $data['menu_list'].
			 * 即：
			 * get resources from role_resource_map, we can get 该资源所在的menu名称，该资源的name, class, method,
			 * 也能拼接成   array('href' => site_url('data_query/day_query'), 'name' => '日记录查询'),这个数组。
		        $role_list = $this->acl->get_roles_from_user($uid);
		        $class_list = $this->acl->get_class_list_from_role($role_list);
		        $data['class_list'] = $class_list;
		        $this->load->view('home_view', $data);

		        // 下面是无ACL时的首页
		        $data = array();
			    $this->load->view('home_view', $data);
 			*/

			// menu_map 应该从conf配置名称。
			$data['menu_map'] = $this->config->item('MANU_MAP');
			if ($this->user_info['role_id'] == 1) { // sys-admin.
				$data['menu_list'] = array(
					/*
					'data_report' => array(
						array('href' => site_url('data_query/day_query'), 'name' => '日记录查询'),
						array('href' => site_url('data_query/month_query'), 'name' => '月记录查询'),
					),*/
					'my_project' => array(
						array('href' => site_url('projects/project_list'), 'name' => '项目列表'),
						array('href' => site_url('projects/project_app_change'), 'name' => '项目申请'),
					), 
					'my_gsend' => array(
						array('href' => site_url('group_send/group_send_list'), 'name' => '群发列表'),
						array('href' => site_url('group_send/group_send_change'), 'name' => '群发申请'),					
						array('href' => site_url('group_send/group_send_test'), 'name' => '短信测试'),					
					),
					'my_bill' => array(
						array('href' => site_url('bill/bill_list'), 'name' => '账单详情'),
						array('href' => site_url('charge/charge_list'), 'name' => '充值详情'),
						array('href' => site_url('charge/let_me_charge'), 'name' => '我要充值'),
						),
					'acl_manage' => array(
						array('href' => site_url('acl/acl_list'), 'name' => '权限管理'),
						array('href' => site_url('acl/add_role'), 'name' => '新增角色'),
						array('href' => site_url('acl/add_res'), 'name' => '新增模块'),
					),
					'user_manage' => array(
						array('href' => site_url('user/user_list'), 'name' => '用户列表'),
						array('href' => site_url('user/user_change'), 'name' => '新增用户'),
						array('href' => site_url('user/user_uptpwd'), 'name' => '我的密码'),
					),
					'channel_manage' => array(
						array('href' => site_url('channels/channel_list'), 'name' => '通道列表'),
						array('href' => site_url('channels/channel_change'), 'name' => '新增通道'),
					),
				);
			}elseif ($this->user_info['role_id'] == 2) { // 公司内部用户
				$data['menu_list'] = array(
					'my_project' => array(
						array('href' => site_url('projects/project_list'), 'name' => '项目列表'),
						array('href' => site_url('projects/project_app_change'), 'name' => '项目申请'),
					), 
					'my_gsend' => array(
						array('href' => site_url('group_send/group_send_list'), 'name' => '群发列表'),
						array('href' => site_url('group_send/group_send_change'), 'name' => '群发申请'),					
						array('href' => site_url('group_send/group_send_test'), 'name' => '短信测试'),					
					),
					'my_bill' => array(
						array('href' => site_url('bill/bill_list'), 'name' => '账单详情'),
						array('href' => site_url('charge/charge_list'), 'name' => '充值详情'),
						array('href' => site_url('charge/let_me_charge'), 'name' => '我要充值'),
						),
					'user_manage' => array(
						array('href' => site_url('user/user_list'), 'name' => '用户列表'),
						array('href' => site_url('user/user_change'), 'name' => '新增用户'),
						array('href' => site_url('user/user_uptpwd'), 'name' => '我的密码'),
					),					
				);
			}elseif ($this->user_info['role_id'] == 3) { // group-send
				$data['menu_list'] = array(
					'my_project' => array(
						array('href' => site_url('projects/project_list'), 'name' => '项目列表'),
						array('href' => site_url('projects/project_app_change'), 'name' => '项目申请'),
					), 
					'my_gsend' => array(
						array('href' => site_url('group_send/group_send_list'), 'name' => '群发列表'),
						array('href' => site_url('group_send/group_send_change'), 'name' => '群发申请'),					
						array('href' => site_url('group_send/group_send_test'), 'name' => '短信测试'),
					),
					'my_bill' => array(
						array('href' => site_url('bill/bill_list'), 'name' => '账单详情'),
						array('href' => site_url('charge/charge_list'), 'name' => '充值详情'),
						),
					'user_manage' => array(
						array('href' => site_url('user/user_uptpwd'), 'name' => '我的密码'),
					),					
				);
			}
				
			$this->load->view('menu_view', $data);
		}

		public function logout()
		{
			$this->load->library('access');
			$this->access->logout();
			redirect(site_url()); 
		}

		public function select_self_file(){
   	    	$data['title'] = '选择文件';
        	$this->load->view('select_file', $data);
    	}    		
	}