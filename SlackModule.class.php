<?php

include_once 'slack-api/Slack.php';

/**
 * Slack module definition
 *
 * @package activeCollab.modules.slack
 * @subpackage models
 */

class SlackModule extends AngieModule
{

	/**
	 * Plain module name
	 *
	 * @var string
	 */
	protected $name = 'slack';

	/**
	 * Module version
	 *
	 * @var string
	 */
	protected $version = '1.0';

	/**
	 * Is system module flag
	 *
	 * @var boolean
	 */
	var $is_system = false;

	/**
	 * Define module routes
	 */
	function defineRoutes() {
	}

	/**
	 * Define event handlers
	 */
	function defineHandlers()
	{

/*
     EventsManager::listen('on_project_created', 'on_project_created');
     EventsManager::listen('on_project_deleted', 'on_project_deleted');
*/

     EventsManager::listen('on_object_inserted', 'on_object_inserted');
     EventsManager::listen('on_object_updated', 'on_object_updated');
     EventsManager::listen('on_object_deleted', 'on_object_deleted');

     EventsManager::listen('on_object_opened', 'on_object_opened');
     EventsManager::listen('on_object_completed', 'on_object_completed');

	}

	/**
	 * Get module display name
	 *
	 * @return string
	 */
	function getDisplayName() {
		return lang('Slack');
	}

	/**
	 * Return module description
	 *
	 * @return string
	 */
	function getDescription() {
		return lang('Display notifications in Slack.');
	}

	/**
	 * Return module uninstallation message
	 *
	 * @return string
	 */
	function getUninstallMessage() {
		return lang('Slack will be deactivated. Are you sure?');
	}

}