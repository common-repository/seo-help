<?php 
defined('ABSPATH') or die("You can't access this file directly.");
/*
 * Parts of the code are inspired and/or copied from FEEDZY RSS Aggregator which is an open-source project.
 */

add_filter( 'qcld_seo_help_get_feed_array', 'qcld_seo_help_get_feed_arrays', 10, 5 );
if ( ! function_exists( 'qcld_seo_help_get_feed_arrays' ) ) {
	function qcld_seo_help_get_feed_arrays( $feed_items, $sc, $feed, $feed_url, $sizes ) {
		$count = 0;
		$items = apply_filters( 'qcld_seo_help_feed_items', $feed->get_items( $sc['offset'] ), $feed_url );
		$index = 0;
		foreach ( (array) $items as $item ) {
			$continue = apply_filters( 'qcld_seo_help_item_keyword', true, $sc, $item, $feed_url, $index );
			if ( true === $continue ) {
				// Count items. This should be > and not >= because max, when not defined and empty, becomes 0.
				if ( $count >= $sc['max'] ) {
					break;
				}
				$item_attr            = apply_filters( 'qcld_seo_help_item_attributes', $item_attr = '', $sizes, $item, $feed_url, $sc, $index );
				$feed_items[ $count ] = qcld_seo_help_get_feed_item_filter( $sc, $sizes, $item, $feed_url, $count, $index );
				if ( isset( $sc['disable_default_style'] ) && 'yes' === $sc['disable_default_style'] ) {
					$item_attr = preg_replace( '/ style=\\"[^\\"]*\\"/', '', $item_attr );
				}
				$feed_items[ $count ]['itemAttr'] = $item_attr;
				$count++;
			}
			$index++;
		}
		return $feed_items;
	}
}

