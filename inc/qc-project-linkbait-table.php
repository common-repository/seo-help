<?php 

defined('ABSPATH') or die("You can't access this file directly.");

if ( ! function_exists( 'qc_seo_help_project_create_tables' ) ) {
	function qc_seo_help_project_create_tables() {
			
		// Globals
		global $wpdb;
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		// Compose charset
		$charset = (empty($wpdb->charset)? '' : ' DEFAULT CHARACTER SET '.$wpdb->charset).(empty($wpdb->collate)? '' : ' COLLATE '.$wpdb->collate);
		
		// Scans table
		$wpdb->query('CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix.'qcld_seo_help_scans` (
			`scan_id` 				BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`type`					VARCHAR(20)   NULL DEFAULT "scan",
			`name`					VARCHAR(255)  NULL DEFAULT "",
			`status`				VARCHAR(20)   NULL DEFAULT "",
			`ready` 				TINYINT(1)  NULL DEFAULT 0,
			`hash` 					VARCHAR(32)  NULL DEFAULT "",
			`created_at`			DATETIME  NULL DEFAULT "0000-00-00 00:00:00",
			`modified_at`			DATETIME  NULL DEFAULT "0000-00-00 00:00:00",
			`modified_by`			BIGINT(20) UNSIGNED  NULL DEFAULT 0,
			`started_at`			DATETIME  NULL DEFAULT "0000-00-00 00:00:00",
			`enqueued_at`			DATETIME  NULL DEFAULT "0000-00-00 00:00:00",
			`stopped_at`			DATETIME  NULL DEFAULT "0000-00-00 00:00:00",
			`continued_at`			DATETIME  NULL DEFAULT "0000-00-00 00:00:00",
			`finished_at`			DATETIME  NULL DEFAULT "0000-00-00 00:00:00",
			`config`				TEXT  NULL DEFAULT "",
			`summary`				TEXT  NULL DEFAULT "",
			`trace`					TEXT  NULL DEFAULT "",
			`threads`				TEXT  NULL DEFAULT "",
			`max_threads`			INT(10) UNSIGNED  NULL DEFAULT 0,
			`connect_timeout`		INT(10) UNSIGNED  NULL DEFAULT 0,
			`request_timeout`		INT(10) UNSIGNED  NULL DEFAULT 0,
			PRIMARY KEY	 			(`scan_id`),
			KEY `type`				(`type`),
			KEY `name` 				(`name`),
			KEY `status`			(`status`),
			UNIQUE KEY `hash`		(`hash`),
			KEY `config`			(`config`(255))
		)'.$charset);
		
		
		// URLs and locations relationship table
		$wpdb->query('CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix.'qcld_seo_help_urls_locations` (
			`loc_id` 				BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`scan_id` 				BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
			`link_type`				VARCHAR(25)  NULL DEFAULT "",
			`object_id`				BIGINT(20) UNSIGNED  NULL DEFAULT 0,
			`object_type`			VARCHAR(50)  NULL DEFAULT "",
			`object_post_type`		VARCHAR(20)  NULL DEFAULT "",
			`object_field`			VARCHAR(100)  NULL DEFAULT "",
			`object_edit`			VARCHAR(250)  NULL DEFAULT "",
			`object_view`			VARCHAR(250)  NULL DEFAULT "",
			`object_trash`			VARCHAR(250)  NULL DEFAULT "",
			`object_date_gmt`		DATETIME  NULL DEFAULT "0000-00-00 00:00:00",
			`detected_at` 			DATETIME  NULL DEFAULT "0000-00-00 00:00:00",
			`chunk`					TEXT  NULL DEFAULT "",
			`anchor`				TEXT  NULL DEFAULT "",
			`raw_url` 				TEXT  NULL DEFAULT "",
			`status_code`			VARCHAR(3)  NULL DEFAULT "",
			`content`				TEXT  NULL DEFAULT "",
			`spaced`				TINYINT(1)  NULL DEFAULT 0,
			`malformed` 			TINYINT(1)  NULL DEFAULT 0,
			`absolute` 				TINYINT(1)  NULL DEFAULT 0,
			`protorel`				TINYINT(1)  NULL DEFAULT 0,
			`relative` 				TINYINT(1)  NULL DEFAULT 0,
			`nofollow`				TINYINT(1)  NULL DEFAULT 0,
			`ignored` 				TINYINT(1)  NULL DEFAULT 0,
			`unlinked` 				TINYINT(1)  NULL DEFAULT 0,
			`modified` 				TINYINT(1)  NULL DEFAULT 0,
			`anchored`				TINYINT(1)  NULL DEFAULT 0,
			`attributed`			TINYINT(1)  NULL DEFAULT 0,
			`created_at`			DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
			`started_at`			DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
			`request_at`			DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
			PRIMARY KEY	 			(`loc_id`),
			KEY `scan_id` 			(`scan_id`),
			KEY `link_type` 		(`link_type`),
			KEY `object_id` 		(`object_id`),
			KEY `object_type` 		(`object_type`),
			KEY `object_date_gmt` 	(`object_date_gmt`),
			KEY `anchor`			(`anchor`(255)),
			KEY `status_code` 		(`status_code`),
			KEY `spaced`			(`spaced`),
			KEY `malformed` 		(`malformed`),
			KEY `absolute` 			(`absolute`),
			KEY `protorel`			(`protorel`),
			KEY `relative`			(`relative`),
			KEY `nofollow`			(`nofollow`),
			KEY `ignored`			(`ignored`),
			KEY `unlinked`			(`unlinked`),
			KEY `modified`			(`modified`),
			KEY `anchored`			(`anchored`),
			KEY `attributed`		(`attributed`)
		)'.$charset);
		

	}
}
