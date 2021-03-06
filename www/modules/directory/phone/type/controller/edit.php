<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Directory_Phone_Type_Controller_Edit
 *
 * @package HostCMS
 * @subpackage Directory
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2018 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Directory_Phone_Type_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		parent::setObject($object);

		$oMainTab = $this->getTab('main');

		$oMainTab
			->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'));

		$oMainTab->move($this->getField('name')->divAttr(array('class' => 'form-group col-xs-12')), $oMainRow1);
		$oMainTab->move($this->getField('sorting')->divAttr(array('class' => 'form-group col-xs-12 col-sm-3')), $oMainRow2);

		$title = $this->_object->id
			? Core::_('Directory_Phone_Type.edit_title')
			: Core::_('Directory_Phone_Type.add_title');

		$this->title($title);

		return $this;
	}}