if ( ! function_exists( 'qcld_seo_help_get_feed_item_filter' ) ) {
	function qcld_seo_help_get_feed_item_filter( $sc, $sizes, $item, $feed_url, $index, $item_index ) {
		$item_link = $item->get_permalink();
		// if the item has no link (possible in some cases), use the feed link.
		if ( empty( $item_link ) ) {
			$item_link = $item->get_id();
			if ( empty( $item_link ) ) {
				$item_link = $item->get_feed()->get_permalink();
			}
		}
		$new_link = apply_filters( 'qcld_seo_help_item_url_filter', $item_link, $sc, $item );

		// Fetch image thumbnail.
		if ( 'yes' === $sc['thumb'] || 'auto' === $sc['thumb'] ) {
			$the_thumbnail = qcld_seo_help_retrieve_image( $item, $sc );
		}
		if ( 'yes' === $sc['thumb'] || 'auto' === $sc['thumb'] ) {
			$content_thumb = '';
			if ( ( ! empty( $the_thumbnail ) && 'auto' === $sc['thumb'] ) || 'yes' === $sc['thumb'] ) {
				if ( ! empty( $the_thumbnail ) ) {
					$the_thumbnail  = qcld_seo_help_image_encode( $the_thumbnail );
					$content_thumb .= '<span class="fetched" style="background-image:  url(\'' . $the_thumbnail . '\');" title="' . esc_html( $item->get_title() ) . '"></span>';
					if ( ! isset( $sc['amp'] ) || 'no' !== $sc['amp'] ) {
						$content_thumb .= '<amp-img width="' . $sizes['width'] . '" height="' . $sizes['height'] . '" src="' . $the_thumbnail . '">';
					}
				}
				if ( empty( $the_thumbnail ) && 'yes' === $sc['thumb'] ) {
					$content_thumb .= '<span class="default" style="background-image:url(' . $sc['default'] . ');" title="' . esc_html( $item->get_title() ) . '"></span>';
					if ( ! isset( $sc['amp'] ) || 'no' !== $sc['amp'] ) {
						$content_thumb .= '<amp-img width="' . $sizes['width'] . '" height="' . $sizes['height'] . '" src="' . $sc['default'] . '">';
					}
				}
			}
			$content_thumb = apply_filters( 'qcld_seo_help_thumb_output', $content_thumb, $feed_url, $sizes, $item );
		} else {
			$content_thumb  = '';
			$content_thumb .= '<span class="default" style="width:' . $sizes['width'] . 'px; height:' . $sizes['height'] . 'px; background-image:url(' . $sc['default'] . ');" title="' . $item->get_title() . '"></span>';
			if ( ! isset( $sc['amp'] ) || 'no' !== $sc['amp'] ) {
				$content_thumb .= '<amp-img width="' . $sizes['width'] . '" height="' . $sizes['height'] . '" src="' . $sc['default'] . '">';
			}
			$content_thumb = apply_filters( 'qcld_seo_help_thumb_output', $content_thumb, $feed_url, $sizes, $item );
		}
		$content_title = html_entity_decode( $item->get_title(), ENT_QUOTES, 'UTF-8' );
		if ( is_numeric( $sc['title'] ) ) {
			$length = intval( $sc['title'] );
			if ( 0 === $length ) {
				$content_title = '';
			}
			if ( $length > 0 && strlen( $content_title ) > $length ) {
				$content_title = preg_replace( '/\s+?(\S+)?$/', '', substr( $content_title, 0, $length ) ) . '...';
			}
		}
		if ( ! is_numeric( $sc['title'] ) && empty( $content_title ) ) {
			$content_title = esc_html__( 'Post Title', 'qcld-seo-help' );
		}
		$content_title = apply_filters( 'qcld_seo_help_title_output', $content_title, $feed_url, $item );

		// meta=yes is for backward compatibility, otherwise its always better to provide the fields with granularity.
		// if meta=yes, then meta will be placed in default order. Otherwise in the order stated by the user.
		$meta_args = array(
			'author'      => 'yes' === $sc['meta'] || strpos( $sc['meta'], 'author' ) !== false,
			'date'        => 'yes' === $sc['meta'] || strpos( $sc['meta'], 'date' ) !== false,
			'time'        => 'yes' === $sc['meta'] || strpos( $sc['meta'], 'time' ) !== false,
			'source'      => 'yes' === $sc['multiple_meta'] || strpos( $sc['multiple_meta'], 'source' ) !== false,
			'categories'  => strpos( $sc['meta'], 'categories' ) !== false,
			'tz'          => 'gmt',
			'date_format' => get_option( 'date_format' ),
			'time_format' => get_option( 'time_format' ),
		);

		// parse the x=y type setting e.g. tz=local or tz=gmt.
		if ( strpos( $sc['meta'], '=' ) !== false ) {
			$components = array_map( 'trim', explode( ',', $sc['meta'] ?? '' ) );
			foreach ( $components as $configs ) {
				if ( strpos( $configs, '=' ) === false ) {
					continue;
				}
				$config                  = explode( '=', $configs ?? '' );
				$meta_args[ $config[0] ] = $config[1];
			}
		}

		// Filter: qcld_seo_help_meta_args.
		$meta_args = apply_filters( 'qcld_seo_help_meta_args', $meta_args, $feed_url, $item );

		// order of the meta tags.
		$meta_order = array( 'author', 'date', 'time', 'categories' );
		if ( 'yes' !== $sc['meta'] ) {
			$meta_order = array_map( 'trim', explode( ',', $sc['meta'] ?? '' ) );
		}

		$content_meta_values = array();

		// multiple sources?
		$is_multiple = is_array( $feed_url );
		$feed_source = $item->get_feed()->get_title();

		// author.
		if ( $item->get_author() && $meta_args['author'] ) {
			$author      = $item->get_author();
			$author_name = $author->get_name();
			if ( ! $author_name ) {
				$author_name = $author->get_email();
			}

			$author_name = apply_filters( 'qcld_seo_help_author_name', $author_name, $feed_url, $item );

			if ( $is_multiple && $meta_args['source'] && ! empty( $feed_source ) ) {
				$author_name .= sprintf( ' (%s)', $feed_source );
			}

			if ( $author_name ) {
				$domain                        = wp_parse_url( $new_link );
				$author_url                    = isset( $domain['host'] ) ? '//' . $domain['host'] : '';
				$author_url                    = apply_filters( 'qcld_seo_help_author_url', $author_url, $author_name, $feed_url, $item );
				$content_meta_values['author'] = apply_filters( 'qcld_seo_help_meta_author', __( 'by', 'qcld-seo-help' ) . ' <a href="' . $author_url . '" target="' . $sc['target'] . '" title="' . $domain['host'] . '" >' . $author_name . '</a> ', $author_name, $author_url, $feed_source, $feed_url, $item );
			}
		} elseif ( $is_multiple && $meta_args['source'] && ! empty( $feed_source ) ) {
			$domain                        = wp_parse_url( $new_link );
			$author_url                    = isset( $domain['host'] ) ? '//' . $domain['host'] : '';
			$author_url                    = apply_filters( 'qcld_seo_help_author_url', $author_url, $feed_source, $feed_url, $item );
			$content_meta_values['author'] = apply_filters( 'qcld_seo_help_meta_author', __( 'by', 'qcld-seo-help' ) . ' <a href="' . $author_url . '" target="' . $sc['target'] . '" title="' . $domain['host'] . '" >' . $feed_source . '</a> ', $feed_source, $author_url, $feed_source, $feed_url, $item );
		}

		// date/time.
		$date_time = $item->get_date( 'U' );
		if ( 'local' === $meta_args['tz'] ) {
			$date_time = get_date_from_gmt( $item->get_date( 'Y-m-d H:i:s' ), 'U' );
			// strings such as Asia/Kolkata need special handling.
			$tz = get_option( 'timezone_string' );
			if ( $tz ) {
				$date_time = gmdate( 'U', $date_time + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
			}
		} elseif ( 'no' === $meta_args['tz'] ) {
			// change the tz component of the date to UTC.
			$raw_date  = preg_replace( '/\++(\d\d\d\d)/', '+0000', $item->get_date( '' ) );
			$date      = DateTime::createFromFormat( DATE_RFC2822, $raw_date );
			$date_time = $date->format( 'U' );
		}

		$date_time = apply_filters( 'qcld_seo_help_feed_timestamp', $date_time, $feed_url, $item );
		if ( $meta_args['date'] && ! empty( $meta_args['date_format'] ) ) {
			$content_meta_values['date'] = apply_filters( 'qcld_seo_help_meta_date', __( 'on', 'qcld-seo-help' ) . ' ' . date_i18n( $meta_args['date_format'], $date_time ) . ' ', $date_time, $feed_url, $item );
		}

		if ( $meta_args['time'] && ! empty( $meta_args['time_format'] ) ) {
			$content_meta_values['time'] = apply_filters( 'qcld_seo_help_meta_time', __( 'at', 'qcld-seo-help' ) . ' ' . date_i18n( $meta_args['time_format'], $date_time ) . ' ', $date_time, $feed_url, $item );
		}

		// categories.
		if ( $meta_args['categories'] && has_filter( 'qcld_seo_help_retrieve_categories' ) ) {
			$categories = apply_filters( 'qcld_seo_help_retrieve_categories', null, $item );
			if ( ! empty( $categories ) ) {
				$content_meta_values['categories'] = apply_filters( 'qcld_seo_help_meta_categories', __( 'in', 'qcld-seo-help' ) . ' ' . $categories . ' ', $categories, $feed_url, $item );
			}
		}

		$content_meta      = '';
		$content_meta_date = '';
		foreach ( $meta_order as $meta ) {
			if ( isset( $content_meta_values[ $meta ] ) ) {
				// collect date/time values separately too.
				if ( in_array( $meta, array( 'date', 'time' ), true ) ) {
					$content_meta_date .= $content_meta_values[ $meta ];
				}
				$content_meta .= $content_meta_values[ $meta ];
			}
		}

		$content_meta    = apply_filters( 'qcld_seo_help_meta_output', $content_meta, $feed_url, $item, $content_meta_values, $meta_order );
		$content_summary = '';
		if ( 'yes' === $sc['summary'] ) {
			$description     = $item->get_description();
			$description     = apply_filters( 'qcld_seo_help_summary_input', $description, $item->get_content(), $feed_url, $item );
			$content_summary = $description;
			if ( is_numeric( $sc['summarylength'] ) && strlen( $description ) > $sc['summarylength'] ) {
				$content_summary = preg_replace( '/\s+?(\S+)?$/', '', substr( $description, 0, $sc['summarylength'] ) ) . ' [&hellip;]';
			}
			$content_summary = apply_filters( 'qcld_seo_help_summary_output', $content_summary, $new_link, $feed_url, $item );
		}
		$item_content = $item->get_content( false );
		if ( empty( $item_content ) ) {
			$item_content = esc_html__( 'Post Content', 'qcld-seo-help' );
		}
		$item_array = array(
			'feed_url'            => $item->get_feed()->subscribe_url(),
			'item_unique_hash'    => wp_hash( $item->get_permalink() ),
			'item_img_class'      => 'rss_image',
			'item_img_style'      => 'width:' . $sizes['width'] . 'px; height:' . $sizes['height'] . 'px;',
			'item_url'            => $new_link,
			'item_url_target'     => $sc['target'],
			'item_url_follow'     => isset( $sc['follow'] ) && 'yes' === $sc['follow'] ? 'nofollow' : '',
			'item_url_title'      => $item->get_title(),
			'item_img'            => $content_thumb,
			'item_img_path'       => qcld_seo_help_retrieve_image( $item, $sc ),
			'item_title'          => $content_title,
			'item_content_class'  => 'rss_content',
			'item_content_style'  => '',
			'item_meta'           => $content_meta,
			'item_date'           => $item->get_date( 'U' ),
			'item_date_formatted' => $content_meta_date,
			'item_author'         => $item->get_author(),
			'item_description'    => $content_summary,
			'item_content'        => apply_filters( 'qcld_seo_help_content', $item_content, $item ),
			'item_source'         => $feed_source,
			'item_full_description' => $item->get_description(),
		);
		$item_array = apply_filters( 'qcld_seo_help_item_filter', $item_array, $item, $sc, $index, $item_index );

		return $item_array;
	}
}

if ( ! function_exists( 'qcld_seo_help_retrieve_image' ) ) {
	function qcld_seo_help_retrieve_image( $item, $sc = null ) {
		$image_mime_types = array();
		foreach ( wp_get_mime_types() as $extn => $mime ) {
			if ( strpos( $mime, 'image/' ) !== false ) {
				$image_mime_types[] = $mime;
			}
		}

		$image_mime_types = apply_filters( 'qcld_seo_help_image_mime_types', $image_mime_types );

		$the_thumbnail = '';
		/*
		$enclosures    = $item->get_enclosures();
		if ( $enclosures ) {
			foreach ( (array) $enclosures as $enclosure ) {
				// Item thumbnail.
				$thumbnail = $enclosure->get_thumbnail();
				$medium    = $enclosure->get_medium();
				if ( in_array( $medium, array( 'video' ), true ) ) {
					break;
				}
				if ( $thumbnail ) {
					$the_thumbnail = $thumbnail;
				}
				if ( isset( $enclosure->thumbnails ) ) {
					foreach ( (array) $enclosure->thumbnails as $thumbnail ) {
						$the_thumbnail = $thumbnail;
					}
				}
				$thumbnail = $enclosure->embed();
				if ( $thumbnail ) {
					$pattern = '/https?:\/\/.*\.(?:jpg|JPG|jpeg|JPEG|jpe|JPE|gif|GIF|png|PNG)/i';
					if ( preg_match( $pattern, $thumbnail, $matches ) ) {
						$the_thumbnail = $matches[0];
					}
				}
				foreach ( (array) $enclosure->get_link() as $thumbnail ) {
					$pattern = '/https?:\/\/.*\.(?:jpg|JPG|jpeg|JPEG|jpe|JPE|gif|GIF|png|PNG)/i';
					$imgsrc  = $thumbnail;
					if ( preg_match( $pattern, $imgsrc, $matches ) ) {
						$the_thumbnail = $thumbnail;
						break;
					} elseif ( in_array( $enclosure->type, $image_mime_types, true ) ) {
						$the_thumbnail = $thumbnail;
						break;
					}
				}
				// Break loop if thumbnail is found.
				if ( ! empty( $the_thumbnail ) ) {
					break;
				}
			}
		}
		*/
		// xmlns:itunes podcast.
		if ( empty( $the_thumbnail ) ) {
			$data = $item->get_item_tags( 'http://www.itunes.com/dtds/podcast-1.0.dtd', 'image' );
			if ( isset( $data['0']['attribs']['']['href'] ) && ! empty( $data['0']['attribs']['']['href'] ) ) {
				$the_thumbnail = $data['0']['attribs']['']['href'];
			}
		}
		// Content image.
		if ( empty( $the_thumbnail ) ) {
			$feed_description = $item->get_content();
			$the_thumbnail    = qcld_seo_help_return_image( $feed_description );
		}
		// Description image.
		if ( empty( $the_thumbnail ) ) {
			$feed_description = $item->get_description();
			$the_thumbnail    = qcld_seo_help_return_image( $feed_description );
		}

		// handle HTTP images.
		if ( $sc && isset( $sc['http'] ) && 0 === strpos( $the_thumbnail, 'http://' ) ) {
			switch ( $sc['http'] ) {
				case 'https':
					// fall-through.
				case 'force':
					$the_thumbnail = str_replace( 'http://', 'https://', $the_thumbnail );
					break;
				case 'default':
					$the_thumbnail = $sc['default'];
					break;
			}
		}

		$the_thumbnail = ( isset( $the_thumbnail ) && !empty( $the_thumbnail ) ) ? html_entity_decode( $the_thumbnail, ENT_QUOTES, 'UTF-8' ) : '';
		if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
			$feed_url      = qcld_seo_help_normalize_urls( $sc['feeds'] );
			$the_thumbnail = ! empty( $the_thumbnail ) ? $the_thumbnail : apply_filters( 'qcld_seo_help_default_image', $sc['default'], $feed_url );
		}
		$the_thumbnail = apply_filters( 'qcld_seo_help_retrieve_image', $the_thumbnail, $item );
		return $the_thumbnail;
	}
}


