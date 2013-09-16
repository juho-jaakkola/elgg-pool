<?php

$user_guid = elgg_get_logged_in_user_guid();

elgg_push_context('widgets');

$pools = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'task_pool',
	'relationship' => 'member',
	'relationship_guid' => $user_guid,
));

foreach ($pools as $pool) {
	$link = elgg_view('output/url', array(
		'href' => "pool/view/$pool->guid",
		'text' => elgg_echo('all'), 
	));

	$pool_view = elgg_view_entity($pool);
	$body = $pool_view . $link;

	echo elgg_view_module('aside', $pool->title, $body);
}

elgg_pop_context();