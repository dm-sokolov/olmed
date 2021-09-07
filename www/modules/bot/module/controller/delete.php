<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Bot.
 *
 * @package HostCMS
 * @subpackage Bot
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2020 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Bot_Module_Controller_Delete extends Admin_Form_Action_Controller
{
	/**
	 * Executes the business logic.
	 * @param mixed $operation Operation name
	 */
	public function execute($operation = NULL)
	{
		$oBot_Module = Core_Entity::factory('Bot_Module')->getById($this->_object->id);

		if (!is_null($oBot_Module))
		{
			$oBot_Module->delete();
		}

		$id = $this->_object->id;

		$this->_Admin_Form_Controller->addMessage(
			"<script>$('.bot-modules div#{$id}').parents('.dd').remove(); $.loadBotNestable();</script>"
		);

		return TRUE;
	}
}