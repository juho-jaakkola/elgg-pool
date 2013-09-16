<?php

echo elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'task_pool',
));

echo elgg_view('output/url', array(
	'href' => 'admin/pool/save',
	'text' => elgg_echo('add'),
	'class' => 'elgg-button elgg-button-action mtl',
));
