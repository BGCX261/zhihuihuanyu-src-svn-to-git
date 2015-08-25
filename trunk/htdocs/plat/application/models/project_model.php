<?php
    class Project_model extends CI_Model {

		private $_pj_tb;
		private $_map_project_channel_tb;

        function __construct()
        {
            parent::__construct();
			$this->load->model('db_model');

			$this->_pj_tb = $this->config->item('TABLE_PROJECT');
			$this->_channel_tb = $this->config->item('TABLE_CHANNEL');
			$this->_map_project_channel_tb = $this->config->item('TABLE_MAP_PROJ_CHANNEL');
        }

        public function get_info($pj_id = NULL, $apply_user_id = NULL, $show_del = NULL)
        {
			$where = 'WHERE 1=1';
            if (!is_null($pj_id))
            {
                $where .= ' AND id = '.intval($pj_id);
            }
			
			if (!is_null($apply_user_id))
			{
				$where .= ' AND apply_user_id = '.intval($apply_user_id);
			}

			if (!is_null($show_del))
			{
				$where .= ' AND status !=4 ';
			}
			
			$sql = "SELECT * FROM ".$this->_pj_tb." $where" ." order by id";

            return is_null($pj_id) ? $this->db_model->select($sql) : $this->db_model->select_one($sql);
        }
        
        public function get_appno($pj_id )
        {
			$where = 'WHERE 1=1';
            if (!is_null($pj_id))
            {
                $where .= ' AND project_no = '.intval($pj_id);
            }
			
			
            $sql = "SELECT * FROM ".$this->_channel_tb." $where" ;
			
			$data =  $this->db_model->select($sql);
			
            return $data['app_no'] ;
        }
        
        public function get_channels_in_proj($pj_id )
        {
			$where = 'WHERE 1=1';
            if (!is_null($pj_id))
            {
                $where .= ' AND pj_id = '.intval($pj_id);
            }

            $sql = "SELECT * FROM ".$this->_map_project_channel_tb." $where" ;
			
			$data = $this->db_model->select($sql);
			if(empty($data) || count($data) == 0) {
				$data = array(0=>array('channel_id'=>0, 'isp_type'=>'', 'longcode'=>'') );
			}

			return $data;
        }       
		
		public function get_info_from($info, $user_id = NULL)
        {
			$where = 'WHERE 1=1';
            
			if (isset($info['pj_status']))
			{
				$where .= ' AND status = '.$info['pj_status'];
			}

        	if (isset($user_id) )
			{
				$where .= ' AND apply_user_id = '.$user_id;
			}
						
			if (!empty($info['content']))
            {
                $where .= " AND (pj_name LIKE '%{$info['content']}%' OR project_no LIKE '%{$info['content']}%' )";
            }
			
            $sql = "SELECT * FROM ".$this->_pj_tb." $where" ." order by project_no";
			//echo $sql;
            return $this->db_model->select($sql);
        }
		
		public function get_info_key_project_no($user_id = NULL)
		{
			$data = array();
			$info = $this->get_info(NULL, $user_id);
			
			if (!empty($info))
			{
				foreach ($info as $v)
				{
					$data[$v['project_no']] = $v;
				}
			}
			
			return $data;
		}

		public function add($info)
		{
			if (empty($info['pj_name']))
			{
				return FALSE;
			}

			$check_sql = 'SELECT * FROM '.$this->_pj_tb." WHERE pj_name='{$info['pj_name']}'";
			if ($this->db_model->select_one($check_sql))
			{
				return 0;
			}
			else
			{
				return $this->db_model->insert($info, $this->_pj_tb);
			}
		}

		// 向 db 中添加 该项目使用的通道列表. $info 仅含一行数据，若插入多行，应多次调用本函数。
    	public function add_pj_channels($info)
		{
			if (empty($info['project_no']))
			{
				return FALSE;
			}
			
			return $this->db_model->insert($info, $this->_map_project_channel_tb);
		}
				
		public function update($info, $pj_id)
		{			
			if (empty($info) || empty($pj_id))
			{
				return FALSE;
			}
			elseif (!empty($info['project_no']))
			{
				$check_sql = 'SELECT * FROM '.$this->_pj_tb." WHERE project_no='{$info['project_no']}' and id <> $pj_id";				
				if ($this->db_model->select_one($check_sql))
				{
					return 0;
				}
			}
			
			return $this->db_model->update($info, array('id' => $pj_id), $this->_pj_tb);
		}
		
		public function update_apv($info, $pj_id)
		{			
			if (empty($info) || empty($pj_id))
			{
				return FALSE;
			}
			
			
			return $this->db_model->update($info, array('id' => $pj_id), $this->_pj_tb);
		}
		
        public function del($pj_id)
        {
			if (is_array($pj_id))
			{
				$where = 'id IN ('.implode(', ', $pj_id).')';
			}
			else
			{
				$where = " id = $pj_id ";
			}

			$sql = 'delete from '.$this->_pj_tb.' where '.$where;

			return $this->db_model->delete($sql);
        }
    }
