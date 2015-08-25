<?php
/*********************************************************
 ** @author diwei
 ** @class description 封装的数据库模型 便于统一控制增删改
 *********************************************************/
	class Db_model extends CI_Model {
    
		function __construct()
		{
			parent::__construct();
		}
		
		/*public function direct_run($sql)
		{
			$db_write = $this->load->database($db, TRUE);
			$query = $db_write->query($sql);
			return $query->result_array();
		}*/
		
		public function select($sql, $db = 'read')
		{
			$db_read = $this->load->database($db, TRUE);
			$query = $db_read->query($sql);
			if ($query->num_rows() > 0) {
				return $query->result_array();
			}
			else {
				return false;
			}
		}
		
		public function select_one($sql, $db = 'read')
		{
			$db_read = $this->load->database($db, TRUE);
			$query = $db_read->query($sql);
			if ($query->num_rows() > 0) {
				return $query->row_array();
			}
			else {
				return false;
			}
		}
		
		public function select_page($select, $table, $condition, $db = 'read')
		{
			$db_read = $this->load->database($db, TRUE);
			$sql = 'SELECT SQL_CALC_FOUND_ROWS '.$select.' FROM '.$table.' '.$condition;
			
			$query = $db_read->query($sql);
			$t_query = $db_read->query('SELECT FOUND_ROWS() as total_rows');
			$rows = $t_query->row();
			if ($query->num_rows() > 0) {
				return array('result'=>$query->result_array(), 'total_rows'=>$rows->total_rows);
			}
			else {
				return false;
			}
		}
		
		public function insert($sql, $table = '', $ret_insid = TRUE, $db = 'write')
		{
			$db_write = $this->load->database($db, TRUE);
			
			if ( empty($sql)) {
				return false;
			}
			elseif (is_array($sql) && !empty($table)) {
				$sql = $db_write->insert_string($table, $sql);
			}
			
			$db_write->query($sql);
			
			if ($db_write->affected_rows() != - 1) {
				if ($ret_insid)
				{
					return $db_write->insert_id();
				}
				else
				{
					return true;
				}
			}
			else {
				return false;
			}
		}
		
		public function insert_black($sql, $table = '', $ret_insid = TRUE, $db = 'write')
		{
			$db_write = $this->load->database($db, TRUE);
			
			if ( empty($sql)) {
				return false;
			}
			elseif (is_array($sql) && !empty($table)) {
				$sql = $db_write->insert_string($table, $sql);
			}
			
			$db_write->query_black($sql);
			
			if ($db_write->affected_rows() != - 1) {
				if ($ret_insid)
				{
					return $db_write->insert_id();
				}
				else
				{
					return true;
				}
			}
			else {
				return false;
			}
		}		
		public function update($sql, $where = array(), $table = '', $db = 'write')
		{
			$db_write = $this->load->database($db, TRUE);
			
			if (empty($sql))
			{
				return false;
			}
			elseif (! empty($table) && ! empty($where) && is_array($sql)) {
				$sql = $db_write->update_string($table, $sql, $where);
			}
			
			$db_write->query($sql);
			
			if ($db_write->affected_rows() != - 1) {
				return true;
			}
			else {
				return false;
			}
		}
		
		public function replace($sql, $table = '', $db = 'write')
		{
			$db_write = $this->load->database($db, TRUE);
			
			if ( empty($sql) ) {
				return false;
			}
			elseif (is_array($sql) && !empty($table)) {
				foreach ($sql as $key=>$val) {
					$fields[] = mysql_real_escape_string($key);
					$values[] = $this->_escape($val);
				}
				
				$sql = "REPLACE INTO ".$table." (".implode(', ', $fields).") VALUES (".implode(', ', $values).")";
				
			}
			
			$db_write->query($sql);
			
			if ($db_write->affected_rows() != - 1) {
				return $db_write->affected_rows();
			}
			else {
				return false;
			}
		}
		
		public function delete($sql, $table = '', $db = 'write')
		{
			$db_write = $this->load->database($db, TRUE);
			
			if (!empty($table) && is_array($sql)) {
				$db_write->where($sql);
				$db_write->delete($table);
			}
			else {
				$query = $db_write->query($sql);
				
			}
			
			if ($db_write->affected_rows() != - 1) {
				return true;
			}
			else {
				return false;
			}
		}
		
		/** 
		* 生成数组的sql查询in字符串
		* 
		* @access private
		* @param array role_id
		* @return string role_id
		*/
		public function where_in_string($in_array = NULL)
		{
			$in_str = '';
				
			if (is_string($in_array))
			{
				settype($in_array, 'array');
			}
			
			if (count($in_array) > 0)
			{
				$in_str = implode(',', $in_array);
			}

			return $in_str;
		}
		
		private function _escape($str)
		{
			if (is_string($str)) {
				$str = "'".mysql_real_escape_string($str)."'";
			}
			elseif (is_bool($str)) {
				$str = ($str === FALSE) ? 0 : 1;
			}
			elseif (is_null($str)) {
				$str = 'NULL';
			}
			
			return $str;
		}
	}