if ( ! function_exists( 'qcld_seo_help_return_image' ) ) {
	function qcld_seo_help_return_image( $string ) {
		$img     = html_entity_decode( $string, ENT_QUOTES, 'UTF-8' );
		$pattern = '/<img[^>]+\>/i';
		preg_match_all( $pattern, $img, $matches );

		$image = null;
		if ( isset( $matches[0] ) ) {
			foreach ( $matches[0] as $match ) {
				$link         = qcld_seo_help_scrape_image( $match );
				$blacklist    = qcld_seo_help_blacklist_images();
				$is_blacklist = false;
				foreach ( $blacklist as $string ) {
					if ( strpos( (string) $link, $string ) !== false ) {
						$is_blacklist = true;
						break;
					}
				}
				if ( ! $is_blacklist ) {
					$image = $link;
					break;
				}
			}
		}

		return $image;
	}
}

if ( ! function_exists( 'qcld_seo_help_scrape_image' ) ) {
	function qcld_seo_help_scrape_image( $string, $link = '' ) {
		$pattern = '/< *img[^>]*src *= *["\']?([^"\']*)/';
		$match   = $link;
		preg_match( $pattern, $string, $link );
		if ( ! empty( $link ) && isset( $link[1] ) ) {
			$match = $link[1];
		}

		return $match;
	}
}

