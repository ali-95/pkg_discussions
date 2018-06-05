<?php
/**
 * @package    Discussions - Latest Topics Module
 * @version    1.0.0
 * @author     Nerudas  - nerudas.ru
 * @copyright  Copyright (c) 2013 - 2018 Nerudas. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 * @link       https://nerudas.ru
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

// Include route helper
JLoader::register('DiscussionsHelperRoute', JPATH_SITE . '/components/com_discussions/helpers/route.php');
JLoader::register('ProfilesHelperRoute', JPATH_SITE . '/components/com_profiles/helpers/route.php');
JLoader::register('CompaniesHelperRoute', JPATH_SITE . '/components/com_companies/helpers/route.php');

// Load Language
$language = Factory::getLanguage();
$language->load('com_discussions', JPATH_SITE, $language->getTag(), false);

// Initialize model
BaseDatabaseModel::addIncludePath(JPATH_ROOT . '/components/com_discussions/models');
$model = BaseDatabaseModel::getInstance('Topics', 'DiscussionsModel', array('ignore_request' => true));
$model->setState('list.limit', $params->get('limit', 5));
$model->setState('filter.category', $params->get('category', 1));
if ((!Factory::getUser()->authorise('core.edit.state', 'com_discussions.topic')) &&
	(!Factory::getUser()->authorise('core.edit', 'com_discussions.topic')))
{
	$model->setState('filter.published', 1);
}
else
{
	$model->setState('filter.published', array(0, 1));
}
$model->setState('filter.onlymy', $params->get('onlymy', ''));
$model->setState('filter.author_id', $params->get('author_id', ''));

// Variables
$items        = $model->getItems();
$categoryLink = DiscussionsHelperRoute::getTopicsRoute($params->get('category', 1));

if (!empty($params->get('author_id', '')))
{
	$categoryLink .= '&filter[author_id]=' . $params->get('author_id');
}
if (!empty($params->get('onlymy', '')))
{
	$categoryLink .= '&filter[onlymy]=' . $params->get('onlymy');
}
$categoryLink = Route::_($categoryLink);

$addLink = Route::_(DiscussionsHelperRoute::getTopicFormRoute());

require ModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));