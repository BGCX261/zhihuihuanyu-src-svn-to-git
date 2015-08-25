<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/************************************************
 ** @author diwei
 ** @class description 重写的Controller基类 实现权限控制
 ************************************************/
	class Base_Controller extends CI_Controller {
		
		protected $Global_user_id;

		function __construct()
		{
			parent::__construct();

			if (!$this->access->is_login())
			{
				echo '<script>top.location.href="'.site_url('welcome').'"</script>';
				exit;
			}
			else
			{
				$this->Global_user_id = $this->access->get_user_id();
				//Get the current class and method
				$cur_class = $this->router->class;
				$cur_method = $this->router->method;
				
				if ($this->access->acl_check($cur_class, $cur_method) === FALSE)
				{
					$this->access->logout();
					echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
					echo '您没有访问权限或者登陆信息过期<br><a href="javascript:top.location.href=\''.site_url('welcome').'\'">返回</a>';
					exit;
				}
			}
		}
	}
