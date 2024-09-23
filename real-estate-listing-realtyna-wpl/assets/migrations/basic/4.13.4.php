<?php
/**
* Google ignores locational meta tags (like geo.position or distribution) or geotargeting HTML attributes.
* @see https://developers.google.com/search/docs/specialty/international/managing-multi-regional-sites
*/
$this->runQuery(<<<'SQL'
DELETE FROM `#__wpl_settings` WHERE setting_name IN (
	'geotag_status',
	'geotag_latitude',
	'geotag_longitude',
	'geotag_region',
	'geotag_placename',
	'geotag_separator'
);
SQL
);

$this->runQuery(<<<'SQL'
DELETE FROM `#__wpl_settings` WHERE `setting_name` IN (
   'dc_separator',
   'dc_status',
   'dc_contributor',
   'dc_publisher',
   'dc_copyright',
   'dc_source',
   'dc_relation',
   'dc_coverage'
);
SQL
);