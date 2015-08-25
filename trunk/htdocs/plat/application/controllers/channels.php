<?php 
/***************************************************************
 ** @author diwei  20110301
 ** @class description 通道控制器 实现通道列表、增加、修改、删除
 ***************************************************************/

	class Channels extends Base_Controller {

		private $_contract_path;

		function __construct()
		{
			parent::__construct();
			$this->load->model('channel_model', 'channels');

			$this->_contract_path = $this->config->item('contract_path');
		}

		public function index()
		{
			$this->channel_list();
		}

		public function channel_list()
		{
			$data = array();
			$data['c_list'] = $this->channels->get_info();

			$data['mo_mt'] = $this->config->item('MO_MT');
			$data['isp_type'] = $this->config->item('ISP_TYPE');
			
            $this->load->view('channel_list_view', $data);
		}

		public function channel_change($c_id = '')
		{
			$c_id = intval($c_id);

			if ($flag = $this->input->post('isPost') == 'TRUE')
			{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

				if ($company_name = $this->input->post('company_name'))
				{
					$change_info['company_name'] = $company_name;
				}

				if ($channel_name = $this->input->post('channel_name'))
				{
					$change_info['channel_name'] = $channel_name;
				}
				else
				{
					echo '必须输入通道名称名称<br><a href="'.site_url('channels/channel_list').'">返回通道列表</a>';
					exit;
				}

				if ($fin_code = $this->input->post('fin_code'))
				{
					$change_info['fin_code'] = $fin_code;
				}

				if ($contract_begin = $this->input->post('contract_begin'))
				{
					$change_info['contract_begin'] = $contract_begin;
				}

				if ($contract_end = $this->input->post('contract_end'))
				{
					$change_info['contract_end'] = $contract_end;
				}
				
				if ($channel_price = $this->input->post('channel_price'))
				{
					$change_info['channel_price'] = $channel_price;
				}

				if ($max_len = $this->input->post('max_len'))
				{
					$change_info['max_len'] = $max_len;
				}

				if ($project_no = $this->input->post('project_no'))
				{
					$change_info['project_no'] = $project_no;
				}

				if ($gateway = $this->input->post('gateway'))
				{
					$change_info['gateway'] = $gateway;
				}
				
				if ($longcode = $this->input->post('longcode'))
				{
					$change_info['longcode'] = $longcode;
				}

				if ($mo_mt = $this->input->post('mo_mt'))
				{
					$change_info['mo_mt_type'] = $mo_mt;
				}
				
				if ($isp_type = $this->input->post('isp_type'))
				{
					$change_info['isp_type'] = $isp_type;
				}
								
				if ($memo = $this->input->post('memo'))
				{
					$change_info['memo'] = htmlspecialchars($memo);
				}

				if (!empty($_FILES['contract_file']['name']))
				{
					$uploadext = strrchr($_FILES['contract_file']['name'], ".");
					$contract_name = $this->_contract_path.'/'.$c_id.$uploadext;

					if ($_FILES['contract_file']['error'] == 0)
					{
						if ( move_uploaded_file($_FILES['contract_file']['tmp_name'], $contract_name) )
						{
							echo '<br>文档文件上传成功<br>';
							$change_info['have_contract'] = 1;
						}
					}
					else
					{
						echo '<br>上传文件失败，错误代码是:'.$_FILES['contract_file']['error'];
					}
				}
				else
				{
					echo '<br>无文件上传<br>';
				}

				if (empty($c_id))
				{
					if ($c_id = $this->channels->add($change_info))
					{
                        //echo '新增通道成功';
						redirect(site_url('channels/channel_list'));
					}
					else
					{
						echo '新增通道失败';
						exit;
					}
				}
				else
				{
					if ($this->channels->update($change_info, $c_id))
					{
						redirect(site_url('channels/channel_list'));
						//echo '通道更新成功<br><a href="'.site_url('channels/channel_list').'">返回通道列表</a>';
					}
					else
					{
						echo '通道更新失败';
						exit;
					}
				}
			}
			else
			{
				if (empty($c_id))
				{
					$data = array(
								'channel_info' => array(),
								'action' => array(
									'name' => '新增',
									'value' => site_url('channels/channel_change')
								)
							);
				}
				else
				{
					$channel_info = $this->channels->get_info($c_id);
					$data = array(
								'channel_info' => $channel_info,
								'action' => array(
									'name' => '修改',
									'value' => site_url('channels/channel_change/'.$c_id)
								)
							);
				}
				
				$data['mo_mt'] = $this->config->item('MO_MT');
				$data['isp_type'] = $this->config->item('ISP_TYPE');
				
				$this->load->view('channel_info_view', $data);
			}
		}

		public function delete($channel_ids)
		{
			if (!empty($channel_ids))
			{
				if ($this->channels->del($channel_ids))
				{
					redirect(site_url('channels/channel_list'));
				}
			}

			echo 'Delete channel Error';
		}
	}