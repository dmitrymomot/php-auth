<?php

namespace Auth;

use \Session\Session;

abstract class Auth {

	/**
	 * Hash for password
	 * @var string
	 */
	protected $_hash;

	/**
	 * @var instance of class \Session\Session
	 */
	protected $_session;

	/**
	 * @var string
	 */
	protected $_sessionKey = 'auth_user';

	/**
	 * @var string
	 */
	protected $_sessionKeyInitUser = 'initial_auth_user';

	/**
	 * @return void
	 */
	public function __construct()
	{
		$this->_session = new Session;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function login($username, $password)
	{
		if (empty($password))
		{
			return false
		}

		return $this->_login($username, $password);
	}

	/**
	 * @param string $username
	 * @param string $role
	 * @return boolean
	 */
	public function loginAs($username, $role = null)
	{
		if ( ! $this->loggedIn($role))
		{
			return false;
		}

		if ($this->_session->set($this->_sessionKeyInitUser, $this->getUser()))
		{
			return $this->_completeLogin($username);
		}

		return false;
	}

	/**
	 * @param string $role
	 * @return boolean
	 */
	public function loggedIn($role = null)
	{
		return ($this->_loggedIn($role));
	}

	/**
	 * Log out a user by removing the related session variables.
	 * @param boolean $destroy
	 * @return boolean
	 */
	public function logout($destroy = false)
	{
		if ($destroy === true)
		{
			$this->_session->destroy();
		}
		else
		{
			$this->_session->delete($this->sessionKey());
			$this->_session->regenerate($destroy);
		}

		return ( ! $this->loggedIn());
	}

	/**
	 * Come back to initial user
	 * @return boolean
	 */
	public function comeBack()
	{
		$initial_user = $this->_session->get($this->_sessionKeyInitUser);

		if ($initial_user)
		{
			$this->logout();
			return $this->_completeLogin($initial_user);
		}

		return false;
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public function hash($str = null)
	{
		if ($str)
		{
			$this->_hash = $str;
		}

		return $this->_hash;
	}

	/**
	 * @param string $password
	 * @return string
	 */
	public function hashPassword($str)
	{
		return hash_hmac('sha256', $str, $this->hash());
	}

	/**
	 * @param string $default
	 * @return mixed
	 */
	public function getUser($default = null)
	{
		$user = $this->_session->get($this->sessionKey());
		return ($user) ? $user : $default;
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public function sessionKey($key = null)
	{
		if ($key)
		{
			$this->_sessionKey = $key;
		}

		return $this->_sessionKey;
	}

	/**
	 * @param mixed $user
	 * @return boolean
	 */
	protected function _completeLogin($user)
	{
		$this->_session->regenerate();
		return ($this->_session->set($this->session_key(), $user));
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	abstract protected function _login($username, $password);

	/**
	 * @param string $role
	 * @return boolean
	 */
	abstract public function _loggedIn($role = null);
}
