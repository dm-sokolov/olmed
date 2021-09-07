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
class Bot_Module_Controller_Add extends Admin_Form_Action_Controller
{
	/**
	 * Executes the business logic.
	 * @param mixed $operation Operation name
	 */
	public function execute($operation = NULL)
	{
		$bot_id = intval(Core_Array::getGet('bot_id'));
		$module_id = intval(Core_Array::getGet('module_id'));
		$entity_id = intval(Core_Array::getGet('entity_id'));
		$type = intval(Core_Array::getGet('type'));

		$oBot_Module = Core_Entity::factory('Bot_Module');
		$oBot_Module->bot_id = $bot_id;
		$oBot_Module->module_id = $module_id;
		$oBot_Module->entity_id = $entity_id;
		$oBot_Module->type = $type;

		// Default values
		$oClass = new $oBot_Module->Bot->class();
		$aFields = $oClass->getFields();

		$aTmp = array();

		foreach ($aFields as $fieldName => $aField)
		{
			isset($aField['value'])
				&& $aTmp[$fieldName] = $aField['value'];
		}

		$oBot_Module->json = json_encode($aTmp);

		// Sorting
		$oBot_Modules = Core_Entity::factory('Bot_Module');
		$oBot_Modules->queryBuilder()
			->where('bot_modules.module_id', '=', $module_id)
			->where('bot_modules.entity_id', '=', $entity_id)
			->where('bot_modules.type', '=', $type);

		$oLast_Bot_Module = $oBot_Modules->getLast();

		$oBot_Module->sorting = !is_null($oLast_Bot_Module)
			? $oLast_Bot_Module->sorting + 1
			: 0;

		$oBot_Module->save();
	}
}