<?php
    class Users_model extends CI_Model {

        private $tb_name;
		private $tb_tmp_name;

        function __construct()
        {
            parent::__construct();
            $this->load->model('db_model');
			
			$this->tb_name =  $this->config->item('TABLE_USERS');
			$this->tb_tmp_name =  $this->config->item('TABLE_TMP_USERS');
        }

        public function get_user_info($user_id = NULL, $is_all = FALSE)
        {
            $where = ' WHERE 1=1 ';

            if ($is_all === FALSE)
            {
                $where .= ' AND status = 1 ';
            }

            if (!is_null($user_id))
            {
                $where .= ' AND id = '.intval($user_id).' ';
            }

            $sql = "SELECT * FROM ".$this->tb_name." $where";
			
            return is_null($user_id) ? $this->db_model->select($sql) : $this->db_model->select_one($sql);
        }
		
		public function get_info_from($info, $user_id = NULL)
        {
			$where = 'WHERE 1=1';
 			
 			if (!is_null($user_id))
			{
				$where .= ' AND user_id = '.intval($user_id);
			}
			
 			
			if (!empty($info['content']))
            {
                $where .= " AND (username LIKE BINARY '%{$info['content']}%' OR email LIKE BINARY '%{$info['content']}%'  )";
            }
			
			
            $sql = "SELECT * FROM ".$this->tb_name." $where";
			//echo $sql;
            return $this->db_model->select($sql);
        }
		
        public function get_user_info_from_username($username = NULL)
        {
            $sql = "SELECT * FROM ".$this->tb_name." WHERE username='$username'";
            return $this->db_model->select_one($sql);
        }
		
		public function get_user_info_key()
        {
			$ret = array();
			$tmp = $this->get_user_info();
			foreach ($tmp as $v)
			{
				$ret[$v['id']] = $v['app_name'];
			}
			return $ret;
        }

        public function add_user($info)
        {
            $check_sql = 'SELECT * FROM '.$this->tb_name." WHERE username='{$info['username']}'";
            if ($this->db_model->select_one($check_sql))
            {
                return 0;
            }
            else
            {
                return $this->db_model->insert($info, $this->tb_name);
            }
        }

        public function modify_user($info, $user_id)
        {
            if (empty($info) || empty($user_id))
            {
                return FALSE;
            }
            else
            {
                return $this->db_model->update($info, array('id' => $user_id), $this->tb_name);
            }
        }

        function del_user($user_id, $complete = FALSE)
        {
            if (is_array($user_id))
            {
                $where = 'id IN ('.implode(', ', $user_id).')';
            }
            else
            {
                $where = "id = $user_id";
            }

            if ($complete === FALSE)
            {
                $info = array('status' => 2);
                return $this->db_model->update($info, $where, $this->tb_name);
            }
            else
            {
                $sql = 'DELETE FROM '.$this->tb_name.' WHERE '.$where;
                return $this->db_model->delete($where);
            }
        }
		
		public function add_tmp_user($info)
        {
            return $this->db_model->insert($info, $this->tb_name);
        }

        public function encode_user_pwd($str)
        {
            return md5($str);
        }
    }
