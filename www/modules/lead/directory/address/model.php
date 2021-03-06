<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Lead_Directory_Address_Model
 *
 * @package HostCMS
 * @subpackage Lead
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2020 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Lead_Directory_Address_Model extends Core_Entity
{
	protected $_tableName = 'lead_directory_addresses';

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'directory_address' => array(),
		'lead' => array()
	);

	/**
	 * Disable markDeleted()
	 * @var mixed
	 */
	protected $_marksDeleted = NULL;
}