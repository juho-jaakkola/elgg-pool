<?php

$entity = elgg_extract('entity', $vars);

$turns = $entity->getTurnsNowAndAfter();

foreach ($turns as $turn) {
	$date = date('j.n.Y', $turn->value);
	$name = $turn->getOwnerEntity()->name;

	$user = $turn->getOwnerEntity();
	$image = elgg_view_entity_icon($user, 'small');

	$metadata = null;

	if (elgg_is_admin_logged_in()) {
		$metadata = elgg_view('output/url', array(
			'text' => elgg_echo('pool:remove'),
			'href' => "action/pool/remove?guid={$entity->guid}&user_guid={$user->guid}",
			'is_action' => true,
			'confirm' => elgg_echo('areyousure'),
			'class' => 'elgg-menu-entity',
		));
	}

	$body = elgg_view('object/elements/summary', array(
		'entity' => $user,
		'subtitle' => $user->name,
		'metadata' => $metadata,
		'title' => $date,
	));

	echo elgg_view_image_block($image, $body);
}