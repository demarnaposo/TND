<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Aparatur_model extends CI_Model
{
	public function get()
	{
		$this->db->from('aparatur');
		$this->db->join('opd', 'aparatur.opd_id = opd.opd_id', 'left');
		$this->db->join('jabatan', 'aparatur.jabatan_id = jabatan.jabatan_id', 'left');
		$this->db->join('levelbaru', 'levelbaru.level_id = aparatur.level_id', 'left');
		$this->db->where('nip !=', '-');
		$this->db->order_by('aparatur.opd_id', 'DESC');
		$this->db->order_by('aparatur.aparatur_id', 'DESC');
		return $this->db->get();
	}

	public function get_adminskpd($opd_id)
	{
		$this->db->from('aparatur');
		$this->db->join('opd', 'aparatur.opd_id = opd.opd_id', 'left');
		$this->db->join('jabatan', 'aparatur.jabatan_id = jabatan.jabatan_id', 'left');
		$this->db->join('levelbaru', 'levelbaru.level_id = aparatur.level_id', 'left');
		$this->db->where(array('nip !=' => '-', 'aparatur.opd_id' => $opd_id));
		$this->db->or_where("(nip != '-' AND opd.opd_induk = " .$opd_id. ")");
		$this->db->order_by('aparatur_id', 'DESC');
		return $this->db->get();
	}

	public function get_adminskpd1($opd_id)
	{
		$this->db->from('aparatur');
		$this->db->join('opd', 'aparatur.opd_id = opd.opd_id', 'left');
		$this->db->join('jabatan', 'aparatur.jabatan_id = jabatan.jabatan_id', 'left');
		$this->db->join('levelbaru', 'levelbaru.level_id = aparatur.level_id', 'left');
		$this->db->where(array('nip !=' => '-', 'opd.opd_induk' => 4));
		$this->db->order_by('aparatur_id', 'DESC');
		return $this->db->get();
	}


	public function get_pindahaparatur($opd_id)
	{
		$this->db->from('aparatur');
		$this->db->join('opd', 'aparatur.opd_id = opd.opd_id', 'left');
		$this->db->join('jabatan', 'aparatur.jabatan_id = jabatan.jabatan_id', 'left');
		$this->db->where(array('nip !=' => '-'));
		$this->db->where('aparatur.opd_id =',$opd_id);
		$this->db->where_not_in('opd.opd_id',['1','2','3']);
		$this->db->order_by('opd.opd_id', 'ASC');
		return $this->db->get();
	}

	public function get_pindahopd(){
		$this->db->from('opd');
		$this->db->where_not_in('opd_id',['1','2','3']);
		$this->db->order_by('opd_id','asc');

		return $this->db->get();
	}
	
	public function get_pindahjabatan($opd_id)
	{
		$this->db->from('jabatan');
		$this->db->join('opd', 'jabatan.opd_id = opd.opd_id', 'left');
		$this->db->where('opd.opd_id',$opd_id);
		$this->db->where('jabatan.nama_jabatan NOT LIKE','%admin%');
		$this->db->order_by('jabatan_id', 'ASC');
		return $this->db->get();
	}
	
	public function get_admin()
	{
		$this->db->from('aparatur');
		$this->db->join('opd', 'aparatur.opd_id = opd.opd_id' ,'left');
		$this->db->join('jabatan', 'aparatur.jabatan_id = jabatan.jabatan_id', 'left');
		$this->db->where('nip', '-');
		$this->db->order_by('aparatur_id', 'DESC');
		return $this->db->get();
	}

	public function get_jabatan()
	{
		$this->db->select('*');
		$this->db->from('jabatan');
		$this->db->join('opd', 'opd.opd_id = jabatan.opd_id');
		return $this->db->get();
	}

	public function get_jabatan_adminskpd($opd_id)
	{
		$this->db->select('*');
		$this->db->from('jabatan');
		$this->db->join('opd', 'opd.opd_id = jabatan.opd_id');
		// $this->db->join('aparatur', 'aparatur.jabatan_id = jabatan.jabatan_id');// Update @MpikEgov 27 Maret 2023
		$this->db->where('jabatan.opd_id', $opd_id);
		// $this->db->or_where("(aparatur.nip != '-' AND opd.opd_induk = " .$opd_id. ")"); // Update @MpikEgov 27 Maret 2023
		$this->db->or_where('opd.opd_induk', $opd_id);
		$this->db->order_by('jabatan.jabatan_id', 'DESC');
		return $this->db->get();


		


	}

	public function get_jabatanadmin()
	{
		return $this->db->get('jabatan');
	}

	public function get_opd($jabatan_id)
	{
		return $this->db->get_where('jabatan', array('jabatan_id' => $jabatan_id));
	}

	public function insert($data)
	{
		$insert = $this->db->insert('aparatur', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('aparatur', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('aparatur', $data,$where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('aparatur', $where);
		return $delete;
	}

	public function delete_data($table,$where)
	{
		$delete = $this->db->delete($table, $where);
		return $delete;
	}
}