<?php

$guid = get_input('guid');
$user_guid = get_input('user_guid');

$pool = get_entity($guid);

if (elgg_instanceof($pool, 'object', 'task_pool') && $pool->canEdit()) {
	if ($pool->leave($user_guid)) {
		system_message(elgg_echo('pool:remove:success'));
	} else {
		register_error(elgg_echo('pool:error:cannot_remove'));
	}
}

forward(REFERER);