<div class='wrap'>
	<h2>Commit Network Suicide?</h2>
	
	<form method='post' name='suicide' >
	<?php wp_nonce_field('commit-suicide'); ?>
	<p><strong style="color:red;">Warning:</strong> By clicking <button type='submit' name='function' class='button' value='commit-suicide' onclick='return confirm("For the love of pete, are you sure?");'>Yes</button> all the data in the database tables checked below will be deleted.</p>

	<h3>Sites</h3>
	<ul style="list-style: none;">
		<li><label><input type="checkbox" name="all_blogs" /> <b>All Sites</b></label></li>
		<li><hr width="30%" align="left"></li>
		<?php
		foreach( $blogs AS $blog_id => $domain ) {
			?>
		<li><label><input type="checkbox" name="blogs[]" value="<?php echo $blog_id;?>" /> <?php echo $domain; ?></label>
			<?php
		}
		?>
	</ul>
	
	<h3>Tables</h3>
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
	
	<p><label><input type="checkbox" name="prevent_further_suicide"  /> <b>Prevent further suicide?</b> (Deactivate plugin after use)</label></p>
		
	</form>
</div>