if ( ! function_exists( 'qcld_seo_help_blacklist_images' ) ) {
	function qcld_seo_help_blacklist_images() {
		$blacklist = array(
			'frownie.png',
			'icon_arrow.gif',
			'icon_biggrin.gif',
			'icon_confused.gif',
			'icon_cool.gif',
			'icon_cry.gif',
			'icon_eek.gif',
			'icon_evil.gif',
			'icon_exclaim.gif',
			'icon_idea.gif',
			'icon_lol.gif',
			'icon_mad.gif',
			'icon_mrgreen.gif',
			'icon_neutral.gif',
			'icon_question.gif',
			'icon_razz.gif',
			'icon_redface.gif',
			'icon_rolleyes.gif',
			'icon_sad.gif',
			'icon_smile.gif',
			'icon_surprised.gif',
			'icon_twisted.gif',
			'icon_wink.gif',
			'mrgreen.png',
			'rolleyes.png',
			'simple-smile.png',
			'//s.w.org/images/core/emoji/',
		);

		return apply_filters( 'qcld_seo_help_feed_blacklist_images', $blacklist );
	}
}

if ( ! function_exists( 'qcld_seo_help_image_encode' ) ) {
	function qcld_seo_help_image_encode( $string ) {
		// Check if img url is set as an URL parameter.
		$url_tab = wp_parse_url( $string );
		if ( isset( $url_tab['query'] ) ) {
			preg_match_all( '/(http|https):\/\/[^ ]+(\.gif|\.GIF|\.jpg|\.JPG|\.jpeg|\.JPEG|\.png|\.PNG)/', $url_tab['query'], $img_url );
			if ( isset( $img_url[0][0] ) ) {
				$string = $img_url[0][0];
			}
		}

		$return = apply_filters( 'qcld_seo_help_image_encode', esc_url( $string ), $string );
		do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Changing image URL from %s to %s', $string, $return ), 'debug', __FILE__, __LINE__ );
		return $return;
	}
}




