<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Calendar_Module
 *
 * @package HostCMS
 * @subpackage Calendar
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Calendar_Module extends Core_Module{
	/**
	 * Module version
	 * @var string
	 */
	public $version = '6.9';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2021-02-16';


	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'calendar';

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		if (Core_Auth::logged())
		{
			Core_Router::add('calendar-callback.php', '/calendar-callback.php')
				->controller('Calendar_Caldav_Command_Controller');
		}
	}
	
	/**
	 * Get Module's Menu
	 * @return array
	 */
	public function getMenu()
	{
		$this->menu = array(
			array(
				'sorting' => 180,
				'block' => 3,
				'ico' => 'fa fa-calendar',
				'name' => Core::_('Calendar.model_name'),
				'href' => "/admin/calendar/index.php",
				'onclick' => "$.adminLoad({path: '/admin/calendar/index.php'}); return false"
			)
		);

		return parent::getMenu();
	}
}