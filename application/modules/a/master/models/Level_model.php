<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Level_model extends CI_Model
{
	public function get()
	{
		return $this->db->order_by('level_id', 'DESC')->get('level');
	}

	public function insert($data)
	{
		$insert = $this->db->insert('level', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('level', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('level', $data,$where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('level', $where);
		return $delete;
	}
}