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
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	protected function _login($username, $password)
	{
		if ( ! is_array($this->_users) OR ! array_key_exists($username, $this->_users))
		{
			return false;
		}

		$hashPassword = (is_array($this->_users[$username]) AND isset($this->_users[$username]['password']))
			? $this->_users[$username]['password']
			: $this->_users[$username];

		if ($hashPassword === $this->hashPassword($password))
		{
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

		if ( ! $username OR empty($username))
		{
			return false;
		}

		if ($role == null OR ! isset($this->_users[$username]['role']))
		{
			return ($username != null);
		}

		return ($this->_users[$username]['role'] == $role);
	}
}
