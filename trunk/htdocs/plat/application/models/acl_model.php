<?php
/************************************************
 ** @author diwei
 ** @class description 角色、资源以及ACL模型
 ************************************************/
    class Acl_model extends CI_Model {

		private $table_role = 'roles';
		private $table_res = 'resources';
		private $table_rrm = 'role_resource_map';
		private $table_urm = 'user_role_map';

        function __construct()
        {
            parent::__construct();
			$this->load->model('db_model');
        }

		/**
		* 根据用户获取其相应的资源
		*
		* @access public
		* @param numeric user_id
		* @return array  res_id
		*/
		public function get_res_list_from_user($user_id = NULL)
		{
			$res_list = array();
			if (!is_null($user_id))
			{
				if ($roles = $this->get_roles_from_user($user_id))
				{
					$res_list = $this->get_res_list_from_role($roles);
				}
			}

			return $res_list;
		}

		/**
		* 根据用户获取其相应的角色
		*
		* @access public
		* @param numeric user_id
		* @return array  role_id
		*/
		public function get_roles_from_user($user_id = NULL, $detail = FALSE)
		{
			$role_array = array();

			if (!is_null($user_id))
			{
				/*$sql = 'SELECT b.role_name, a.role_id FROM '
					. $this->table_urm . ' AS a,'. $this->table_role.' AS b '
					." WHERE a.user_id = $user_id AND a.role_id = b.id";*/
				$sql = "SELECT role_id,role_name FROM users u,roles r WHERE u.id = $user_id AND r.id = u.role_id";
				if ($result = $this->db_model->select($sql))
				{
					if ($detail === FALSE)
					{
						foreach ($result as $row)
						{
							$role_array[] = $row['role_id'];
						}
					}
					else
					{
						foreach ($result as $row)
						{
							$role_array[] = array('id' => $row['role_id'], 'name' => $row['role_name']);
						}
					}
				}
			}

			return $role_array;
		}

		/**
		* 根据function method获取其对应的资源id
		*
		* @access public
		* @param string  $class $method
		* @return string res_id
		*/
		public function get_res_id_from_controller($class_name, $method_name)
		{
			$sql = "SELECT id FROM ". $this->table_res ." WHERE class = '$class_name' AND method = '$method_name'";
			if ($result = $this->db_model->select_one($sql))
			{
				return $result['id'];
			}
			//if not find res_id, return -1
			return -1;
		}

		/**
		* 根据角色获取其对应的资源id
		*
		* @access public
		* @param array  $roles
		* @return array res_list
		*/
		public function get_res_list_from_role($roles)
		{
			$res_list = array();

			$role_str = $this->db_model->where_in_string($roles);
			$sql = "SELECT res_id FROM ". $this->table_rrm ." WHERE role_id in ($role_str)";

			if ($result = $this->db_model->select($sql))
			{
				foreach ($result as $row)
				{
					$res_list[] = $row['res_id'];
				}
				$res_list = array_unique($res_list);
			}

			return $res_list;
		}
		
		/**
		* 获取用户角色对应的全部列表
		*
		* @access public
		* @param array  $role_id
		* @return array role_list
		*/
		public function get_user_role_list()
		{
			$list = array();
			$sql = "SELECT * FROM ". $this->table_urm;
			if ($result = $this->db_model->select($sql))
			{
				foreach ($result as $row)
				{
					$list[$row['user_id']][] = $row['role_id'];
				}
			}
			return $list;
		}

		/**
		* 获取角色其对应的详细信息
		*
		* @access public
		* @param array  $role_id
		* @return array role_list
		*/
		public function get_role_info($role_id = NULL)
		{
			$where = '';
			if (!is_null($role_id))
			{
				$where = " WHERE id = $role_id ";
			}

			$sql = 'SELECT * FROM '. $this->table_role . $where;
			
			return is_null($role_id) ? $this->db_model->select($sql) : $this->db_model->select_one($sql);
		}

		/**
		* 增加角色
		*
		* @access public
		* @param array  $role_info
		* @return boolean
		*/
		public function add_role($info)
		{
			if ($result = $this->db_model->insert($info, $this->table_role, TRUE))
			{
				return is_numeric($result) ? $result : false;
			}

			return false;
		}

		/**
		* 修改角色
		*
		* @access public
		* @param array  $role_info
		* @return boolean
		*/
		public function modify_role($info, $role_id)
        {
			return $this->db_model->update($info, array('id' => $role_id), $this->table_role);			
        }

		/**
		* 删除角色
		*
		* @access public
		* @param string role_id
		* @return boolean
		*/
		public function del_role($role_id)
        {
			return $this->db_model->delete(array('id' => $role_id), $this->table_role);
        }

        /**
		* 获取资源其对应的详细信息
		*
		* @access public
		* @param array  $role_id
		* @return array role_list
		*/
		public function get_res_info($res_id = NULL)
		{			
			$where = '';
			if (!is_null($res_id))
			{
				$where = " WHERE id = $res_id ";
			}

			$sql = 'SELECT * FROM '. $this->table_res . $where;
			
			return is_null($res_id) ? $this->db_model->select($sql) : $this->db_model->select_one($sql);
		}

		/**
		* 增加资源
		*
		* @access public
		* @param array  $res_info
		* @return boolean
		*/
		public function add_res($info)
		{
			if ($result = $this->db_model->insert($info, $this->table_res, TRUE))
			{
				return is_numeric($result) ? $result : false;
			}

			return false;
		}

		/**
		* 修改资源
		*
		* @access public
		* @param array  $res_info
		* @return boolean
		*/
		public function modify_res($info, $res_id)
        {
			return $this->db_model->update($info, array('id' => $res_id), $this->table->res);			
        }

		/**
		* 删除资源
		*
		* @access public
		* @param string res_id
		* @return boolean
		*/
		public function del_res($res_id)
        {
			return $this->db_model->delete(array('id' => $res_id), $this->table->res);
        }
		
		/**
		* 增加用户角色
		*
		* @access public
		* @param array  $ur_info
		* @return boolean
		*/
		public function add_user_role($info)
		{
			if ($result = $this->db_model->insert($info, $this->table_urm, TRUE))
			{
				return is_numeric($result) ? $result : false;
			}

			return false;
		}

		/**
		* 删除用户角色
		*
		* @access public
		* @param array $ur_condition
		* @return boolean
		*/
		public function del_user_role($info)
        {
			return $this->db_model->delete($info, $this->table_urm);
        }
		
		/**
		* 增加权限
		*
		* @access public
		* @param array  $acl_info
		* @return boolean
		*/
		public function add_acl($info)
		{
			if ($result = $this->db_model->insert($info, $this->table_rrm, TRUE))
			{
				return is_numeric($result) ? $result : false;
			}

			return false;
		}

		/**
		* 删除权限
		*
		* @access public
		* @param string acl_id
		* @return boolean
		*/
		public function del_acl($info)
        {
			return $this->db_model->delete($info, $this->table_rrm);
        }
    }