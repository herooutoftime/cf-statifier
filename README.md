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

* Your forms and mails will be available through your filesystem and therefore easy to edit.
* You can enable `premailer` to inline all your CSS automatically for each email you want to send.
* With `premailer` enabled your emails will be `multipart`: HTML & plain text

**This plugin is in an early stage. Please use with caution**

## Requirements

You will either need
* [http](http://php.net/manual/fa/book.http.php)
* [curl](http://php.net/manual/en/book.curl.php)

PHP extension installed and enabled to use *Premailer* service.

## Installation

* Clone `cf7-statifier` to the `/wp-content/plugins/` directory: `git clone git@github.com:herooutoftime/cf-statifier.git`
* Or download the [ZIP](https://github.com/herooutoftime/cf-statifier/archive/master.zip) and upload to `wp-content/plugins/`
* Activate the plugin through the 'Plugins' menu in WordPress

## Usage

### CF7-Statifier settings

```
static_file_enabled: yes
static_file_form: yes
static_file_mail: yes
static_file_mail_premailer: yes
static_file_mail_2: yes
static_file_mail_2_premailer: yes
```

### Premailer settings

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

### Static files

Files will be written to the following directories (depending on your settings):
* Form & unprocessed mail(s): `[STYLESHEET_DIRECTORY]/cf_static`
* Processed mail(s): `[STYLESHEET_DIRECTORY]/cf_static/cf7_proc`

## Frequently Asked Questions

* **Q**: Where should I store my static files for CF7?
* **A**: The plugin searches for static files in your theme's directory via `get_stylesheet_directory()`


## Changelog

### 1.0
* Basic version

## Upgrade Notice