add_filter( 'qcld_seo_help_item_filter', 'qcld_seo_help_add_data_to_items', 10, 5 );

if ( ! function_exists( 'qcld_seo_help_add_data_to_items' ) ) {
	function qcld_seo_help_add_data_to_items( $itemArray, $item, $sc = null, $index = null, $item_index = null ) {
		$itemArray['item_categories'] = qcld_seo_help_retrieve_categories( null, $item );

		// If set to true, SimplePie will return a unique MD5 hash for the item.
		// If set to false, it will check <guid>, <link>, and <title> before defaulting to the hash.
		$itemArray['item_id'] = $item->get_id( false );

		$itemArray['item']       = $item;
		$itemArray['item_index'] = $item_index;

		return $itemArray;
	}
}

if ( ! function_exists( 'qcld_seo_help_retrieve_categories' ) ) {
	function qcld_seo_help_retrieve_categories( $dumb, $item ) {
		$cats       = array();
		$categories = $item->get_categories();
		if ( $categories ) {
			foreach ( $categories as $category ) {
				if ( is_string( $category ) ) {
					$cats[] = $category;
				} else {
					$cats[] = $category->get_label();
				}
			}
		}

		return apply_filters( 'qcld_seo_help_categories', implode( ', ', $cats ), $cats, $item );
	}
}

