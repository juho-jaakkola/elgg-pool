<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'task_pool')) {
	if (!$entity->canEdit()) {
		register_error(elgg_echo('actionunauthorized'));
		forward(REFERER);
	}
} else {
	$entity = new ElggObject();
	$entity->subtype = 'task_pool';
}

$title = get_input('title');
$description = get_input('description');
$interval = get_input('interval');
$interval_time = get_input('interval_time');

$entity->access_id = ACCESS_PUBLIC;
$entity->title = $title;
$entity->description = $description;
$entity->interval = $interval;
$entity->interval_time = $interval_time;

if ($entity->save()) {
	system_message(elgg_echo('pool:save:success'));
} else {
	register_error(elgg_echo('pool:save:error'));
	forward(REFERER);
}

forward('admin/pool/list');