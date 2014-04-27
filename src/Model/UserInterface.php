<?php

namespace Auth\Model;

interface UserInterface {

	/**
	 * Gets role name of current user
	 * @return string
	 */
	public function getRole($default = null);

	/**
	 * Create new user
	 * @param array $data
	 * @return boolean|array errors
	 */
	public function createNew(array $data);

	/**
	 * Update loaded user
	 * @param array $data
	 * @return boolean|array errors
	 */
	public function updateThis(array $data);
}
