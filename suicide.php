<?php
/*
Plugin Name: Suicide
Version: 1.5
Plugin URI: http://justinsomnia.org/2006/04/wordpress-suicide/
Description: Delete all content from your blog's database (by table). Goto <a href="tools.php?page=suicide.php">Tools &gt; Suicide</a> to operate.
Author: Justin Watt
Author URI: http://justinsomnia.org/

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

LICENSE

wp-suicide.php
Copyright (C) 2012 Justin Watt
justincwatt@gmail.com
http://justinsomnia.org/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

function manage_suicide() {
  // Add a new menu under Tools:
  add_management_page('Suicide', 'Suicide', 'manage_options', __FILE__, 'commit_suicide');
}
add_action('admin_menu', 'manage_suicide');

function commit_suicide() 
{
  if (!empty($_POST)) {
    check_admin_referer('commit-suicide');
  }

  global $wpdb;
  $tables = array(
    'posts',
    'comments',
    'commentmeta',
    'links',
    'postmeta',
    'term_relationships',
    'terms',
    'term_taxonomy',
    'users',
    'usermeta',
    'options'
  );

  print "<div class='wrap'>";
  print "<h2>Commit Suicide?</h2>";

  if ($_POST['function'] == 'commit suicide') {

    print "<p><strong>Progress:</strong></p>";

    foreach ($tables as $table) {
      if (isset($_POST["delete_$table"])) {
        $wpdb->query("TRUNCATE TABLE {$wpdb->$table}");
        print "<p>{$wpdb->$table} deleted.</p>";
      }
    }
    
    // deactivate plugin for safety's sake (borrowed from wp-admin/plugins.php)
    $current = get_option('active_plugins');
    if (in_array(basename(__FILE__), $current)) {
      array_splice($current, array_search(basename(__FILE__), $current), 1); // Array-fu!
      update_option('active_plugins', $current);
      print "<p><strong>Note:</strong> For your safety (and the safety of others around you), the Suicide plugin has been deactivated. However, you can reactivate it from the <a href='plugins.php'>Plugins</a> interface if you'd like to commit suicide again.</p>";
    }
  
  } else { 

    ?>
    
    <form method='post' name='suicide' >
    <?php wp_nonce_field('commit-suicide'); ?>
    <p><strong style="color:red;">Warning:</strong> By clicking <button type='submit' name='function' class='button' value='commit suicide' onclick='return confirm("For the love of pete, are you sure?");'>Yes</button> all the data in the database tables checked below will be deleted.</p>

    <ul style="list-style-type:none;">
    <li><input type="checkbox" name="delete_posts"              id="delete_posts"              checked="checked" /> <label for="delete_posts"><?php print $wpdb->posts; ?></label></li>
    <li><input type="checkbox" name="delete_comments"           id="delete_comments"           checked="checked" /> <label for="delete_comments"><?php print $wpdb->comments; ?></label></li>
    <li><input type="checkbox" name="delete_commentmeta"        id="delete_commentmeta"        checked="checked" /> <label for="delete_commentmeta"><?php print $wpdb->commentmeta; ?></label></li>    
    <li><input type="checkbox" name="delete_links"              id="delete_links"              checked="checked" /> <label for="delete_links"><?php print $wpdb->links; ?></label></li>
    <li><input type="checkbox" name="delete_postmeta"           id="delete_postmeta"           checked="checked" /> <label for="delete_postmeta"><?php print $wpdb->postmeta;?> (custom fields)</label></li>
    <li><input type="checkbox" name="delete_term_relationships" id="delete_term_relationships" checked="checked" /> <label for="delete_term_relationships"><?php print $wpdb->term_relationships; ?></label></li>
    </ul>

    <p>Uncheck the following checkboxes if you want to preserve your categories and tags:</p>
    
    <ul style="list-style-type:none;">
    <li><input type="checkbox" name="delete_terms"         id="delete_terms"         checked="checked" /> <label for="delete_terms"><?php print $wpdb->terms; ?></label></li>
    <li><input type="checkbox" name="delete_term_taxonomy" id="delete_term_taxonomy" checked="checked" /> <label for="delete_term_taxonomy"><?php print $wpdb->term_taxonomy; ?></label></li>
    </ul>

    <p>By sparing the data in the following tables, you will be left with a functional, though empty WordPress install:</p>

    <ul style="list-style-type:none;">
    <li><input type="checkbox" name="delete_users"    id="delete_users"    /> <label for="delete_users"><?php print $wpdb->users; ?></label></li>
    <li><input type="checkbox" name="delete_usermeta" id="delete_usermeta" /> <label for="delete_usermeta"><?php print $wpdb->usermeta; ?></label></li>
    <li><input type="checkbox" name="delete_options"  id="delete_options"  /> <label for="delete_options"><?php print $wpdb->options; ?></label></li>
    </ul>

    <p><strong>Use with extreme caution!</strong> The author of this plugin assumes no liability whatsoever for the destructive effects of its use.</p>
    </form>

    <?php
  }

  print "</div>";
}
