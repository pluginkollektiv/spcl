# Save Post. Check Links. #
* Contributors:      pluginkollektiv
* Donate link:       https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TD4AMD2D8EMZW
* Tags:              admin, broken, check, links, link checker, maintenance, post, posts, seo
* Requires at least: 3.7
* Tested up to:      5.7
* Requires PHP:      5.2
* Stable tag:        1.0.1
* License:           GPLv2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.html


Verifies URLs of links in your content are reachable when saving a post in WordPress.


## Description ##
When a post is saved or published, this plugin will

* scan the post’s content for any URLs,
* ping all the URLs found (except relative ones),
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
* Maintainers: [pluginkollektiv](https://pluginkollektiv.org)


## Frequently Asked Questions ##

### Will this plugin automatically correct link URLs on my website? ###
No, but it will automatically *find* any broken URLs in a post and list them for you, so you can review and correct them.

### Will it find broken image URLs, too? ###
Yes, the plugin will ping every URL in your post’s content, no matter if it’s in a link, an image** or even in a shortcode.

### Will the plugin prevent a post with broken links in it from being published? ###
No, it will just list any broken URLs for you, but it will do so already when you save a draft. Most people save a draft multiple times before they publish it, so there’s a fair chance you’ll notice any broken links before actually publishing.

### Does it matter whether a URL is http or https? ###
By default the plugin will try to ping both, http and https URLs. If needed, you can change accepted protocols via hook. For example, in order to check only URLs with SSL:

```
add_filter( 'spcl_acceptable_protocols', 'set_spcl_acceptable_protocols' );
function set_spcl_acceptable_protocols( $schemes ) {
	return array( 'https' );
}
```

### Where’s the settings page? ###
There is none, no configuration necessary.


## Changelog ##

### 1.0.1 ###
* Fix issue that check does work in Gutenberg

### 1.0.0 ###
* Add support for the Gutenberg editor (compatible with the latest WordPress version now)

### 0.7.5 ###
* Use a more secure hashing function

### 0.7.4 ###
* Bugfix for translations via wordpress.org

### 0.7.3 ###
* Improve code style
* Improve error messages

### 0.7.2 ###
* updated and translated README
* updated [plugin authors](https://gist.github.com/glueckpress/f058c0ab973d45a72720)

### 0.7.1 ###
* No verification of relative links (e.g. image paths)
* Hook `spcl_acceptable_protocols` added


## Screenshots ##
1. Output of faulty links in the Gutenberg Editor
2. Output of faulty links in the Classic Editor
