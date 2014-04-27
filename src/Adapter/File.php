<?php

namespace Auth\Adapter;

use \Auth\Auth;

class File extends Auth {

	/**
	 * User list
	 * @var array
	 */
	protected $_users;

	/**
	 * @param array $users
	 * @return void
	 */
	public function __construct(array $users = null)
	{
		parent::__construct();
		$this->_users = $users;
	}

	/**
	 * Sets user list
	 * @param array $users
	 * @return $this
	 */
	public function setUsers(array $users)
	{
		$this->_users = (is_array($this->_users))
			? array_merge($this->_users, $users)
			: $users;

		return $this;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	protected function _login($username, $password)
	{
		if ( ! is_array($this->_users) OR ! array_key_exists($username, $this->_users)) {
			return false;
		}

		$hashPassword = (is_array($this->_users[$username]) AND isset($this->_users[$username]['password']))
			? $this->_users[$username]['password']
			: $this->_users[$username];

		if ($hashPassword === $this->hashPassword($password)) {
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
		$username 	= $this->getUser();
		$userRole	= $this->getRole();

		if ( ! $username OR empty($username)) {
			return false;
		}

		return ($role == null OR $userRole == $role);
	}

	/**
	 * Gets role of current user
	 * @return string
	 */
	protected function _getRole()
	{
		if ($this->getUser() != null) {
			return (isset($this->_users[$this->getUser()]['role']))
				? $this->_users[$this->getUser()]['role']
				: Auth::ROLE_USER;
		}
		return Auth::ROLE_GUEST;
	}
}
