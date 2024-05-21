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
		$this->db->or_where('opd.opd_induk', $opd_id);
		$this->db->order_by('jabatan.opd_id', 'DESC');
		$this->db->order_by('jabatan_id', 'DESC');
		return $this->db->get();
	}

	public function get_adminskpd1($opd_id)
	{
		$this->db->from('jabatan');
		$this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left');
		$this->db->where('opd.opd_induk', 4);
		$this->db->order_by('jabatan.opd_id', 'DESC');
		$this->db->order_by('jabatan_id', 'DESC');
		return $this->db->get();
	}


	public function get_opd()
	{
		// Update @Mpik Egov 8 Agustus 2022
		$get=$this->db->query("SELECT * FROM opd LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id WHERE urutan_opd.urutan_id !=0 AND opd.statusopd='Aktif' ORDER BY urutan_opd.urutan_id ASC");
		return $get;
		// END 8 Agustus 2022
	}

	public function get_opd_adminskpd($opd_id)
	{
		// Update @Mpik Egov 8 Agustus 2022
		$get=$this->db->query("SELECT * FROM opd LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id WHERE urutan_opd.urutan_id !=0 AND opd.statusopd='Aktif' ORDER BY urutan_opd.urutan_id ASC");
		return $get;
		// END 8 Agustus 2022
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