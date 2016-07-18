# CF7 Static Form & Mail Content
Contributors: herooutoftime
Donate link: http://www.herooutoftime.com
Tags: contact form, contact form 7, cf7
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make CF7 (ContactForm 7) form & emails served via static files

## Special thanks
* [barockok](https://github.com/barockok) for this GIST: https://gist.github.com/barockok/1591053

## Description

This plugin was written to ease the work with Contact Form 7, one of the most popular Wordpress plugins,
by making all HTML related content available through static files. By placing additional settings in each
form you can decide to *statify* your form & email contents.

**Plus: You can enable `premailer` to inline all your CSS automatically**

**This plugin is in an early stage. Please use with caution**

## Requirements

You will either need: [http](http://php.net/manual/fa/book.http.php) or [curl](http://php.net/manual/en/book.curl.php) enabled.

## Installation

1. Upload/Clone `cf7-statifier` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

### Additional settings

```
static_file_enabled: yes
static_file_form: yes
static_file_mail: yes
static_file_mail_premailer: yes
static_file_mail_2: yes
static_file_mail_2_premailer: yes
```

### Premailer

CF7-Statifier implements all options available through [Premailer-API](http://premailer.dialect.ca/api)
Add like above to *Additional settings`

```
static_file_premailer_fetchresult: yes | no
static_file_premailer_adaptor: nokogiri | hpricot
static_file_premailer_base_url: http://www.example.com
static_file_premailer_line_length: 75
static_file_premailer_link_query_string: ?utm_source=newsletter&utm_medium=email&utm_campaign=mycampaign
static_file_premailer_preserve_styles: yes | no
static_file_premailer_remove_ids: no | yes
static_file_premailer_remove_classes: no | yes
static_file_premailer_remove_comments: no | yes
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

## Frequently Asked Questions

* **Q**: Where should I store my static files for CF7?
* **A**: The plugin searches for static files in your theme's directory via `get_stylesheet_directory()`


## Changelog

### 1.0
* Basic version

## Upgrade Notice
