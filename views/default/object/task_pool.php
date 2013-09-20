<?php

$entity = elgg_extract('entity', $vars);

$turn = $entity->getFirstTurn();
$user = $turn->getOwnerEntity();

if (elgg_in_context('widgets')) {
	$image = elgg_view_entity_icon($user, 'small');

	$user_link = elgg_view('output/url', array(
		'href' => $user->getURL(),
		'text' => $user->name,
	));

	// TODO The intervals needs specific time of day to compare to (e.g. 14:00)
	switch ($entity->interval) {
		case 'daily':
			$next_interval = strtotime("tomorrow");
			break;
		case 'weekly':
			// TODO Make first weekday configurable
			$next_interval = strtotime("next monday");
			break;
		case 'monthly':
			$next_interval = strtotime("next month");
	}

	if ($next_interval > $turn->value) {
		// Display the turn of this day/week/month
		$content = elgg_echo("pool:current:$entity->interval", array($user_link));
	} else {
		// Display the turn of next day/week/month
		$content = elgg_echo("pool:next:$entity->interval", array($user_link));
	}

	$params = array(
		'content' => $content,
		'title' => false,
	);
} else {
	$body = $entity->title;

	$metadata = elgg_view_menu('entity', array(
		'entity' => $entity,
		'handler' => 'task_pool',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));

	$params = array(
		'entity' => $entity,
		'metadata' => $metadata,
		'subtitle' => $entity->description,
	);

	$image = null;
}

$params = $params + $vars;
$body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($image, $body);