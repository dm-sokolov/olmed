<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Siteuser_Type_Controller_Edit
 *
 * @package HostCMS
 * @subpackage Siteuser
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Siteuser_Type_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		parent::setObject($object);

		$this->title($this->_object->id
			? Core::_('Siteuser_Type.edit_siteuser_types_title', $this->_object->name)
			: Core::_('Siteuser_Type.add_siteuser_types_title'));

		$oMainTab = $this->getTab('main');

		$oMainTab
			->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))	;

		$sColorValue = ($this->_object->id && $this->getField('color')->value)
			? $this->getField('color')->value
			: '#eee';

		$this->getField('color')
			->colorpicker(TRUE)
			->value($sColorValue);

		$oMainTab
			->move($this->getField('name'), $oMainRow1)
			->move($this->getField('color')->set('data-control', 'hue'), $oMainRow2);

		return $this;
	}
}