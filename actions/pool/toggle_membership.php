<?php

$pool_guid = get_input('pool_guid');
$user_guid = get_input('user_guid');

$pool = get_entity($pool_guid);

if (elgg_instanceof($pool, 'object', 'task_pool')) {
	if ($pool->isMember($user_guid)) {
		if ($pool->leave($user_guid)) {
			system_message(elgg_echo('pool:leave:success'));
		} else {
			register_error(elgg_echo('pool:leave:error'));
		}
	} else {
		if ($pool->join($user_guid)) {
			system_message(elgg_echo('pool:join:success'));
		} else {
			register_error(elgg_echo('pool:join:error'));
		}
	}
} else {
	register_error(elgg_echo('actionunauthorized'));
}

forward(REFERER);
