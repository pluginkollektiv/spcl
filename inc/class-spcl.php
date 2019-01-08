<?php
/**
 * Class SPCL
 *
 * @package spcl
 */

defined( 'ABSPATH' ) || exit;

/**
 * SPCL
 *
 * @since 0.7.0
 */
final class SPCL {

	/**
	 * Initialize the class.
	 *
	 * @since   0.1
	 * @change  0.7.0
	 */
	public static function init() {
		// Skip DOING_X.
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			 || ( defined( 'DOING_CRON' ) && DOING_CRON )
			 || ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			 || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
			return;
		}

		// Restrict access.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		// Add actions.
		add_action(
			'save_post',
			array(
				__CLASS__,
				'validate_links',
			)
		);
		add_action(
			'load-post.php',
			array(
				__CLASS__,
				'admin_notices',
			)
		);
	}

	/**
	 * Callback for admin_notices
	 *
	 * @since   0.7.0
	 * @change  0.7.0
	 */
	public static function admin_notices() {
		add_action(
			'admin_notices',
			array(
				__CLASS__,
				'display_errors',
			)
		);
	}

	/**
	 * Validate post links
	 *
	 * @since   0.1.0
	 * @change  0.7.3
	 *
	 * @hook    array  spcl_acceptable_protocols
	 *
	 * @param   int $id  Post ID.
	 */
	public static function validate_links( $id ) {
		// Skip check if no Post ID available.
		if ( empty( $id ) ) {
			return;
		}

		// Skip check if post is empty.
		$post = get_post( $id );
		if ( empty( $post ) || empty( $post->post_content ) ) {
			return;
		}

		// Extract URLs.
		$urls = wp_extract_urls( $post->post_content );
		if ( ! $urls ) {
			return;
		}

		// Apply filter for acceptable protocols.
		$acceptable_protocols = (array) apply_filters(
			'spcl_acceptable_protocols',
			array(
				'http',
				'https',
			)
		);

		$found = array();
		foreach ( $urls as $url ) {
			// Skip URL if depending on acceptable protocols check.
			if ( ! in_array( parse_url( $url, PHP_URL_SCHEME ), $acceptable_protocols ) ) {
				continue;
			}

			// Fragment check.
			$hash = parse_url( $url, PHP_URL_FRAGMENT );
			if ( $hash ) {
				$url = str_replace( '#' . $hash, '', $url );
			}

			// URL sanitization.
			$url = esc_url_raw(
				$url,
				$acceptable_protocols
			);

			// Skip empty URL.
			if ( empty( $url ) ) {
				continue;
			}

			/* Ping */
			$response = wp_safe_remote_head( $url );
			if ( is_wp_error( $response ) ) {
				// Response code.
				$found[] = array(
					'url'   => $url,
					'error' => $response->get_error_message(),
				);
			} else {
				// Status code.
				$code = (int) wp_remote_retrieve_response_code( $response );
				if ( $code >= 400 && 405 != $code ) {
					$found[] = array(
						'url'   => $url,
						'code' => $code,
					);
				}
			}
		}

		// No items?
		if ( empty( $found ) ) {
			return;
		}

		// Cache the result.
		set_transient(
			self::_transient_hash(),
			$found,
			60 * 30
		);
	}


	/**
	 * Output of validation errors.
	 *
	 * @since   0.1.0
	 * @change  0.7.3
	 */
	public static function display_errors() {
		// Check for error message.
		if ( empty( $_GET['message'] ) ) {
			return;
		}

		// Cache hash.
		$hash = self::_transient_hash();

		/* Get errors from cache */
		$items = get_transient( $hash );
		if ( ! $items || ! is_array( $items ) ) {
			return;
		}

		// Kill current cache.
		delete_transient( $hash );

		// Output errors.
		echo '<div class="notice notice-error is-dismissible">';
		foreach ( $items as $item ) {
			$link = sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_url( $item['url'] ),
				esc_url( $item['url'] )
			);

			if ( isset( $item['code'] ) ) {
				$error_text = sprintf(
					/* translators: 1: URL 2: HTTP status code */
					esc_html__( 'Check for URL %1$s failed with status code %2$s.', 'spcl' ),
					$link,
					esc_html( $item['code'] )
				);
			} else {
				$error_text = sprintf(
					/* translators: 1: URL 2: error message */
					esc_html__( 'Check for URL %1$s failed with error: %2$s.', 'spcl' ),
					$link,
					esc_html( $item['error'] )
				);
			}

			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<p>' . $error_text . '</p>'; // Everything is escaped properly already.
			// phpcs:enable
		}
		echo '</div>';
	}


	/**
	 * Create transient hash based on post and user IDs
	 *
	 * @since   0.1.0
	 * @change  0.6.1
	 *
	 * @return  string  Transient hash
	 */
	private static function _transient_hash() {
		return md5(
			sprintf(
				'SPCL_%s_%s',
				get_the_ID(),
				get_current_user_id()
			)
		);
	}
}
