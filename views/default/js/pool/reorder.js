/**
 * Allows reordering of pool participants by dragging
 */

define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');

	var guid = $('.elgg-button-action').attr('id');

	/**
	 * Save the pool order after a move event
	 *
	 * @param {Object} e  Event object.
	 * @param {Object} ui jQueryUI object
	 * @return void
	 */
	function reorder (e, ui) {
		var guids = [];
		$('#elgg-pool-users .elgg-image-block').each(function() {
			guids.push($(this).attr('id'));
		});

		elgg.action('pool/reorder', {
			data: {
				guid: guid,
				guids: guids,
			}
		});
	}

	$('#elgg-pool-users').sortable({
		items:                '.elgg-image-block',
		handle:               'h3',
		forcePlaceholderSize: true,
		placeholder:          'elgg-widget-placeholder',
		opacity:              0.8,
		revert:               500,
		stop:                 reorder
	});
});
