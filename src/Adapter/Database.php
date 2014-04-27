<?php

namespace Auth\Adapter;

use \Model;
use \Auth\Auth;
use \Auth\Model\User;

class Database extends Auth {

	/**
	 * Instance of Model\User
	 * @var object
	 */
	protected $_user;

	/**
	 * Class name
	 * @var string
	 */
	protected $_userModelName;

	/**
	 * @param string $userModelName
	 * @return void
	 */
	public function __construct($userModelName = null)
	{
		parent::__construct();

		$this->_userModelName =
		$userModelName = ($userModelName)
			? $userModelName
			: '\\Auth\\Model\\User';

		$interfaces = class_implements($userModelName);
		if (!in_array('Auth\Model\UserInterface', $interfaces)) {
			throw new \Exception('Model '.$userModelName.' should implements interface \Auth\Model\UserInterface');
		}
	}

	/**
	 * Create new user
	 * @param array $data
	 * @return boolean
	 */
	public function createUser(array $data)
	{
		$model = $this->_userModelName;
		$model = new $model;
		return $model->createNew($data);
	}

	/**
	 * Update user
	 * @param array $data
	 * @return boolean
	 */
	public function updateUser(array $data)
	{
		$model = $this->getUser();
		return ($model) ? $model->updateThis($data) : false;
	}

	/**
	 * Get current user
	 * @param string $default // default user
	 * @return mixed // instance of model User, default username, or false, if user isn't in database
	 */
	public function getUser($default = null)
	{
		if ($this->_user instanceof User) {
			return $this->_user;
		}

		$username = parent::getUser();

		if (!$username) {
			return $default;
		}

		return $this->_getUser($username);
	}

	/**
	 * @param string $default
	 * @return string
	 */
	public function getUserName($default = null)
	{
		$user = $this->getUser($default);
		return ($user instanceof User) ? $user->username : $default;
	}

	/**
	 * Loading user from database
	 * @param string $username
	 * @return object
	 */
	protected function _getUser($username)
	{
		$model = $this->_userModelName;
		$this->_user = $model::find_by_username($username);
		return $this->_user;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	protected function _login($username, $password)
	{
		$user = $this->_getUser($username);
		if ( ! $user instanceof User) {
			return false;
		}

		if ($user->password === $this->hashPassword($password)) {
			return $this->_completeLogin($username);
		}

		return false;
	}

	/**
	 * @param string $role
	 * @return boolean
	 */
	protected function _loggedIn($role = null)
	{
		$username = $this->getUser();

		if ( ! $username OR empty($username)) {
			return false;
		}

		return ($role == null OR $this->getRole() == $role);
	}

	/**
	 * Gets role of current user
	 * @return string
	 */
	protected function _getRole()
	{
		$user = $this->getUser();

		if ($user) {
			return $user->getRole(Auth::ROLE_USER);
		}

		return Auth::ROLE_GUEST;
	}
}
