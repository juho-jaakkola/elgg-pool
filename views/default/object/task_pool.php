<?php

$entity = elgg_extract('entity', $vars);

$user = $entity->getNextUserInTurn();

if (elgg_in_context('widgets')) {
	$image = elgg_view_entity_icon($user, 'small');

	$user_link = elgg_view('output/url', array(
		'href' => $user->getURL(),
		'text' => $user->name,
	));

	// TODO Make more generic (not just week days)
	$week_day = date('l', time());
	$content = elgg_echo("pool:current:$entity->interval", array($user_link));

	$params = array(
		//'entity' => $entity,
		//'metadata' => $metadata,
		//'subtitle' => $entity->description,
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