if ( ! function_exists( 'qcld_seo_help_generate_featured_image' ) ) {
	function qcld_seo_help_generate_featured_image( $file, $post_id, $desc, &$import_errors, &$import_info, $post_data = array() ) {
		if ( ! function_exists( 'post_exists' ) ) {
			require_once ABSPATH . 'wp-admin/includes/post.php';
		}
		// Find existing attachment by item title.
		$id = post_exists( $desc, '', '', 'attachment' );

		if ( ! $id ) {
			do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Trying to generate featured image for %s and postID %d', $file, $post_id ), 'debug', __FILE__, __LINE__ );

			require_once ABSPATH . 'wp-admin' . '/includes/image.php';
			require_once ABSPATH . 'wp-admin' . '/includes/file.php';
			require_once ABSPATH . 'wp-admin' . '/includes/media.php';

			$file_array = array();
			$file       = trim( $file, chr( 0xC2 ) . chr( 0xA0 ) );
			$local_file = download_url( $file );
			if ( is_wp_error( $local_file ) ) {
				do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Unable to download file = %s and postID %d', print_r( $local_file, true ), $post_id ), 'error', __FILE__, __LINE__ );

				return false;
			}

			$type = mime_content_type( $local_file );
			// the file is downloaded with a .tmp extension
			// if the URL mentions the extension of the file, the upload succeeds
			// but if the URL is like https://source.unsplash.com/random, then the upload fails
			// so let's determine the file's mime type and then rename the .tmp file with that extension
			if ( in_array( $type, array_values( get_allowed_mime_types() ), true ) ) {
				$new_local_file = str_replace( '.tmp', str_replace( 'image/', '.', $type ), $local_file );
				$renamed        = rename( $local_file, $new_local_file );
				if ( $renamed ) {
					$local_file = $new_local_file;
				} else {
					do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Unable to rename file for postID %d', $post_id ), 'error', __FILE__, __LINE__ );

					return false;
				}
			}

			$file_array['tmp_name'] = $local_file;
			$file_array['name']     = basename( $local_file );

			$id = media_handle_sideload( $file_array, $post_id, $desc, $post_data );
			if ( is_wp_error( $id ) ) {
				do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Unable to attach file for postID %d = %s', $post_id, print_r( $id, true ) ), 'error', __FILE__, __LINE__ );
				unlink( $file_array['tmp_name'] );

				return false;
			}
		} else {
			do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Found an existing attachment(ID: %d) image for %s and postID %d', $id, $file, $post_id ), 'debug', __FILE__, __LINE__ );
		}

		if ( ! empty( $post_data ) ) {
			return $id;
		}

		$success = set_post_thumbnail( $post_id, $id );
		if ( false === $success ) {
			do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Unable to attach file for postID %d for no apparent reason', $post_id ), 'error', __FILE__, __LINE__ );
		} else {
			do_action( 'themeisle_log_event', qcld_seo_help_NAME, sprintf( 'Attached file as featured image for postID %d', $post_id ), 'info', __FILE__, __LINE__ );
		}

		return $success;
	}

}



