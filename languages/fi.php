<?php

return array(
	'pool' => 'Vuorot',
	'pool:all' => 'Vuorolistat',
	'pool:join' => 'Liity',
	'pool:leave' => 'Poistu',
	'pool:members:count' => '(%s jäsentä)',
	'pool:shift' => 'Siirrä',
	'pool:remove' => 'Poista listalta',
	'pool:list:title' => '%s klo %s',
	'item:object:task_pool' => 'Vuorolistat',

	'pool:current:daily' => 'Tänään vuorossa:',
	'pool:current:weekly' => 'Tällä viikolla vuorossa:',
	'pool:current:monthly' => 'Tässä kuussa vuorossa:',

	'pool:next:daily' => 'Huomenna vuorossa:',
	'pool:next:weekly' => 'Ensi viikolla vuorossa:',
	'pool:next:monthly' => 'Ensi kuussa vuorossa:',

	'pool:daily' => 'päivittäin',
	'pool:weekly' => 'viikoittain',
	'pool:monthly' => 'kuukausittain',

	// Notifications
	'notifier:notify:daily:subject' => 'Vuoro huomenna: %s',
	'notifier:notify:weekly:subject' => 'Vuoro ensi viikolla: %s',
	'notifier:notify:monthly:subject' => 'Vuoro ensi kuussa: %s',
	'notifier:notify:daily:body' => 'Huomenna on sinun vuorosi listassa "%s".

%s
',
	'notifier:notify:weekly:body' => 'Ensi viikolla on sinun vuorosi listassa "%s"

%s
',
	'notifier:notify:monthly:body' => 'Ensi kuussa on sinun vuorosi listassa "%s"

%s
',

	// Messages
	'pool:join:success' => 'Liityit listaan',
	'pool:join:error' => 'Liittyminen epäonnistui',
	'pool:leave:success' => 'Poistuit listalta',
	'pool:leave:error' => 'Listalta poistuminen epäonnistui',

	// Admin panel
	'admin:pool' => 'Ylläpito',
	'admin:pool:list' => 'Vuorolistat',
	'admin:pool:save' => 'Luo uusi vuorolista',
	'pool:interval' => 'Toistuvuus',
	'pool:interval:time' => 'Ajankohta',
	'pool:time' => 'Kellonaika',
	'pool:time:help' => 'Syötä muodossa HH:MM',

	// Admin messages
	'pool:save:success' => 'Tehtävä tallennettu',
	'pool:save:error' => 'Tehtävän tallentaminen epäonnistui',
	'pool:delete:success' => 'Tehtävä poistettu',
	'pool:error:cannot_delete_pool' => 'Tehtävän poistaminen epäonnistui',
	'pool:error:pool_not_found' => 'Tehtävää ei löytynyt',
	'pool:shift:success' => 'Vuorolista päivitetty',
	'pool:remove:success' => 'Käyttäjä poistettu listalta',
	'pool:error:cannot_remove' => 'Käyttäjän poistaminen listalta epäonnistui',
);
