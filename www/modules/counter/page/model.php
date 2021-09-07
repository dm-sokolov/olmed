<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Counter_Page_Model
 *
 * @package HostCMS
 * @subpackage Counter
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Counter_Page_Model extends Core_Entity
{
	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'site' => array(),
	);

	/**
	 * Disable markDeleted()
	 * @var mixed
	 */
	protected $_marksDeleted = NULL;

	/**
	 * Backend callback method
	 * @return string
	 */
	public function pageBackend()
	{
		ob_start();

		Core::factory('Core_Html_Entity_A')
			->href($this->page)
			->value(
				htmlspecialchars(Core_Str::cut($this->page, 50))
			)
			->target('_blank')
			->execute();

		return ob_get_clean();
	}

	/**
	 * Get Related Site
	 * @return Site_Model|NULL
	 * @hostcms-event counter_page.onBeforeGetRelatedSite
	 * @hostcms-event counter_page.onAfterGetRelatedSite
	 */
	public function getRelatedSite()
	{
		Core_Event::notify($this->_modelName . '.onBeforeGetRelatedSite', $this);

		$oSite = $this->Site;

		Core_Event::notify($this->_modelName . '.onAfterGetRelatedSite', $this, array($oSite));

		return $oSite;
	}
}