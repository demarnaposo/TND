<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Perangkatdaerah_model extends CI_Model
{
	public function get()
	{
		$get = $this->db->order_by('opd_id', 'DESC')->get('opd');
		return $get;
	}

	public function insert($data)
	{
		$insert = $this->db->insert('opd', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('opd', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('opd', $data, $where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('opd', $where);
		return $delete;
	}
}