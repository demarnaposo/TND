<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Eksternal_model extends CI_Model
{
	public function get($opd_id)
	{
		return $this->db->order_by('id', 'DESC')->get_where('eksternal_keluar', array('opd_id' => $opd_id));
	}

	public function insert($data)
	{
		$insert = $this->db->insert('eksternal_keluar', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('eksternal_keluar', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('eksternal_keluar', $data,$where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('eksternal_keluar', $where);
		return $delete;
	}
}