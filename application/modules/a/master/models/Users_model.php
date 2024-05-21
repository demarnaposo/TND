<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Users_model extends CI_Model
{
	public function get()
	{
		$this->db->from('users');
		$this->db->join('level', 'users.level_id = level.level_id');
		$this->db->join('aparatur', 'aparatur.aparatur_id = users.aparatur_id');
		$this->db->where('nip !=', '-');
		$this->db->order_by('aparatur.opd_id', 'DESC');
		$this->db->order_by('aparatur.aparatur_id', 'DESC');
		return $this->db->get();
	}

	public function get_adminskpd($opd_id)
	{
		$this->db->from('users');
		$this->db->join('level', 'users.level_id = level.level_id');
		$this->db->join('aparatur', 'aparatur.aparatur_id = users.aparatur_id');
		$this->db->where(array('nip !=' => '-', 'aparatur.opd_id' => $opd_id));
		$this->db->order_by('users_id', 'DESC');
		return $this->db->get();
	}

	public function get_admin()
	{
		$this->db->from('users');
		$this->db->join('level', 'users.level_id = level.level_id');
		$this->db->join('aparatur', 'aparatur.aparatur_id = users.aparatur_id');
		$this->db->where('nip', '-');
		$this->db->order_by('users_id', 'DESC');
		return $this->db->get();
	}

	public function get_level()
	{
		$this->db->from('level');
		$this->db->where('level_id !=', 1);
		$this->db->where('level_id !=', 2);
		$this->db->where('level_id !=', 3);
		return $this->db->get();
	}

	public function get_level_adminskpd()
	{
		$this->db->from('level');
		$this->db->where('level_id !=', 1);
		$this->db->where('level_id !=', 2);
		$this->db->where('level_id !=', 3);
		return $this->db->get();
	}

	public function get_level_admin()
	{
		$this->db->from('level');
		$this->db->where('level_id !=', 5);
		$this->db->where('level_id !=', 6);
		$this->db->where('level_id !=', 7);
		$this->db->where('level_id !=', 8);
		$this->db->where('level_id !=', 12);
		$this->db->where('level_id !=', 13);
		$this->db->where('level_id !=', 14);
		$this->db->where('level_id !=', 15);
		return $this->db->get();
	}

	public function get_aparatur()
	{
		$this->db->from('aparatur');
		$this->db->join('jabatan', 'aparatur.jabatan_id = jabatan.jabatan_id', 'left');
		$this->db->where('nip !=', '-');
		$this->db->order_by('aparatur.opd_id', 'DESC');
		$this->db->order_by('aparatur_id', 'DESC');
		return $this->db->get();
	}

	public function get_aparatur_adminskpd($opd_id)
	{
		$this->db->from('aparatur');
		$this->db->join('jabatan', 'aparatur.jabatan_id = jabatan.jabatan_id', 'left');
		$this->db->order_by('aparatur_id', 'DESC');
		$this->db->where('aparatur.opd_id', $opd_id);
		$this->db->where('nip !=', '-');
		return $this->db->get();
	}

	public function get_aparaturadmin()
	{
		return $this->db->order_by('aparatur_id', 'DESC')->get_where('aparatur', array('nip' => '-'));
	}

	public function get_username($aparatur_id)
	{
		return $this->db->get_where('aparatur', array('aparatur_id' => $aparatur_id));
	}

	public function cek_nip($nip)
	{
		return $this->db->get_where('users', array('username' => $nip));
	}

	public function cek_jabatan($jabatan_id)
	{
		$this->db->from('users');
		$this->db->join('aparatur', 'users.aparatur_id = aparatur.aparatur_id', 'left');
		$this->db->where('aparatur.jabatan_id', $jabatan_id);
		return $this->db->get();
	}

	public function insert($data)
	{
		$insert = $this->db->insert('users', $data);
		return $insert;
	}

	public function edit($where)
	{
		$edit = $this->db->get_where('users', $where);
		return $edit;
	}

	public function update($data,$where)
	{
		$update = $this->db->update('users', $data,$where);
		return $update;
	}

	public function delete($where)
	{
		$delete = $this->db->delete('users', $where);
		return $delete;
	}
}