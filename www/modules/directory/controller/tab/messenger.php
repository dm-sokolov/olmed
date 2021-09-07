<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Directory_Controller_Tab_Messenger
 *
 * @package HostCMS
 * @subpackage Directory
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Directory_Controller_Tab_Messenger extends Directory_Controller_Tab
{
	protected $_directoryTypeName = 'Directory_Messenger_Type';
	protected $_titleHeaderColor = 'yellow';
	// protected $_titleHeaderColor = 'bordered-yellow';
	protected $_faTitleIcon = 'fa fa-comments-o';

	protected function _execute($oPersonalDataInnerWrapper)
	{
		$aDirectory_Relations = $this->relation->findAll();

		$aMasDirectoryTypes = $this->_getDirectoryTypes();

		$oButtons = $this->_buttons();

		if (count($this->_aDirectory_Relations))
		{
			foreach ($this->_aDirectory_Relations as $oDirectory_Relation)
			{
				$oRowElements = $this->_messengerTemplate($aMasDirectoryTypes, $oDirectory_Relation);

				$oPersonalDataInnerWrapper->add(
					$oRowElements->add($oButtons)
				);
			}
		}
		else
		{
			$oRowElements = $this->_messengerTemplate($aMasDirectoryTypes);

			$oPersonalDataInnerWrapper->add(
				$oRowElements->add($oButtons)
			);
		}
	}

	protected function _messengerTemplate($aMasDirectoryMessengers, $oUser_Directory_Messenger = NULL)
	{
		$sNameSuffix = $oUser_Directory_Messenger ? '#' . $oUser_Directory_Messenger->Directory_Messenger->id : '[]';

		$oRowElements = Admin_Form_Entity::factory('Div')
			->class('row')
			->add(
				Admin_Form_Entity::factory('Select')
					->options($aMasDirectoryMessengers)
					->name($this->prefix . 'messenger' . $sNameSuffix)
					->value($oUser_Directory_Messenger ? $oUser_Directory_Messenger->Directory_Messenger->directory_messenger_type_id : '')
					->caption(Core::_('Directory_Messenger.messenger'))
					->divAttr(array('class' => 'form-group col-xs-4'))
			)
			->add(
				Admin_Form_Entity::factory('Input')
					->name($this->prefix . 'messenger_username' . $sNameSuffix)
					->value($oUser_Directory_Messenger ? $oUser_Directory_Messenger->Directory_Messenger->value : '')
					->caption(Core::_('Directory_Messenger.messenger_username'))
					->divAttr(array('class' => 'form-group no-padding-left ' . ($this->showPublicityControlElement ? 'col-sm-4 col-xs-3' : 'col-lg-5 col-sm-6 col-xs-5')))
			);

		if ($this->showPublicityControlElement)
		{
			$iMessengerPublic = $oUser_Directory_Messenger ? $oUser_Directory_Messenger->Directory_Messenger->public : 0;
			
			$oRowElements->add(
				Admin_Form_Entity::factory('Checkbox')
					->divAttr(array('class' => 'col-xs-3 col-sm-2 no-padding margin-top-23'))
					->name($this->prefix . 'messenger_public' . $sNameSuffix)
					->checked($iMessengerPublic ? $iMessengerPublic : NULL)
					->value($iMessengerPublic)
					->caption(Core::_('Directory_Messenger.messenger_public'))
			);
			
			// Для нового свойства добавляет скрытое поле, хранящее состояние чекбокса
			if (!$oUser_Directory_Messenger)
			{
				$oRowElements->add(
					Core::factory('Core_Html_Entity_Input')
						->type('hidden')
						->value(0)
						->name($this->prefix . 'messenger_public_value' . $sNameSuffix)
				);
			}
		}

		return $oRowElements;
	}
}