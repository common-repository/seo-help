<?php
/*
 * Parts of the code are inspired and/or copied from FEEDZY RSS Aggregator which is an open-source project.
 */

if ( ! class_exists( 'SimplePie' ) ) {
	require_once ABSPATH . WPINC . '/class-simplepie.php';
	require_once ABSPATH . WPINC . '/class-wp-feed-cache-transient.php';
	require_once ABSPATH . WPINC . '/class-wp-simplepie-file.php';
}

/**
 * The class that contains a custom implementation of SimplePie.
 *
 * Class that contains a custom implementation of SimplePie.
 *
 * @package    Qcld_seo_help-rss-feeds
 * @subpackage Qcld_seo_help-rss-feeds/includes/util
 * @author     Themeisle <friends@themeisle.com>
 */
class Qcld_seo_help_Rss_Feeds_Util_SimplePie extends SimplePie {

	/**
	 * The shortcode attributes.
	 *
	 * @access   private
	 * @var      array $sc The shortcode attributes.
	 */
	private static $sc;

	/**
	 * Whether custom sorting is enabled.
	 *
	 * @access   private
	 * @var      bool $custom_sorting Whether custom sorting is enabled.
	 */
	private static $custom_sorting = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @access  public
	 *
	 * @param   array $sc The shortcode attributes.
	 */
	public function __construct( $sc ) {
		self::$sc = $sc;
		if ( array_key_exists( 'sort', self::$sc ) && ! empty( self::$sc['sort'] ) ) {
			if ( 'date_desc' === self::$sc['sort'] ) {
				$this->enable_order_by_date( true );
			} else {
				self::$custom_sorting = true;
			}
		}
		parent::__construct();
	}

	/**
	 * Sorting callback for items
	 *
	 * @access public
	 * @param SimplePie $a The SimplePieItem.
	 * @param SimplePie $b The SimplePieItem.
	 * @return boolean
	 */
	public static function sort_items( $a, $b ) {
		if ( self::$custom_sorting ) {
			switch ( self::$sc['sort'] ) {
				case 'title_desc':
					return $a->get_title() <= $b->get_title();
				case 'title_asc':
					return $a->get_title() > $b->get_title();
				case 'date_asc':
					return $a->get_date( 'U' ) > $b->get_date( 'U' );
			}
		}
		return parent::sort_items( $a, $b );
	}

	/**
	 * Return the filename (i.e. hash, without path and without extension) of the file to cache a given URL.
	 *
	 * @param string $url The URL of the feed to be cached.
	 * @return string A filename (i.e. hash, without path and without extension).
	 */
	public function get_cache_filename( $url ) {
		// Append custom parameters to the URL to avoid cache pollution in case of multiple calls with different parameters.
		$url .= $this->force_feed ? '#force_feed' : '';
		return call_user_func( $this->cache_name_function, $url );
	}
}