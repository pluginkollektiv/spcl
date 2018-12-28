# Save Post. Check Links. #
* Contributors:      pluginkollektiv
* Donate link:       https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8CH5FPR88QYML
* Tags:              admin, broken, check, links, link checker, maintenance, post, posts, seo
* Requires at least: 3.7
* Tested up to:      4.9
* Requires PHP:      5.2
* Stable tag:        0.7.3
* License:           GPLv2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.html


Verifies URLs of links in your content are reachable when saving a post in WordPress.


## Description ##
When a post is saved or published, this plugin will

* scan the post’s content for any URLs,
* ping all the URLs found,
* detect any broken or unreachable URLs and list them for review and correction.

No more publishing of links or images broken by typos or incompletely copy-pasted URLs!

### Support ###
* Community support via the [support forums on wordpress.org](https://wordpress.org/support/plugin/spcl)
* We don’t handle support via e-mail, Twitter, GitHub issues etc.

### Contribute ###
* Active development of this plugin is handled [on GitHub](https://github.com/pluginkollektiv/spcl).
* Pull requests for documented bugs are highly appreciated.
* If you think you’ve found a bug (e.g. you’re experiencing unexpected behavior), please post at the [support forums](https://wordpress.org/support/plugin/spcl) first.
* If you want to help us translate this plugin you can do so [on WordPress Translate](https://translate.wordpress.org/projects/wp-plugins/spcl).

### Credits ###
* Author: [Sergej Müller](https://sergejmueller.github.io/)
* Maintainers: [pluginkollektiv](http://pluginkollektiv.org)


## Installation ##
* If you don’t know how to install a plugin for WordPress, [here’s how](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

### Requirements ###
* PHP 5.2.4 or greater
* WordPress 3.7 or greater

## Frequently Asked Questions ##
### Will this plugin automatically correct link URLs on my website? ###
No, but it will automatically *find* any broken URLs in a post and list them for you, so you can review and correct them.

### Will it find broken image URLs, too? ###
Yes, the plugin will ping every URL in your post’s content, no matter if it’s in a **link**, an **image** or even in a **shortcode**.

### Will the plugin prevent a post with broken links in it from being published? ###
No, it will just list any broken URLs for you, but it will do so already when you save a draft. Most people save a draft multiple times before they publish it, so there’s a fair chance you’ll notice any broken links before actually publishing.

If you want to **to avoid accidental publishing**, try the free [Publish Confirm](https://wordpress.org/plugins/publish-confirm/) plugin. It will add an extra confirmation dialogue for the publish button.

### What about any relative URLs? ###
Relative URLs will not be checked.

### Does it matter whether a URL is http or https? ###
By default the plugin will try to ping both, http and https URLs. If needed, you can change accepted protocols via hook. For example, in order to check only URLs with SSL:

`add_filter( 'spcl_acceptable_protocols', 'set_spcl_acceptable_protocols' );
function set_spcl_acceptable_protocols( $schemes ) {
	return array( 'https' );
}`

### Will this plugin optimize my site for search engines (aka SEO)? ###
While the term “optimize” would surely be an overstatement, this plugin can surely add a fair share to the link sanity on your site. Link sanity is a pretty important SEO factor.

### Where’s the settings page? ###
There is none, no configuration necessary.


## Changelog ##

### 0.7.3 ###
* Improve code style

### 0.7.2 ###
* updated and translated README
* updated [plugin authors](https://gist.github.com/glueckpress/f058c0ab973d45a72720)

### 0.7.1 ###
* No verification of relative links (e.g. image paths)
* Hook [spcl_acceptable_protocols](https://gist.github.com/sergejmueller/b515138b23b39ebfd1e5) added

### 0.7.0 ###
* Restructuring in order to reduce memory consumption

### 0.6.2 ###
* Supplementary test for extracted links

### 0.6.1 ###
* Added values to plug-in memory usage
* `get_current_user_id` instead of `wp_get_current_user()->ID`

### 0.6.0 ###
* WordPress 3.9 support
* Revision of the source code

### 0.5.1 ###
* Exchange `esc_url` against `esc_url_raw`

### 0.5 ###
* Xmas Edition

### 0.4.1 ###
* Hotfix for URLs with hash fragments

### 0.4 ###
* Live on wordpress.org

### 0.3 ###
* Output of the error or status codes
* Source code revision

### 0.2 ###
* Conversion of action to `admin_notices`
* Additional check of status code 405

### 0.1 ###
* Plugin Release


## Screenshots ##
1. Output of faulty links