add_action('wp_ajax_nopriv_qcld_rss_save_settings',  'qcld_rss_save_settings' );
add_action('wp_ajax_qcld_rss_save_settings', 'qcld_rss_save_settings' );
    
if ( ! function_exists( 'qcld_rss_save_settings' ) ) {
    function qcld_rss_save_settings(){

        check_ajax_referer( 'seo-help-pro', 'security');

        $qcld_rss_settings_enable           = isset($_POST["qcld_rss_settings_enable"]) ? sanitize_text_field(($_POST["qcld_rss_settings_enable"])) : '';
        $qcld_seo_help_cron_execution       = isset($_POST["qcld_seo_help_cron_execution"]) ? sanitize_text_field(($_POST["qcld_seo_help_cron_execution"])) : '';
        $qcld_seo_help_cron_schedule        = isset($_POST["qcld_seo_help_cron_schedule"]) ? sanitize_text_field(($_POST["qcld_seo_help_cron_schedule"])) : '';

        update_option('qcld_rss_settings_enable', $qcld_rss_settings_enable);
        update_option('qcld_seo_help_cron_execution', $qcld_seo_help_cron_execution );
        update_option('qcld_seo_help_cron_schedule', $qcld_seo_help_cron_schedule );

        $message = esc_html('Data Updated Successfully');

        wp_send_json(['status'=> 'success','message'=> $message ]);
        wp_die();
    }
}