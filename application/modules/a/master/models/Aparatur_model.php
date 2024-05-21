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
		$this->db->where(array('nip !=' => '-', 'aparatur.opd_id' => $opd_id));
		$this->db->order_by('aparatur_id', 'DESC');
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
		$this->db->where('jabatan.opd_id', $opd_id);
		$this->db->order_by('jabatan.jabatan_id', 'DESC');
		return $this->db->get();
	}

	public function get_jabatanadmin()
	{
		return $this->db->order_by('jabatan_id', 'DESC')->get_where('jabatan', array('atasan_id' => 0));
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
}