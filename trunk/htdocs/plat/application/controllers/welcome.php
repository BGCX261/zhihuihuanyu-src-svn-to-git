<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('access');
	}

	public function index()
	{
		if ($this->access->is_login())
        {
			redirect('home');
        }
        else
        {
			$this->config->load('base_config');
			$data['title'] = $this->config->item('WEBSITE_TITLE');
			$this->load->view('index', $data);
        }
	}

	public function login()
	{
		$username = $this->input->post('username');
        $password = $this->input->post('password');

        if (empty($username) || empty($password))
        {
            $data['errors'] = '用户名或者密码为空';
        }
        else
        {
            if ($this->access->valid_login($username, $password))
            {
                $this->load->model('user_model', 'users');
                $info = array('lastvisit_date' => date('YmdHis'));

                $this->users->modify_user($info, $this->access->get_user_id());

                redirect(site_url('home'));
            }
            else
            {
            	$data['errors'] = '用户名或者密码错误';
            }
        }
		$data['title'] = $this->config->item('WEBSITE_TITLE');
        $this->load->view('index', $data);
	}
    //申请权限
    //edit by jingchen
    public function apply_for_permission(){
   	    $data['title'] = '申请权限';
        $this->load->view('apply_permission', $data);
    }
    
    public function select_self_file(){
   	    $data['title'] = '选择文件';
        $this->load->view('select_file', $data);
    }    
    //发送邮件
    //edit by jingchen
    public function send_apply_email(){
        $email=$_POST['email'];
        $this->load->library('email');
        $this->email->from('qxtgateway@staff.sina.com.cn', 'gateway');
        $this->email->to($email);
        $this->email->subject("企信通通道权限申请表");
       	$this->email->message("
{unwrap}1.微博相关业务，请将附件中的表格填写完成后将电子档发送至 yingchen@staff.sina.com.cn 时请将最终打印版交由部门总监签字后，提交马英宸(理想国际大厦15层 分机3281)或 朱晨光（3268）{/unwrap} 
{unwrap}2.非微博相关业务，请将附件中的表格填写完成后将电子档发送至 zhaojie2@staff.sina.com.cn 时请将最终打印版交由部门总监签字后，提交赵洁(理想国际大厦15层 分机5845){/unwrap} 
{unwrap}我们将尽快处理您的请求，谢谢！{/unwrap}
           ");
        $filename="application_form.docx"; 
        //var_dump($filename);
        //exit; 
        $this->email->attach($filename,$disposition = 'attachment');   
        $this->email->send();
        redirect();
        
    }
    

	public function regiest()
	{
		if ( ($name = $this->input->post('name')) && ($dept = $this->input->post('dept')) && ($phone = $this->input->post('phone')) && ($ext = $this->input->post('ext')) && ($email = $this->input->post('email')) )
		{
			if ($add_name = $this->input->post('add_name'))
			{
				$info['add_name'] = $add_name;
			}
			
			if ($add_phone = $this->input->post('add_phone'))
			{
				$info['add_phone'] = $add_phone;
			}
			
			$info['name'] = $name;
			$info['dept'] = $dept;
			$info['phone'] = $phone;
			$info['ext'] = $ext;
			$info['email'] = $email;
			
			echo  '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			$this->load->model('users_model', 'user');
			if ($this->user->add_tmp_user($info))
			{				
				echo '注册成功，请等候管理员审批';
			}
			else
			{
				echo '注册失败，请联系管理员';
			}
		}
		else
		{
			$this->load->view('user_regiest_view');
		}
	}
}