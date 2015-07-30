=== Save Post. Check Links. ===
Contributors: pluginkollektiv
Tags: check, links, broken, seo, link checker
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZAQUT9RLPW8QN
Requires at least: 3.7
Tested up to: 4.2.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



Erreichbarkeitsprüfung der Links beim Speichern von Artikeln im WordPress-Administrationsbereich.



== Description ==

*Save Post. Check Links.* übernimmt die Prüfung interner und externer Verlinkungen innerhalb der WordPress-Artikel. Das Plugin erkennt somit Tipp- sowie Copy&Paste-Fehler in gesetzten Links und Bildpfaden. Der Vorteil: Defekte Website-Verknüpfungen und Bild-Referenzierungen werden noch vor der Veröffentlichung der Beiträge erkannt und vom Autor korrigiert.

Beim Speichern bzw. Publizieren der Artikel sucht sich die WordPress-Erweiterung alle URLs aus dem Inhalt heraus und pingt sie zwecks Richtigkeit/Erreichbarkeit an. Fehlerhafte Links samt Ursache (Fehlercode) listet das Plugin zur Kontrolle bzw. zum Nachbessern auf.


= Stärken =
* Links-Check im Hintergrund
* Anzeige der Fehlerursache
* Keine Konfiguration notwendig
* Intakte Links = SEO-Optimierung
* Performante Lösung, übersichtlicher Code


= Hooks =
* [spcl_acceptable_protocols](https://gist.github.com/sergejmueller/b515138b23b39ebfd1e5)


= Systemvoraussetzungen =
* PHP ab 5
* WordPress ab 3.7


= Speicherbelegung =
* Im Backend: ~ 0,04 MB
* Im Frontend: ~ 0,01 MB


= Unterstützung =
* Per [Flattr](https://flattr.com/thing/d3678820253d89a088d7b415a739cd57)
* Per [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZAQUT9RLPW8QN)


= Autor =
* [Twitter](https://twitter.com/wpSEO)
* [Google+](https://plus.google.com/110569673423509816572)
* [Plugins](http://wpcoder.de)



== Changelog ==

= 0.7.1 =
* Keine Überprüfung relativer Links (z.B. bei Bildpfaden)
* Hook [spcl_acceptable_protocols](https://gist.github.com/sergejmueller/b515138b23b39ebfd1e5) hinzugefügt

= 0.7.0 =
* Umstrukturierung zwecks Reduzierung des Speicherverbrauchs

= 0.6.2 =
* Zusatzprüfung für extrahierte Links

= 0.6.1 =
* Werte zu Plugin-Speichernutzung hinzugefügt
* `get_current_user_id` statt `wp_get_current_user()->ID`

= 0.6.0 =
* Support zu WordPress 3.9
* Überarbeitung des Sourcecodes

= 0.5.1 =
* Tausch `esc_url` gegen `esc_url_raw`

= 0.5 =
* Xmas Edition

= 0.4.1 =
* Hotfix für URLs mit Hash-Fragmenten

= 0.4 =
* Live auf wordpress.org

= 0.3 =
* Ausgabe des Fehlers bzw. Status Codes
* Quelltext-Überarbeitung

= 0.2 =
* Umstellung der Action auf `admin_notices`
* Zusätzliche Prüfung des Status Codes 405

= 0.1 =
* Plugin-Veröffentlichung



== Screenshots ==

1. Ausgabe fehlerhafter Links