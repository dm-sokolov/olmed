<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Directory_Controller_Tab_Social
 *
 * @package HostCMS
 * @subpackage Directory
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Directory_Controller_Tab_Social extends Directory_Controller_Tab
{
	protected $_directoryTypeName = 'Directory_Social_Type';
	protected $_titleHeaderColor = 'blue';
	// protected $_titleHeaderColor = 'bordered-blue';
	protected $_faTitleIcon = 'fa fa-share-alt';

	protected function _execute($oPersonalDataInnerWrapper)
	{
		$aDirectory_Relations = $this->relation->findAll();

		$aMasDirectoryTypes = $this->_getDirectoryTypes();

		$oButtons = $this->_buttons();

		if (count($this->_aDirectory_Relations))
		{
			foreach ($this->_aDirectory_Relations as $oDirectory_Relation)
			{
				$oRowElements = $this->_socialTemplate($aMasDirectoryTypes, $oDirectory_Relation);

				$oPersonalDataInnerWrapper->add(
					$oRowElements->add($oButtons)
				);
			}
		}
		else
		{
			$oRowElements = $this->_socialTemplate($aMasDirectoryTypes);

			$oPersonalDataInnerWrapper->add(
				$oRowElements->add($oButtons)
			);
		}
	}

	protected function _socialTemplate($aMasDirectorySocials, $oUser_Directory_Social = NULL)
	{		
		$sNameSuffix = $oUser_Directory_Social ? '#' . $oUser_Directory_Social->Directory_Social->id : '[]';
		
		$oRowElements = Admin_Form_Entity::factory('Div')
			->class('row')
			->add(
				Admin_Form_Entity::factory('Select')
					->options($aMasDirectorySocials)
					->name($this->prefix . 'social' . $sNameSuffix)
					->value($oUser_Directory_Social ? $oUser_Directory_Social->Directory_Social->directory_social_type_id : '')
					->caption(Core::_('Directory_Social.social'))
					->divAttr(array('class' => 'form-group col-xs-4'))
			)
			->add(
				Admin_Form_Entity::factory('Input')
					->name($this->prefix . 'social_address' . ($oUser_Directory_Social ? '#' . $oUser_Directory_Social->Directory_Social->id : '[]'))
					->value($oUser_Directory_Social ? $oUser_Directory_Social->Directory_Social->value : '')
					->caption(Core::_('Directory_Social.social_address'))
					->divAttr(array('class' => 'form-group no-padding-left ' . ($this->showPublicityControlElement ? 'col-sm-4 col-xs-3' : 'col-lg-5 col-sm-6 col-xs-5')))
			);

		if ($this->showPublicityControlElement)
		{
			$iSocialPublic = $oUser_Directory_Social ? $oUser_Directory_Social->Directory_Social->public : 0;

			$oRowElements->add(
				Admin_Form_Entity::factory('Checkbox')
					->divAttr(array('class' => 'col-xs-3 col-sm-2 no-padding margin-top-23'))
					->name($this->prefix . 'social_public' . $sNameSuffix)
					->checked($iSocialPublic ? $iSocialPublic : NULL)
					->value($iSocialPublic)
					->caption(Core::_('Directory_Social.social_public'))
			);
			
			// Для нового свойства добавляет скрытое поле, хранящее состояние чекбокса
			if (!$oUser_Directory_Social)
			{
				$oRowElements->add(
					Core::factory('Core_Html_Entity_Input')
						->type('hidden')
						->value(0)
						->name($this->prefix . 'social_public_value' . $sNameSuffix)
				);
			}
		}

		return $oRowElements;
	}
}