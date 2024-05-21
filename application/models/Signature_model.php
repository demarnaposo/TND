<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Signature_model extends CI_Model
{
	/**
	 * Table Name 
	 * 
	 * @var void
	 */
	protected $table; 

	/**
	 * Primary Key 
	 * 
	 * @var void
	 */
	protected $primaryKey; 

	/**
	 * Construct 
	 */
	public function __construct()
	{
		parent::__construct(); 

		$this->table = 'signature'; 
		$table->primaryKey = 'id'; 
	}

	/**
	 * Create 
	 * 
	 * @param  array 
	 * @return void
	 */
	public function create($data)
	{
		$this->db->insert($this->table, $data); 
	}
}