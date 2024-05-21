<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Kodesurat_model extends CI_Model
{
	public function get()
	{
		return $this->db->order_by('kodesurat_id', 'DESC')->get('kode_surat');
	}

	public function insert($data)
	{
		$insert = $this->db->insert('kode_surat', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('kode_surat', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('kode_surat', $data,$where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('kode_surat', $where);
		return $delete;
	}
}