<?php
    class Bill_model extends CI_Model {

		private $_bill_tb;
		private $_bill_detail_tb;

        function __construct()
        {
            parent::__construct();
			$this->load->model('db_model');

			$this->_bill_tb = $this->config->item('TABLE_BILL');
			$this->_bill_detail_tb = $this->config->item('TABLE_BILL_DETAIL');
        }

        public function get_info($bill_id = NULL, $user_id = NULL)
        {
			$where = 'WHERE 1=1 ';
            if (!is_null($bill_id))
            {
                $where .= ' AND '.$this->_bill_tb.'.bill_id = '.intval($bill_id);
            }
			
			if (!is_null($user_id))
			{
				$where .= ' AND '.$this->_bill_tb.'.user_id = '.intval($user_id);
			}
			

            $sql = "SELECT * FROM ".$this->_bill_tb." $where order by bill_id desc";
            
            return is_null($bill_id) ? $this->db_model->select($sql) : $this->db_model->select_one($sql);
        }
		
		public function get_from($user_id = NULL)
        {
			$where = 'WHERE 1=1';

			if (!is_null($user_id))
			{
				$where .= ' AND apply_user_id = '.intval($user_id);
			}

            $sql = "SELECT pj_name,project_no FROM ".$this->_gs_from." $where order by project_no desc";

			$data = array();
			if ($result = $this->db_model->select($sql))
			{
				foreach($result as $v)
				{
					$data[$v['project_no']] = $v['project_no'];
				}
			}
			return $data;

        }
		
		public function get_longnum($FROM = NULL)
        {
			$where = 'WHERE 1=1';

			
			if (!is_null($FROM))
			{
				$where .= ' AND project_no = '.intval($FROM);
			}
			else
				return -1;

            $sql = "SELECT longcode FROM ".$this->_gs_from." $where";

			
			if ($result = $this->db_model->select($sql))
			{
				return $result[0]['longcode'];
			}
			else 
				return -1;

        }
		
		
		public function get_info_from($info, $user_id = NULL)
        {
			$where = 'WHERE 1=1';
            
			if (isset($info['gs_status']))
			{
				$where .= ' AND gs_status = '.$info['gs_status'];
			}
			
			if (!is_null($user_id))
			{
				$where .= ' AND apply_user_id = '.intval($user_id);
			}
			
			if (!empty($info['content']))
            {
                $where .= " AND (gsend_name LIKE '%{$info['content']}%' OR project_no LIKE '%{$info['content']}%' ) ";
            }

            $sql = "SELECT * FROM ".$this->_gs_tb." $where order by mt_date DESC";
			
            return $this->db_model->select($sql);
        }		

		public function add($info)
		{
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
				return $this->db_model->update($info, array('gsend_id' => $gs_id), $this->_gs_tb);
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

			
			return $this->db_model->delete($sql);
        }
		
		public function filter_phone($phone_list)
		{
			$data = array();
			foreach ($phone_list as $v)
			{
				$tmpphone = trim($v);
				//if (strlen($tmpphone) == 11  &&  is_numeric($tmpphone))
				if ( is_numeric($tmpphone))
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
		
		public function get_black_info( $mobile = NULL)
        {
			$where = 'WHERE 1=1';
            
			if (!is_null($mobile))
			{
				$where .= ' AND mobile = '."$mobile";
			}
			

            $sql = "SELECT * FROM ".$this->_gs_black." $where ";
			
			
            return $this->db_model->select($sql);
        }
		
		public function addblack($info)
		{

			return $this->db_model->insert($info, $this->_gs_black,FALSE);			
		}
		
		public function addblack_new($info)
		{
			return $this->db_model->insert_black($info, $this->_gs_black,FALSE);	
//			return $this->db_model->insert($info, $this->_gs_black,FALSE);			
		}		
		
		public function delblack($mobile,$from)
		{
			
				$where = "mobile = $mobile and msgfrom = $from ";
			

			$sql = 'DELETE FROM '.$this->_gs_black.' WHERE '.$where;

			
			return $this->db_model->delete($sql);
			
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
