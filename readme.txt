=== WPeMatico Polylang ===
Contributors: etruel, khaztiel, cjsq24
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=B8V39NWK3NFQU
Author URI: https://www.netmdp.com
Plugin URL: https://etruel.com/downloads/wpematico_polylang
Tags: wpematico,polylang,autoblog,autopost,auto translate, multilanguage,wpml,translation,translator
Requires at Least: 4.9
Tested Up To: 5.5
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later

Gives support to WPeMatico for auto publishing posts with Polylang Multilanguage plugin.

== Description ==

THIS VERSION SHOULD BE THE RELEASE CANDIDATE. TEST IN DEVELOPMENT SITES BEFORE USE.
Polylang allows you to create a bilingual or multilingual WordPress site. 
With [Polylang](https://wordpress.org/plugins/polylang/) You write posts, pages, etc.

WPeMatico is a very easy to use autoblogging plugin.
With [WPeMatico](https://wordpress.org/plugins/wpematico/) `It` writes `your` posts, pages, etc.

With **WPeMatico Polylang** you can send the automated publishing posts of each WPeMatico campaign to a different Polylang language to allow translate them later.
This translation could be done by Lingotek addon of Polylang or any other translation service manual or automated, but the matter is: you already has the post in your WordPress website with the right language to be translated.

# Features
- Adds a new Metabox in each campaign to set the final language of the published posts(types)
- You can assign any Polylang language to the posts of each campaign.
- The initial value of the selected option of the languages list is the Default language of the website.
- All the post terms and images will be assigned with the same language of the published post.


== Installation ==

= Requirements =

- WordPress 4.9+ / Tested up to 5.4
- Require PHP 5.6
- [WPeMatico (2.5+)](https://www.wpematico.com)
- [Polylang (1.8)](https://polylang.pro)

= WordPress =

You can either install it automatically from the WordPress admin, or do it manually:
First activate and configure `WPeMatico` & `Polylang` on you site.

= Using the Plugin Manager =

1. Click Plugins
2. Click Add New
3. Search for `wpematico-polylang`
4. Click Install
5. Click Install Now
6. Click Activate Plugin
7. Now you could see Languages Metabox inside WPeMatico Campaigns.

= Manually =

1. Upload `wpematico-polylang` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Polylang Lenguages list in each campaign.

1. Polylang Lenguages list in context.

== Frequently Asked Questions ==

No yet.  Be the first in the support forums.

== Changelog ==

= 1.1.0 - 21 Jul 2020 =
- Added set the language of the inserted post and also its terms.
- Added compatibility with WPeMatico Professional Addon.
- Fixes the saving of messages in the language assigned to the campaign when it is not the default.
- Corrects the deletion of categories when the selected language was not the default.

= 1.0.0 - 31 Mar 2020 =
- Initial plugin.

== Upgrade Notice == 
- Initial plugin.