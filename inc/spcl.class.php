<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Optimus
*
* @since 0.7.0
*/

final class SPCL {


    /**
    * Initiator der Klasse
    *
    * @since   0.1
    * @change  0.7.0
    */

    public static function init()
    {
        /* Skip DOING_X */
        if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) OR (defined('DOING_CRON') && DOING_CRON) OR (defined('DOING_AJAX') && DOING_AJAX) OR (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) ) {
            return;
        }

        /* Restrict access */
        if ( ! current_user_can('edit_posts') ) {
            return;
        }

        /* Actions */
        add_action(
            'save_post',
            array(
                __CLASS__,
                'validate_links'
            )
        );
        add_action(
            'load-post.php',
            array(
                __CLASS__,
                'admin_notices'
            )
        );
    }


    /**
    * Callback for admin_notices
    *
    * @since   0.7.0
    * @change  0.7.0
    */

    public static function admin_notices()
    {
        add_action(
            'admin_notices',
            array(
                __CLASS__,
                'display_errors'
            )
        );
    }


    /**
    * Validate post links
    *
    * @since   0.1.0
    * @change  0.7.1
    *
    * @hook    array  spcl_acceptable_protocols
    *
    * @param   intval  $id  Post ID
    */

    public static function validate_links($id)
    {
        /* No PostID? */
        if ( empty($id) ) {
            return;
        }

        /* Get post data */
        $post = get_post($id);

        /* Post incomplete? */
        if ( empty($post) OR empty($post->post_content) ) {
            return;
        }

        /* Extract urls */
        if ( ! $urls = wp_extract_urls($post->post_content) ) {
            return;
        }

        /* Init */
        $found = array();

        /* Loop the urls */
        foreach ( $urls as $url ) {
            /* Acceptable protocols filter */
            $acceptable_protocols = (array)apply_filters(
                'spcl_acceptable_protocols',
                array(
                    'http',
                    'https'
                )
            );

            /* Scheme check */
            if ( ! in_array( parse_url($url, PHP_URL_SCHEME), $acceptable_protocols ) ) {
                continue;
            }

            /* Fragment check */
            if ( $hash = parse_url($url, PHP_URL_FRAGMENT) ) {
                $url = str_replace('#' .$hash, '', $url);
            }

            /* URL sanitization */
            $url = esc_url_raw(
                $url,
                $acceptable_protocols
            );

            /* Skip URL */
            if ( empty($url) ) {
                continue;
            }

            /* Ping */
            $response = wp_safe_remote_head($url);

            /* Error? */
            if ( is_wp_error($response) ) {
                $found[] = array(
                    'url'   => $url,
                    'error' => $response->get_error_message()
                );

            /* Respronse code */
            } else {
                /* Status code */
                $code = (int)wp_remote_retrieve_response_code($response);

                /* Handle error codes */
                if ( $code >= 400 && $code != 405 ) {
                    $found[] = array(
                        'url'   => $url,
                        'error' => sprintf(
                            'Status Code %d',
                            $code
                        )
                    );
                }
            }
        }

        /* No items? */
        if ( empty($found) ) {
            return;
        }

        /* Cache the result */
        set_transient(
            self::_transient_hash(),
            $found,
            60*30
        );
    }


    /**
    * Output of validation errors
    *
    * @since   0.1.0
    * @change  0.7.0
    *
    */

    public static function display_errors()
    {
        /* Check for error message */
        if ( empty($_GET['message']) ) {
            return;
        }

        /* Cache hash */
        $hash = self::_transient_hash();

        /* Get errors from cache */
        if ( (! $items = get_transient($hash)) OR (! is_array($items)) ) {
            return;
        }

        /* Kill current cache */
        delete_transient($hash);

        /* Output start */
        echo '<div class="notice notice-error is-dismissible">';

        /* Loop the cache items */
        foreach ( $items as $item ) {
            echo sprintf(
                '<p><a href="%1$s" target="_blank">%1$s</a> (%2$s)</p>',
                esc_url($item['url']),
                esc_html($item['error'])

            );
        }

        /* Output end */
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