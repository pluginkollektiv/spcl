<?php
/*
Plugin Name: Save Post. Check Links.
Description: Bei der Speicherung der Artikel prüft das Plugin die im Text vorhandenen Verlinkungen auf ihre Erreichbarkeit bzw. Gültigkeit.
Author: Sergej M&uuml;ller
Author URI: http://wpcoder.de
Plugin URI: https://wordpress.org/plugins/spcl/
License: GPLv2 or later
Version: 0.7.1
*/

/*
Copyright (C)  2011-2014 Sergej Müller

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/


/* Quit */
defined('ABSPATH') OR exit;


/* Backend only */
if ( ! is_admin() ) {
    return;
}

/* Include core */
require_once(
    sprintf(
        '%s/inc/spcl.class.php',
        dirname(__FILE__)
    )
);

/* Fire */
add_action(
    'admin_init',
    array(
        'SPCL',
        'init'
    )
);