<?php
/**
 * Events.
 *
 * @package HostCMS
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2021 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
require_once('../../bootstrap.php');

Core_Auth::authorization($sModule = 'event');

// File download
if (Core_Array::getGet('downloadFile'))
{
	$oEvent_Attachment = Core_Entity::factory('Event_Attachment')->find(intval(Core_Array::getGet('downloadFile')));
	if (!is_null($oEvent_Attachment->id))
	{
		$oUser = Core_Auth::getCurrentUser();

		$oEvent_User = $oEvent_Attachment->Event->Event_Users->getByuser_id($oUser->id);

		if (!is_null($oEvent_User))
		{
			$filePath = $oEvent_Attachment->getFilePath();
			Core_File::download($filePath, $oEvent_Attachment->file_name, array('content_disposition' => 'inline'));
		}
		else
		{
			throw new Core_Exception('Access denied');
		}
	}
	else
	{
		throw new Core_Exception('Access denied');
	}

	exit();
}

// Код формы
$iAdmin_Form_Id = 220;
$sAdminFormAction = '/admin/event/index.php';

$oAdmin_Form = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id);

$parent_id = intval(Core_Array::getGet('parent_id', 0));
$bShow_subs = !is_null(Core_Array::getGet('show_subs'));

// Контроллер формы
$oAdmin_Form_Controller = Admin_Form_Controller::create($oAdmin_Form);
$oAdmin_Form_Controller
	->module(Core_Module::factory($sModule))
	->setUp()
	->path($sAdminFormAction)
	->title(Core::_('Event.events_title'))
	->pageTitle(Core::_('Event.events_title'))
	->addView('kanban', 'Event_Controller_Kanban');

if ($bShow_subs && $parent_id)
{
	$oAdmin_Form_Controller
		->Admin_View('Admin_Internal_View')
		->addView('event', 'Event_Controller_Related_Event')
		->view('event');
}

$siteuser_id = intval(Core_Array::getGet('siteuser_id'));
$siteuser_id && $oAdmin_Form_Controller->Admin_View('Admin_Internal_View');

if (Core_Array::getPost('id') && (Core_Array::getPost('target_id') || Core_Array::getPost('sender_id')))
{
	$aJSON = array(
		'status' => 'error'
	);

	$iEventId = intval(Core_Array::getPost('id'));
	$iTargetStatusId = intval(Core_Array::getPost('target_id'));

	$oEvent_Status = Core_Entity::factory('Event_Status')->find($iTargetStatusId);
	if (!is_null($oEvent_Status->id))
	{
		$oEvent = Core_Entity::factory('Event')->find($iEventId);

		if (!is_null($oEvent->id))
		{
			$previousStatusId = $oEvent->event_status_id;

			$oEvent->event_status_id = $oEvent_Status->id;
			$oEvent->save();

			if ($previousStatusId != $oEvent->event_status_id)
			{
				$oEvent->notifyBotsChangeStatus();
			}

			$aJSON['status'] = 'success';
		}
	}

	Core::showJson($aJSON);
}

$oCurrentUser = Core_Auth::getCurrentUser();
$windowId = $oAdmin_Form_Controller->getWindowId();

$additionalParams = Core_Str::escapeJavascriptVariable(
	str_replace(array('"'), array('&quot;'), $oAdmin_Form_Controller->additionalParams)
);

// Меню формы
$oAdmin_Form_Entity_Menus = Admin_Form_Entity::factory('Menus');

// Элементы меню
$oAdmin_Form_Entity_Menus->add(
	Admin_Form_Entity::factory('Menu')
		->name(Core::_('Event.events_menu_add_event'))
		->icon('fa fa-plus')
		->img('/admin/images/add.gif')
		->href(
			$bShow_subs
				? NULL
				: $oAdmin_Form_Controller->getAdminActionLoadHref($oAdmin_Form_Controller->getPath(), 'edit', NULL, 0, 0)
		)
		->onclick(
			$bShow_subs // &show_subs=1&hideMenu=1&parent_id={$parent_id}
				? "$.modalLoad({path: '{$oAdmin_Form_Controller->getPath()}', action: 'edit', operation: 'modal', additionalParams: 'hostcms[checked][0][0]=1&{$additionalParams}', windowId: '{$windowId}'}); return false"
				: $oAdmin_Form_Controller->getAdminActionLoadAjax($oAdmin_Form_Controller->getPath(), 'edit', NULL, 0, 0)
		)
);

if (!$siteuser_id && is_null(Core_Array::getGet('hideMenu')))
{
	$oAdmin_Form_Entity_Menus->add(
		Admin_Form_Entity::factory('Menu')
			->name(Core::_('Event.events_menu_directories'))
			->icon('fa fa-book')
			->add(
				Admin_Form_Entity::factory('Menu')
					->name(Core::_('Event.events_menu_types'))
					->icon('fa fa-bars')
					->img('/admin/images/add.gif')
					->href(
						$oAdmin_Form_Controller->getAdminLoadHref($sEventGroupsFormPath = '/admin/event/type/index.php', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'list')
					)
					->onclick(
						$oAdmin_Form_Controller->getAdminLoadAjax($sEventGroupsFormPath, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'list')
					)
			)
			->add(
				Admin_Form_Entity::factory('Menu')
					->name(Core::_('Event.events_menu_groups'))
					->icon('fa fa-folder-o')
					->img('/admin/images/add.gif')
					->href(
						$oAdmin_Form_Controller->getAdminLoadHref($sEventGroupsFormPath = '/admin/event/group/index.php', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'list')
					)
					->onclick(
						$oAdmin_Form_Controller->getAdminLoadAjax($sEventGroupsFormPath, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'list')
					)
			)
			->add(
				Admin_Form_Entity::factory('Menu')
					->name(Core::_('Event.events_menu_statuses'))
					->icon('fa fa-circle')
					->img('/admin/images/add.gif')
					->href(
						$oAdmin_Form_Controller->getAdminLoadHref($sEventStatusesFormPath = '/admin/event/status/index.php', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'list')
					)
					->onclick(
						$oAdmin_Form_Controller->getAdminLoadAjax($sEventStatusesFormPath, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'list')
					)
			)
	);
}

// Добавляем все меню контроллеру
$oAdmin_Form_Controller->addEntity($oAdmin_Form_Entity_Menus);

if (!$siteuser_id && !$oCurrentUser->read_only && is_null(Core_Array::getGet('hideMenu')))
{
	$oAdmin_Form_Controller->addEntity(
		Admin_Form_Entity::factory('Code')
			->html('<div class="add-event margin-bottom-20">
				<form action="/admin/event/index.php" method="POST">
					<div class="input-group">
						<input type="text" name="event_name" class="form-control" placeholder="' . Core::_('Event.placeholderEventName') . '">
						<span class="input-group-btn bg-azure bordered-azure">
							<button id="sendForm" class="btn btn-azure" type="submit" onclick="' . $oAdmin_Form_Controller->getAdminSendForm('addEvent', NULL, '') . '">
								<i class="fa fa-check no-margin"></i>
							</button>
						</span>
						<input type="hidden" name="hostcms[checked][0][0]" value="1"/>
					</div>
				</form>
			</div>')
	);
}

$oAdmin_Form_Entity_Breadcrumbs = Admin_Form_Entity::factory('Breadcrumbs');

// Добавляем крошку на текущую форму
$oAdmin_Form_Entity_Breadcrumbs->add(
	Admin_Form_Entity::factory('Breadcrumb')
		->name(Core::_('Event.events_title'))
		->href(
			$oAdmin_Form_Controller->getAdminLoadHref($oAdmin_Form_Controller->getPath(), NULL, NULL, '')
		)
		->onclick(
			$oAdmin_Form_Controller->getAdminLoadAjax($oAdmin_Form_Controller->getPath(), NULL, NULL, '')
		)
);

if ($parent_id)
{
	$oParentEvent = Core_Entity::factory('Event')->find($parent_id);

	if (!is_null($oParentEvent->id))
	{
		$aBreadcrumbs = array();

		do
		{
			$additionalParams = '&parent_id=' . $oParentEvent->id;

			$aBreadcrumbs[] = Admin_Form_Entity::factory('Breadcrumb')
				->name($oParentEvent->name)
				->href(
					$oAdmin_Form_Controller->getAdminLoadHref($oAdmin_Form_Controller->getPath(), NULL, NULL, $additionalParams)
				)
				->onclick(
					$oAdmin_Form_Controller->getAdminLoadAjax($oAdmin_Form_Controller->getPath(), NULL, NULL, $additionalParams)
				);
		} while ($oParentEvent = $oParentEvent->getParent());

		$aBreadcrumbs = array_reverse($aBreadcrumbs);

		foreach ($aBreadcrumbs as $oAdmin_Form_Entity_Breadcrumb)
		{
			$oAdmin_Form_Entity_Breadcrumbs->add(
				$oAdmin_Form_Entity_Breadcrumb
			);
		}
	}
}

$oAdmin_Form_Controller->addEntity($oAdmin_Form_Entity_Breadcrumbs);

// Действие редактирования
$oAdmin_Form_Action = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('edit');

if ($oAdmin_Form_Action && $oAdmin_Form_Controller->getAction() == 'edit')
{
	$oEvent_Controller_Edit = Admin_Form_Action_Controller::factory(
		'Event_Controller_Edit', $oAdmin_Form_Action
	);

	// Хлебные крошки для контроллера редактирования
	$oEvent_Controller_Edit
		->addEntity(
			$oAdmin_Form_Entity_Breadcrumbs
		);

	// Добавляем типовой контроллер редактирования контроллеру формы
	$oAdmin_Form_Controller->addAction($oEvent_Controller_Edit);
}

// Действие "Применить"
$oAdminFormActionApply = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('apply');

if ($oAdminFormActionApply && $oAdmin_Form_Controller->getAction() == 'apply')
{
	$oControllerApply = Admin_Form_Action_Controller::factory(
		'Admin_Form_Action_Controller_Type_Apply', $oAdminFormActionApply
	);

	// Добавляем типовой контроллер редактирования контроллеру формы
	$oAdmin_Form_Controller->addAction($oControllerApply);
}

// Действие "Копировать"
$oAdminFormActionCopy = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('copy');

if ($oAdminFormActionCopy && $oAdmin_Form_Controller->getAction() == 'copy')
{
	$oControllerCopy = Admin_Form_Action_Controller::factory(
		'Admin_Form_Action_Controller_Type_Copy', $oAdminFormActionCopy
	);

	// Добавляем типовой контроллер редактирования контроллеру формы
	$oAdmin_Form_Controller->addAction($oControllerCopy);
}

// Действие "Изменить группу"
$oAdminFormActionChangeGroup = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('changeGroup');

if ($oAdminFormActionChangeGroup && $oAdmin_Form_Controller->getAction() == 'changeGroup')
{
	$oEventControllerGroup = Admin_Form_Action_Controller::factory(
		'Event_Controller_Group', $oAdminFormActionChangeGroup
	);

	// Добавляем типовой контроллер редактирования контроллеру формы
	$oAdmin_Form_Controller->addAction($oEventControllerGroup);
}

// Действие "Изменить статус"
$oAdminFormActionChangeStatus = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('changeStatus');

if ($oAdminFormActionChangeStatus && $oAdmin_Form_Controller->getAction() == 'changeStatus')
{
	$oEventControllerStatus = Admin_Form_Action_Controller::factory(
		'Event_Controller_Status', $oAdminFormActionChangeStatus
	);

	// Добавляем типовой контроллер редактирования контроллеру формы
	$oAdmin_Form_Controller->addAction($oEventControllerStatus);
}

// Действие "Удалить файл"
$oAdminFormActionDeleteFile = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('deleteFile');

if ($oAdminFormActionDeleteFile && $oAdmin_Form_Controller->getAction() == 'deleteFile')
{
	$oController_Type_Delete_File = Admin_Form_Action_Controller::factory(
		'Admin_Form_Action_Controller_Type_Delete_File', $oAdminFormActionDeleteFile
	);

	$oController_Type_Delete_File
		->methodName('deleteFile')
		->divId('file_' . $oAdmin_Form_Controller->getOperation());

	// Добавляем контроллер удаления файла контроллеру формы
	$oAdmin_Form_Controller->addAction($oController_Type_Delete_File);
}

// Действие "Добавить дело"
$oAdminFormActionAddEvent = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('addEvent');

if ($oAdminFormActionAddEvent && $oAdmin_Form_Controller->getAction() == 'addEvent')
{
	$oControllerAddEvent = Admin_Form_Action_Controller::factory(
		'Event_Controller_Add', $oAdminFormActionAddEvent
	);

	$sEventName = trim(strval(Core_Array::getRequest('event_name')));

	$oControllerAddEvent->event_name($sEventName);

	// Добавляем типовой контроллер редактирования контроллеру формы
	$oAdmin_Form_Controller->addAction($oControllerAddEvent);
}

// Источник данных 0
$oAdmin_Form_Dataset = new Admin_Form_Dataset_Entity(
	Core_Entity::factory('Event')
);

$parent_id
	&& $oAdmin_Form_Dataset->addCondition(array('where' => array('parent_id', '=', $parent_id)));

// Только если идет фильтрация, Контрагент TopFilter, фильтр по идентификатору
if (isset($oAdmin_Form_Controller->request['topFilter_1582'])
	&& $oAdmin_Form_Controller->request['topFilter_1582'] != '')
{
	$oAdmin_Form_Dataset->addCondition(
		array('leftJoin' => array('event_siteusers', 'events.id', '=', 'event_siteusers.event_id'))
	);
}

// Только если идет фильтрация, Контрагент, фильтр по тексту
if (isset($oAdmin_Form_Controller->request['admin_form_filter_1497'])
	&& $oAdmin_Form_Controller->request['admin_form_filter_1497'] != '')
{
	$oAdmin_Form_Dataset
		->addCondition(
			array('select' => array(
					array(Core_QueryBuilder::expression('GROUP_CONCAT(COALESCE(siteuser_people.surname, \'\'), \' \', COALESCE(siteuser_people.name, \'\'), \' \', COALESCE(siteuser_people.patronymic, \'\'), \' \', COALESCE(siteuser_companies.name, \'\') SEPARATOR \' \')'), 'counterparty')
				)
			)
		)
		->addCondition(
			array('join' => array('event_siteusers', 'events.id', '=', 'event_siteusers.event_id'))
		)
		->addCondition(
			array('leftJoin' => array('siteuser_companies', 'event_siteusers.siteuser_company_id', '=', 'siteuser_companies.id'))
		)
		->addCondition(
			array('leftJoin' => array('siteuser_people', 'event_siteusers.siteuser_person_id', '=', 'siteuser_people.id'))
		);
}

$oAdmin_Form_Dataset
	->addCondition(
		array('select' => array('events.*'))
	)
	->addCondition(
		array('join' => array('event_users', 'events.id', '=', 'event_users.event_id'))
	)
	->addCondition(
		array('where' => array('event_users.user_id', '=', $oCurrentUser->id))
	)->addCondition(
		array('groupBy' => array('events.id'))
	);

if ($siteuser_id)
{
	$oAdmin_Form_Dataset->addCondition(
		array('join' => array(array('event_siteusers', 'es'), 'events.id', '=', 'es.event_id'))
	)->addCondition(
		array('leftJoin' => array(array('siteuser_companies', 'sc'), 'es.siteuser_company_id', '=', 'sc.id'))
	)
	->addCondition(
		array('leftJoin' => array(array('siteuser_people', 'sp'), 'es.siteuser_person_id', '=', 'sp.id'))
	)
	->addCondition(
		array('open' => array())
	)
		->addCondition(
			array('where' => array('sc.siteuser_id', '=', $siteuser_id))
		)
		->addCondition(
			array('setOr' => array())
		)
		->addCondition(
			array('where' => array('sp.siteuser_id', '=', $siteuser_id))
		)
	->addCondition(
		array('close' => array())
	);
}

// Список значений для фильтра и поля
$aEvent_Groups = Core_Entity::factory('Event_Group')->findAll();
$aList[0] = "—";
foreach ($aEvent_Groups as $oEvent_Group)
{
	$aList[$oEvent_Group->id] = $oEvent_Group->name;
}

$oAdmin_Form_Dataset->changeField('event_group_id', 'list', $aList);

!Core::moduleIsActive('siteuser')
	&& $oAdmin_Form_Dataset->changeField('counterparty', 'class', 'hidden');

function correctDateTime($sDateTime, $oAdmin_Form_Field)
{
	if (strlen($sDateTime))
	{
		$aDateTime = explode(' ', trim($sDateTime, '*'));

		// Дата
		if (isset($aDateTime[0]))
		{
			$aDate = explode('.', $aDateTime[0]);

			foreach ($aDate as $key => $value)
			{
				// Добавляем ведущий ноль элементам даты
				strlen($value) == 1 && $aDate[$key] = '0' . $value;
			}

			count($aDate) > 1 && $aDateTime[0] = implode('-', array_reverse($aDate));
		}

		return '*' . implode(' ', $aDateTime) . '*';
	}
}

$oAdmin_Form_Controller
	->addFilterCallback('start', 'correctDateTime')
	->addFilterCallback('deadline', 'correctDateTime');

$oAdmin_Form_Controller->addFilter('dataCounterparty', array($oAdmin_Form_Controller, '_filterCallbackCounterparty'));

// Добавляем источник данных контроллеру формы
$oAdmin_Form_Controller->addDataset($oAdmin_Form_Dataset);

function dataCounterparty($value, $oAdmin_Form_Field)
{
	if (!is_null($value) && $value !== '')
	{
		if (strpos($value, 'person_') === 0)
		{
			// Change where() fieldname
			$oAdmin_Form_Field->name = 'event_siteusers.siteuser_person_id';
			$value = substr($value, 7);
		}
		elseif (strpos($value, 'company_') === 0)
		{
			// Change where() fieldname
			$oAdmin_Form_Field->name = 'event_siteusers.siteuser_company_id';
			$value = substr($value, 8);
		}
		else
		{
			//throw new Core_Exception('Wrong `dataCounterparty` value!');
		}
	}

	return $value;
}

$oAdmin_Form_Controller->addFilterCallback('dataCounterparty', 'dataCounterparty');

// Показ формы
$oAdmin_Form_Controller->execute();