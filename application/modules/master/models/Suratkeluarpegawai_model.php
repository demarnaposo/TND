<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Suratkeluarpegawai_model extends CI_Model
{
	public function get($opd,$)
	{
		$this->db->from('suratkeluar_pegawai');
		$this->db->join('draft', 'suratkeluar_pegawai.surat_id = draft.surat_id', 'left');			
		$this->db->join('aparatur', 'suratkeluar_pegawai.pegawai_id = aparatur.aparatur_id', 'left');

		$this->db->order_by('suratkeluar_pegawai.surat_id', 'DESC');
		$this->db->order_by('aparatur_id', 'ASC');
		return $this->db->get();
	}

	public function get_opd()
	{
		return $this->db->order_by('opd_id', 'DESC')->get('opd');
	}


	public function insert($data)
	{
		$insert = $this->db->insert('suratkeluar_pegawai', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('suratkeluar_pegawai', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('suratkeluar_pegawai', $data,$where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('suratkeluar_pegawai', $where);
		return $delete;
	}
}