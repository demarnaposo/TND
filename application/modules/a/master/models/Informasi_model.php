<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Informasi_model extends CI_Model
{
	public function get()
	{
		return $this->db->order_by('id', 'DESC')->get('informasi');
	}

	public function insert($data)
	{
		$insert = $this->db->insert('informasi', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('informasi', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('informasi', $data,$where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('informasi', $where);
		return $delete;
	}
}