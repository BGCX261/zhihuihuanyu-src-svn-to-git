<?php
    class Data_query_model extends CI_Model {

		private $_num_each_page = 100;

        function __construct()
        {
            parent::__construct();
			$this->load->model('db_model');

			$this->_channel_tb = $this->config->item('TABLE_CHANNEL');
       }

		public function query_day_send($info)
		{
			$where = ' WHERE 1=1 ';
			$getdate = $info['getdate'];
			$tablename = "SLOG$getdate";		
						
			if ($charge = $info['charge'])
			{
				$where .= " AND charge = '$charge'";
			}
			
			if ($src_no = $info['src_no']) {
				if($info['sql_like'] && 0 == strcmp('sql_like_src_no', $info['sql_like'][0])) {
					$where .= " AND src LIKE '$src_no%'";
				}else {
					$where .= " AND src='$src_no' ";
				}
			}

			if ($orderby = $info['orderby'])
			{
				$order = " ORDER BY $orderby";
			}
						
			if ($project_no = $info['project_no'])
			{
				$where .= " AND msgfrom IN ($project_no) ";
			}
			
			if(isset($info['app_no'])) {
				$where .= " AND service in ('".$info['app_no']."')";
			}else {
				$this->load->library('libcommon');
				$service = $this->libcommon->get_sql_in_string($this->get_total_service_code());
				$where .= " AND service in (".$service.")";
			}	
			
			//echo 'model.'.$info['page'].'.over';
			$pages = empty($info['page']) ? 0 : ($info['page']-1);
			$offset = $this->_num_each_page * $pages;
			$rows = $this->_num_each_page;
			$limit = " LIMIT $offset, $rows ";

			$sqlfield = implode(',', $info['showfield']);
			$str_sqlfield = empty($sqlfield) ? 'charge,stat_rpt' : 'charge,stat_rpt,'.$sqlfield;

			$sql = "SELECT $str_sqlfield FROM $tablename $where $order $limit";
			$sql2 = "SELECT count(id) as total_rows FROM $tablename $where";

			$result['result'] = $this->db_model->select($sql, 'query');
			$tmpresult = $this->db_model->select_one($sql2, 'query');

			$result['total_rows'] = $tmpresult['total_rows'];
			$result['total_pages'] = ceil($tmpresult['total_rows'] / $this->_num_each_page);
			
			return $result;
		}
		
		public function get_total_service_code()
		{
			$this->load->model('channel_model');
			$channel_info = $this->channel_model->get_info();
			$service_code = array();
			foreach($channel_info as $one_channel) {
				array_push($service_code, $one_channel['app_no']);
			}
			return $service_code;
		}

		public function query_day_recieve($info)
		{
			$where = ' WHERE 1=1';
			$getdate = $info['getdate'];
			$tablename = "RLOG$getdate";

			if ($charge = $info['charge'])
			{
				$where .= " AND charge = '$charge'";
			}

			if ($src_no = $info['src_no']) {
				if($info['sql_like'] && 0 == strcmp('sql_like_src_no', $info['sql_like'][0])) {
					$where .= " AND dest LIKE '$src_no%'";
				}else {
					$where .= " AND dest='$src_no' ";
				}
			}

			if ($orderby = $info['orderby'])
			{
				$order = " ORDER BY $orderby";
			}

			$pages = empty($info['page']) ? 0 : ($info['page']-1);
			$offset = $this->_num_each_page * $pages;
			$rows = $this->_num_each_page;
			$limit = " LIMIT $offset, $rows ";

			$sqlfield = implode(',', $info['showfield']);
			$str_sqlfield = empty($sqlfield) ? 'charge' : 'charge,'.$sqlfield;

			$sql = "SELECT $str_sqlfield FROM $tablename $where $order $limit";
			$sql2 = "SELECT count(id) as total_rows FROM $tablename $where";
			$result['result'] = $this->db_model->select($sql, 'query');
			$tmpresult = $this->db_model->select_one($sql2, 'query');
			$result['total_rows'] = $tmpresult['total_rows'];
			$result['total_pages'] = ceil($tmpresult['total_rows'] / $this->_num_each_page);
			
			return $result;
		}

		public function query_month($info)
		{
			$where = ' WHERE 1=1 ';
			$getdate = $info['getdate'];
			$tablename = "QXT_RPT_$getdate";

			if ($src_no = $info['src_no']) {
				if($info['sql_like'] && 0 == strcmp('sql_like_src_no', $info['sql_like'][0])) {
					$where .= " AND dest LIKE '$src_no%'";
				}else {
					$where .= " AND dest='$src_no' ";
				}
			}
			
			if ($project_no = $info['project_no']) { // from
				$where .= " AND mark = '$project_no'";
			}

			/*
			if(isset($info['app_no'])) {
				$where .= " AND service in ('".$info['app_no']."')";
			}else {
				$this->load->library('libcommon');
				$service = $this->libcommon->get_sql_in_string($this->get_total_service_code());
				$where .= " AND service in (".$service.")";
			}*/			

			if (($datetime_s = $info['day_s']) && ($datetime_e = $info['day_e'])) {
				$where .= " AND date >= '$getdate{$datetime_s}' AND date <= '$getdate{$datetime_e}' ";
			}

			$result['showfield'] = array();
			
			// group by clause. view-in-web => field-in-db
			$group_by_arr = array('date'=>'date', 'from'=>'mark', 'longcode'=>'dest');
			$tmp = array();
			$tmp['date'] = 'date';// 默认以日期分组
			$group = ' GROUP BY date ';
			if (!empty($info['showfield'])) {
				foreach ($info['showfield'] as $v) {
					if (array_key_exists($v, $group_by_arr)) {
						$tmp[$v] = $group_by_arr[$v];
					}
				}
				$group .= ', '.implode(',', array_values($tmp));
			}
			$result['showfield'] = array_merge($result['showfield'], array_values($tmp));
			
			// order by clause.
			$order = empty($info['orderby']) ? " ORDER BY date" : " ORDER BY ".implode(',', $info['orderby']);

			// page to.
			$pages = empty($info['page']) ? 0 : ($info['page']-1);
			$offset = $this->_num_each_page * $pages;
			$rows = $this->_num_each_page;
			$limit = " LIMIT $offset, $rows";
			
			$mustbe_query = array(
					's_cnt_total' => 'sum(cnt_total) AS s_cnt_total', 's_cnt_gw' => 'sum(cnt_gw) AS s_cnt_gw', 's_cnt_rpt' => 'sum(cnt_rpt) AS s_cnt_rpt',	's_smo_total'	=> 'sum(smo_total) AS s_smo_total'
					);			
			$result['showfield'] = array_merge($result['showfield'], array_keys($mustbe_query));
			$tmp = array_merge($tmp, $mustbe_query);
			$str_sqlfield = implode(',', array_values($tmp));

			$sql = "SELECT $str_sqlfield FROM $tablename $where $group $order $limit";
			$sql2 = "SELECT $str_sqlfield FROM $tablename $where $group";
//			echo $sql;
			
			$result['result'] = $this->db_model->select($sql, 'query_month');
			$tmpresult = $this->db_model->select($sql2, 'query_month');			
			$result['total_rows'] = count($tmpresult);
			$result['total_pages'] = ceil($result['total_rows'] / $this->_num_each_page);
			
			return $result;
		}
    }
