<?php

$entity = elgg_extract('entity', $vars);

if (elgg_is_admin_logged_in()) {
	elgg_require_js('pool/reorder');
}

$turns = $entity->getTurnsNowAndAfter();

$items = '';
foreach ($turns as $turn) {
	$date = date('j.n.Y', $turn->value);
	$name = $turn->getOwnerEntity()->name;

	$user = $turn->getOwnerEntity();
	$image = elgg_view_entity_icon($user, 'small');

	$metadata = null;

	if (elgg_is_admin_logged_in()) {
		$metadata = elgg_view('output/confirmlink', array(
			'text' => elgg_echo('pool:remove'),
			'href' => "action/pool/remove?guid={$entity->guid}&user_guid={$user->guid}",
			'is_action' => true,
			'class' => 'elgg-menu-entity',
		));
	}

	$body = elgg_view('object/elements/summary', array(
		'entity' => $user,
		'subtitle' => $user->name,
		'metadata' => $metadata,
		'title' => $date,
	));

	$block = elgg_view_image_block($image, $body, array(
		'id' => $user->guid
	));

	$items .= "<li>$block</li>";
}

echo "<ul id=\"elgg-pool-users\">$items</ul>";
