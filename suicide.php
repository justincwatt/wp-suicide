<?php
/*
Plugin Name: WordPress Suicide
Version: 1.0
Plugin URI: http://justinsomnia.org/2006/04/wordpress-suicide/
Description: Delete all content from your blog (by table). Goto to Manage &gt; Suicide to operate.
Author: Justin Watt
Author URI: http://justinsomnia.org/

1.1
updated for WordPress 2.1 (linkcategories table renamed link2cat)

1.0
initial version

LICENSE

wp-suicide.php
Copyright (C) 2006 Justin Watt
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

// mt_add_pages() is the sink function for the 'admin_menu' hook
function mt_add_page() {
    // Add a new menu under Manage:
    add_management_page('Suicide', 'Suicide', 10, __FILE__, 'manage_suicide');
}

// mt_manage_page() displays the page content for the Test Manage submenu
function manage_suicide() 
{
    global $wpdb, $table_prefix;

    print "<div class='wrap'>";
    print "<h2>Suicide</h2>";


    if(isset($_POST['submitted']))
    {
        if (isset($_POST['delete_posts']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->posts");
            print "<p>$wpdb->posts deleted.</p>";
        }
        
        if (isset($_POST['delete_categories']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->categories");       
            print "<p>$wpdb->categories deleted.</p>";
        }
        
        if (isset($_POST['delete_post2cat']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->post2cat");         
            print "<p>$wpdb->post2cat deleted.</p>";
        }
        
        if (isset($_POST['delete_comments']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->comments");         
            print "<p>$wpdb->comments deleted.</p>";
        }
        
        if (isset($_POST['delete_links']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->links");            
            print "<p>$wpdb->links deleted.</p>";
        }
        
        if (isset($_POST['delete_link2cat']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->link2cat");   
            print "<p>$wpdb->link2cat deleted.</p>";
        }
        
        if (isset($_POST['delete_postmeta']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->postmeta");
            print "<p>$wpdb->postmeta deleted.</p>";
        }
        
        if (isset($_POST['delete_users']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->users");
            print "<p>$wpdb->users deleted.</p>";
        }
        
        if (isset($_POST['delete_usermeta']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->usermeta");
            print "<p>$wpdb->usermeta deleted.</p>";
        }
        
        if (isset($_POST['delete_options']))
        {
            $wpdb->query("TRUNCATE TABLE $wpdb->options");
            print "<p>$wpdb->options deleted.</p>";
        }
    }
    else
    { ?>
        <form name='suicide' action='' method='post'>
        <p>Delete your blog? 
        <button type='submit' onclick='return confirm("For the love of God, are you sure?");'>Yes</button></p>
        <input type="hidden" name="submitted" />

        <p><strong>Warning:</strong> By clicking the "Yes" button above, all the data in the checked tables will be deleted:</p>

        <ul style="list-style-type:none;">
        <li><input type="checkbox" name="delete_posts"    id="delete_posts"    checked="checked" /> <label for="delete_posts"><?php print $wpdb->posts; ?></label></li>
        <li><input type="checkbox" name="delete_post2cat" id="delete_post2cat" checked="checked" /> <label for="delete_post2cat"><?php print $wpdb->post2cat; ?></label></li>
        <li><input type="checkbox" name="delete_comments" id="delete_comments" checked="checked" /> <label for="delete_comments"><?php print $wpdb->comments; ?></label></li>
        <li><input type="checkbox" name="delete_postmeta" id="delete_postmeta" checked="checked" /> <label for="delete_postmeta"><?php print $wpdb->postmeta;?> (custom fields)</label></li>
        </ul>

        <p>Uncheck the following checkboxes if you want to preserve your categories and links:</p>
        
        <ul style="list-style-type:none;">
        <li><input type="checkbox" name="delete_categories"     id="delete_categories"     checked="checked" /> <label for="delete_categories"><?php print $wpdb->categories; ?></label></li>
        <li><input type="checkbox" name="delete_links"          id="delete_links"          checked="checked" /> <label for="delete_links"><?php print $wpdb->links; ?></label></li>
        <li><input type="checkbox" name="delete_link2cat"       id="delete_link2cat"       checked="checked" /> <label for="delete_link2cat"><?php print $wpdb->link2cat; ?></label></li>
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

// Insert the mt_add_pages() sink into the plugin hook list for 'admin_menu'
add_action('admin_menu', 'mt_add_page');
?>
