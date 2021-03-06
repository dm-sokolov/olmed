<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Lead_Step_Model
 *
 * @package HostCMS
 * @subpackage Lead
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2020 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Lead_Step_Model extends Core_Entity
{
	/**
	 * Disable markDeleted()
	 * @var mixed
	 */
	protected $_marksDeleted = NULL;

	/**
	 * One-to-many or many-to-many relations
	 * @var array
	 */
	protected $_hasMany = array(
		// 'deal_step_user' => array()
	);

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'lead' => array(),
		'lead_status' => array(),
		'user' => array()
	);

	/**
	 * Delete object from database
	 * @param mixed $primaryKey primary key for deleting object
	 * @return Core_Entity
	 */
	public function delete($primaryKey = NULL)
	{
		if (is_null($primaryKey))
		{
			$primaryKey = $this->getPrimaryKey();
		}

		$this->id = $primaryKey;

		// $this->Deal_Step_Users->deleteAll(FALSE);

		return parent::delete($primaryKey);
	}
}