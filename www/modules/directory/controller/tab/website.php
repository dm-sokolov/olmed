<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Directory_Controller_Tab_Website
 *
 * @package HostCMS
 * @subpackage Directory
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Directory_Controller_Tab_Website extends Directory_Controller_Tab
{
	protected $_titleHeaderColor = 'gray';
	// protected $_titleHeaderColor = 'bordered-gray';
	protected $_faTitleIcon = 'fa fa-globe';

	protected function _execute($oPersonalDataInnerWrapper)
	{
		$aDirectory_Relations = $this->relation->findAll();

		$oButtons = $this->_buttons();

		if (count($this->_aDirectory_Relations))
		{
			foreach ($this->_aDirectory_Relations as $oDirectory_Relation)
			{
				$oRowElements = $this->_websiteTemplate($oDirectory_Relation);

				$oPersonalDataInnerWrapper->add(
					$oRowElements->add($oButtons)
				);
			}
		}
		else
		{
			$oRowElements = $this->_websiteTemplate();

			$oPersonalDataInnerWrapper->add(
				$oRowElements->add($oButtons)
			);
		}
	}

	protected function _websiteTemplate($oUser_Directory_Website = NULL)
	{
		$sNameSuffix = $oUser_Directory_Website ? '#' . $oUser_Directory_Website->Directory_Website->id : '[]';
		
		$oRowElements = Admin_Form_Entity::factory('Div')
			->class('row')
			->add(
				Admin_Form_Entity::factory('Input')
					->name($this->prefix . 'website_address' . $sNameSuffix)
					->value($oUser_Directory_Website ? $oUser_Directory_Website->Directory_Website->value : '')
					->caption(Core::_('Directory_Website.site'))
					->divAttr(array('class' => 'form-group ' . ($this->showPublicityControlElement ? 'col-xs-4' : 'col-lg-4 col-xs-5')))
			)
			->add(
				Admin_Form_Entity::factory('Input')
					->name($this->prefix . 'website_description' . $sNameSuffix)
					->value($oUser_Directory_Website ? $oUser_Directory_Website->Directory_Website->description : '')
					->caption(Core::_('Directory_Website.name'))
					->divAttr(array('class' => 'form-group no-padding-left ' . ($this->showPublicityControlElement ? 'col-sm-4 col-xs-3' : 'col-lg-5 col-sm-6 col-xs-5')))
			);

		if ($this->showPublicityControlElement)
		{
			$iWebsitePublic = $oUser_Directory_Website ? $oUser_Directory_Website->Directory_Website->public : 0;

			$oRowElements->add(
				Admin_Form_Entity::factory('Checkbox')
					->divAttr(array('class' => 'col-xs-3 col-sm-2 no-padding margin-top-23'))
					->name($this->prefix . 'website_public' . $sNameSuffix)
					->checked($iWebsitePublic ? $iWebsitePublic : NULL)
					->value($iWebsitePublic)
					->caption(Core::_('Directory_Website.website_public'))
			);
			
			// Для нового свойства добавляет скрытое поле, хранящее состояние чекбокса
			if (!$oUser_Directory_Website)
			{
				$oRowElements->add(
					Core::factory('Core_Html_Entity_Input')
						->type('hidden')
						->value(0)
						->name($this->prefix . 'website_public_value' . $sNameSuffix)
				);
			}
		}

		return $oRowElements;
	}
}