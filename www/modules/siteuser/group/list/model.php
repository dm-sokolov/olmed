<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Siteuser_Group_List_Model
 *
 * @package HostCMS
 * @subpackage Siteuser
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2019 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Siteuser_Group_List_Model extends Core_Entity
{
	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'siteuser_group' => array(),
		'siteuser' => array(),
		'user' => array()
	);

	/**
	 * Disable markDeleted()
	 * @var mixed
	 */
	protected $_marksDeleted = NULL;

	/**
	 * Column consist item's name
	 * @var string
	 */
	protected $_nameColumn = 'id';

	/**
	 * Constructor.
	 * @param int $id entity ID
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);

		if (is_null($id) && !$this->loaded())
		{
			$oUser = Core_Auth::getCurrentUser();
			$this->_preloadValues['user_id'] = is_null($oUser) ? 0 : $oUser->id;
		}
	}

	/**
	 * Get siteuser group relations by $siteuser_id
	 * @param int $siteuser_id user ID
	 * @return Siteuser_Group_List_Model|NULL
	 */
	public function getBySiteuserId($siteuser_id)
	{
		$this
			->queryBuilder()
			//->clear()
			->where('siteuser_id', '=', $siteuser_id)
			->limit(1);

		$aSiteuser_Group_Lists = $this->findAll();

		if (count($aSiteuser_Group_Lists) > 0)
		{
			return $aSiteuser_Group_Lists[0];
		}

		return NULL;
	}
}