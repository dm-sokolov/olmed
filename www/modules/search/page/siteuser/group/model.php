<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Search_Page_Siteuser_Group_Model
 *
 * @package HostCMS
 * @subpackage Search
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2016 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Search_Page_Siteuser_Group_Model extends Core_Entity{
	/**
	 * Disable markDeleted()
	 * @var mixed
	 */
	protected $_marksDeleted = NULL;
	
	/**
	 * Belongs to relations
	 * @var array
	 */	protected $_belongsTo = array(		'search_page' => array(),
		'siteuser_group' => array()	);	}