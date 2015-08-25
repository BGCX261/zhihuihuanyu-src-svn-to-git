<?php
	class Data_query extends Base_Controller {
  
		private $_operator_list = array(
				0 => '全部',
				1 => '移动',
				2 => '联通',
				4 => '电信',
			);
		private $_operator_phone_list = array(
				1 => array('134', '135', '136', '137', '138', '139', '147', '150', '151', '152', '157', '158', '159', '182', '187', '188'),
				2 => array('130', '131', '132', '145', '155', '156', '186'),
				4 => array('133', '153', '180', '189')
			);


		//城市city  省份province  项目编号project_no  时间msgtime  通道号码src_no  下行内容msg  应用编号app_no
		private $_map_data_field = array(
				'msgtime' => '时间',
				'date' => '日期',
				'city' => '城市',
				'province' => '省份',
				'msg' => '短信内容',
				'msgfrom' => 'from值',
				'mark' => 'from值',
				'from' => 'from值',
				'src' => '长号码',
				'longcode' => '长号码',
				'dest' => '长号码',
				'app_no' => '应用编号',
				'service' => '应用编号',
				'charge' => '手机号',
				's_cnt_total' => '总下行',
				's_cnt_gw' => '网关成功下行',
				's_cnt_rpt' => '用户成功接收',
				's_smo_total' => '总上行',
				'stat_rpt' => '状态报告',
			);

		function __construct()
		{
			parent::__construct();
			$this->load->model('data_query_model', 'dqm');
			$this->load->model('project_model', 'pj');
			
			$this->_contract_path = $this->config->item('contract_path');
		}

		public function index()
		{
			
		}

		public function day_query()
		{
			$data = array();
			if ($this->access->is_admin()) {
				$data['pj'] = $this->pj->get_info();
			}else { 
				$data['pj'] = $this->pj->get_info(NULL, $this->Global_user_id);
			}			

			$data['param'] = array();
			$data['err_msg'] = '';						
			
			if (false === $this->input->post('project_no')) {
				$this->load->view('day_query_view', $data);
				return;
			}else {
				$showfield = $this->input->post('showfield');
				$query_type = $this->input->post('query_type');
				$param['check_field'] = $showfield;
				$param['query_type'] = $query_type;
				
				if (false === empty($showfield) && $query_type !== FALSE) {
					$param['showfield'] = $this->_filter_showfield($showfield, $query_type);
				}else {
					$param['showfield']  = array();
				}
				
				if (($cur_page = $this->input->post('jumppage'))  || ($cur_page = $this->input->get('page')) ) {
					$param['page'] = $cur_page;
				}else {
					$param['page'] = 1;
				}

				$year = $this->input->post('year');
				$month = $this->input->post('month');
				$day = $this->input->post('day');

				$param['getdate'] = sprintf('%4d%02d%02d', $year, $month, $day);
				$param['year'] = $year;
				$param['month'] = $month;
				$param['day'] = $day;
				
				$param['project_no'] = $this->input->post('project_no'); //from
				if('all' == $param['project_no']) {
					//假如为全部from，则应构造from列表
					$project_no = array();
					foreach($data['pj'] as $one_project) {
						array_push($project_no, $one_project['project_no']);
					}					
					$this->load->library('libcommon');
					$param['project_no'] = $this->libcommon->get_sql_in_string($project_no);
				}else {
					$param['project_no'] = "'".$param['project_no']."'";					
				}
				
				$param['src_no'] = $this->input->post('src_no'); //longnum
				$param['sql_like'] = $this->input->post('sql_like');
				$param['charge'] = $this->input->post('charge');
				$param['stat_rpt'] = '1';
				$param['orderby'] = 'msgtime';

				if(2==$query_type) {
					if(false === is_numeric($this->input->post('src_no')) ) {
						$data['err_msg'] = '当查询上行数据时，必须输入长号码';						
						$data['showfield'] = $param['showfield'];
						$data['filed_map'] = $this->_map_data_field;
						$data['param'] = $param;
						$this->load->view('day_query_view', $data);
						return;
					}
				}
								
				switch ($query_type) {
					case 1:
						$data['list'] = $this->dqm->query_day_send($param);						
						array_unshift($param['showfield'], 'stat_rpt');
						break;
					case 2:
						$data['list'] = $this->dqm->query_day_recieve($param);
						break;
					default:
						$data['list'] = array();
				}
				
				if (empty($data['list']['result'])) {
					$data['err_msg'] = '查询无数据';
				}
				
				array_unshift($param['showfield'], 'charge');
				$data['showfield'] = $param['showfield'];
				$data['filed_map'] = $this->_map_data_field;
				$data['param'] = $param;
				$this->load->view('day_query_view', $data);
			}
		}

		public function month_query()
		{
			$data = array();
			if ($this->access->is_admin()) {
				$data['pj'] = $this->pj->get_info();
			}else { 
				$data['pj'] = $this->pj->get_info(NULL, $this->Global_user_id);
			}
						
			$data['param'] = array();
			$data['err_msg'] = '';	
			if (false === $this->input->post('src_no')) {
				$this->load->view('month_query_view', $data);
				return;
			}

			$param['sql_like'] = $this->input->post('sql_like');
			$showfield = $this->input->post('showfield');
			$param['check_field'] = $showfield;
			$param['showfield'] = $showfield;
			$data['err_msg'] = '';

			if (($cur_page = $this->input->post('jumppage'))  || ($cur_page = $this->input->get('page')) ) {
				$param['page'] = $cur_page;
			}else {
				$param['page'] = 1;
			}

			$year = $this->input->post('year');
			$month = $this->input->post('month');
			$s_day = $this->input->post('s_day'); // start
			$e_day = $this->input->post('e_day'); // end
			$param['getdate'] = sprintf('%4d%02d', $year, $month);
			$param['day_s'] = sprintf('%02d', $s_day);
			$param['day_e'] = sprintf('%02d', $e_day);
			
			if(false === is_numeric($this->input->post('src_no')) 
				&& false === is_numeric($this->input->post('project_no'))) {
				$data['err_msg'] = '长号码和from值至少输入一项，才能进行查询';						
				$data['showfield'] = $param['showfield'];
				$data['filed_map'] = $this->_map_data_field;
				$data['param'] = $param;
				$this->load->view('month_query_view', $data);
				return;
			}else {
				$param['src_no'] = $this->input->post('src_no'); //longnum				
				$param['project_no'] = $this->input->post('project_no'); //from
			}
			
			$param['orderby'] = array();
			$data['list'] = $this->dqm->query_month($param);
			if (empty($data['list']['result'])) {
				$data['err_msg'] = '查询无数据';
			}
			
			$data['filed_map'] = $this->_map_data_field;
			$data['param'] = $param;

			$this->load->view('month_query_view', $data);
		}

		private function _filter_showfield($field, $type)
		{
			$all_filter = array('city', 'province');
			$day_query[1] = array(
					'project_no' => 'msgfrom', 'msgtime' => 'msgtime' , 'src_no' => 'src', 'msg' => 'msg', 'app_no' => 'service'
			);

			$day_query[2] = array(
					'msgtime' => 'msgtime',  'src_no' => 'dest', 'msg' => 'msg'
			);

			$tmp = array_diff($field, $all_filter);

			foreach ($tmp as $k => $v)
			{
				if (array_key_exists($v, $day_query[$type]))
				{
					$tmp[$k] = $day_query[$type][$v];
				}
				else
				{
					unset($tmp[$k]);
				}
			}
			return $tmp;
		}
	}