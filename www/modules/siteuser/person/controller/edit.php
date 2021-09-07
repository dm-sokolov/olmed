<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Siteuser_Person_Controller_Edit
 *
 * @package HostCMS
 * @subpackage Siteuser
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Siteuser_Person_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		$iSiteuserId = Core_Array::getGet('siteuser_id');

		if (!$object->id)
		{
			$object->siteuser_id = $iSiteuserId;
		}

		$this->addSkipColumn('image');

		return parent::setObject($object);
	}

	/**
	 * Prepare backend item's edit form
	 *
	 * @return self
	 */
	protected function _prepareForm()
	{
		parent::_prepareForm();

		$object = $this->_object;

		$modelName = $object->getModelName();

		$windowId = $this->_Admin_Form_Controller->getWindowId();

		$oMainTab = $this->getTab('main');

		$oSiteuser = Core_Entity::factory('Siteuser', $this->_object->siteuser_id);

		switch ($modelName)
		{
			case 'siteuser_person':

				$title = $object->id
					? Core::_('Siteuser_Person.siteuser_person_edit_form_title', $object->getFullName(), $oSiteuser->login, FALSE)
					: Core::_('Siteuser_Person.siteuser_person_add_form_title', $oSiteuser->login, FALSE);

				// Email'ы
				$oSiteuserPersonEmailsRow = Directory_Controller_Tab::instance('email')
					->title(Core::_('Directory_Email.emails'))
					->relation($object->Siteuser_Person_Directory_Emails)
					->showPublicityControlElement(TRUE)
					->execute();

				// Телефоны
				$oSiteuserPersonPhonesRow = Directory_Controller_Tab::instance('phone')
					->title(Core::_('Directory_Phone.phones'))
					->relation($object->Siteuser_Person_Directory_Phones)
					->showPublicityControlElement(TRUE)
					->execute();

				// Социальные сети
				$oSiteuserPersonSocialsRow = Directory_Controller_Tab::instance('social')
					->title(Core::_('Directory_Social.socials'))
					->relation($object->Siteuser_Person_Directory_Socials)
					->showPublicityControlElement(TRUE)
					->execute();

				// Мессенджеры
				$oSiteuserPersonMessengersRow = Directory_Controller_Tab::instance('messenger')
					->title(Core::_('Directory_Messenger.messengers'))
					->relation($object->Siteuser_Person_Directory_Messengers)
					->showPublicityControlElement(TRUE)
					->execute();

				// Сайты
				$oSiteuserPersonWebsitesRow = Directory_Controller_Tab::instance('website')
					->title(Core::_('Directory_Website.sites'))
					->relation($object->Siteuser_Person_Directory_Websites)
					->showPublicityControlElement(TRUE)
					->execute();

				$oMainTab
					->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oSiteuserPersonPhonesRow)
					->add($oSiteuserPersonEmailsRow)
					->add($oMainRow4 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow5 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow3 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oSiteuserPersonSocialsRow)
					->add($oSiteuserPersonMessengersRow)
					->add($oSiteuserPersonWebsitesRow);

				$oMainTab
					->move($this->getField('surname')->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))->class('form-control input-lg semi-bold black'), $oMainRow1)
					->move($this->getField('name')->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))->class('form-control input-lg semi-bold black'), $oMainRow1)
					->move($this->getField('patronymic')->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))->class('form-control input-lg'), $oMainRow1)
					->move($this->getField('birthday')->divAttr(array('class' => 'form-group col-xs-12 col-sm-2')), $oMainRow3)
					->move($this->getField('post')->divAttr(array('class' => 'form-group col-xs-12 col-sm-3')), $oMainRow3);

				$sFormPath = $this->_Admin_Form_Controller->getPath();

				$aConfig = Core_Config::instance()->get('siteuser_person_config', array()) + array (
					'max_height' => 130,
					'max_width' => 130
				);

				// Изображение
				$oImageField = Admin_Form_Entity::factory('File');
				$oImageField
					->type('file')
					->caption(Core::_('Siteuser_Person.image'))
					->name('image')
					->id('image')
					->largeImage(
						array(
							'max_width' => $aConfig['max_width'],
							'max_height' => $aConfig['max_height'],
							'path' => is_file($object->getImageFilePath())
								? $object->getImageFileHref()
								: '',
							'show_params' => TRUE,
							'preserve_aspect_ratio_checkbox_checked' => FALSE,
							// deleteWatermarkFile
							'delete_onclick' => "$.adminLoad({path: '{$sFormPath}', additionalParams: 'hostcms[checked][{$this->_datasetId}][{$object->id}]=1', action: 'deleteImageFile', windowId: '{$windowId}'}); return false",
							'place_watermark_checkbox' => FALSE,
							'place_watermark_x_show' => FALSE,
							'place_watermark_y_show' => FALSE
						)
					)
					->smallImage(
						array(
							'show' => FALSE
						)
					)
					->divAttr(array('class' => 'input-group col-xs-12 col-sm-3'));

				$oMainRow3->add($oImageField);

				$oMainTab->delete($this->getField('sex'));

				// Добавляем пол физ. лица
				$oSiteuserPersonSex = Admin_Form_Entity::factory('Radiogroup')
					->name('sex')
					->id('sex' . time())
					->caption(Core::_('Siteuser_Person.sex'))
					->value($object->sex)
					->divAttr(array('class' => 'form-group col-xs-12'))
					->radio(array(
						0 => Core::_('Siteuser_Person.male'),
						1 => Core::_('Siteuser_Person.female')
					))
					->ico(
						array(
							0 => 'fa-mars',
							1 => 'fa-venus'
					))
					->colors(
						array(
							0 => 'btn-sky',
							1 => 'btn-pink'
						)
					);

				$oMainRow3->add($oSiteuserPersonSex);

				$this->getField('postcode')->divAttr(array('class' => 'form-group col-xs-12 col-sm-4'));
				$this->getField('country')->divAttr(array('class' => 'form-group col-xs-12 col-sm-4'));
				$this->getField('city')->divAttr(array('class' => 'form-group col-xs-12 col-sm-4'));

				$oMainTab
					->move($this->getField('postcode'), $oMainRow4)
					->move($this->getField('country'), $oMainRow4)
					->move($this->getField('city'), $oMainRow4);

				$this->getField('address')->divAttr(array('class' => 'form-group col-xs-12'));
				$oMainTab
					->move($this->getField('address'), $oMainRow5);
			break;
			case 'siteuser_company':
			default:
				$title = $object->id
					? Core::_('Siteuser_Company.siteuser_company_edit_form_title', $object->name, $oSiteuser->login, FALSE)
					: Core::_('Siteuser_Company.siteuser_company_add_form_title', $oSiteuser->login, FALSE);

				$oMainTab = $this->getTab('main');

				$oTabContacts = Admin_Form_Entity::factory('Tab')
					->caption(Core::_('Siteuser_Company.tabContacts'))
					->name('Contacts');

				$oTabBankingDetails = Admin_Form_Entity::factory('Tab')
					->caption(Core::_('Siteuser_Company.tabBankingDetails'))
					->name('BankingDetails');

				$this
					->addTabAfter($oTabContacts, $oMainTab)
					->addTabAfter($oTabBankingDetails, $oTabContacts);

				$oMainTab
					->add($oMainTabRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainTabRow2 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainTabRow3 = Admin_Form_Entity::factory('Div')->class('row'));

				$oTabContacts
					->add($oTabContactsRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oTabContactsRow2 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oTabContactsRow3 = Admin_Form_Entity::factory('Div')->class('row'));

				$oTabBankingDetails
					->add($oTabBankingDetailsRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oTabBankingDetailsRow2 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oTabBankingDetailsRow3 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oTabBankingDetailsRow4 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oTabBankingDetailsRow5 = Admin_Form_Entity::factory('Div')->class('row'));

				$oMainTab
					->move($this->getField('name')->class('form-control input-lg semi-bold black'), $oMainTabRow1)
					->move($this->getField('description'), $oMainTabRow2)
					->move($this->getField('business_area')->divAttr(array('class' => 'form-group col-xs-12 col-md-4')), $oMainTabRow2)
					->move($this->getField('headcount')->divAttr(array('class' => 'form-group col-xs-12 col-md-4')), $oMainTabRow2)
					->move($this->getField('annual_turnover')->divAttr(array('class' => 'form-group col-xs-12 col-md-4')), $oMainTabRow2)
					->move($this->getField('tin')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6')), $oTabBankingDetailsRow2)
					->move($this->getField('bank_account')->divAttr(array('class' => 'form-group col-xs-12')), $oTabBankingDetailsRow5);

				// Адреса
				$oSiteuserCompanyAddressesRow = Directory_Controller_Tab::instance('address')
					->title(Core::_('Directory_Address.address'))
					->relation($object->Siteuser_Company_Directory_Addresses)
					->showPublicityControlElement(TRUE)
					->execute();

				// Телефоны
				$oSiteuserCompanyPhonesRow = Directory_Controller_Tab::instance('phone')
					->title(Core::_('Directory_Phone.phones'))
					->relation($object->Siteuser_Company_Directory_Phones)
					->showPublicityControlElement(TRUE)
					->execute();

				// Email'ы
				$oSiteuserCompanyEmailsRow = Directory_Controller_Tab::instance('email')
					->title(Core::_('Directory_Email.emails'))
					->relation($object->Siteuser_Company_Directory_Emails)
					->showPublicityControlElement(TRUE)
					->execute();

				// Социальные сети
				$oSiteuserCompanySocialsRow = Directory_Controller_Tab::instance('social')
					->title(Core::_('Directory_Social.socials'))
					->relation($object->Siteuser_Company_Directory_Socials)
					->showPublicityControlElement(TRUE)
					->execute();

				// Мессенджеры
				$oSiteuserCompanyMessengersRow = Directory_Controller_Tab::instance('messenger')
					->title(Core::_('Directory_Messenger.messengers'))
					->relation($object->Siteuser_Company_Directory_Messengers)
					->showPublicityControlElement(TRUE)
					->execute();

				// Сайты
				$oSiteuserCompanyWebsitesRow = Directory_Controller_Tab::instance('website')
					->title(Core::_('Directory_Website.sites'))
					->relation($object->Siteuser_Company_Directory_Websites)
					->showPublicityControlElement(TRUE)
					->execute();

				$oTabContacts
					->add($oSiteuserCompanyPhonesRow)
					->add($oSiteuserCompanyEmailsRow)
					->add($oSiteuserCompanyAddressesRow)
					->add($oSiteuserCompanySocialsRow)
					->add($oSiteuserCompanyMessengersRow)
					->add($oSiteuserCompanyWebsitesRow);

				$sFormPath = $this->_Admin_Form_Controller->getPath();

				$aConfig = Core_Config::instance()->get('siteuser_company_config', array()) + array (
					'max_height' => 130,
					'max_width' => 130
				);

				// Изображение
				$oImageField = Admin_Form_Entity::factory('File');
				$oImageField
					->type('file')
					->caption(Core::_('Siteuser_Company.image'))
					->name('image')
					->id('image')
					->largeImage(
						array(
							'max_width' => $aConfig['max_width'],
							'max_height' => $aConfig['max_height'],
							'path' => is_file($object->getImageFilePath())
								? $object->getImageFileHref()
								: '',
							'show_params' => TRUE,
							'preserve_aspect_ratio_checkbox_checked' => FALSE,
							// deleteWatermarkFile
							'delete_onclick' => "$.adminLoad({path: '{$sFormPath}', additionalParams: 'hostcms[checked][{$this->_datasetId}][{$object->id}]=1', action: 'deleteImageFile', windowId: '{$windowId}'}); return false",
							'place_watermark_checkbox' => FALSE,
							'place_watermark_x_show' => FALSE,
							'place_watermark_y_show' => FALSE
						)
					)
					->smallImage(
						array(
							'show' => FALSE
						)
					)
					->divAttr(array('class' => 'input-group col-lg-6 col-md-6 col-sm-12 col-xs-12'));

				$oMainTabRow3->add($oImageField);

			break;
		}

		$this->title($title);

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @hostcms-event Informationsystem_Item_Controller_Edit.onAfterRedeclaredApplyObjectProperty
	 */
	protected function _applyObjectProperty()
	{
		parent::_applyObjectProperty();

		$object = $this->_object;

		$modelName = $object->getModelName();

		$windowId = $this->_Admin_Form_Controller->getWindowId();

		$aObject_Directory_Emails = $modelName == 'siteuser_person'
			? $object->Siteuser_Person_Directory_Emails->findAll()
			: $object->Siteuser_Company_Directory_Emails->findAll();

		foreach ($aObject_Directory_Emails as $oObject_Directory_Email)
		{
			$oDirectory_Email = $oObject_Directory_Email->Directory_Email;

			$sEmail = trim(Core_Array::getPost("email#{$oDirectory_Email->id}"));

			if (!empty($sEmail))
			{
				$oDirectory_Email
					->directory_email_type_id(intval(Core_Array::getPost("email_type#{$oDirectory_Email->id}", 0)))
					->public(!is_null(Core_Array::getPost("email_public#{$oDirectory_Email->id}")) ? 1 : 0)
					->value($sEmail)
					->save();
			}
			else
			{
				// Удаляем пустую строку с полями
				ob_start();
				Core::factory('Core_Html_Entity_Script')
					->value("$.deleteFormRow($(\"#{$windowId} select[name='email_type#{$oDirectory_Email->id}']\").closest('.row').find('.btn-delete111').get(0));")
					->execute();

				$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				$oObject_Directory_Email->Directory_Email->delete();
			}
		}

		// Электронные адреса, новые значения
		$aEmails = Core_Array::getPost('email', array());
		$aEmail_Types = Core_Array::getPost('email_type', array());
		//$aEmail_Public = Core_Array::getPost('email_public', array());
		$aEmail_Public = Core_Array::getPost('email_public_value', array());

		if (is_array($aEmails) && count($aEmails))
		{
			$i = 0;
			foreach ($aEmails as $key => $sEmail)
			{
				$sEmail = trim($sEmail);

				if (!empty($sEmail))
				{
					$oDirectory_Email = Core_Entity::factory('Directory_Email')
						->directory_email_type_id(intval(Core_Array::get($aEmail_Types, $key)))
						->public(intval(Core_Array::get($aEmail_Public, $key)))
						->value($sEmail)
						->save();

					$object->add($oDirectory_Email);

					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("var rowElement = $(\"#{$windowId} select[name='email_type\\[\\]']\").eq({$i}).prop('name', 'email_type#{$oDirectory_Email->id}').closest('.row');
						$(\"#{$windowId} input[name='email\\[\\]']\").eq({$i}).prop('name', 'email#{$oDirectory_Email->id}');
						$(\"#{$windowId} input[name='email_public\\[\\]']\").eq({$i}).prop('name', 'email_public#{$oDirectory_Email->id}');
						rowElement.find('[name=\"email_public_value[]\"]').remove();
						rowElement.find('.btn-delete').removeClass('hide');
						")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				}
				else
				{
					$i++;
				}
			}
		}

		if ($modelName == 'siteuser_company')
		{
			// Адреса, установленные значения
			$aObject_Directory_Addresses = $object->Siteuser_Company_Directory_Addresses->findAll();

			foreach ($aObject_Directory_Addresses as $oObject_Directory_Address)
			{
				$oDirectory_Adress = $oObject_Directory_Address->Directory_Address;

				$sAdress = trim(Core_Array::getPost("address#{$oDirectory_Adress->id}"));
				$sCountry = strval(Core_Array::getPost("address_country#{$oDirectory_Adress->id}"));
				$sPostcode = strval(Core_Array::getPost("address_postcode#{$oDirectory_Adress->id}"));
				$sCity = strval(Core_Array::getPost("address_city#{$oDirectory_Adress->id}"));

				if (strlen($sAdress) || strlen($sCountry) || strlen($sPostcode) || strlen($sCity))
				{
					$oDirectory_Adress
						->directory_address_type_id(intval(Core_Array::getPost("address_type#{$oDirectory_Adress->id}", 0)))
						->public(!is_null(Core_Array::getPost("address_public#{$oDirectory_Adress->id}")) ? 1 : 0)
						->country($sCountry)
						->postcode($sPostcode)
						->city($sCity)
						->value($sAdress)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} select[name='address_type#{$oDirectory_Adress->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();
					$this->_Admin_Form_Controller->addMessage(ob_get_clean());

					$oObject_Directory_Address->Directory_Address->delete();
				}
			}

			//Адреса, новые значения
			$aAddresses = Core_Array::getPost('address', array());
			$aAddress_Types = Core_Array::getPost('address_type', array());
			$aAddress_Country = Core_Array::getPost('address_country', array());
			$aAddress_Postcode = Core_Array::getPost('address_postcode', array());
			$aAddress_City = Core_Array::getPost('address_city', array());
			//$aAddress_Public = Core_Array::getPost('address_public', array());
			$aAddress_Public = Core_Array::getPost('address_public_value', array());

			if (is_array($aAddresses) && count($aAddresses))
			{
				$i = 0;
				foreach ($aAddresses as $key => $sAddress)
				{
					$sAddress = trim($sAddress);
					$sCountry = strval(Core_Array::get($aAddress_Country, $key));
					$sPostcode = strval(Core_Array::get($aAddress_Postcode, $key));
					$sCity = strval(Core_Array::get($aAddress_City, $key));

					if (strlen($sAddress) || strlen($sCountry) || strlen($sPostcode) || strlen($sCity))
					{
						$oDirectory_Address = Core_Entity::factory('Directory_Address')
							->directory_address_type_id(intval(Core_Array::get($aAddress_Types, $key)))
							->public(intval(Core_Array::get($aAddress_Public, $key)))
							->country($sCountry)
							->postcode($sPostcode)
							->city($sCity)
							->value($sAddress)
							->save();

						$object->add($oDirectory_Address);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("var rowElement = $(\"#{$windowId} select[name='address_type\\[\\]']\").eq({$i}).prop('name', 'address_type#{$oDirectory_Address->id}').closest('.row');
							$(\"#{$windowId} input[name='address\\[\\]']\").eq({$i}).prop('name', 'address#{$oDirectory_Address->id}');
							$(\"#{$windowId} input[name='address_public\\[\\]']\").eq({$i}).prop('name', 'address_public#{$oDirectory_Address->id}');
							$(\"#{$windowId} input[name='address_country\\[\\]']\").eq({$i}).prop('name', 'address_country#{$oDirectory_Address->id}');
							$(\"#{$windowId} input[name='address_postcode\\[\\]']\").eq({$i}).prop('name', 'address_postcode#{$oDirectory_Address->id}');
							$(\"#{$windowId} input[name='address_city\\[\\]']\").eq({$i}).prop('name', 'address_city#{$oDirectory_Address->id}');

							rowElement.find('[name=\"address_public_value[]\"]').remove();
							rowElement.find('.btn-delete').removeClass('hide');
							")
							->execute();

						$this->_Admin_Form_Controller->addMessage(ob_get_clean());
					}
					else
					{
						$i++;
					}
				}
			}
		}

		$aObject_Directory_Phones = $modelName == 'siteuser_person'
			? $object->Siteuser_Person_Directory_Phones->findAll()
			: $object->Siteuser_Company_Directory_Phones->findAll();

		foreach ($aObject_Directory_Phones as $oObject_Directory_Phone)
		{
			$oDirectory_Phone = $oObject_Directory_Phone->Directory_Phone;

			$sPhone = trim(Core_Array::getPost("phone#{$oDirectory_Phone->id}"));

			if (!empty($sPhone))
			{
				$oDirectory_Phone
					->directory_phone_type_id(intval(Core_Array::getPost("phone_type#{$oDirectory_Phone->id}", 0)))
					->public(!is_null(Core_Array::getPost("phone_public#{$oDirectory_Phone->id}")) ? 1 : 0)
					->value($sPhone)
					->save();
			}
			else
			{
				// Удаляем пустую строку с полями
				ob_start();
				Core::factory('Core_Html_Entity_Script')
					->value("$.deleteFormRow($(\"#{$windowId} select[name='phone_type#{$oDirectory_Phone->id}']\").closest('.row').find('.btn-delete').get(0));")
					->execute();
				$this->_Admin_Form_Controller->addMessage(ob_get_clean());

				$oObject_Directory_Phone->Directory_Phone->delete();
			}
		}

		// Телефоны, новые значения
		$aPhones = Core_Array::getPost('phone', array());
		$aPhone_Types = Core_Array::getPost('phone_type', array());
		//$aPhone_Public = Core_Array::getPost('phone_public', array());
		$aPhone_Public = Core_Array::getPost('phone_public_value', array());

		if (is_array($aPhones) && count($aPhones))
		{
			$i = 0;
			foreach ($aPhones as $key => $sPhone)
			{
				$sPhone = trim($sPhone);

				if (!empty($sPhone))
				{
					$oDirectory_Phone = Core_Entity::factory('Directory_Phone')
						->directory_phone_type_id(intval(Core_Array::get($aPhone_Types, $key)))
						->public(intval(Core_Array::get($aPhone_Public, $key)))
						->value($sPhone)
						->save();

					$object->add($oDirectory_Phone);

					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("var rowElement = $(\"#{$windowId} select[name='phone_type\\[\\]']\").eq({$i}).prop('name', 'phone_type#{$oDirectory_Phone->id}').closest('.row');
						$(\"#{$windowId} input[name='phone\\[\\]']\").eq({$i}).prop('name', 'phone#{$oDirectory_Phone->id}');
						$(\"#{$windowId} input[name='phone_public\\[\\]']\").eq({$i}).prop('name', 'phone_public#{$oDirectory_Phone->id}');
						rowElement.find('[name=\"phone_public_value[]\"]').remove();
						rowElement.find('.btn-delete').removeClass('hide');
						")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				}
				else
				{
					$i++;
				}
			}
		}

		// Социальные сети, установленные значения
		$aObject_Directory_Socials = $modelName == 'siteuser_person'
			? $object->Siteuser_Person_Directory_Socials->findAll()
			: $object->Siteuser_Company_Directory_Socials->findAll();

		foreach ($aObject_Directory_Socials as $oObject_Directory_Social)
		{
			$oDirectory_Social = $oObject_Directory_Social->Directory_Social;

			$sSocial_Address = trim(Core_Array::getPost("social_address#{$oDirectory_Social->id}"));

			if (!empty($sSocial_Address))
			{
				$aUrl = @parse_url($sSocial_Address);

				// Если не был указан протокол, или
				// указанный протокол некорректен для url
				!array_key_exists('scheme', $aUrl)
					&& $sSocial_Address = 'http://' . $sSocial_Address;

				$oDirectory_Social
					->directory_social_type_id(intval(Core_Array::getPost("social#{$oDirectory_Social->id}", 0)))
					->public(!is_null(Core_Array::getPost("social_public#{$oDirectory_Social->id}")) ? 1 : 0)
					->value($sSocial_Address)
					->save();
			}
			else
			{
				// Удаляем пустую строку с полями
				ob_start();
				Core::factory('Core_Html_Entity_Script')
					->value("$.deleteFormRow($(\"#{$windowId} select[name='social#{$oDirectory_Social->id}']\").closest('.row').find('.btn-delete').get(0));")
					->execute();
				$this->_Admin_Form_Controller->addMessage(ob_get_clean());

				$oObject_Directory_Social->Directory_Social->delete();
			}
		}

		// Социальные сети, новые значения
		$aSocial_Addresses = Core_Array::getPost('social_address', array());
		$aSocials = Core_Array::getPost('social', array());
		//$aSocial_Public = Core_Array::getPost('social_public', array());
		$aSocial_Public = Core_Array::getPost('social_public_value', array());

		if (is_array($aSocial_Addresses) && count($aSocial_Addresses))
		{
			$i = 0;
			foreach ($aSocial_Addresses as $key => $sSocial_Address)
			{
				$sSocial_Address = trim($sSocial_Address);

				if (!empty($sSocial_Address))
				{
					$aUrl = @parse_url($sSocial_Address);

					// Если не был указан протокол, или
					// указанный протокол некорректен для url
					!array_key_exists('scheme', $aUrl)
						&& $sSocial_Address = 'http://' . $sSocial_Address;

					$oDirectory_Social = Core_Entity::factory('Directory_Social')
						->directory_social_type_id(intval(Core_Array::get($aSocials, $key)))
						//->public(!is_null(Core_Array::get($aSocial_Public, $key)) ? 1 : 0)
						->public(intval(Core_Array::get($aSocial_Public, $key)))
						->value($sSocial_Address)
						->save();

					$object->add($oDirectory_Social);

					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("var rowElement = $(\"#{$windowId} select[name='social\\[\\]']\").eq({$i}).prop('name', 'social#{$oDirectory_Social->id}').closest('.row');
						$(\"#{$windowId} input[name='social_address\\[\\]']\").eq({$i}).prop('name', 'social_address#{$oDirectory_Social->id}');
						$(\"#{$windowId} input[name='social_public\\[\\]']\").eq({$i}).prop('name', 'social_public#{$oDirectory_Social->id}');
						rowElement.find('[name=\"social_public_value[]\"]').remove();
						rowElement.find('.btn-delete').removeClass('hide');
						")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				}
				else
				{
					$i++;
				}
			}
		}

		// Мессенджеры, установленные значения
		$aObject_Directory_Messengers = $modelName == 'siteuser_person'
			? $object->Siteuser_Person_Directory_Messengers->findAll()
			: $object->Siteuser_Company_Directory_Messengers->findAll();

		foreach ($aObject_Directory_Messengers as $oObject_Directory_Messenger)
		{
			$oDirectory_Messenger = $oObject_Directory_Messenger->Directory_Messenger;

			$sMessenger_Address = trim(Core_Array::getPost("messenger_username#{$oDirectory_Messenger->id}"));

			if (!empty($sMessenger_Address))
			{
				$oDirectory_Messenger
					->directory_messenger_type_id(intval(Core_Array::getPost("messenger#{$oDirectory_Messenger->id}", 0)))
					->public(!is_null(Core_Array::getPost("messenger_public#{$oDirectory_Messenger->id}")) ? 1 : 0)
					->value($sMessenger_Address)
					->save();
			}
			else
			{
				// Удаляем пустую строку с полями
				ob_start();
				Core::factory('Core_Html_Entity_Script')
					->value("$.deleteFormRow($(\"#{$windowId} select[name='messenger#{$oDirectory_Messenger->id}']\").closest('.row').find('.btn-delete').get(0));")
					->execute();
				$this->_Admin_Form_Controller->addMessage(ob_get_clean());

				$oObject_Directory_Messenger->Directory_Messenger->delete();
			}
		}

		// Мессенджеры, новые значения
		$aMessenger_Addresses = Core_Array::getPost('messenger_username', array());
		$aMessengers = Core_Array::getPost('messenger', array());
		//$aMessenger_Public = Core_Array::getPost('messenger_public', array());
		$aMessenger_Public = Core_Array::getPost('messenger_public_value', array());

		if (is_array($aMessenger_Addresses) && count($aMessenger_Addresses))
		{
			$i = 0;
			foreach ($aMessenger_Addresses as $key => $sMessenger_Address)
			{
				$sMessenger_Address = trim($sMessenger_Address);

				//echo 'count($aMessenger_Public) = ' . count($aMessenger_Public);
				if (!empty($sMessenger_Address))
				{
					$oDirectory_Messenger = Core_Entity::factory('Directory_Messenger')
						->directory_messenger_type_id(intval(Core_Array::get($aMessengers, $key)))
						->public(intval(Core_Array::get($aMessenger_Public, $key)))
						->value($sMessenger_Address)
						->save();

					$object->add($oDirectory_Messenger);

					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("var rowElement = $(\"#{$windowId} select[name='messenger\\[\\]']\").eq({$i}).prop('name', 'messenger#{$oDirectory_Messenger->id}').closest('.row');
						$(\"#{$windowId} input[name='messenger_username\\[\\]']\").eq({$i}).prop('name', 'messenger_username#{$oDirectory_Messenger->id}');
						$(\"#{$windowId} input[name='messenger_public\\[\\]']\").eq({$i}).prop('name', 'messenger_public#{$oDirectory_Messenger->id}');
						rowElement.find('[name=\"messenger_public_value[]\"]').remove();
						rowElement.find('.btn-delete').removeClass('hide');
						")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				}
				else
				{
					$i++;
				}
			}
		}

		// Cайты, установленные значения
		$aObject_Directory_Websites = $modelName == 'siteuser_person'
			? $object->Siteuser_Person_Directory_Websites->findAll()
			: $object->Siteuser_Company_Directory_Websites->findAll();

		foreach ($aObject_Directory_Websites as $oObject_Directory_Website)
		{
			$oDirectory_Website = $oObject_Directory_Website->Directory_Website;

			$sWebsite_Address = trim(Core_Array::getPost("website_address#{$oDirectory_Website->id}"));

			if (!empty($sWebsite_Address))
			{
				$aUrl = @parse_url($sWebsite_Address);

				// Если не был указан протокол, или
				// указанный протокол некорректен для url
				!array_key_exists('scheme', $aUrl)
					&& $sWebsite_Address = 'http://' . $sWebsite_Address;

				$oDirectory_Website
					->description(strval(Core_Array::getPost("website_description#{$oDirectory_Website->id}")))
					->public(!is_null(Core_Array::getPost("website_public#{$oDirectory_Website->id}")) ? 1 : 0)
					->value($sWebsite_Address)
					->save();
			}
			else
			{
				// Удаляем пустую строку с полями
				ob_start();
				Core::factory('Core_Html_Entity_Script')
					->value("$.deleteFormRow($(\"#{$windowId} input[name='website_address#{$oDirectory_Website->id}']\").closest('.row').find('.btn-delete').get(0));")
					->execute();

				$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				$oObject_Directory_Website->Directory_Website->delete();
			}
		}

		// Сайты, новые значения
		$aWebsite_Addresses = Core_Array::getPost('website_address', array());
		$aWebsite_Names = Core_Array::getPost('website_description', array());
		//$aWebsite_Public = Core_Array::getPost('website_public', array());
		$aWebsite_Public = Core_Array::getPost('website_public_value', array());

		if (is_array($aWebsite_Addresses) && count($aWebsite_Addresses))
		{
			$i = 0;
			foreach ($aWebsite_Addresses as $key => $sWebsite_Address)
			{
				$sWebsite_Address = trim($sWebsite_Address);

				if (!empty($sWebsite_Address))
				{
					$aUrl = @parse_url($sWebsite_Address);

					// Если не был указан протокол, или
					// указанный протокол некорректен для url
					!array_key_exists('scheme', $aUrl)
						&& $sWebsite_Address = 'http://' . $sWebsite_Address;

					$oDirectory_Website = Core_Entity::factory('Directory_Website')
						//->public(!is_null(Core_Array::get($aWebsite_Public, $key)) ? 1 : 0)
						->public(intval(Core_Array::get($aWebsite_Public, $key)))
						->description(Core_Array::get($aWebsite_Names, $key))
						->value($sWebsite_Address);

					$object->add($oDirectory_Website);

					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("var rowElement = $(\"#{$windowId} input[name='website_address\\[\\]']\").eq({$i}).prop('name', 'website_address#{$oDirectory_Website->id}').closest('.row');
						$(\"#{$windowId} input[name='website_description\\[\\]']\").eq({$i}).prop('name', 'website_description#{$oDirectory_Website->id}');
						$(\"#{$windowId} input[name='website_public\\[\\]']\").eq({$i}).prop('name', 'website_public#{$oDirectory_Website->id}');
						rowElement.find('[name=\"website_public_value[]\"]').remove();
						rowElement.find('.btn-delete').removeClass('hide');
						")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
				}
				else
				{
					$i++;
				}
			}
		}

		if (
			// Поле файла существует
			!is_null($aFileData = Core_Array::getFiles('image', NULL))
			// и передан файл
			&& intval($aFileData['size']) > 0)
		{
			if (Core_File::isValidExtension($aFileData['name'], array('JPG', 'JPEG', 'GIF', 'PNG')))
			{
				$fileExtension = Core_File::getExtension($aFileData['name']);
				$sImageName = ($modelName == 'siteuser_person' ? 'image.' : 'logo.' ) . $fileExtension;

				$param = array();
				// Путь к файлу-источнику большого изображения;
				$param['large_image_source'] = $aFileData['tmp_name'];
				// Оригинальное имя файла большого изображения
				$param['large_image_name'] = $aFileData['name'];

				// Путь к создаваемому файлу большого изображения;
				$param['large_image_target'] = $object->getPath() . $sImageName;

				// Использовать большое изображение для создания малого
				$param['create_small_image_from_large'] = FALSE;

				// Значение максимальной ширины большого изображения
				$param['large_image_max_width'] = Core_Array::getPost('large_max_width_image', 0);

				// Значение максимальной высоты большого изображения
				$param['large_image_max_height'] = Core_Array::getPost('large_max_height_image', 0);

				// Сохранять пропорции изображения для большого изображения
				$param['large_image_preserve_aspect_ratio'] = !is_null(Core_Array::getPost('large_preserve_aspect_ratio_image'));

				$object->createDir();

				$result = Core_File::adminUpload($param);

				if ($result['large_image'])
				{
					$object->image = $sImageName;
					$object->save();
				}
			}
			else
			{
				$this->addMessage(
					Core_Message::get(
						Core::_('Core.extension_does_not_allow', Core_File::getExtension($aFileData['name'])),
						'error'
					)
				);
			}
		}

		if (!$this->_object->siteuser_id)
		{
			$aObject_Directory_Emails = $this->_object->Directory_Emails->findAll(FALSE);
			$objectEmail = isset($aObject_Directory_Emails[0])
				? $aObject_Directory_Emails[0]->value
				: NULL;

			$oSiteuser = strlen($objectEmail)
				? Core_Entity::factory('Siteuser')->getByEmail($objectEmail)
				: NULL;

			if (is_null($oSiteuser))
			{
				$oSiteuser = Core_Entity::factory('Siteuser');
				$oSiteuser->login = Core_Guid::get();
				$oSiteuser->email = $objectEmail;
				$oSiteuser->save();

				$oSiteuser->login = 'id' . $oSiteuser->id;
				$oSiteuser->save();
			}

			$this->_object->siteuser_id = $oSiteuser->id;
			$this->_object->save();

			ob_start();
			Core::factory('Core_Html_Entity_Script')
				->value('var dealSiteuserSelect = $("#object_siteuser_id");
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/admin/siteuser/index.php?'
							. ($modelName == 'siteuser_person' ? 'loadSiteuserPersonSelect2' : 'loadSiteuserCompanySelect2')
							. '=' . $this->_object->id . '"
					}).then(function (data) {
						if (data)
						{
							// create the option and append to Select2
							var option = new Option(data.text, data.id, true, true);
							dealSiteuserSelect.append(option).trigger("change");

							// manually trigger the `select2:select` event
							dealSiteuserSelect.trigger({
								type: "select2:select",
								params: {
									data: data
								}
							});
						}
					});')
				->execute();

			$sOperationName = $this->_Admin_Form_Controller->getOperation();

			$sOperationName == 'saveModal' && $this->_Admin_Form_Controller->addMessage(ob_get_clean());
			$sOperationName == 'applyModal' && $this->_Admin_Form_Controller->addContent(ob_get_clean());
		}

		/*
		if (Core::moduleIsActive('search') && $object->indexing && $object->active)
		{
			Search_Controller::indexingSearchPages(array($object->indexing()));
		}
		*/

		Core_Event::notify(get_class($this) . '.onAfterRedeclaredApplyObjectProperty', $this, array($this->_Admin_Form_Controller));
	}
}