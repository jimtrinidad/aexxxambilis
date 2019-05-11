<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Appdb extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/*
	* return single row object
	*/
	public function getRowObject($table, $find, $field = 'id')
	{
		$q = $this->db->where($field, $find)->get($table);
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}

	/*
	* return single row object
	* multiple condition
	*/
	public function getRowObjectWhere($table, $where)
	{
		$q = $this->db->where($where)->get($table);
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}

	/**
	* basic table search
	* return result array
	*/
	public function getRecords($tableName, $where = false, $order = false, $limit = false)
	{

		if ($where != false) {
			$this->db->where($where);
		}

		if ($order != false) {
			$this->db->order_by($order);
		}

		if ($limit != false) {
			call_user_func_array(array($this->db, 'limit'), $limit);
		}

		return $this->db->get($tableName)->result_array();
		// echo $this->db->last_query();

	}

	/**
	* basic table search
	* return result array
	*/
	public function getCount($tableName, $where = false)
	{

		if ($where != false) {
			$this->db->where($where);
		}

		return 	$this->db->count_all_results($tableName);

	}

	/**
	* insert or update data
	*/
	public function saveData($table, $data)
	{

		$do_update = false;

		if (isset($data['id'])) {
			$q = $this->db->where('id', $data['id'])->get($table);
			if ($q->num_rows() > 0) {
				$do_update = true;
			} else {
				//record does not exists, return invalid update.
				return false;
			}
		}


		if ($do_update) {
			$id = $data['id'];
			unset($data['id']);
			$this->db
					->set($data)
					->where('id', $id)
					->update($table);

			return $this->db->affected_rows();
		} else {
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		}

	}

	/**
	* delete single item
	*/
	public function deleteData($table, $id)
	{
		if (!empty($id)) {
			$this->db->where('id', $id);
			return $this->db->delete($table);
		}
		return false;
	}

	/*
	* return table columns
	*/
	public function tableColumns($table)
	{
		return $this->db->list_fields($table);
	}

	/**
	* paginate data
	*/
	public function getPaginationData($tableName, $limit, $start, $where = false, $order = false, $join = false)
	{
		
		// GET COUNT
		if ($where != false) {
			$this->db->where($where);
		}

		$count 	= $this->db->count_all_results($tableName);

		// GET RESULTS DATA

		if ($where != false) {
			$this->db->where($where);
		}

		if ($order != false) {
			$this->db->order_by($order);
		}

		$data 	= $this->db->get($tableName, $limit, $start)->result();
		// echo $this->db->last_query();

		return array(
			'count'	=> $count,
			'data'	=> $data
		);
	}


	/**
	* paginate data
	*/
	public function getMarketplaceData($limit, $start, $where = false, $order = false)
	{
		
		// GET COUNT
		$this->db->select('si.*');
		$this->db->from('StoreItems si');
		$this->db->join('StoreDetails sd', 'sd.id = si.StoreID');
		$this->db->where('sd.Status', 1);
		if ($where != false) {
			$this->db->where($where);
		}

		$count 	= $this->db->count_all_results();

		// GET RESULTS DATA

		$this->db->select('si.*');
		$this->db->from('StoreItems si');
		$this->db->join('StoreDetails sd', 'sd.id = si.StoreID');
		$this->db->where('sd.Status', 1);

		if ($where != false) {
			$this->db->where($where);
		}

		if ($order != false) {
			$this->db->order_by($order);
		}

		$this->db->limit($limit, $start);

		$data 	= $this->db->get()->result();
		// echo $this->db->last_query();

		return array(
			'count'	=> $count,
			'data'	=> $data
		);
	}

}