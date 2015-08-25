<?php
    class Channel_model extends CI_Model {

		private $_channel_tb;

        function __construct()
        {
            parent::__construct();
			$this->load->model('db_model');

			$this->_channel_tb = $this->config->item('TABLE_CHANNEL');
        }

        public function get_info($channel_id = NULL)
        {
			$where = '';
            if (!is_null($channel_id))
            {
                $where .= 'WHERE id = '.intval($channel_id);
            }

            $sql = "SELECT * FROM ".$this->_channel_tb." $where";

            return is_null($channel_id) ? $this->db_model->select($sql) : $this->db_model->select_one($sql);
        }

		public function add($info)
		{
			if (empty($info['channel_name']))
			{
				return FALSE;
			}

			$check_sql = 'SELECT * FROM '.$this->_channel_tb." WHERE channel_name='{$info['channel_name']}'";
			if ($this->db_model->select_one($check_sql))
			{
				return 0;
			}
			else
			{
				return $this->db_model->insert($info, $this->_channel_tb);
			}
		}

		public function update($info, $channel_id)
		{
			if (empty($info) || empty($channel_id))
			{
				return FALSE;
			}
			else
			{
				return $this->db_model->update($info, array('id' => $channel_id), $this->_channel_tb);
			}
		}

        public function del($channel_id)
        {
			if (is_array($channel_id))
			{
				$where = 'id IN ('.implode(', ', $channel_id).')';
			}
			else
			{
				$where = "id = $channel_id";
			}

			$sql = 'DELETE FROM '.$this->_channel_tb.' WHERE '.$where;
			return $this->db_model->delete($sql);
        }
		
		public function get_channels_group_by_ips()
		{
			$sql = 'SELECT * FROM '.$this->_channel_tb;
			
			$data = array('CM'=>array(0=>array('id'=>0, 'channel_name'=>'无') ) , 
						'CU'=>array(0=>array('id'=>0, 'channel_name'=>'无') ), 
						'CT'=>array(0=>array('id'=>0, 'channel_name'=>'无') ), ); 
			
			if ($result = $this->db_model->select($sql))
			{
				foreach($result as $v)
				{	
					if(false !== strpos($v['isp_type'], 'CM') ) {
						$data['CM'][] = $v;
					}
					if(false !== strpos($v['isp_type'], 'CU') ) {
						$data['CU'][] = $v;
					}
					if(false !== strpos($v['isp_type'], 'CT') ) {
						$data['CT'][] = $v;
					}
				}
			}

			return $data;
		}
		
    	public function get_channel_list()
		{
			$sql = 'SELECT * FROM '.$this->_channel_tb;
			
			$data = array();
			if ($result = $this->db_model->select($sql))
			{
				foreach($result as $v)
				{	
					//$data[$v['project_no']] = $v['channel_name'];
				}
			}
			return $data;
		}
				
		public function get_appno_list()
		{
			$sql = 'SELECT project_no,app_no FROM '.$this->_channel_tb;
			
			$data = array();
			if ($result = $this->db_model->select($sql))
			{
				foreach($result as $v)
				{
					$data[$v['project_no']] = $v['app_no'];
				}
			}
			return $data;
		}
		
		/*
		 * gumeng. 2011-10-14 added.
		 */
    	public function get_channel_list_for_day_query()
		{
			$sql = 'SELECT app_no,channel_name FROM '.$this->_channel_tb;
			
			$data = array();
			if ($result = $this->db_model->select($sql))
			{
				foreach($result as $v)
				{
					// 注意：由于很多通道采用相同的appno，故下述方法失败：
					// $data[$v['app_no']] = $v['channel_name'];
					// 因为key有相同啦！
					// 假如以channel_name作为key的话，在view中要注意区分技巧。
					$data[$v['channel_name']] = $v['app_no'];
				}
			}
			return $data;
		}
    }
