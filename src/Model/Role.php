<?php

namespace Auth\Model;

use \ActiveRecord\Model;
use \Auth\Auth;

class Role extends Model {

	/**
	 * Rules for validation
	 * @var array
	 */
	public static $validates_presence_of = array(
		array('name', 'message' => 'must be not empty'),
	);

	/**
	 * Rules for validation
	 * @var array
	 */
	public static $validates_uniqueness_of = array(
		array('name', 'message' => 'already exists'),
	);

	/**
	 * Associations
	 * @var array
	 */
	public static $has_many = array(
		array('users'),
	);
}
