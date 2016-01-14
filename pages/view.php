<?php

$guid = get_input('guid');

$pool = get_entity($guid);

if (!elgg_instanceof($pool, 'object', 'task_pool')) {
	register_error(elgg_echo('noaccess'));
	forward(REFERER);
}

$count = $pool->countMembers();

$member_count = elgg_echo('pool:members:count', array($count));
$title = "{$pool->title} $member_count";

$user_guid = elgg_get_logged_in_user_guid();

if ($pool->isMember($user_guid)) {
	$text = elgg_echo('pool:leave');
} else {
	$text = elgg_echo('pool:join');
}

elgg_register_menu_item('title', array(
	'name' => 'toggle_membership',
	'href' => "action/pool/toggle_membership?pool_guid={$pool->guid}&user_guid=$user_guid",
	'text' => $text,
	'link_class' => 'elgg-button elgg-button-action',
	'is_action' => true,
	'id' => $guid,
));

$metadata = elgg_view_menu('entity', array(
	'entity' => $pool,
	'handler' => 'task_pool',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$description = elgg_view('output/longtext', array(
	'value' => $pool->description,
	'class' => 'mbl'
));

$list = elgg_view('pool/list', array('entity' => $pool));

$params = array(
	'title' => $title,
	'content' => $metadata . $description . $list,
	'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);