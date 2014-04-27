<?php

namespace Auth\Model;

use \ActiveRecord\Model;
use \Auth\Auth;

class User extends Model {

	/**
	 * Rules for validation
	 * @var array
	 */
	public static $validates_presence_of = array(
		array('username', 'message' => 'must be not empty'),
		array('password', 'message' => 'must be not empty'),
		array('email', 'message' => 'must be not empty'),
	);

	/**
	 * Rules for validation
	 * @var array
	 */
	public static $validates_size_of = array(
		array('username', 'minimum' => 5, 'too_short' => 'must be minimum 5 chars'),
		array('password', 'minimum' => 5, 'too_short' => 'must be minimum 5 chars'),
	);

	/**
	 * Rules for validation
	 * @var array
	 */
	public static $validates_format_of = array(
		array(
			'email',
			'with' => '/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/',
			'message' => 'is not valid'
		),
	);

	/**
	 * Rules for validation
	 * @var array
	 */
	public static $validates_uniqueness_of = array(
		array('username', 'message' => 'already exists'),
		array('email', 'message' => 'already exists'),
	);

	/**
	 * Associations
	 * @var array
	 */
	public static $belongs_to = array(
		array('role'),
	);

	/**
	 * Gets role name of current user
	 * @return string
	 */
	public function getRole($default = null)
	{
		$role = $this->role->name;
		return ($role) ? $role : $default;
	}

	/**
	 * Update loaded user
	 * @param array $data
	 * @return boolean|array errors
	 */
	public function updateThis(array $data)
	{
		$this->update_attributes($data);
		return $this->_modify($data);
	}

	/**
	 * Create new user
	 * @param array $data
	 * @return boolean|array errors
	 */
	public function createNew(array $data)
	{
		$this->set_attributes($data);
		return $this->_modify($data);
	}

	/**
	 * Check validation and hashing password
	 * @param array $data
	 * @return boolean|array errors
	 */
	protected function _modify(array $data = null)
	{
		if ($this->is_valid()) {
			if (isset($data['password'])) {
				$this->password = Auth::hash($data['password']);
			}
			return $this->save();
		}

		return $this->errors->full_messages();
	}
}
