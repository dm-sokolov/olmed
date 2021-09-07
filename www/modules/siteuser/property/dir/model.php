<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Siteuser_Property_Dir_Model
 *
 * @package HostCMS
 * @subpackage Siteuser
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2016 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Siteuser_Property_Dir_Model extends Core_Entity{
	/**
	 * Disable markDeleted()
	 * @var mixed
	 */	protected $_marksDeleted = NULL;
	/**
	 * Belongs to relations
	 * @var array
	 */	protected $_belongsTo = array(		'site' => array(),		'property_dir' => array()	);}