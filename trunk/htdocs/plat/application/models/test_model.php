<?php
    class Test_model extends CI_Model {

		private $_gs_tb;
		private $_mms_gs_tb;

        function __construct()
        {
            parent::__construct();
			$this->load->model('db_model');

			$this->_gs_tb = $this->config->item('TABLE_GROUP_SEND');
			$this->_mms_gs_tb = $this->config->item('TABLE_MMS_GROUP_SEND');
        }

        public function get_info($gs_id = NULL, $user_id = NULL)
        {
			$where = 'WHERE 1=1';
            if (!is_null($gs_id))
            {
                $where .= ' AND id = '.intval($gs_id);
            }
			
			if (!is_null($user_id))
			{
				$where .= ' AND user_id = '.intval($user_id);
			}

            $sql = "SELECT * FROM ".$this->_gs_tb." $where order by app_date desc";

            return is_null($gs_id) ? $this->db_model->select($sql) : $this->db_model->select_one($sql);
        }
		
		public function get_info_from($info, $user_id = NULL)
        {
			$where = 'WHERE 1=1';
            
			if (isset($info['gs_status']))
			{
				$where .= ' AND status = '.$info['gs_status'];
			}
			
			if (!is_null($user_id))
			{
				$where .= ' AND user_id = '.intval($user_id);
			}
			
			if (!empty($info['content']))
            {
                $where .= " AND (gs_name LIKE BINARY '%{$info['content']}%' OR project_no LIKE BINARY '%{$info['content']}%' OR longcode LIKE BINARY '%{$info['content']}%' OR app_no = '{$info['content']}' )";
            }

			if (!empty($info['channel_no']))
			{
				$where .= "AND channel_no = '{$info['channel_no']}'";
			}
			
            $sql = "SELECT * FROM ".$this->_gs_tb." $where";
			
            return $this->db_model->select($sql);
        }		

		public function add($info)
		{
			if (empty($info['phone_list']))
			{
				return FALSE;
			}

			return $this->db_model->insert($info, $this->_gs_tb);			
		}

		public function update($info, $gs_id)
		{
			if (empty($info) || empty($gs_id))
			{
				return FALSE;
			}
			else
			{
				return $this->db_model->update($info, array('id' => $gs_id), $this->_gs_tb);
			}
		}

        public function del($gs_id)
        {
			if (is_array($gs_id))
			{
				$where = 'id IN ('.implode(', ', $gs_id).')';
			}
			else
			{
				$where = "id = $gs_id";
			}

			$sql = 'DELETE FROM '.$this->_gs_tb.' WHERE '.$where;
			return $this->db_model->delete($where);
        }
		
		public function filter_phone($phone_list)
		{
			$data = array();
			foreach ($phone_list as $v)
			{
				$tmpphone = trim($v);
				if (strlen($tmpphone) == 11  &&  is_numeric($tmpphone))
				{
					$data[] = $tmpphone;
				}
			}
			
			if (!empty($data))
				return implode("\r\n", $data);
			else
				return '';
		}
		
		public function check_phonefile($phonefile)
		{
			return TRUE;
			$data = array();
			foreach ($phone_list as $v)
			{
				$tmpphone = trim($v);
				if (strlen($tmpphone) == 11  &&  is_numeric($tmpphone))
				{
					$data[] = $tmpphone;
				}
			}
			
			if (!empty($data))
				return implode("\r\n", $data);
			else
				return '';
		}
		
		public function check_mmsfile($mmsfile)
		{
			return TRUE;
		}
		
		public function mms_add($info)
		{
			return $this->db_model->insert($info, $this->_mms_gs_tb, true);			
		}
		
		public function get_mms_info($gs_id = NULL, $user_id = NULL)
        {
			$where = 'WHERE 1=1';
            if (!is_null($gs_id))
            {
                $where .= ' AND id = '.intval($gs_id);
            }
			
			if (!is_null($user_id))
			{
				$where .= ' AND user_id = '.intval($user_id);
			}

            $sql = "SELECT * FROM ".$this->_mms_gs_tb." $where order by app_date desc";

            return is_null($gs_id) ? $this->db_model->select($sql) : $this->db_model->select_one($sql);
        }
		
		public function mms_update($info, $gs_id)
		{
			if (empty($info) || empty($gs_id))
			{
				return FALSE;
			}
			else
			{
				return $this->db_model->update($info, array('id' => $gs_id), $this->_mms_gs_tb);
			}
		}
    }
