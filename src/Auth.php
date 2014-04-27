<?php

namespace Auth;

use \Session\Session;

abstract class Auth {

	// Default roles
	const ROLE_GUEST 	= 'guest';
	const ROLE_USER 	= 'user';
	const ROLE_ADMIN 	= 'admin';

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
		$this->_session = Session::instance();
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function login($username, $password)
	{
		if (empty($password)) {
			return false;
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
		if ( ! $this->loggedIn($role)) {
			return false;
		}

		if ($this->_session->set($this->_sessionKeyInitUser, $this->getUserName())) {
			$this->logout();
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
		if ($destroy === true) {
			$this->_session->destroy();
		} else {
			$this->_session->delete($this->sessionKey());
			$this->_session->regenerate();
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

		if ($initial_user) {
			$this->logout();
			return $this->_completeLogin($initial_user);
		}

		return false;
	}

	/**
	 * @param string $password
	 * @return string
	 */
	public function hashPassword($str)
	{
		return static::hash($str);
	}

	/**
	 * @param string $default
	 * @return mixed
	 */
	public function getUser($default = null)
	{
		return $this->_session->get($this->sessionKey(), $default);
	}

	/**
	 * @param string $default
	 * @return string
	 */
	public function getUserName($default = null)
	{
		return $this->getUser($default);
	}

	/**
	 * Gets role of current user
	 * @return string
	 */
	public function getRole()
	{
		return $this->_getRole();
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public function sessionKey($key = null)
	{
		if ($key) {
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
		return ($this->_session->set($this->sessionKey(), $user));
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
	abstract protected function _loggedIn($role = null);

	/**
	 * Gets role of current user
	 * @return string
	 */
	abstract protected function _getRole();

	// ============================== Helpers =============================== //

	/**
	 * @var string
	 */
	public static $hashKey = null;

	/**
	 * Hash string
	 * @param string $str
	 * @param string $hash
	 * @return string
	 */
	public static function hash($str, $hash = null)
	{
		$hash = ($hash) ? $hash : static::$hashKey;
		return hash_hmac('sha256', $str, $hash);
	}
}
