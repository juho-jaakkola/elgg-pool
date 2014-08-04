<?php

elgg_register_event_handler('init', 'system', 'pool_init');

/**
 * Initialize the plugin
 */
function pool_init () {
	$actions_path = elgg_get_plugins_path() . 'pool/actions/pool/';
	elgg_register_action('pool/admin/save', $actions_path . 'save.php', 'admin');
	elgg_register_action('pool/admin/delete', $actions_path . 'delete.php', 'admin');
	elgg_register_action('pool/toggle_membership', $actions_path . 'toggle_membership.php');
	elgg_register_action('pool/shift', $actions_path . 'shift.php', 'admin');
	elgg_register_action('pool/remove', $actions_path . 'remove.php', 'admin');

	if (elgg_in_context('activity')) {
		elgg_extend_view('page/elements/sidebar', 'pool/sidebar');
	}

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'pool_entity_menu');
	elgg_register_plugin_hook_handler('entity:url', 'object', 'pool_url_handler');

	elgg_register_page_handler('pool', 'pool_page_handler');

	elgg_register_menu_item('site', array(
		'name' => 'pool',
		'href' => 'pool/all',
		'text' => elgg_echo('pool:all'),
	));

	// Register cron hook for each of the periods
	foreach (array('daily', 'weekly', 'monthly') as $period) {
		elgg_register_plugin_hook_handler('cron', $period, 'pool_assign_new_turn_cron');
	}

	// Rearrange lists when user is banned or deleted
	elgg_register_event_handler('delete', 'user', 'pool_remove_user');
	elgg_register_event_handler('ban', 'user', 'pool_remove_user');

	// This is used instead of elgg_register_admin_menu_item() because we
	// don't want to create a separate menu section
	elgg_register_menu_item('page', array(
		'name' => 'pools',
		'href' => 'admin/pool/list',
		'text' => elgg_echo('admin:pool:list'),
		'context' => 'admin',
		'section' => 'administer'
	));
}

/**
 * Set up entity menu for pool objects
 *
 * @param string $hook 'register'
 * @param string $type 'menu:entity'
 * @param ElggMenuItem[] $return
 * @param array $params
 * @return ElggMenuItem[]
 */
function pool_entity_menu ($hook, $type, $return, $params) {
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'task_pool') {
		return $return;
	}

	if (elgg_is_logged_in()) {
		$entity = $params['entity'];

		$user_guid = elgg_get_logged_in_user_guid();

		if ($entity->isMember($user_guid)) {
			$text = elgg_echo('pool:leave');
		} else {
			$text = elgg_echo('pool:join');
		}

		$return[] = ElggMenuItem::factory(array(
			'name' => 'test',
			'text' => "<span>$text</span>",
			'href' => "action/pool/toggle_membership?pool_guid={$entity->guid}&user_guid=$user_guid",
			'priority' => 150,
			'is_action' => true,
		));

		if (elgg_is_admin_logged_in()) {
			$return[] = ElggMenuItem::factory(array(
				'name' => 'edit',
				'text' => elgg_echo('edit'),
				'href' => "admin/pool/save?guid={$entity->guid}",
			));

			$return[] = ElggMenuItem::factory(array(
				'name' => 'delete',
				'text' => elgg_view_icon('delete'),
				'href' => "action/pool/admin/delete?guid={$entity->guid}",
				'is_action' => true,
				'confirm' => elgg_echo('question:areyousure'),
				'priority' => 200,
			));

			if ($entity->countMembers()) {
				$return[] = ElggMenuItem::factory(array(
					'name' => 'shift',
					'text' => elgg_echo('pool:shift'),
					'href' => "action/pool/shift?guid={$entity->guid}",
					'priority' => 150,
					'is_action' => true,
					'confirm' => elgg_echo('question:areyousure'),
				));
			}
		}
	}

	return $return;
}

/**
 * Pull together variables for the save form
 *
 * @param Pool $entity Pool object
 * @return array $values Array of form values
 */
function pool_prepare_form_vars($entity = null) {
	$values = array(
		'guid' => null,
		'title' => null,
		'description' => null,
		'interval' => null,
		'interval_time' => null,
		'access_id' => null,
		'owner_guid' => null,
		'container_guid' => null,
	);

	if ($entity) {
		foreach ($values as $name => $value) {
			$values[$name] = $entity->$name;
		}
	}

	if (elgg_is_sticky_form('task_pool')) {
		$sticky_values = elgg_get_sticky_values('task_pool');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('task_pool');

	return $values;
}

/**
 * Format and return the URL for a task pool.
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string URL of pool.
 */
function pool_url_handler($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if ($entity instanceof Pool) {
		$friendly_title = elgg_get_friendly_title($entity->title);
		return "pool/view/{$entity->guid}/$friendly_title";
	}
}

/**
 * Pool page handler
 *
 * URLs take the form of
 *  All pools in site:   pool/all
 *  View a single pool:  pool/view/<guid>
 *
 * @param array $page Array of url segments for routing
 * @return bool
 */
function pool_page_handler($page) {
	if (empty($page[0])) {
		$page[0] = 'all';
	}

	$base_path = elgg_get_plugins_path();

	switch ($page[0]) {
		case 'view':
			set_input('guid', $page[1]);
			include_once($base_path . 'pool/pages/view.php');
			break;
		case 'all':
		default:
			include_once($base_path . 'pool/pages/list.php');
			break;
	}

	return true;
}

/**
 * Assing new turns for users and send notifications.
 *
 * @param string $hook
 * @param string $period
 * @param array  $return
 * @param array  $params
 */
function pool_assign_new_turn_cron($hook, $period, $return, $params) {
	$ia = elgg_set_ignore_access(true);

	$batch = new ElggBatch('elgg_get_entities_from_metadata', array(
		'type' => 'object',
		'subtype' => 'task_pool',
		'metadata_name_value_pairs' => array(
			'name' => 'interval',
			'value' => $period,
		),
		'limit' => false
	));

	foreach ($batch as $pool) {
		// Move current user to the end, and the next user in turn to the beginning
		$pool->shift();

		// Get the details and notify the next user in turn
		$turn = $pool->getFirstTurn();
		$user = $turn->getOwnerEntity();
		$site = elgg_get_site_entity()->guid;

		$title = elgg_echo("notifier:notify:{$pool->interval}:subject", array($pool->title));
		$message = elgg_echo("notifier:notify:{$pool->interval}:body", array($pool->title, $pool->getURL()));

		$params = array(
			'object' => $pool,
			'action' => 'shift',
		);

		notify_user($user->guid, $site->guid, $title, $message, $params);
	}

	elgg_set_ignore_access($ia);
}

/**
 * Remove user from pools when s/he is deleted or banned.
 *
 * @param string     $event
 * @param string     $type
 * @param ElggEntity $user
 */
function pool_remove_user ($event, $type, $user) {
	if (!elgg_instanceof($user, 'user')) {
		return true;
	}

	$pools = new ElggBatch('elgg_get_entities_from_relationship', array(
		'type' => 'object',
		'subtype' => 'task_pool',
		'relationship' => 'member',
		'limit' => false,
	));

	foreach ($pools as $pool) {
		$pool->leave($user->guid);
	}
}
