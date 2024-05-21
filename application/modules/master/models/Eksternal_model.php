<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Eksternal_model extends CI_Model
{
	//Perangkat Eksternal	
	public function get_id($table)
	{
		$query = $this->db->query("SELECT * FROM $table ORDER BY LENGTH(id) ASC, id ASC");
		return $query;
	}
	
	public function get($opd_id)
	{
		return $this->db->order_by('id', 'ASC')->get_where('eksternal_keluar', array('opd_id' => $opd_id));
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

	//Tembusan Eksternal
	public function get_tembusan($opd_id)
	{
		return $this->db->order_by('id', 'ASC')->get_where('tembusan_keluar', array('opd_id' => $opd_id));
	}

	public function insert_tembusan($data)
	{
		$insert = $this->db->insert('tembusan_keluar', $data);
		return $insert;
	}

	public function edit_tembusan($where)
	{
		$edit = $this->db->get_where('tembusan_keluar', $where);
		return $edit;
	}

	public function update_tembusan($data,$where)
	{
		$update = $this->db->update('tembusan_keluar', $data,$where);
		return $update;
	}

	public function delete_tembusan($where)
	{
		$delete = $this->db->delete('tembusan_keluar', $where);
		return $delete;
	}
}