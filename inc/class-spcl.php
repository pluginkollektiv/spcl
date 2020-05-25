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

		// Load text domain.
		load_plugin_textdomain( 'spcl' );

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

		// Add block editor file.
		add_action(
			'enqueue_block_editor_assets',
			array(
				__CLASS__,
				'enqueue_block_editor_asset',
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
	 * Enqueue script for block editor.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue_block_editor_asset() {
		wp_enqueue_script(
			'spcl-block-editor-script',
			plugins_url( 'assets/js/notice.min.js', dirname( __FILE__ ) ),
			array( 'wp-dom-ready', 'wp-data' ),
			SPCL_VERSION
		);

		// Add nonce for AJAX request.
		wp_localize_script(
			'spcl-block-editor-script',
			'spclScriptData',
			array(
				'nonce' => wp_create_nonce( 'spcl-block-editor-nonce' ),
			)
		);
	}

	/**
	 * Handle the AJAX request coming from the block editor.
	 *
	 * @since 1.0.0
	 */
	public static function handle_ajax_request() {
		// Check if user can edit posts.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		// Check nonce.
		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['_nonce'] ), 'spcl-block-editor-nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			return;
		}

		// Check if no post ID.
		if ( ! isset( $_POST['check_post'] ) || empty( $_POST['check_post'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			return;
		}

		// Validate links.
		self::validate_links( $_POST['check_post'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash

		// Get errors.
		// Cache hash.
		$hash = self::_transient_hash();

		// Get errors from cache.
		$items = get_transient( $hash );
		if ( ! $items || ! is_array( $items ) ) {
			return;
		}

		// Kill current cache.
		delete_transient( $hash );

		// Send JSON to the block editor.
		wp_send_json( $items );
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
			if ( ! in_array( parse_url( $url, PHP_URL_SCHEME ), $acceptable_protocols, true ) ) {
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

			$link = sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_url( $url ),
				esc_url( $url )
			);

			/* Ping */
			$response = wp_safe_remote_head( $url );
			if ( is_wp_error( $response ) ) {
				// Response code.
				$found[] = array(
					'url'   => $url,
					'error' => $response->get_error_message(),
					'error_text' => sprintf(
						/* translators: 1: URL 2: error message, ending with a period already */
						esc_html__( 'Check for URL %1$s failed with error: %2$s', 'spcl' ),
						$link,
						esc_html( $response->get_error_message() )
					),
				);
			} else {
				// Status code.
				$code = (int) wp_remote_retrieve_response_code( $response );
				if ( $code >= 400 && 405 !== $code ) {
					$found[] = array(
						'url'   => $url,
						'code' => $code,
						'error_text' => sprintf(
							/* translators: 1: URL 2: HTTP status code */
							esc_html__( 'Check for URL %1$s failed with status code %2$s.', 'spcl' ),
							$link,
							esc_html( $code )
						),
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
			$error_text = $item['error_text'];

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
		return self::hash(
			sprintf(
				'SPCL_%s_%s',
				get_the_ID(),
				get_current_user_id()
			)
		);
	}

	/**
	 * Hashes a value with the best available hashing function.
	 *
	 * @param string $value The value to hash.
	 *
	 * @return string A hash of the value.
	 */
	private static function hash( $value ) {
		// If ext/hash is not present, use sha1() instead.
		if ( function_exists( 'hash' ) ) {
			return hash( 'sha256', $value );
		}

		return sha1( $value );
	}
}
