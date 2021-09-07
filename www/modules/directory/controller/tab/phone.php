<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Directory_Controller_Tab_Phone
 *
 * @package HostCMS
 * @subpackage Directory
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Directory_Controller_Tab_Phone extends Directory_Controller_Tab
{
	protected $_directoryTypeName = 'Directory_Phone_Type';
	// protected $_titleHeaderColor = 'bordered-palegreen';
	protected $_titleHeaderColor = 'palegreen';
	protected $_faTitleIcon = 'fa fa-phone';

	protected function _execute($oPersonalDataInnerWrapper)
	{
		$aDirectory_Relations = $this->relation->findAll();

		$aMasDirectoryTypes = $this->_getDirectoryTypes();

		$oButtons = $this->_buttons();

		if (count($this->_aDirectory_Relations))
		{
			foreach ($this->_aDirectory_Relations as $oDirectory_Relation)
			{
				$oRowElements = $this->_phoneTemplate($aMasDirectoryTypes, $oDirectory_Relation);

				$oPersonalDataInnerWrapper->add(
					$oRowElements->add($oButtons)
				);
			}
		}
		else
		{
			$oRowElements = $this->_phoneTemplate($aMasDirectoryTypes);

			$oPersonalDataInnerWrapper->add(
				$oRowElements->add($oButtons)
			);
		}
	}

	protected function _phoneTemplate($aMasDirectoryPhoneTypes, $oUser_Directory_Phone = NULL)
	{
		$sNameSuffix = $oUser_Directory_Phone ? '#' . $oUser_Directory_Phone->Directory_Phone->id : '[]';

		 $oRowElements = Admin_Form_Entity::factory('Div')
			->class('row')
			->add(
				Admin_Form_Entity::factory('Select')
					->options($aMasDirectoryPhoneTypes)
					->name($this->prefix . 'phone_type' . $sNameSuffix)
					->value($oUser_Directory_Phone ? $oUser_Directory_Phone->Directory_Phone->directory_phone_type_id : '')
					->caption(Core::_('Directory_Phone.type_phone'))
					->divAttr(array('class' => 'form-group col-xs-4'))
			)
			->add(
				Admin_Form_Entity::factory('Input')
					->name($this->prefix . 'phone' . $sNameSuffix)
					->value($oUser_Directory_Phone ? $oUser_Directory_Phone->Directory_Phone->value : '')
					->caption(Core::_('Directory_Phone.phone'))
					->divAttr(array('class' => 'form-group no-padding-left ' . ($this->showPublicityControlElement ? 'col-sm-4 col-xs-3' : 'col-lg-5 col-sm-6 col-xs-5')))
			);

		if ($this->showPublicityControlElement)
		{
			$iPhonePublic = $oUser_Directory_Phone ? $oUser_Directory_Phone->Directory_Phone->public : 0;

			$oRowElements->add(
				Admin_Form_Entity::factory('Checkbox')
					->divAttr(array('class' => 'col-xs-3 col-sm-2 no-padding margin-top-23 margin-right-5'))
					->name($this->prefix . 'phone_public' . $sNameSuffix)
					->checked($iPhonePublic ? $iPhonePublic : NULL)
					->value($iPhonePublic)
					->caption(Core::_('Directory_Phone.phone_public'))
			);

			// Для нового свойства добавляет скрытое поле, хранящее состояние чекбокса
			if (!$oUser_Directory_Phone)
			{
				$oRowElements->add(
					Core::factory('Core_Html_Entity_Input')
						->type('hidden')
						->value(0)
						->name($this->prefix . 'phone_public_value' . $sNameSuffix)
				);
			}
		}

		return $oRowElements;
	}
}