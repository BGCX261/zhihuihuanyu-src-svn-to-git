<?php
    class Task_model extends CI_Model {

		private $_task_tb;

        function __construct()
        {
            parent::__construct();
			$this->load->model('db_model');

			$this->_task_tb = $this->config->item('TABLE_TASK');
        }

        public function get_info($task_id = NULL)
        {
			$where = 'WHERE 1=1';
            if (!is_null($task_id))
            {
                $where .= ' AND id = '.intval($task_id);
            }

            $sql = "SELECT * FROM ".$this->_task_tb." $where";

            return is_null($task_id) ? $this->db_model->select($sql) : $this->db_model->select_one($sql);
        }
		
		public function get_info_from($info)
        {
			$where = 'WHERE 1=1';
            
			if (isset($info['status']))
			{
				$where .= ' AND status = '.$info['status'];
			}
			
			if (!empty($info['content']))
            {
                $where .= " AND (task_name LIKE BINARY '%{$info['content']}%' OR project_no LIKE BINARY '%{$info['content']}%')";
            }
			
			if (!empty($info['project_no']))
			{
				$where .= " AND project_no IN (".implode(',', $info['project_no']).")";
			}
			
            $sql = "SELECT * FROM ".$this->_task_tb." $where";
			
            return $this->db_model->select($sql);
        }

		public function add($info)
		{
			if (empty($info['task_name']))
			{
				return FALSE;
			}

			$check_sql = 'SELECT * FROM '.$this->_task_tb." WHERE task_name='{$info['task_name']}'";
			if ($this->db_model->select_one($check_sql))
			{
				return 0;
			}
			else
			{
				return $this->db_model->insert($info, $this->_task_tb);
			}
		}

		public function update($info, $task_id)
		{
			if (empty($info) || empty($task_id))
			{
				return FALSE;
			}
			else
			{
				return $this->db_model->update($info, array('id' => $task_id), $this->_task_tb);
			}
		}

        public function del($task_id)
        {
			if (is_array($task_id))
			{
				$where = 'id IN ('.implode(', ', $task_id).')';
			}
			else
			{
				$where = "id = $task_id";
			}

			$sql = 'DELETE FROM '.$this->_task_tb.' WHERE '.$where;
			return $this->db_model->delete($where);
        }
    }
