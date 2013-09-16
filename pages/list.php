<?php

$title = elgg_echo('pool:all');

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'task_pool',
));

$params = array(
	'title' => $title,
	'content' => $content,
	'filter' => '',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);