=== CF7 Static Form & Mail Content ===
Contributors: herooutoftime
Donate link: http://www.herooutoftime.com
Tags: contact form, contact form 7, cf7
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make CF7 (ContactForm 7) form & emails served via static files

== Description ==

This plugin was written to ease the work with Contact Form 7, one of the most popular Wordpress plugins,
by making all HTML related content available through static files. By placing additional settings in each
form you can decide to *statify* your form & email contents.

**Plus: You can enable `premailer` to inline all your CSS automatically**



== Installation ==

1. Upload `cf7-statifier.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Usage ==

1. Add additional settings to each CF7-form

```
static_file_enabled: yes
static_file_form: yes
static_file_mail: yes
static_file_mail_premailer: yes
static_file_mail_2: yes
static_file_mail_2_premailer: yes
```

The plugin listens to several actions:
* `wpcf7_after_save`: Saves the static file(s) and creates, if set, processed mail(s)
* `wpcf7_before_send_mail`: Fetches the static mail(s)

The plugins manipulates CF7's properties:
* `wpcf7_contact_form_properties`: Show the contents of the static file(s) instead of DB-contents

Files will be written to the following directories (depending on your settings):
* Form & unprocessed mail(s): `[STYLESHEET_DIRECTORY]/cf_static`
* Processed mail(s): `[STYLESHEET_DIRECTORY]/cf_static/cf7_proc`

For compatibility with common mail-clients (e.g. Outlook, Gmail, Apple Mail) you are able to set `static_file_[MAIL_ID]_premailer: yes`
When saving (via WP-Admin) a folder with the processed contents of each mail will be stored and fetched on submit.

== Frequently Asked Questions ==

= Where should I store my static files for CF7? =

The plugin searches for static files in your theme's directory via `get_stylesheet_directory()`


== Changelog ==

= 1.0 =
* Basic version

== Upgrade Notice ==