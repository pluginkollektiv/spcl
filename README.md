# Save Post. Check Links. #
* Contributors:      pluginkollektiv
* Donate link:       https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LG5VC9KXMAYXJ
* Tags:              check, links, broken, seo, link checker
* Requires at least: 3.7
* Tested up to:      4.3
* Stable tag:        trunk
* License:           GPLv2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.html


Verifies URLs of links in your content are reachable when saving a post in WordPress.


## Description ##
When a post is saved or published, this plugin will

* scan the post’s content for any URLs,
* ping all the URLs found,
* detect any broken or unreachable URLs and list them for review and correction.

No more publishing of links or images broken by typos or incompletely copy-pasted URLs!


### Deutsch ###
*Save Post. Check Links.* übernimmt die Prüfung interner und externer Verlinkungen innerhalb der WordPress-Artikel. Das Plugin erkennt somit Tipp- sowie Copy&Paste-Fehler in gesetzten Links und Bildpfaden. Der Vorteil: Defekte Website-Verknüpfungen und Bild-Referenzierungen werden noch vor der Veröffentlichung der Beiträge erkannt und vom Autor korrigiert.

Beim Speichern bzw. Publizieren der Artikel sucht sich die WordPress-Erweiterung alle URLs aus dem Inhalt heraus und pingt sie zwecks Richtigkeit/Erreichbarkeit an. Fehlerhafte Links samt Ursache (Fehlercode) listet das Plugin zur Kontrolle bzw. zum Nachbessern auf.


### Requirements ###
* PHP 5.2.4+
* WordPress 3.7+


### Memory Usage ###
* Back-end: ~ 0.04 MB
* Front-end: ~ 0.01 MB


### Credits ###
* Author: [Sergej Müller](https://sergejmueller.github.io/)
* Maintainers: [pluginkollektiv](http://pluginkollektiv.org)


## Installation ##
* If you don’t know how to install a plugin for WordPress, [here’s how](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).


## Frequently Asked Questions ##
### Will this plugin automatically correct link URLs on my website? ###
No, but it will automatically _find_ any broken URLs in a post and list them for you, so you can review and correct them.

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
### 0.7.1 ###
* Keine Überprüfung relativer Links (z.B. bei Bildpfaden)
* Hook [spcl_acceptable_protocols](https://gist.github.com/sergejmueller/b515138b23b39ebfd1e5) hinzugefügt

### 0.7.0 ###
* Umstrukturierung zwecks Reduzierung des Speicherverbrauchs

### 0.6.2 ###
* Zusatzprüfung für extrahierte Links

### 0.6.1 ###
* Werte zu Plugin-Speichernutzung hinzugefügt
* `get_current_user_id` statt `wp_get_current_user()->ID`

### 0.6.0 ###
* Support zu WordPress 3.9
* Überarbeitung des Sourcecodes

### 0.5.1 ###
* Tausch `esc_url` gegen `esc_url_raw`

### 0.5 ###
* Xmas Edition

### 0.4.1 ###
* Hotfix für URLs mit Hash-Fragmenten

### 0.4 ###
* Live auf wordpress.org

### 0.3 ###
* Ausgabe des Fehlers bzw. Status Codes
* Quelltext-Überarbeitung

### 0.2 ###
* Umstellung der Action auf `admin_notices`
* Zusätzliche Prüfung des Status Codes 405

### 0.1 ###
* Plugin-Veröffentlichung


## Screenshots ##
1. Ausgabe fehlerhafter Links
