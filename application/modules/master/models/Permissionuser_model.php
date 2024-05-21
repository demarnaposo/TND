<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Permissionuser_model extends CI_Model
{
	public function get()
	{
		$this->db->from('permission_user');
		$this->db->join('users','users.users_id=permission_user.users_id','left');
		$this->db->join('aparatur','aparatur.aparatur_id=users.aparatur_id','left');
		$this->db->join('opd','opd.opd_id=aparatur.opd_id','left');
		$this->db->order_by('permission_user.users_id','ASC');
		return $this->db->get();
	}

	public function insert($data)
	{
		$insert = $this->db->insert('permission_user', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('permission_user', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('permission_user', $data,$where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('permission_user', $where);
		return $delete;
	}
}