<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Jabatan_model extends CI_Model
{
	public function get()
	{
		$this->db->from('jabatan');
		$this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left');
		$this->db->order_by('jabatan.opd_id', 'DESC');
		$this->db->order_by('jabatan_id', 'DESC');
		return $this->db->get();
	}

	public function get_adminskpd($opd_id)
	{
		$this->db->from('jabatan');
		$this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left');
		$this->db->where('jabatan.opd_id', $opd_id);
		$this->db->order_by('jabatan.opd_id', 'DESC');
		$this->db->order_by('jabatan_id', 'DESC');
		return $this->db->get();
	}

	public function get_opd()
	{
		return $this->db->order_by('opd_id', 'DESC')->get('opd');
	}

	public function get_opd_adminskpd($opd_id)
	{
		$this->db->from('opd');
		$this->db->where('opd_id', $opd_id);
		return $this->db->get();
	}

	public function get_jabatan()
	{
		return $this->db->order_by('jabatan_id', 'DESC')->get('jabatan');
	}

	public function get_jabatan_adminskpd($opd_id)
	{
		return $this->db->get_where('jabatan', array('opd_id', $opd_id));
	}

	public function insert($data)
	{
		$insert = $this->db->insert('jabatan', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('jabatan', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('jabatan', $data,$where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('jabatan', $where);
		return $delete;
	}
}