<?php

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'value' => $vars['title'],
));

$description_label = elgg_echo('description');
$description_input = elgg_view('input/longtext', array(
	'name' => 'description',
	'value' => $vars['description'],
));

$interval_label = elgg_echo('pool:interval');
$interval_input = elgg_view('input/dropdown', array(
	'name' => 'interval',
	'options_values' => array(
		//'daily' => elgg_echo('pool:daily'),
		'weekly' => elgg_echo('pool:weekly'),
		//'monthly' => elgg_echo('pool:monthly'),
	),
	'value' => $vars['interval'],
));

$time_label = elgg_echo('pool:interval:time');
$time_input = elgg_view('input/dropdown', array(
	'name' => 'interval_time',
	'options_values' => array(
		'monday' => elgg_echo('monday'),
		'tuesday' => elgg_echo('tuesday'),
		'wednesday' => elgg_echo('wednesday'),
		'thursday' => elgg_echo('thursday'),
		'friday' => elgg_echo('friday'),
		'saturday' => elgg_echo('saturday'),
		'sunday' => elgg_echo('sunday'),
	),
	'value' => $vars['interval_time'],
));

$guid_input = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $vars['guid'],
));

$submit_input = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
));

echo <<<HTML
	<div>
		<label>$title_label</label>
		$title_input
	</div>
	<div>
		<label>$description_label</label>
		$description_input
	</div>
	<div>
		<label>$interval_label</label>
		$interval_input
	</div>
	<div>
		<label>$time_label</label>
		$time_input
	</div>
	<div class="">
		$guid_input
		$submit_input
	</div>
HTML;
