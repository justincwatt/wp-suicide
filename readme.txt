=== Suicide ===
Contributors: justinwatts, blobaugh
Tags: suicide, remove content, wipe database, multisite
Requires at least: 3.0
Tested up to: 3.9
Stable tag: trunk

Remove all content from your blog's database (by table). Multisite compatible

== Description ==

Removes all the content from your blog's database on a per table basis. 

To use simply install and visit the Tools > Suicide page


**Multisite Compatible:** As of version 2.0 Suicide is now multisite compatible. 

Visit Network Admin > Sites > Network Suicide to choose which sites you would like to remove content from

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Changelog ==
2.0
- Security Fix: Fixed nonce so that it is checked properly and will not allow suicide to happen if invalid
- Bugfix: Renamed plugin function to prevent fatal conflicts with other plugins/core function names
- Feature: Added ability to suicide all network content on a WordPress Multisite install
- Upgrade: Moved suicide functions into a Suicide object
- Users can suicide all network content from Network Admin > Sites > Network Suicide
- By default the plugin is deactivated after use on an individual site. Stays active at network level

1.5
Add wp_nonce_field check, minor code cleanup

1.4
updated for WordPress 2.9 (wp_commentmeta table added)

1.3
deactivate wp-suicide after running (thanks Steven!)

1.2
updated for WordPress 2.3 (post2cat and link2cat became wp_term_relationships, and categories became terms and term_taxonmy)

1.1
updated for WordPress 2.1 (linkcategories table renamed link2cat)

1.0
initial version
