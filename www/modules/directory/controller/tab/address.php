<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Directory_Controller_Tab_Address
 *
 * @package HostCMS
 * @subpackage Directory
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Directory_Controller_Tab_Address extends Directory_Controller_Tab
{
	protected $_directoryTypeName = 'Directory_Address_Type';
	// protected $_titleHeaderColor = 'bordered-purple';
	protected $_titleHeaderColor = 'purple';
	protected $_faTitleIcon = 'fa fa-map-marker';

	protected function _execute($oPersonalDataInnerWrapper)
	{
		$aMasDirectoryTypes = $this->_getDirectoryTypes();

		$oButtons = $this->_buttons('address-buttons');

		if (count($this->_aDirectory_Relations))
		{
			foreach ($this->_aDirectory_Relations as $oDirectory_Relation)
			{
				$oRowElements = $this->_addressTemplate($aMasDirectoryTypes, $oDirectory_Relation);

				$oPersonalDataInnerWrapper->add(
					$oRowElements->add($oButtons)
				);
			}
		}
		else
		{
			$oRowElements = $this->_addressTemplate($aMasDirectoryTypes);

			$oPersonalDataInnerWrapper->add(
				$oRowElements->add($oButtons)
			);
		}
	}

	protected function _addressTemplate($aMasDirectoryAddressTypes, $oUser_Directory_Address = NULL)
	{
		$sNameSuffix = $oUser_Directory_Address ? '#' . $oUser_Directory_Address->Directory_Address->id : '[]';
		
		 $oRowElements = Admin_Form_Entity::factory('Div')
			->class('row')
			->add(
				Admin_Form_Entity::factory('Select')
					->options($aMasDirectoryAddressTypes)
					->name($this->prefix . 'address_type' . $sNameSuffix)
					->value($oUser_Directory_Address ? $oUser_Directory_Address->Directory_Address->directory_address_type_id : '')
					->caption(Core::_('Directory_Address.type_address'))
					->divAttr(array('class' => 'form-group col-xs-12 col-sm-6 col-lg-3'))
			)
			->add(
				Admin_Form_Entity::factory('Input')
					->name($this->prefix . 'address_country' . $sNameSuffix)
					->value($oUser_Directory_Address ? $oUser_Directory_Address->Directory_Address->country : '')
					->caption(Core::_('Directory_Address.address_country'))
					// ->divAttr(array('class' => 'form-group no-padding-left ' . ($this->showPublicityControlElement ? 'col-xs-3' : 'col-lg-5 col-sm-6 col-xs-5')))
					->divAttr(array('class' => 'form-group col-xs-12 col-sm-6 col-lg-3'))
			)
			->add(
				Admin_Form_Entity::factory('Input')
					->name($this->prefix . 'address_postcode' . $sNameSuffix)
					->value($oUser_Directory_Address ? $oUser_Directory_Address->Directory_Address->postcode : '')
					->caption(Core::_('Directory_Address.address_postcode'))
					// ->divAttr(array('class' => 'form-group no-padding-left ' . ($this->showPublicityControlElement ? 'col-xs-3' : 'col-lg-5 col-sm-6 col-xs-5')))
					->divAttr(array('class' => 'form-group col-xs-12 col-sm-6 col-lg-3'))
			)
			->add(
				Admin_Form_Entity::factory('Input')
					->name($this->prefix . 'address_city' . $sNameSuffix)
					->value($oUser_Directory_Address ? $oUser_Directory_Address->Directory_Address->city : '')
					->caption(Core::_('Directory_Address.address_city'))
					// ->divAttr(array('class' => 'form-group no-padding-left ' . ($this->showPublicityControlElement ? 'col-xs-3' : 'col-lg-5 col-sm-6 col-xs-5')))
					->divAttr(array('class' => 'form-group col-xs-12 col-sm-6 col-lg-3'))
			)
			->add(
				Admin_Form_Entity::factory('Input')
					->name($this->prefix . 'address' . $sNameSuffix)
					->value($oUser_Directory_Address ? $oUser_Directory_Address->Directory_Address->value : '')
					->caption(Core::_('Directory_Address.address'))
					// ->divAttr(array('class' => 'form-group ' . ($this->showPublicityControlElement ? 'col-sm-8 col-xs-3' : 'col-lg-5 col-sm-6 col-xs-5')))
					->divAttr(array('class' => 'form-group col-xs-12 col-sm-8'))
			);

		if ($this->showPublicityControlElement)
		{
			$iAddressPublic = $oUser_Directory_Address ? $oUser_Directory_Address->Directory_Address->public : 0;

			$oRowElements->add(
				Admin_Form_Entity::factory('Checkbox')
					->divAttr(array('class' => 'col-xs-3 col-sm-2 no-padding !margin-top-23 address-public'))
					->name($this->prefix . 'address_public' . $sNameSuffix)
					->checked($iAddressPublic ? $iAddressPublic : NULL)
					->value($iAddressPublic)
					->caption(Core::_('Directory_Address.address_public'))
			);
			
			// Для нового свойства добавляет скрытое поле, хранящее состояние чекбокса
			if (!$oUser_Directory_Address)
			{
				$oRowElements->add(
					Core::factory('Core_Html_Entity_Input')
						->type('hidden')
						->value(0)
						->name($this->prefix . 'address_public_value' . $sNameSuffix)
				);
			}
		}

		return $oRowElements;
	}
}