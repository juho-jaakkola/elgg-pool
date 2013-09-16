<?php

$guid = get_input('guid');

$pool = get_entity($guid);

if (elgg_instanceof($pool, 'object', 'task_pool') && $pool->canEdit()) {
	$pool->shift(true);
	system_message(elgg_echo('pool:shift:success'));
} else {
	register_error(elgg_echo('noaccess'));
	forward(REFERER);
}