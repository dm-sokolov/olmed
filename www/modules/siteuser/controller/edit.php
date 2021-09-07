<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Siteuser Backend Editing Controller.
 *
 * @package HostCMS
 * @subpackage Siteuser
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Siteuser_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		$this
			->addSkipColumn('guid')
			->addSkipColumn('last_activity');

		parent::setObject($object);

		$this->title($this->_object->id
			? Core::_('Siteuser.su_edit_users_data_title', $this->_object->login)
			: Core::_('Siteuser.su_add_users_data_title'));

		$oMainTab = $this->getTab('main');
		$oAdditionalTab = $this->getTab('additional');

		$oSite = $this->_object->Site;

		// Shop orders
		$oShopOrderTab = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('Siteuser.tabShopOrders'))
			->name('Shop_Order');

		$this->addTabAfter($oShopOrderTab, $oMainTab);

		$oShopOrderTab
			->add($oShopOrderTabRow1 = Admin_Form_Entity::factory('Div')->class('row'));

		if (Core::moduleIsActive('shop') && Core_Entity::factory('Shop')->getCountBySite_id(CURRENT_SITE))
		{
			if ($this->_object->id)
			{
				$sShopOrdersContainerId = 'shop-orders';

				$oDivShopOrders = Admin_Form_Entity::factory('Div')
					->id($sShopOrdersContainerId)
					->class('col-xs-12')
					->add(
						Admin_Form_Entity::factory('Script')
							->value("$(function (){
								$.adminLoad({ path: '/admin/shop/order/index.php', additionalParams: 'siteuser_id=" . $this->_object->id . "&hideMenu=1&_module=0', windowId: '{$sShopOrdersContainerId}' });
							});")
					);

				$oShopOrderTabRow1->add($oDivShopOrders);

				$countOrders = $this->_object->Shop_Orders->getCount(FALSE);

				$countOrders&& $oShopOrderTab
					->badge($countOrders)
					->badgeColor('palegreen');
			}
			else
			{
				$oShopOrderTabRow1->add(Admin_Form_Entity::factory('Code')->html(
					Core_Message::get(Core::_('Siteuser.enable_after_save'), 'warning')
				));
			}
		}

		// Siteuser emails
		$oEmailTab = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('Siteuser.tabEmails'))
			->name('Siteuser_Email');

		$this->addTabAfter($oEmailTab, $oShopOrderTab);

		$oEmailTab
			->add($oEmailTabRow1 = Admin_Form_Entity::factory('Div')->class('row'));

		if ($this->_object->id)
		{
			$sEmailsContainerId = 'siteuser-emails';

			$oDivEmails = Admin_Form_Entity::factory('Div')
				->id($sEmailsContainerId)
				->class('col-xs-12')
				->add(
					Admin_Form_Entity::factory('Script')
						->value("$(function (){
							$.adminLoad({ path: '/admin/siteuser/email/index.php', additionalParams: 'siteuser_id=" . $this->_object->id . "&hideMenu=1&_module=0', windowId: '{$sEmailsContainerId}' });
						});")
				);

			$oEmailTabRow1->add($oDivEmails);

			$countEmails = $this->_object->Siteuser_Emails->getCount(FALSE);

			$countEmails && $oEmailTab
				->badge($countEmails)
				->badgeColor('azure');
		}

		// Events
		$oEventTab = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('Siteuser.tabEvents'))
			->name('Events');

		$this->addTabAfter($oEventTab, $oEmailTab);

		$oEventTab
			->add($oEventTabRow1 = Admin_Form_Entity::factory('Div')->class('row'));

		if (Core::moduleIsActive('event'))
		{
			if ($this->_object->id)
			{
				$sEventsContainerId = 'events';

				$oDivEvents = Admin_Form_Entity::factory('Div')
					->id($sEventsContainerId)
					->class('col-xs-12')
					->add(
						Admin_Form_Entity::factory('Script')
							->value("$(function (){
								$.adminLoad({ path: '/admin/event/index.php', additionalParams: 'siteuser_id=" . $this->_object->id . "&hideMenu=1&_module=0', windowId: '{$sEventsContainerId}' });
							});")
					);

				$oEventTabRow1->add($oDivEvents);

				$oEvents = Core_Entity::factory('Event');
				$oEvents
					->queryBuilder()
					->join('event_siteusers', 'events.id', '=', 'event_siteusers.event_id')
					->leftJoin('siteuser_companies', 'event_siteusers.siteuser_company_id', '=', 'siteuser_companies.id')
					->leftJoin('siteuser_people', 'event_siteusers.siteuser_person_id', '=', 'siteuser_people.id')
					->open()
						->where('siteuser_companies.siteuser_id', '=', $this->_object->id)
						->setOr()
						->where('siteuser_people.siteuser_id', '=', $this->_object->id)
					->close();

				$countEvents = $oEvents->getCount(FALSE);

				$countEvents && $oEventTab
					->badge($countEvents)
					->badgeColor('darkorange');
			}
			else
			{
				$oEventTabRow1->add(Admin_Form_Entity::factory('Code')->html(
					Core_Message::get(Core::_('Siteuser.enable_after_save'), 'warning')
				));
			}
		}

		// Deal
		$oDealTab = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('Siteuser.tabDeals'))
			->name('Events');

		$this->addTabAfter($oDealTab, $oEventTab);

		$oDealTab
			->add($oDealTabRow1 = Admin_Form_Entity::factory('Div')->class('row'));

		if (Core::moduleIsActive('deal'))
		{
			if ($this->_object->id)
			{
				$sDealsContainerId = 'deals';

				$oDivDeals = Admin_Form_Entity::factory('Div')
					->id($sDealsContainerId)
					->class('col-xs-12')
					->add(
						Admin_Form_Entity::factory('Script')
							->value("$(function (){
								$.adminLoad({ path: '/admin/deal/index.php', additionalParams: 'siteuser_id=" . $this->_object->id . "&hideMenu=1&_module=0', windowId: '{$sDealsContainerId}' });
							});")
					);

				$oDealTabRow1->add($oDivDeals);

				$oDeals = Core_Entity::factory('Deal');
				$oDeals
					->queryBuilder()
					->join('deal_siteusers', 'deals.id', '=', 'deal_siteusers.deal_id')
					->leftJoin('siteuser_companies', 'deal_siteusers.siteuser_company_id', '=', 'siteuser_companies.id')
					->leftJoin('siteuser_people', 'deal_siteusers.siteuser_person_id', '=', 'siteuser_people.id')
					->open()
						->where('siteuser_companies.siteuser_id', '=', $this->_object->id)
						->setOr()
						->where('siteuser_people.siteuser_id', '=', $this->_object->id)
					->close();

				$countDeals = $oDeals->getCount(FALSE);

				$countDeals && $oDealTab
					->badge($countDeals)
					->badgeColor('warning');
			}
			else
			{
				$oDealTabRow1->add(Admin_Form_Entity::factory('Code')->html(
					Core_Message::get(Core::_('Siteuser.enable_after_save'), 'warning')
				));
			}
		}

		$oPropertyTab = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('Admin_Form.tabProperties'))
			->name('Property');

		$this->addTabAfter($oPropertyTab, $oDealTab);

		// Вкладку с группами пользователя добавляем и показываем при редактировании пользователя и пользователь входит хотя бы в одну группу
		if ($this->_object->id)
		{
			$isOnline = $this->_object->isOnline();

			$sStatus = $isOnline ? 'online' : 'offline';

			$lng = $isOnline ? 'siteuser_active' : 'siteuser_last_activity';

			$sStatusTitle = !is_null($this->_object->last_activity)
				? Core::_('Siteuser.' . $lng, Core_Date::sql2datetime($this->_object->last_activity))
				: '';

			$this->addEntity(
				Admin_Form_Entity::factory('Code')
					->html('
						<script>
							$("h5.row-title").append("<span title=\"' . htmlspecialchars($sStatusTitle) . '\" class=\"' . htmlspecialchars($sStatus) . ' margin-left-5\"></span>");
						</script>
				')
			);

			$aSiteuser_Groups = $oSite->Siteuser_Groups->findAll(FALSE);
			if (count($aSiteuser_Groups))
			{
				$oSiteuserGroupsTab = Admin_Form_Entity::factory('Tab')
					->caption(Core::_('Siteuser.siteuser_groups'))
					->name('SiteuserGroups');

				$this->addTabAfter($oSiteuserGroupsTab, $oPropertyTab);

				$sTableBody = '';

				$aTmp = array();

				$aGroupsForSiteuser = $this->_object->Siteuser_Groups->findAll(FALSE);
				foreach ($aGroupsForSiteuser as $oSiteuser_Group)
				{
					$aTmp[] = $oSiteuser_Group->id;
				}

				foreach ($aSiteuser_Groups as $oSiteuser_Group)
				{
					$checked = in_array($oSiteuser_Group->id, $aTmp)
						? 'checked="checked"'
						: '';

					$sTableBody .= '<tr>
						<td class="text-align-center">
							<label>
								<input type="checkbox" ' . $checked . ' name="siteuser_group_' . $oSiteuser_Group->id . '" value="1"/>
								<span class="text"></span>
							</label>
						</td>
						<td>
							<a href="/admin/siteuser/group/list/index.php?siteuser_group_id=' . $oSiteuser_Group->id .'" onclick="$.adminLoad({path: \'/admin/siteuser/group/list/index.php\', additionalParams: \'siteuser_group_id=' . $oSiteuser_Group->id . '\', windowId: \'' . $this->_Admin_Form_Controller->getWindowId() . '\'}); return false">'
								. htmlspecialchars($oSiteuser_Group->name)
								. '</a>
						</td>
						<td>' . htmlspecialchars($oSiteuser_Group->description) . '</td>
						<td>' . ($oSiteuser_Group->default ? '<i class="fa fa-lightbulb-o"></i>' : '') . '</td>
						</tr>';
				}

				ob_start();

				Admin_Form_Entity::factory('code')
					->html($sTableBody)
					->execute();

				$sHtmlTableBody = ob_get_clean();

				$oSiteuserGroupRow = Admin_Form_Entity::factory('Div')
					->class('row')
					->add(
						 Admin_Form_Entity::factory('Div')
							->class("form-group col-lg-12")
							->add(
								Admin_Form_Entity::factory('code')
									->html('
										<table class="table">
											<thead>
												<tr>
													<th></th>
													<th>' . Core::_('Siteuser.siteuser_group') . '</th>
													<th>' . Core::_('Siteuser.siteuser_group_description') . '</th>
													<th width="120px">' . Core::_('Siteuser.siteuser_group_default') . '</th>
												</tr>
												<tbody>' . $sHtmlTableBody . '</tbody>
											</thead>
										</table>
									')
							)

					);

				$oSiteuserGroupsTab->add($oSiteuserGroupRow);
			}
		}

		$oMainTab
			->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow3 = Admin_Form_Entity::factory('Div')->class('row'));

		$oAdditionalTab
			->add($oAdditionalRow1 = Admin_Form_Entity::factory('Div')->class('row'));

		$oMainTab
			->move($this->getField('login')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6'))->format(FALSE), $oMainRow1)
			->move($this->getField('email')->divAttr(array('class' => 'form-group col-xs-12 col-sm-6'))->class('form-control input-lg semi-bold black'), $oMainRow1);

		$oMainTab->delete($this->getField('password'));

		$aPasswordFormat = array(
			'minlen' => array('value' => 5),
			'maxlen' => array('value' => 255),
			'fieldEquality' => array(
				'value' => 'password_second',
				'message' => Core::_('Siteuser.passwords_not_valid')
			)
		);

		$oPasswordFirst = Admin_Form_Entity::factory('Password');
		$oPasswordFirst
			->caption(Core::_('Siteuser.password'))
			->id('password_first')
			->name('password_first')
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-4'))
			->generatePassword(TRUE);

		$oPasswordSecond = Admin_Form_Entity::factory('Password');

		$aPasswordFormatSecond = array(
			'fieldEquality' => array(
				'value' => 'password_first',
				'message' => Core::_('Siteuser.passwords_not_valid')
			)
		);

		if (!$this->_object->id)
		{
			$password = Core_Password::get();

			$oPasswordFirst->format($aPasswordFormat)->value($password)->type('text');
			$aPasswordFormatSecond += $aPasswordFormat;
			$oPasswordSecond->value($password)->type('text');
		}

		$oPasswordSecond
			->caption(Core::_('Siteuser.password_retry'))
			->id('password_second')
			->name('password_second')
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-4'))
			->format($aPasswordFormatSecond);

		$oMainRow2
			->add($oPasswordFirst)
			->add($oPasswordSecond);

		$oMainTab
			->move($this->getField('active')->divAttr(array('class' => 'form-group col-xs-12 col-sm-4 margin-top-21')), $oMainRow2);

		$oAdditionalTab
			->delete($this->getField('site_id'))
			->delete($this->getField('siteuser_type_id'))
			->delete($this->getField('siteuser_status_id'))
			->delete($this->getField('crm_source_id'));

		$aMasSiteuserTypes = array(array('value' => Core::_('Siteuser.not'), 'color' => '#aebec4'));

		$aSiteuser_Types = Core_Entity::factory('Siteuser_Type', 0)->findAll();
		foreach ($aSiteuser_Types as $oSiteuser_Type)
		{
			$aMasSiteuserTypes[$oSiteuser_Type->id] = array('value' => $oSiteuser_Type->name, 'color' => $oSiteuser_Type->color);
		}

		$oDropdownlistSiteuserTypes = Admin_Form_Entity::factory('Dropdownlist')
			->options($aMasSiteuserTypes)
			->name('siteuser_type_id')
			->value($this->_object->siteuser_type_id)
			->caption(Core::_('Siteuser.siteuser_type_id'))
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-4'));

		$oMainRow3->add($oDropdownlistSiteuserTypes);

		$aMasSiteuserStatuses = array(array('value' => Core::_('Siteuser.not'), 'color' => '#aebec4'));

		$aSiteuser_Statuses = Core_Entity::factory('Siteuser_Status', 0)->findAll();
		foreach ($aSiteuser_Statuses as $oSiteuser_Status)
		{
			$aMasSiteuserStatuses[$oSiteuser_Status->id] = array('value' => $oSiteuser_Status->name, 'color' => $oSiteuser_Status->color);
		}

		$oDropdownlistSiteuserStatuses = Admin_Form_Entity::factory('Dropdownlist')
			->options($aMasSiteuserStatuses)
			->name('siteuser_status_id')
			->value($this->_object->siteuser_status_id)
			->caption(Core::_('Siteuser.siteuser_status_id'))
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-4'));

		$oMainRow3->add($oDropdownlistSiteuserStatuses);

		$aMasCrmSources = array(array('value' => Core::_('Siteuser.not'), 'color' => '#aebec4'));

		$aCrm_Sources = Core_Entity::factory('Crm_Source')->findAll();
		foreach ($aCrm_Sources as $oCrm_Source)
		{
			$aMasCrmSources[$oCrm_Source->id] = array(
				'value' => $oCrm_Source->name,
				'color' => $oCrm_Source->color,
				'icon' => $oCrm_Source->icon
			);
		}

		$oDropdownlistCrmSources = Admin_Form_Entity::factory('Dropdownlist')
			->options($aMasCrmSources)
			->name('crm_source_id')
			->value($this->_object->crm_source_id)
			->caption(Core::_('Siteuser.crm_source_id'))
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-4'));

		$oMainRow3
			->add($oDropdownlistCrmSources);

		// Ответственные сотрудники
		$aResponsibleEmployees = array();

		$showBar = FALSE;

		if (!$this->_object->id)
		{
			$oSiteuser_Company = Core_Entity::factory('Siteuser_Company');

			$oSiteuser_Person = Core_Entity::factory('Siteuser_Person');
		}
		else
		{
			$aSiteuser_Companies = $this->_object->Siteuser_Companies->findAll(FALSE);

			$oSiteuser_Company = count($aSiteuser_Companies) == 1
				? $aSiteuser_Companies[0]
				: NULL;

			$aSiteuser_People = $this->_object->Siteuser_People->findAll(FALSE);

			$oSiteuser_Person = count($aSiteuser_People) == 1
				? $aSiteuser_People[0]
				: NULL;

			$aSiteuser_Users = $this->_object->Siteuser_Users->findAll(FALSE);
			foreach ($aSiteuser_Users as $oSiteuserUser)
			{
				$aResponsibleEmployees[] = $oSiteuserUser->user_id;
			}

			!is_null($oSiteuser_Company) || !is_null($oSiteuser_Person)
				&& $showBar = TRUE;
		}

		if (is_null($oSiteuser_Company) && is_null($oSiteuser_Person) && $showBar)
		{
			$oMainTab
				->add($oRepresentativeTitle = Admin_Form_Entity::factory('Div')->class('row profile-container'));

			$oRepresentativeTitle->add(
				Admin_Form_Entity::factory('Code')
					->html('<div class="col-xs-12"><h6 class="row-title before-palegreen">' . Core::_('Siteuser.representatives') . '</h6>'
						. $this->_object->counterpartyBackend(NULL, $this->_Admin_Form_Controller)
						. '</div>'
					)
			);
		}
		else
		{
			$oMainTab
				->add($oPersonTitle = Admin_Form_Entity::factory('Div')->class('row profile-container'))
				->add($oPersonBlock = Admin_Form_Entity::factory('Div')->class('well-person-block'))
				->add($oCompanyTitle = Admin_Form_Entity::factory('Div')->class('row profile-container'))
				->add($oCompanyBlock = Admin_Form_Entity::factory('Div')->class('well-company-block'));

			if ($oSiteuser_Person)
			{
				$oPersonTitle->add(
					Admin_Form_Entity::factory('Code')
						->html('<div class="col-xs-12"><h6 class="row-title before-darkorange">' . Core::_('Siteuser.person_header') . '</h6></div>')
				);

				// Представитель
				$oPersonBlock
					/*->add(Admin_Form_Entity::factory('Div')
						->class('header bordered-darkorange')
						->value(Core::_('Siteuser.person_header'))
					)*/
					->add($oPersonRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oPersonRow2 = Admin_Form_Entity::factory('Div')->class('row hidden-field hidden'))
					->add($oPersonRow3 = Admin_Form_Entity::factory('Div')->class('row hidden-field hidden'))
					->add($oPersonRow4 = Admin_Form_Entity::factory('Div')->class('row hidden-field hidden'))
					->add($oPersonRowShow = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oPersonRow5 = Admin_Form_Entity::factory('Div')->class(''));

				$oPersonRow1->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))
						->caption(Core::_('Siteuser_Person.surname'))
						->class('form-control input-lg semi-bold black')
						->name('person_surname')
						->value($oSiteuser_Person->surname)
				)->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))
						->caption(Core::_('Siteuser_Person.name'))
						->class('form-control input-lg semi-bold black')
						->name('person_name')
						->value($oSiteuser_Person->name)
				)->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))
						->caption(Core::_('Siteuser_Person.patronymic'))
						->class('form-control input-lg')
						->name('person_patronymic')
						->value($oSiteuser_Person->patronymic)
				);

				$oPersonRow2->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12'))
						->caption(Core::_('Siteuser_Person.post'))
						->class('form-control')
						->name('person_post')
						->value($oSiteuser_Person->post)
				);

				$oPersonRow3->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))
						->caption(Core::_('Siteuser_Person.postcode'))
						->class('form-control')
						->name('person_postcode')
						->value($oSiteuser_Person->postcode)
				)->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))
						->caption(Core::_('Siteuser_Person.country'))
						->class('form-control')
						->name('person_country')
						->value($oSiteuser_Person->country)
				)->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))
						->caption(Core::_('Siteuser_Person.city'))
						->class('form-control')
						->name('person_city')
						->value($oSiteuser_Person->city)
				);

				$oPersonRow4->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12'))
						->caption(Core::_('Siteuser_Person.address'))
						->class('form-control')
						->name('person_address')
						->value($oSiteuser_Person->address)
				);

				$oPersonRowShow->add(Admin_Form_Entity::factory('Span')
					->divAttr(array('class' => 'form-group col-xs-12'))
					->add(Admin_Form_Entity::factory('A')
						->value(Core::_("Siteuser.show_fields"))
						->class('representative-show-link darkgray')
						->onclick('$.toggleRepresentativeFields(".well-person-block")')
					)
				);

				// Телефоны
				$oSiteuserPersonPhonesRow = Directory_Controller_Tab::instance('phone')
					->title(Core::_('Directory_Phone.phones'))
					->relation($oSiteuser_Person->Siteuser_Person_Directory_Phones)
					->showPublicityControlElement(TRUE)
					->prefix('person_')
					->execute();

				// Email'ы
				$oSiteuserPersonEmailsRow = Directory_Controller_Tab::instance('email')
					->title(Core::_('Directory_Email.emails'))
					->relation($oSiteuser_Person->Siteuser_Person_Directory_Emails)
					->showPublicityControlElement(TRUE)
					->prefix('person_')
					->execute();

				// Социальные сети
				$oSiteuserPersonSocialsRow = Directory_Controller_Tab::instance('social')
					->title(Core::_('Directory_Social.socials'))
					->relation($oSiteuser_Person->Siteuser_Person_Directory_Socials)
					->showPublicityControlElement(TRUE)
					->prefix('person_')
					->execute();

				// Мессенджеры
				$oSiteuserPersonMessengersRow = Directory_Controller_Tab::instance('messenger')
					->title(Core::_('Directory_Messenger.messengers'))
					->relation($oSiteuser_Person->Siteuser_Person_Directory_Messengers)
					->showPublicityControlElement(TRUE)
					->prefix('person_')
					->execute();

				// Сайты
				$oSiteuserPersonWebsitesRow = Directory_Controller_Tab::instance('website')
					->title(Core::_('Directory_Website.sites'))
					->relation($oSiteuser_Person->Siteuser_Person_Directory_Websites)
					->showPublicityControlElement(TRUE)
					->prefix('person_')
					->execute();

				$oPersonRow5
					->add($oSiteuserPersonPhonesRow)
					->add($oSiteuserPersonEmailsRow)
					->add($oSiteuserPersonSocialsRow)
					->add($oSiteuserPersonMessengersRow)
					->add($oSiteuserPersonWebsitesRow);
			}

			if ($oSiteuser_Company)
			{
				$oCompanyTitle->add(
					Admin_Form_Entity::factory('Code')
						->html('<div class="col-xs-12"><h6 class="row-title before-palegreen">' . Core::_('Siteuser.company_header') . '</h6></div>')
				);

				// Компания
				$oCompanyBlock
					/*->add(Admin_Form_Entity::factory('Div')
							->class('header bordered-palegreen')
							->value(Core::_('Siteuser.company_header'))
					)*/
					->add($oCompanyRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oCompanyRow2 = Admin_Form_Entity::factory('Div')->class('row hidden-field hidden'))
					->add($oCompanyRowShow = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oCompanyRow3 = Admin_Form_Entity::factory('Div')->class(''))
					->add($oCompanyRow4 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oCompanyRow5 = Admin_Form_Entity::factory('Div')->class('row'));

				$oCompanyRow1->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12'))
						->caption(Core::_('Siteuser_Company.name'))
						->class('form-control input-lg semi-bold black')
						->name('company_name')
						->value($oSiteuser_Company->name)
				);

				$oCompanyRow2->add(
					Admin_Form_Entity::factory('Textarea')
						->divAttr(array('class' => 'form-group col-xs-12'))
						->caption(Core::_('Siteuser_Company.description'))
						->class('form-control')
						->name('company_description')
						->value($oSiteuser_Company->description)
				)->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))
						->caption(Core::_('Siteuser_Company.business_area'))
						->class('form-control')
						->name('company_business_area')
						->value($oSiteuser_Company->business_area)
				)->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))
						->caption(Core::_('Siteuser_Company.headcount'))
						->class('form-control')
						->name('company_headcount')
						->value(intval($oSiteuser_Company->headcount))
				)
				->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12 col-md-4'))
						->caption(Core::_('Siteuser_Company.annual_turnover'))
						->class('form-control')
						->name('company_annual_turnover')
						->value(intval($oSiteuser_Company->annual_turnover))
				);

				$oCompanyRowShow->add(Admin_Form_Entity::factory('Span')
					->divAttr(array('class' => 'form-group col-xs-12'))
					->add(Admin_Form_Entity::factory('A')
						->value(Core::_("Siteuser.show_fields"))
						->class('representative-show-link darkgray')
						->onclick('$.toggleRepresentativeFields(".well-company-block")')
					)
				);

				// Адреса
				$oSiteuserCompanyAddressesRow = Directory_Controller_Tab::instance('address')
					->title(Core::_('Directory_Address.address'))
					->relation($oSiteuser_Company->Siteuser_Company_Directory_Addresses)
					->showPublicityControlElement(TRUE)
					->prefix('company_')
					->execute();

				// Телефоны
				$oSiteuserCompanyPhonesRow = Directory_Controller_Tab::instance('phone')
					->title(Core::_('Directory_Phone.phones'))
					->relation($oSiteuser_Company->Siteuser_Company_Directory_Phones)
					->showPublicityControlElement(TRUE)
					->prefix('company_')
					->execute();

				// Email'ы
				$oSiteuserCompanyEmailsRow = Directory_Controller_Tab::instance('email')
					->title(Core::_('Directory_Email.emails'))
					->relation($oSiteuser_Company->Siteuser_Company_Directory_Emails)
					->showPublicityControlElement(TRUE)
					->prefix('company_')
					->execute();

				// Социальные сети
				$oSiteuserCompanySocialsRow = Directory_Controller_Tab::instance('social')
					->title(Core::_('Directory_Social.socials'))
					->relation($oSiteuser_Company->Siteuser_Company_Directory_Socials)
					->showPublicityControlElement(TRUE)
					->prefix('company_')
					->execute();

				// Мессенджеры
				$oSiteuserCompanyMessengersRow = Directory_Controller_Tab::instance('messenger')
					->title(Core::_('Directory_Messenger.messengers'))
					->relation($oSiteuser_Company->Siteuser_Company_Directory_Messengers)
					->showPublicityControlElement(TRUE)
					->prefix('company_')
					->execute();

				// Сайты
				$oSiteuserCompanyWebsitesRow = Directory_Controller_Tab::instance('website')
					->title(Core::_('Directory_Website.sites'))
					->relation($oSiteuser_Company->Siteuser_Company_Directory_Websites)
					->showPublicityControlElement(TRUE)
					->prefix('company_')
					->execute();

				$oCompanyRow3
					->add($oSiteuserCompanyPhonesRow)
					->add($oSiteuserCompanyEmailsRow)
					->add($oSiteuserCompanyAddressesRow)
					->add($oSiteuserCompanySocialsRow)
					->add($oSiteuserCompanyMessengersRow)
					->add($oSiteuserCompanyWebsitesRow);

				$oCompanyRow4->add(
					Admin_Form_Entity::factory('Input')
						->divAttr(array('class' => 'form-group col-xs-12'))
						->caption(Core::_('Siteuser_Company.tin'))
						->divAttr(array('class' => 'form-group col-xs-12 col-sm-6'))
						->class('form-control')
						->name('company_tin')
						->value($oSiteuser_Company->tin)
				);

				$oCompanyRow5->add(
					Admin_Form_Entity::factory('Textarea')
						->divAttr(array('class' => 'form-group col-xs-12'))
						->caption(Core::_('Siteuser_Company.bank_account'))
						->class('form-control')
						->name('company_bank_account')
						->value($oSiteuser_Company->bank_account)
				);
			}
		}

		$aSelectedResponsibleEmployees = $oSite->Companies->getUsersOptions();

		$oSelectResponsibleEmployees = Admin_Form_Entity::factory('Select');
		$oSelectResponsibleEmployees
			->id('siteuser_user_id')
			->multiple('multiple')
			->options($aSelectedResponsibleEmployees)
			->name('siteuser_user_id[]')
			->value($aResponsibleEmployees)
			// ->caption(Core::_('Siteuser.siteuser_user_id'))
			->divAttr(array('class' => 'form-group col-xs-12'));

		$oScriptResponsibleEmployees = Admin_Form_Entity::factory('Script')
			->value('
				$("#siteuser_user_id").select2({
					placeholder: "",
					allowClear: true,
					//multiple: true,
					templateResult: $.templateResultItemResponsibleEmployees,
					escapeMarkup: function(m) { return m; },
					templateSelection: $.templateSelectionItemResponsibleEmployees,
					language: "' . Core_i18n::instance()->getLng() . '",
					width: "100%"
				});'
			);

		$oMainTab
			->add($oResponsibleTitle = Admin_Form_Entity::factory('Div')->class('row profile-container'))
			->add($oMainRow4 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow5 = Admin_Form_Entity::factory('Div')->class('row'));

		$oResponsibleTitle->add(
			Admin_Form_Entity::factory('Code')
				->html('<div class="col-xs-12"><h6 class="row-title before-darkorange">' . Core::_('Siteuser.siteuser_user_id') . '</h6></div>')
		);

		$oMainRow4
			->add($oSelectResponsibleEmployees)
			->add($oScriptResponsibleEmployees);

		if (!$this->_object->active)
		{
			$oSend_Mail_Confirm = Admin_Form_Entity::factory('Checkbox')
				->name('send_mail_confirm')
				->caption(Core::_('Siteuser.send_mail_confirm'))
				->value(1);

			$oMainTab->add($oMainRow5 = Admin_Form_Entity::factory('Div')->class('row'));
			$oMainRow5->add($oSend_Mail_Confirm);
		}

		// Properties
		Property_Controller_Tab::factory($this->_Admin_Form_Controller)
			->setObject($this->_object)
			->setDatasetId($this->getDatasetId())
			->linkedObject(Core_Entity::factory('Siteuser_Property_List', CURRENT_SITE))
			->setTab($oPropertyTab)
			->fillTab();

		// Siteuser's Affiliate
		$oSiteuser_Affiliate = Core_Entity::factory('Siteuser_Affiliate')->getByReferral_siteuser_id($this->_object->id);

		if (!is_null($oSiteuser_Affiliate))
		{
			$oSiteuserLink = Admin_Form_Entity::factory('Link');
			$oSiteuserLink
				->divAttr(array('class' => 'form-group col-lg-3 col-md-3 col-sm-12 col-xs-12'))
				->caption(Core::_('Siteuser.affiliate'))
				->a
					->class('btn btn-labeled btn-sky')
					->href($this->_Admin_Form_Controller->getAdminActionLoadHref('/admin/siteuser/index.php', 'edit', NULL, 0, $oSiteuser_Affiliate->Siteuser->id))
					->onclick("$.openWindowAddTaskbar({path: '/admin/siteuser/index.php', additionalParams: 'hostcms[checked][0][{$oSiteuser_Affiliate->Siteuser->id}]=1&hostcms[action]=edit', shortcutImg: '" . '/modules/skin/' . Core_Skin::instance()->getSkinName() . '/images/module/siteuser.png' . "', shortcutTitle: 'undefined', Minimize: true}); return false")
					->value($oSiteuser_Affiliate->Siteuser->login)
					->target('_blank');
			$oSiteuserLink
				->icon
					->class('btn-label fa fa-user');

			$oAdditionalRow1->add($oSiteuserLink);
		}

		$oUser_Controller_Edit = new User_Controller_Edit($this->_Admin_Form_Action);

		$oMainTab->move($this->getField('ip')->divAttr(array('class' => 'form-group col-xs-12 col-sm-3')), $oAdditionalRow1);

		// Список сайтов
		$oSelect_Sites = Admin_Form_Entity::factory('Select');
		$oSelect_Sites
			->options($oUser_Controller_Edit->fillSites())
			->name('site_id')
			->value($this->_object->site_id)
			->caption(Core::_('Siteuser.site_id'))
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-3'));

		$oAdditionalRow1->add($oSelect_Sites);

		$oMainTab->move($this->getField('datetime')->divAttr(array('class' => 'form-group col-xs-12 col-sm-3')), $oAdditionalRow1);

		if (Core::moduleIsActive('forum'))
		{
			$countMessages = 0;

			if ($this->_object->Forum_Siteuser_Counts->getCount())
			{
				$aForum_Siteuser_Counts = $this->_object->Forum_Siteuser_Counts->findAll(FALSE);
				foreach ($aForum_Siteuser_Counts as $oForum_Siteuser_Count)
				{
					$countMessages += $oForum_Siteuser_Count->count;
				}
			}

			$oAdditionalRow1->add(
				Admin_Form_Entity::factory('Input')
					->divAttr(array('class' => 'form-group col-xs-12 col-sm-3'))
					->caption(Core::_('Siteuser.count_messages'))
					->class('form-control')
					->name('count_messages')
					->disabled('disabled')
					->value($countMessages)
			);
		}

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @hostcms-event Siteuser_Controller_Edit.onAfterRedeclaredApplyObjectProperty
	 */
	protected function _applyObjectProperty()
	{
		$this
			->addSkipColumn('password')
			->addSkipColumn('last_activity');

		$password = Core_Array::getPost('password_first');

		$bNewSiteuser = is_null($this->_object->id);

		if ($password != '' || $bNewSiteuser)
		{
			$this->_object->password = Core_Hash::instance()->hash($password);
		}

		parent::_applyObjectProperty();

		if ($bNewSiteuser)
		{
			/*$placeholder = Core::_('Siteuser.select_siteuser');
			$language = Core_i18n::instance()->getLng();
			$windowId = $this->_Admin_Form_Controller->getWindowId();

			$windowIdEscaped = Core_Str::escapeJavascriptVariable($windowId);*/

			ob_start();
			Core::factory('Core_Html_Entity_Script')
				->value('
					var objectSiteuserSelect = $("#object_siteuser_id");

					$.ajax({
						type: "GET",
						dataType: "json",
						url: "/admin/siteuser/index.php?loadSiteuserSelect2=' . $this->_object->id . '"
					}).then(function (data) {
						if (data)
						{
							// create the option and append to Select2
							var option = new Option(data.text, data.id, true, true);
							objectSiteuserSelect.append(option).trigger("change");

							// manually trigger the `select2:select` event
							objectSiteuserSelect.trigger({
								type: "select2:select",
								params: {
									data: data
								}
							});
						}
					});
				')
				->execute();

			$sOperationName = $this->_Admin_Form_Controller->getOperation();

			$sOperationName == 'saveModal' && $this->_Admin_Form_Controller->addMessage(ob_get_clean());
			$sOperationName == 'applyModal' && $this->_Admin_Form_Controller->addContent(ob_get_clean());
		}

		if ($this->_object->login == '')
		{
			$this->_object->login = 'id' . $this->_object->id;
			$this->_object->save();
		}

		$oSiteuser = $this->_object;

		// Ответственные сотрудники
		$aSiteuser_Users = $oSiteuser->Siteuser_Users->findAll(FALSE);

		$aSiteuserUserId = Core_Array::getPost('siteuser_user_id');
		!is_array($aSiteuserUserId) && $aSiteuserUserId = array();

		$aAlreadyExists = array();
		foreach ($aSiteuser_Users as $oSiteuser_User)
		{
			$iSearchIndex = array_search($oSiteuser_User->user_id, $aSiteuserUserId);

			$iSearchIndex === FALSE
				? $oSiteuser_User->delete()
				: $aAlreadyExists[] = $oSiteuser_User->user_id;
		}

		foreach ($aSiteuserUserId as $iSiteuserUserId)
		{
			if (!in_array($iSiteuserUserId, $aAlreadyExists))
			{
				$oSiteuser_User = Core_Entity::factory('Siteuser_User')
					->user_id($iSiteuserUserId);

				$oSiteuser->add($oSiteuser_User);
			}
		}

		$aSiteuser_Group_Lists = $oSiteuser->Siteuser_Group_Lists->findAll(FALSE);
		$aTmp = array();
		foreach ($aSiteuser_Group_Lists as $oSiteuser_Group_List)
		{
			$aTmp[$oSiteuser_Group_List->siteuser_group_id] = $oSiteuser_Group_List;
		}

		$aSiteuser_Groups = $oSiteuser->Site->Siteuser_Groups->findAll(FALSE);
		foreach ($aSiteuser_Groups as $oSiteuser_Group)
		{
			if (Core_Array::getPost('siteuser_group_' . $oSiteuser_Group->id))
			{
				if (!isset($aTmp[$oSiteuser_Group->id]))
				{
					$oSiteuser_Group_List = Core_Entity::factory('Siteuser_Group_List');
					$oSiteuser_Group_List->siteuser_group_id = $oSiteuser_Group->id;
					$oSiteuser_Group_List->siteuser_id = $oSiteuser->id;
					$oSiteuser_Group_List->save();
				}
			}
			elseif (isset($aTmp[$oSiteuser_Group->id]))
			{
				$aTmp[$oSiteuser_Group->id]->delete();
			}
		}

		// Дополнительные свойства
		$oProperty_Controller_Tab = Property_Controller_Tab::factory($this->_Admin_Form_Controller);
		$oProperty_Controller_Tab
			->setObject($this->_object)
			->linkedObject(Core_Entity::factory('Siteuser_Property_List', CURRENT_SITE))
			->applyObjectProperty();

		$windowId = $this->_Admin_Form_Controller->getWindowId();

		if ($bNewSiteuser)
		{
			$oSiteuser_Company = Core_Entity::factory('Siteuser_Company');
			$oSiteuser_Person = Core_Entity::factory('Siteuser_Person');
		}
		else
		{
			$aSiteuser_Companies = $this->_object->Siteuser_Companies->findAll(FALSE);

			$oSiteuser_Company = count($aSiteuser_Companies) == 1
				? $aSiteuser_Companies[0]
				: NULL;

			$aSiteuser_People = $this->_object->Siteuser_People->findAll(FALSE);

			$oSiteuser_Person = count($aSiteuser_People) == 1
				? $aSiteuser_People[0]
				: NULL;
		}

		// Добавление компании
		if ($oSiteuser_Company && strlen(Core_Array::getPost('company_name')))
		{
			$oSiteuser_Company->name = strval(Core_Array::getPost('company_name'));
			$oSiteuser_Company->description = strval(Core_Array::getPost('company_description'));
			$oSiteuser_Company->tin = intval(Core_Array::getPost('company_tin'));
			$oSiteuser_Company->bank_account = strval(Core_Array::getPost('company_bank_account'));
			$oSiteuser_Company->headcount = intval(Core_Array::getPost('company_headcount'));
			$oSiteuser_Company->annual_turnover = intval(Core_Array::getPost('company_annual_turnover'));
			$oSiteuser_Company->business_area = strval(Core_Array::getPost('company_business_area'));

			if ($bNewSiteuser)
			{
				$oSiteuser->add($oSiteuser_Company);
			}
			else
			{
				$oSiteuser_Company->save();
			}

			// Адреса, установленные значения
			$aSiteuser_Company_Directory_Addresses = $oSiteuser_Company->Siteuser_Company_Directory_Addresses->findAll();
			foreach ($aSiteuser_Company_Directory_Addresses as $oSiteuser_Company_Directory_Address)
			{
				$oDirectory_Adress = $oSiteuser_Company_Directory_Address->Directory_Address;

				$sAdress = trim(Core_Array::getPost("company_address#{$oDirectory_Adress->id}"));
				$sCountry = strval(Core_Array::getPost("company_address_country#{$oDirectory_Adress->id}"));
				$sPostcode = strval(Core_Array::getPost("company_address_postcode#{$oDirectory_Adress->id}"));
				$sCity = strval(Core_Array::getPost("company_address_city#{$oDirectory_Adress->id}"));

				if (strlen($sAdress) || strlen($sCountry) || strlen($sPostcode) || strlen($sCity))
				{
					$oDirectory_Adress
						->directory_address_type_id(intval(Core_Array::getPost("company_address_type#{$oDirectory_Adress->id}", 0)))
						->public(intval(Core_Array::getPost("company_address_public#{$oDirectory_Adress->id}", 0)))
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
						->value("$.deleteFormRow($(\"#{$windowId} select[name='company_address_type#{$oDirectory_Adress->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();
					$this->_Admin_Form_Controller->addMessage(ob_get_clean());

					$oSiteuser_Company_Directory_Address->Directory_Address->delete();
				}
			}

			// Адреса, новые значения
			$aCompanyAddresses = Core_Array::getPost('company_address', array());
			$aCompanyAddressTypes = Core_Array::getPost('company_address_type', array());
			$aCompanyAddressPublic = Core_Array::getPost('company_address_public', array());
			$aCompanyAddressCountry = Core_Array::getPost('company_address_country', array());
			$aCompanyAddressPostcode = Core_Array::getPost('company_address_postcode', array());
			$aCompanyAddressCity = Core_Array::getPost('company_address_city', array());

			if (is_array($aCompanyAddresses) && count($aCompanyAddresses))
			{
				$i = 0;
				foreach ($aCompanyAddresses as $key => $sAddress)
				{
					$sAddress = trim($sAddress);
					$sCountry = strval(Core_Array::get($aCompanyAddressCountry, $key));
					$sPostcode = strval(Core_Array::get($aCompanyAddressPostcode, $key));
					$sCity = strval(Core_Array::get($aCompanyAddressCity, $key));

					if (strlen($sAddress) || strlen($sCountry) || strlen($sPostcode) || strlen($sCity))
					{
						$oDirectory_Address = Core_Entity::factory('Directory_Address')
							->directory_address_type_id(intval(Core_Array::get($aCompanyAddressTypes, $key)))
							->public(intval(Core_Array::get($aCompanyAddressPublic, $key)))
							->country($sCountry)
							->postcode($sPostcode)
							->city($sCity)
							->value($sAddress)
							->save();

						$oSiteuser_Company->add($oDirectory_Address);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} select[name='company_address_type\\[\\]']\").eq({$i}).prop('name', 'company_address_type#{$oDirectory_Address->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='company_address\\[\\]']\").eq({$i}).prop('name', 'company_address#{$oDirectory_Address->id}');
							$(\"#{$windowId} input[name='company_address_public\\[\\]']\").eq({$i}).prop('name', 'company_address_public#{$oDirectory_Address->id}');
							$(\"#{$windowId} input[name='company_address_country\\[\\]']\").eq({$i}).prop('name', 'company_address_country#{$oDirectory_Address->id}');
							$(\"#{$windowId} input[name='company_address_postcode\\[\\]']\").eq({$i}).prop('name', 'company_address_postcode#{$oDirectory_Address->id}');
							$(\"#{$windowId} input[name='company_address_city\\[\\]']\").eq({$i}).prop('name', 'company_address_city#{$oDirectory_Address->id}');
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

			// Телефоны, установленные значения
			$aSiteuser_Company_Directory_Phones = $oSiteuser_Company->Siteuser_Company_Directory_Phones->findAll();
			foreach ($aSiteuser_Company_Directory_Phones as $oSiteuser_Company_Directory_Phone)
			{
				$oDirectory_Phone = $oSiteuser_Company_Directory_Phone->Directory_Phone;

				$sPhone = trim(Core_Array::getPost("company_phone#{$oDirectory_Phone->id}"));

				if (!empty($sPhone))
				{
					$oDirectory_Phone
						->directory_phone_type_id(intval(Core_Array::getPost("company_phone_type#{$oDirectory_Phone->id}", 0)))
						->public(intval(Core_Array::getPost("company_phone_public#{$oDirectory_Phone->id}", 0)))
						->value($sPhone)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} select[name='company_phone_type#{$oDirectory_Phone->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();
					$this->_Admin_Form_Controller->addMessage(ob_get_clean());

					$oSiteuser_Company_Directory_Phone->Directory_Phone->delete();
				}
			}

			// Телефоны, новые значения
			$aCompanyPhones = Core_Array::getPost('company_phone', array());
			$aCompanyPhoneTypes = Core_Array::getPost('company_phone_type', array());
			$aCompanyPhonePublic = Core_Array::getPost('company_phone_public', array());

			if (is_array($aCompanyPhones) && count($aCompanyPhones))
			{
				$i = 0;
				foreach ($aCompanyPhones as $key => $sPhone)
				{
					$sPhone = trim($sPhone);

					if (!empty($sPhone))
					{
						$oDirectory_Phone = Core_Entity::factory('Directory_Phone')
							->directory_phone_type_id(intval(Core_Array::get($aCompanyPhoneTypes, $key)))
							->public(intval(Core_Array::get($aCompanyPhonePublic, $key)))
							->value($sPhone)
							->save();

						$oSiteuser_Company->add($oDirectory_Phone);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} select[name='company_phone_type\\[\\]']\").eq({$i}).prop('name', 'company_phone_type#{$oDirectory_Phone->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='company_phone\\[\\]']\").eq({$i}).prop('name', 'company_phone#{$oDirectory_Phone->id}');
							$(\"#{$windowId} input[name='company_phone_public\\[\\]']\").eq({$i}).prop('name', 'company_phone_public#{$oDirectory_Phone->id}');
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

			// Электронные адреса, установленные значения
			$aSiteuser_Company_Directory_Emails = $oSiteuser_Company->Siteuser_Company_Directory_Emails->findAll();
			foreach ($aSiteuser_Company_Directory_Emails as $oSiteuser_Company_Directory_Email)
			{
				$oDirectory_Email = $oSiteuser_Company_Directory_Email->Directory_Email;

				$sEmail = trim(Core_Array::getPost("company_email#{$oDirectory_Email->id}"));

				if (!empty($sEmail))
				{
					$oDirectory_Email
						->directory_email_type_id(intval(Core_Array::getPost("company_email_type#{$oDirectory_Email->id}", 0)))
						->public(intval(Core_Array::getPost("company_email_public#{$oDirectory_Email->id}", 0)))
						->value($sEmail)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} select[name='company_email_type#{$oDirectory_Email->id}']\").closest('.row').find('.btn-delete111').get(0));")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
					$oSiteuser_Company_Directory_Email->Directory_Email->delete();
				}
			}

			// Электронные адреса, новые значения
			$aCompanyEmails = Core_Array::getPost('company_email', array());
			$aCompanyEmailTypes = Core_Array::getPost('company_email_type', array());
			$aCompanyEmailPublic = Core_Array::getPost('company_email_public', array());

			if (is_array($aCompanyEmails) && count($aCompanyEmails))
			{
				$i = 0;
				foreach ($aCompanyEmails as $key => $sEmail)
				{
					$sEmail = trim($sEmail);

					if (!empty($sEmail))
					{
						$oDirectory_Email = Core_Entity::factory('Directory_Email')
							->directory_email_type_id(intval(Core_Array::get($aCompanyEmailTypes, $key)))
							->public(intval(Core_Array::get($aCompanyEmailPublic, $key)))
							->value($sEmail)
							->save();

						$oSiteuser_Company->add($oDirectory_Email);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} select[name='company_email_type\\[\\]']\").eq({$i}).prop('name', 'company_email_type#{$oDirectory_Email->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='company_email\\[\\]']\").eq({$i}).prop('name', 'company_email#{$oDirectory_Email->id}');
							$(\"#{$windowId} input[name='company_email_public\\[\\]']\").eq({$i}).prop('name', 'company_email_public#{$oDirectory_Email->id}');
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
			$aSiteuser_Company_Directory_Socials = $oSiteuser_Company->Siteuser_Company_Directory_Socials->findAll();
			foreach ($aSiteuser_Company_Directory_Socials as $oSiteuser_Company_Directory_Social)
			{
				$oDirectory_Social = $oSiteuser_Company_Directory_Social->Directory_Social;

				$sSocial_Address = trim(Core_Array::getPost("company_social_address#{$oDirectory_Social->id}"));

				if (!empty($sSocial_Address))
				{
					$aUrl = @parse_url($sSocial_Address);

					// Если не был указан протокол, или
					// указанный протокол некорректен для url
					!array_key_exists('scheme', $aUrl)
						&& $sSocial_Address = 'https://' . $sSocial_Address;

					$oDirectory_Social
						->directory_social_type_id(intval(Core_Array::getPost("company_social#{$oDirectory_Social->id}", 0)))
						->public(intval(Core_Array::getPost("company_social_public#{$oDirectory_Social->id}", 0)))
						->value($sSocial_Address)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} select[name='company_social#{$oDirectory_Social->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();
					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
					$oSiteuser_Company_Directory_Social->Directory_Social->delete();
				}
			}

			// Социальные сети, новые значения
			$aCompanySocialAddresses = Core_Array::getPost('company_social_address', array());
			$aCompanySocials = Core_Array::getPost('company_social', array());
			$aCompanySocialPublic = Core_Array::getPost('company_social_public', array());

			if (is_array($aCompanySocialAddresses) && count($aCompanySocialAddresses))
			{
				$i = 0;
				foreach ($aCompanySocialAddresses as $key => $sSocial_Address)
				{
					$sSocial_Address = trim($sSocial_Address);

					if (!empty($sSocial_Address))
					{
						$aUrl = @parse_url($sSocial_Address);

						// Если не был указан протокол, или
						// указанный протокол некорректен для url
						!array_key_exists('scheme', $aUrl)
							&& $sSocial_Address = 'https://' . $sSocial_Address;

						$oDirectory_Social = Core_Entity::factory('Directory_Social')
							->directory_social_type_id(intval(Core_Array::get($aCompanySocials, $key)))
							->public(intval(Core_Array::get($aCompanySocialPublic, $key)))
							->value($sSocial_Address)
							->save();

						$oSiteuser_Company->add($oDirectory_Social);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} select[name='company_social\\[\\]']\").eq({$i}).prop('name', 'company_social#{$oDirectory_Social->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='company_social_address\\[\\]']\").eq({$i}).prop('name', 'company_social_address#{$oDirectory_Social->id}');
							$(\"#{$windowId} input[name='company_social_public\\[\\]']\").eq({$i}).prop('name', 'company_social_public#{$oDirectory_Social->id}');
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
			$aSiteuser_Company_Directory_Messengers = $oSiteuser_Company->Siteuser_Company_Directory_Messengers->findAll();
			foreach ($aSiteuser_Company_Directory_Messengers as $oSiteuser_Company_Directory_Messenger)
			{
				$oDirectory_Messenger = $oSiteuser_Company_Directory_Messenger->Directory_Messenger;

				$sMessenger_Address = trim(Core_Array::getPost("company_messenger_username#{$oDirectory_Messenger->id}"));

				if (!empty($sMessenger_Address))
				{
					$oDirectory_Messenger
						->directory_messenger_type_id(intval(Core_Array::getPost("company_messenger#{$oDirectory_Messenger->id}", 0)))
						->public(intval(Core_Array::getPost("company_messenger_public#{$oDirectory_Messenger->id}", 0)))
						->value($sMessenger_Address)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} select[name='company_messenger#{$oDirectory_Messenger->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();
					$this->_Admin_Form_Controller->addMessage(ob_get_clean());

					$oSiteuser_Company_Directory_Messenger->Directory_Messenger->delete();
				}
			}

			// Мессенджеры, новые значения
			$aCompanyMessengerAddresses = Core_Array::getPost('company_messenger_username', array());
			$aCompanyMessengers = Core_Array::getPost('company_messenger', array());
			$aCompanyMessengerPublic = Core_Array::getPost('company_messenger_public', array());

			if (is_array($aCompanyMessengerAddresses) && count($aCompanyMessengerAddresses))
			{
				$i = 0;
				foreach ($aCompanyMessengerAddresses as $key => $sMessenger_Address)
				{
					$sMessenger_Address = trim($sMessenger_Address);

					if (!empty($sMessenger_Address))
					{
						$oDirectory_Messenger = Core_Entity::factory('Directory_Messenger')
							->directory_messenger_type_id(intval(Core_Array::get($aCompanyMessengers, $key)))
							->public(intval(Core_Array::get($aCompanyMessengerPublic, $key)))
							->value($sMessenger_Address)
							->save();

						$oSiteuser_Company->add($oDirectory_Messenger);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} select[name='company_messenger\\[\\]']\").eq({$i}).prop('name', 'company_messenger#{$oDirectory_Messenger->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='company_messenger_username\\[\\]']\").eq({$i}).prop('name', 'company_messenger_username#{$oDirectory_Messenger->id}');
							$(\"#{$windowId} input[name='company_messenger_public\\[\\]']\").eq({$i}).prop('name', 'company_messenger_public#{$oDirectory_Messenger->id}');
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
			$aSiteuser_Company_Directory_Websites = $oSiteuser_Company->Siteuser_Company_Directory_Websites->findAll();
			foreach ($aSiteuser_Company_Directory_Websites as $oSiteuser_Company_Directory_Website)
			{
				$oDirectory_Website = $oSiteuser_Company_Directory_Website->Directory_Website;

				$sWebsite_Address = trim(Core_Array::getPost("company_website_address#{$oDirectory_Website->id}"));

				if (!empty($sWebsite_Address))
				{
					$aUrl = @parse_url($sWebsite_Address);

					// Если не был указан протокол, или
					// указанный протокол некорректен для url
					!array_key_exists('scheme', $aUrl)
						&& $sWebsite_Address = 'http://' . $sWebsite_Address;

					$oDirectory_Website
						->description(strval(Core_Array::getPost("company_website_description#{$oDirectory_Website->id}")))
						->public(intval(Core_Array::getPost("company_website_public#{$oDirectory_Website->id}")))
						->value($sWebsite_Address)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} input[name='company_website_address#{$oDirectory_Website->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
					$oSiteuser_Company_Directory_Website->Directory_Website->delete();
				}
			}

			// Сайты, новые значения
			$aCompanyWebsiteAddresses = Core_Array::getPost('company_website_address', array());
			$aCompanyWebsiteNames = Core_Array::getPost('company_website_description', array());
			$aCompanyWebsitePublic = Core_Array::getPost('company_website_public', array());

			if (is_array($aCompanyWebsiteAddresses) && count($aCompanyWebsiteAddresses))
			{
				$i = 0;
				foreach ($aCompanyWebsiteAddresses as $key => $sWebsite_Address)
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
							->public(intval(Core_Array::get($aCompanyWebsitePublic, $key)))
							->description(Core_Array::get($aCompanyWebsiteNames, $key))
							->value($sWebsite_Address);

						$oSiteuser_Company->add($oDirectory_Website);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} input[name='company_website_address\\[\\]']\").eq({$i}).prop('name', 'company_website_address#{$oDirectory_Website->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='company_website_description\\[\\]']\").eq({$i}).prop('name', 'company_website_description#{$oDirectory_Website->id}');
							$(\"#{$windowId} input[name='company_website_public\\[\\]']\").eq({$i}).prop('name', 'company_website_public#{$oDirectory_Website->id}');
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

		// Добавление представителя
		if ($oSiteuser_Person
			&& (strlen(Core_Array::getPost('person_surname')) || strlen(Core_Array::getPost('person_name')) || strlen(Core_Array::getPost('person_patronymic')))
		)
		{
			$oSiteuser_Person->name = strval(Core_Array::getPost('person_name'));
			$oSiteuser_Person->surname = strval(Core_Array::getPost('person_surname'));
			$oSiteuser_Person->patronymic = strval(Core_Array::getPost('person_patronymic'));
			$oSiteuser_Person->post = strval(Core_Array::getPost('person_post'));
			$oSiteuser_Person->country = strval(Core_Array::getPost('person_country'));
			$oSiteuser_Person->postcode = strval(Core_Array::getPost('person_postcode'));
			$oSiteuser_Person->city = strval(Core_Array::getPost('person_city'));
			$oSiteuser_Person->address = strval(Core_Array::getPost('person_address'));

			if ($bNewSiteuser)
			{
				$oSiteuser->add($oSiteuser_Person);
			}
			else
			{
				$oSiteuser_Person->save();
			}

			// Телефоны, установленные значения
			$aSiteuser_Person_Directory_Phones = $oSiteuser_Person->Siteuser_Person_Directory_Phones->findAll();
			foreach ($aSiteuser_Person_Directory_Phones as $oSiteuser_Person_Directory_Phone)
			{
				$oDirectory_Phone = $oSiteuser_Person_Directory_Phone->Directory_Phone;

				$sPhone = trim(Core_Array::getPost("person_phone#{$oDirectory_Phone->id}"));

				if (!empty($sPhone))
				{
					$oDirectory_Phone
						->directory_phone_type_id(intval(Core_Array::getPost("person_phone_type#{$oDirectory_Phone->id}", 0)))
						->public(intval(Core_Array::getPost("person_phone_public#{$oDirectory_Phone->id}", 0)))
						->value($sPhone)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} select[name='person_phone_type#{$oDirectory_Phone->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();
					$this->_Admin_Form_Controller->addMessage(ob_get_clean());

					$oSiteuser_Person_Directory_Phone->Directory_Phone->delete();
				}
			}

			// Телефоны, новые значения
			$aPersonPhones = Core_Array::getPost('person_phone', array());
			$aPersonPhoneTypes = Core_Array::getPost('person_phone_type', array());
			$aPersonPhonePublic = Core_Array::getPost('person_phone_public', array());

			if (is_array($aPersonPhones) && count($aPersonPhones))
			{
				$i = 0;
				foreach ($aPersonPhones as $key => $sPhone)
				{
					$sPhone = trim($sPhone);

					if (!empty($sPhone))
					{
						$oDirectory_Phone = Core_Entity::factory('Directory_Phone')
							->directory_phone_type_id(intval(Core_Array::get($aPersonPhoneTypes, $key)))
							->public(intval(Core_Array::get($aPersonPhonePublic, $key)))
							->value($sPhone)
							->save();

						$oSiteuser_Person->add($oDirectory_Phone);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} select[name='person_phone_type\\[\\]']\").eq({$i}).prop('name', 'person_phone_type#{$oDirectory_Phone->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='person_phone\\[\\]']\").eq({$i}).prop('name', 'person_phone#{$oDirectory_Phone->id}');
							$(\"#{$windowId} input[name='person_phone_public\\[\\]']\").eq({$i}).prop('name', 'person_phone_public#{$oDirectory_Phone->id}');
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

			// Электронные адреса, установленные значения
			$aSiteuser_Person_Directory_Emails = $oSiteuser_Person->Siteuser_Person_Directory_Emails->findAll();
			foreach ($aSiteuser_Person_Directory_Emails as $oSiteuser_Person_Directory_Email)
			{
				$oDirectory_Email = $oSiteuser_Person_Directory_Email->Directory_Email;

				$sEmail = trim(Core_Array::getPost("person_email#{$oDirectory_Email->id}"));

				if (!empty($sEmail))
				{
					$oDirectory_Email
						->directory_email_type_id(intval(Core_Array::getPost("person_email_type#{$oDirectory_Email->id}", 0)))
						->public(intval(Core_Array::getPost("person_email_public#{$oDirectory_Email->id}", 0)))
						->value($sEmail)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} select[name='person_email_type#{$oDirectory_Email->id}']\").closest('.row').find('.btn-delete111').get(0));")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
					$oSiteuser_Person_Directory_Email->Directory_Email->delete();
				}
			}

			// Электронные адреса, новые значения
			$aPersonEmails = Core_Array::getPost('person_email', array());
			$aPersonEmailTypes = Core_Array::getPost('person_email_type', array());
			$aPersonEmailPublic = Core_Array::getPost('person_email_public', array());

			if (is_array($aPersonEmails) && count($aPersonEmails))
			{
				$i = 0;
				foreach ($aPersonEmails as $key => $sEmail)
				{
					$sEmail = trim($sEmail);

					if (!empty($sEmail))
					{
						$oDirectory_Email = Core_Entity::factory('Directory_Email')
							->directory_email_type_id(intval(Core_Array::get($aPersonEmailTypes, $key)))
							->public(intval(Core_Array::get($aPersonEmailPublic, $key)))
							->value($sEmail)
							->save();

						$oSiteuser_Person->add($oDirectory_Email);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} select[name='person_email_type\\[\\]']\").eq({$i}).prop('name', 'person_email_type#{$oDirectory_Email->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='person_email\\[\\]']\").eq({$i}).prop('name', 'person_email#{$oDirectory_Email->id}');
							$(\"#{$windowId} input[name='person_email_public\\[\\]']\").eq({$i}).prop('name', 'person_email_public#{$oDirectory_Email->id}');
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
			$aSiteuser_Person_Directory_Socials = $oSiteuser_Person->Siteuser_Person_Directory_Socials->findAll();
			foreach ($aSiteuser_Person_Directory_Socials as $oSiteuser_Person_Directory_Social)
			{
				$oDirectory_Social = $oSiteuser_Person_Directory_Social->Directory_Social;

				$sSocial_Address = trim(Core_Array::getPost("person_social_address#{$oDirectory_Social->id}"));

				if (!empty($sSocial_Address))
				{
					$aUrl = @parse_url($sSocial_Address);

					// Если не был указан протокол, или
					// указанный протокол некорректен для url
					!array_key_exists('scheme', $aUrl)
						&& $sSocial_Address = 'https://' . $sSocial_Address;

					$oDirectory_Social
						->directory_social_type_id(intval(Core_Array::getPost("person_social#{$oDirectory_Social->id}", 0)))
						->public(intval(Core_Array::getPost("person_social_public#{$oDirectory_Social->id}", 0)))
						->value($sSocial_Address)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} select[name='person_social#{$oDirectory_Social->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();
					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
					$oSiteuser_Person_Directory_Social->Directory_Social->delete();
				}
			}

			// Социальные сети, новые значения
			$aPersonSocialAddresses = Core_Array::getPost('person_social_address', array());
			$aPersonSocials = Core_Array::getPost('person_social', array());
			$aPersonSocialPublic = Core_Array::getPost('person_social_public', array());

			if (is_array($aPersonSocialAddresses) && count($aPersonSocialAddresses))
			{
				$i = 0;
				foreach ($aPersonSocialAddresses as $key => $sSocial_Address)
				{
					$sSocial_Address = trim($sSocial_Address);

					if (!empty($sSocial_Address))
					{
						$aUrl = @parse_url($sSocial_Address);

						// Если не был указан протокол, или
						// указанный протокол некорректен для url
						!array_key_exists('scheme', $aUrl)
							&& $sSocial_Address = 'https://' . $sSocial_Address;

						$oDirectory_Social = Core_Entity::factory('Directory_Social')
							->directory_social_type_id(intval(Core_Array::get($aPersonSocials, $key)))
							->public(intval(Core_Array::get($aPersonSocialPublic, $key)))
							->value($sSocial_Address)
							->save();

						$oSiteuser_Person->add($oDirectory_Social);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} select[name='person_social\\[\\]']\").eq({$i}).prop('name', 'person_social#{$oDirectory_Social->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='person_social_address\\[\\]']\").eq({$i}).prop('name', 'person_social_address#{$oDirectory_Social->id}');
							$(\"#{$windowId} input[name='person_social_public\\[\\]']\").eq({$i}).prop('name', 'person_social_public#{$oDirectory_Social->id}');
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
			$aSiteuser_Person_Directory_Messengers = $oSiteuser_Person->Siteuser_Person_Directory_Messengers->findAll();
			foreach ($aSiteuser_Person_Directory_Messengers as $oSiteuser_Person_Directory_Messenger)
			{
				$oDirectory_Messenger = $oSiteuser_Person_Directory_Messenger->Directory_Messenger;

				$sMessenger_Address = trim(Core_Array::getPost("person_messenger_username#{$oDirectory_Messenger->id}"));

				if (!empty($sMessenger_Address))
				{
					$oDirectory_Messenger
						->directory_messenger_type_id(intval(Core_Array::getPost("person_messenger#{$oDirectory_Messenger->id}", 0)))
						->public(intval(Core_Array::getPost("person_messenger_public#{$oDirectory_Messenger->id}", 0)))
						->value($sMessenger_Address)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} select[name='person_messenger#{$oDirectory_Messenger->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();
					$this->_Admin_Form_Controller->addMessage(ob_get_clean());

					$oSiteuser_Person_Directory_Messenger->Directory_Messenger->delete();
				}
			}

			// Мессенджеры, новые значения
			$aPersonMessengerAddresses = Core_Array::getPost('person_messenger_username', array());
			$aPersonMessengers = Core_Array::getPost('person_messenger', array());
			$aPersonMessengerPublic = Core_Array::getPost('person_messenger_public', array());

			if (is_array($aPersonMessengerAddresses) && count($aPersonMessengerAddresses))
			{
				$i = 0;
				foreach ($aPersonMessengerAddresses as $key => $sMessenger_Address)
				{
					$sMessenger_Address = trim($sMessenger_Address);

					if (!empty($sMessenger_Address))
					{
						$oDirectory_Messenger = Core_Entity::factory('Directory_Messenger')
							->directory_messenger_type_id(intval(Core_Array::get($aPersonMessengers, $key)))
							->public(intval(Core_Array::get($aPersonMessengerPublic, $key)))
							->value($sMessenger_Address)
							->save();

						$oSiteuser_Person->add($oDirectory_Messenger);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} select[name='person_messenger\\[\\]']\").eq({$i}).prop('name', 'person_messenger#{$oDirectory_Messenger->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='person_messenger_username\\[\\]']\").eq({$i}).prop('name', 'person_messenger_username#{$oDirectory_Messenger->id}');
							$(\"#{$windowId} input[name='person_messenger_public\\[\\]']\").eq({$i}).prop('name', 'person_messenger_public#{$oDirectory_Messenger->id}');
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
			$aSiteuser_Person_Directory_Websites = $oSiteuser_Person->Siteuser_Person_Directory_Websites->findAll();
			foreach ($aSiteuser_Person_Directory_Websites as $oSiteuser_Person_Directory_Website)
			{
				$oDirectory_Website = $oSiteuser_Person_Directory_Website->Directory_Website;

				$sWebsite_Address = trim(Core_Array::getPost("person_website_address#{$oDirectory_Website->id}"));

				if (!empty($sWebsite_Address))
				{
					$aUrl = @parse_url($sWebsite_Address);

					// Если не был указан протокол, или
					// указанный протокол некорректен для url
					!array_key_exists('scheme', $aUrl)
						&& $sWebsite_Address = 'http://' . $sWebsite_Address;

					$oDirectory_Website
						->description(strval(Core_Array::getPost("person_website_description#{$oDirectory_Website->id}")))
						->public(intval(Core_Array::getPost("person_website_public#{$oDirectory_Website->id}")))
						->value($sWebsite_Address)
						->save();
				}
				else
				{
					// Удаляем пустую строку с полями
					ob_start();
					Core::factory('Core_Html_Entity_Script')
						->value("$.deleteFormRow($(\"#{$windowId} input[name='person_website_address#{$oDirectory_Website->id}']\").closest('.row').find('.btn-delete').get(0));")
						->execute();

					$this->_Admin_Form_Controller->addMessage(ob_get_clean());
					$oSiteuser_Person_Directory_Website->Directory_Website->delete();
				}
			}

			// Сайты, новые значения
			$aPersonWebsiteAddresses = Core_Array::getPost('person_website_address', array());
			$aPersonWebsiteNames = Core_Array::getPost('person_website_description', array());
			$aPersonWebsitePublic = Core_Array::getPost('person_website_public', array());

			if (is_array($aPersonWebsiteAddresses) && count($aPersonWebsiteAddresses))
			{
				$i = 0;
				foreach ($aPersonWebsiteAddresses as $key => $sWebsite_Address)
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
							->public(intval(Core_Array::get($aPersonWebsitePublic, $key)))
							->description(Core_Array::get($aPersonWebsiteNames, $key))
							->value($sWebsite_Address);

						$oSiteuser_Person->add($oDirectory_Website);

						ob_start();
						Core::factory('Core_Html_Entity_Script')
							->value("$(\"#{$windowId} input[name='person_website_address\\[\\]']\").eq({$i}).prop('name', 'person_website_address#{$oDirectory_Website->id}').closest('.row').find('.btn-delete').removeClass('hide');
							$(\"#{$windowId} input[name='person_website_description\\[\\]']\").eq({$i}).prop('name', 'person_website_description#{$oDirectory_Website->id}');
							$(\"#{$windowId} input[name='person_website_public\\[\\]']\").eq({$i}).prop('name', 'person_website_public#{$oDirectory_Website->id}');
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

		// Отправка письма с подтверждением регистрации
		if (!is_null(Core_Array::getPost('send_mail_confirm')))
		{
			$Siteuser_Controller_Show = new Siteuser_Controller_Show($oSiteuser);

			$oXsl = Core_Entity::factory('Xsl')->getByName('ПисьмоПодтверждениеРегистрации');

			if (!is_null($oXsl))
			{
				$oSite_Alias = $oSiteuser->Site->getCurrentAlias();
				$Siteuser_Controller_Show->subject(
						Core::_('Siteuser.confirm_subject', !is_null($oSite_Alias) ? $oSite_Alias->alias_name_without_mask : '')
					)
					->sendConfirmationMail($oXsl);
			}
		}

		Core_Event::notify(get_class($this) . '.onAfterRedeclaredApplyObjectProperty', $this, array($this->_Admin_Form_Controller));
	}

	/**
	 * Executes the business logic.
	 * @param mixed $operation Operation name
	 * @return mixed
	 */
	public function execute($operation = NULL)
	{
		if (!is_null($operation) && $operation != '')
		{
			$id = Core_Array::getRequest('id');
			$login = Core_Array::getRequest('login');
			$site_id = Core_Array::getRequest('site_id');

			$oSameSiteuser = Core_Entity::factory('Site', $site_id)->Siteusers->getByLogin($login);

			// The same login
			if (!is_null($oSameSiteuser) && $oSameSiteuser->id != $id)
			{
				$this->addMessage(
					Core_Message::get(Core::_('Siteuser.login_error'), 'error')
				);
				return TRUE;
			}

			$email = Core_Array::getRequest('email');
			if (strlen($email))
			{
				$oSameSiteuser = Core_Entity::factory('Site', $site_id)->Siteusers->getByEmail($email);

				// The same e-mail
				if (!is_null($oSameSiteuser) && $oSameSiteuser->id != $id)
				{
					$this->addMessage(
						Core_Message::get(Core::_('Siteuser.email_error'), 'error')
					);
					return TRUE;
				}
			}
		}

		return parent::execute($operation);
	}

	/**
	 * Fill user groups
	 * @param int $iSiteId site ID
	 * @return array
	 */
	public function fillSiteuserGroups($iSiteId)
	{
		$aReturn = array();
		$aChildren = Core_Entity::factory('Siteuser_Group')->getBySiteId($iSiteId);

		foreach ($aChildren as $oSiteuser_Group)
		{
			$aReturn[$oSiteuser_Group->id] = $oSiteuser_Group->name;
		}

		return $aReturn;
	}

	/*
	 * Show siteuser select2 with button
	 */
	static public function addSiteuserSelect2($oSiteuserSelect, $oSiteuser, $oAdmin_Form_Controller)
	{
		$windowId = $oAdmin_Form_Controller->getWindowId();
		$siteuser_id = $oSiteuser ? $oSiteuser->id : 0;

		$oUser = Core_Auth::getCurrentUser();

		if ($oSiteuser && $oUser->checkModuleAccess(array('siteuser'), $oSiteuser->Site))
		{
			$oSiteuserLink = Core::factory('Core_Html_Entity_Span');
			$oSiteuserLink
				->class('input-group-addon siteuser-link' . (!$siteuser_id ? ' hidden' : ''))
				->add(
					Core::factory('Core_Html_Entity_A')
						->target('_blank')
						->href(
							'/admin/siteuser/index.php?hostcms[action]=edit&hostcms[checked][0][' . $siteuser_id . ']=1'
						)
						->onclick(
							'mainFormLocker.unlock(); $.modalLoad({path: \'/admin/siteuser/index.php\', action: \'edit\', operation: \'modal\', additionalParams: \'hostcms[checked][0][' . $siteuser_id . ']=1\', windowId: \'' . $windowId . '\'}); return false'
						)
						->class('fa fa-user btn-default show-user-info')
				);

			$oSiteuserSelect
				->add($oSiteuserLink)
				->add(
					Core::factory('Core_Html_Entity_Span')
						->class('input-group-addon')
						->onclick('mainFormLocker.unlock(); $.modalLoad({path: \'/admin/siteuser/index.php\', action: \'edit\', operation: \'modal\', additionalParams: \'hostcms[checked][0][0]=1\', windowId: \'' . $windowId . '\'}); return false')
						->add(
							Core::factory('Core_Html_Entity_Span')
								->class('fa fa-plus')
						)
				);
		}

		$placeholder = Core::_('Siteuser.select_siteuser');
		$language = Core_i18n::instance()->getLng();

		$windowIdEscaped = Core_Str::escapeJavascriptVariable($windowId);

		$oCore_Html_Entity_Script = Core::factory('Core_Html_Entity_Script')
			->value("$('#{$windowIdEscaped} select[name = {$oSiteuserSelect->name}]')
				.selectSiteuser({language: '{$language}', placeholder: '{$placeholder}'})
				.val('{$siteuser_id}')
				.trigger('change.select2')
				.parent()
				.addClass('col-xs-12');

				$('#{$windowIdEscaped} select[name = {$oSiteuserSelect->name}]').on('select2:unselect', function (){
					$(this)
						.nextAll('.input-group-addon.siteuser-link')
						.toggleClass('hidden');

					$('.siteuser-representative-list').empty();
				});

				var siteuserLink = $('#{$windowIdEscaped} .siteuser-link');

				if (siteuserLink.length)
				{
					$('select[name = {$oSiteuserSelect->name}]').on('select2:select', function (e) {
						var data = e.params.data;

						siteuserLink.removeClass('hidden');

						siteuserLink.find('a.show-user-info')
							.attr('href', '/admin/siteuser/index.php?hostcms[action]=edit&hostcms[checked][0][' + data.id + ']=1')
							.attr('onclick', 'mainFormLocker.unlock(); $.modalLoad({path: \'/admin/siteuser/index.php\',action: \'edit\', operation: \'modal\',additionalParams: \'hostcms[checked][0][' + data.id + ']=1\',view: \'list\', windowId: \'id_content\'}); return false');

						// Замена аватарок представителей
						$.ajax({
							url: '/admin/siteuser/index.php',
							data: { 'loadSelect2Avatars': 1, 'siteuser_id': data.id },
							dataType: 'json',
							type: 'POST',
							success: function(answer){
								$('.siteuser-representative-list').empty();

								if (answer.result == 'success')
								{
									$('.siteuser-representative-list').append(answer.html);
								}
							}
						});
					});
				}");

		$oSiteuserSelect->add($oCore_Html_Entity_Script);
	}

	static public function addSiteuserRepresentativeAvatars($oSiteuser)
	{
		$aObjects = array();

		$aSiteuser_Companies = $oSiteuser->Siteuser_Companies->findAll(FALSE);
		foreach ($aSiteuser_Companies as $oSiteuser_Company)
		{
			$aObjects[] = array(
				'id' => $oSiteuser_Company->id,
				'name' =>  htmlspecialchars($oSiteuser_Company->name),
				'src' => $oSiteuser_Company->getAvatar(),
				'type' =>  'company'
			);
		}

		$aSiteuser_People = $oSiteuser->Siteuser_People->findAll(FALSE);
		foreach ($aSiteuser_People as $oSiteuser_Person)
		{
			$fullName = $oSiteuser_Person->getFullName();

			$aObjects[] = array(
				'id' => $oSiteuser_Person->id,
				'name' =>  htmlspecialchars($fullName),
				'src' => $oSiteuser_Person->getAvatar(),
				'type' =>  'person'
			);
		}

		$icons = '<div class="siteuser-representative-wrapper">';

		$oAdmin_Form = Core_Entity::factory('Admin_Form', 230); // Форма "Компании и представители"
		$oAdminUser = Core_Auth::getCurrentUser();

		foreach ($aObjects as $aObject)
		{
			$dataset = $aObject['type'] == 'company'
				? 0
				: 1;

			$image = $oAdmin_Form->Admin_Form_Actions->checkAllowedActionForUser($oAdminUser, 'view')
				? '<a href="/admin/siteuser/representative/index.php?hostcms[action]=view&hostcms[checked][' . $dataset . '][' . $aObject['id'] . ']=1" onclick="$.modalLoad({path: \'/admin/siteuser/representative/index.php\', action: \'view\', operation: \'modal\', additionalParams: \'hostcms[checked][' . $dataset . '][' . $aObject['id'] . ']=1\', windowId: \'id_content\'}); return false"><img width="30" height="30" class="img-circle user-avatar" title="' . $aObject['name'] . '" src="' . $aObject['src'] . '"/></a>'
				: '<img width="30" height="30" title="' . $aObject['name'] . '" class="img-circle user-avatar" src="' . $aObject['src'] . '"/>';

			$icons .= '<div>' . $image . '</div>';
		}

		$icons .= '</div>';

		return $icons;
	}
}