<?php 
function UPRDisplayPageData()
{
	$search = '';
	if(isset($_POST['search']))
	{
		$search = sanitize_text_field($_POST['upr-searchUser']);
	}

	$usersQuery = new WP_User_Query(array(
		'role' => '',
		'search'         => '*'.esc_attr($search).'*',
		'search_columns' => array(
			'ID',
			'user_login',
			'user_nicename',
			'user_email'
		),
	));
	$users = $usersQuery->get_results();
	echo '
	<div class="upr-overlay-div" style="
	display:none;
	position: absolute;
    top: 0;
    left: 0;
    z-index: 90;
    width: 100%;
    height: 1024px;
    background-color: white;
    opacity: 0.5;"></div>
	<hr class="wp-header-end">
	<div id="upr-bulk-action-popup" class="upr-bulk-action-popup">
	  <div class="upr-bulk-action-popup-content">
		<span class="upr-bulk-action-popup-close">&times;</span>
		<p>Bulk Action</p>
		<div class="upr-reset-password-popup">
				<input type="text" name="upr-reset-new-password-popup" class="upr-reset-new-password-popup" />
				<input type="button" name="upr-generate-new-password-popup" class="upr-generate-new-password-popup" value="Password Generate" /><br>';
				wp_nonce_field( 'UPR_Ajax_Request' );
				echo'
				<input type="button" name="upr-reset-new-password-submit-popup" class="upr-reset-new-password-submit-popup" value="Reset" />
		</div>
	  </div>
	</div>
		<div class="tablenav top">
				<div class="alignleft actions bulkactions">
					<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
					<select name="action" id="bulk-action-selector-top">
						<option value="">Bulk Actions</option>
						<option value="selected-users">Selected Users</option>
						<option value="all-users">All Users</option>
					</select>
					<input type="submit" id="upr-doaction" class="button action" value="Apply">
				</div>
				<div class="alignleft actions">
					<form action="" method="post">
						<label class="screen-reader-text" for="new_role">Change role to…</label>
						<input id="upr-searchUser" name="upr-searchUser" value="'.sanitize_text_field($_POST['upr-searchUser']).'" type="text" size="50" placeholder="Search Users By ID, Username, Email">
						<input type="submit" name="search" id="search" class="button" value="Search This">
					</form>
					<br class="clear">
				</div>
		</div>
	<table class="wp-list-table widefat fixed striped users">
		<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
				<label class="screen-reader-text" for="cb-select-all-1">Select All</label><input class="cb-select-all-1" type="checkbox">
				</td>	
				<th scope="col" id="username" class="manage-column column-name">Username</th>
				<th scope="col" id="name" class="manage-column column-name">Name</th>
				<th scope="col" id="email" class="manage-column column-name">Email</th>
				<th scope="col" id="role" class="manage-column column-name">Role</th>
				<th scope="col" id="actions" class="manage-column column-name">Actions</th>
			</tr>
		</thead>
		<tbody id="the-list" data-wp-lists="list:user">';
		foreach($users as $user):
			echo'<tr id="user-'.sanitize_text_field($user->data->ID).'">
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="user-'.sanitize_text_field($user->data->ID).'">Select umair</label>
					<input type="checkbox" name="users[]" id="user-'.sanitize_text_field($user->data->ID).'" class="upr-cb-checkboxes" value="'.sanitize_text_field($user->data->ID).'">
				</th>
				<td class="username column-username has-row-actions column-primary" data-colname="Username"> <strong>'.sanitize_text_field($user->data->user_login).'</strong></td>
				<td class="name column-name" data-colname="Name"> '.sanitize_text_field($user->data->user_nicename).'</td>
				<td class="email column-email" data-colname="Email"> '.sanitize_text_field($user->data->user_email).'</td>
				<td class="role column-role" data-colname="Role">'.sanitize_text_field($user->roles[0]).'</td>
				<td class="reset-password column-role" data-colname="reset-password">
					<a href="#" class="reset-password-href" data-id="'.sanitize_text_field($user->data->ID).'">Reset Password</a>
					<div class="reset-password-div">
						  <input type="text" name="upr-reset-new-password" class="upr-reset-new-password" />
						  <input type="button" name="upr-generate-new-password" class="upr-generate-new-password" value="Password Generate" /><br>';
						  wp_nonce_field( 'UPR_Ajax_Request' );
					echo'
						<input type="button" name="upr-reset-new-password-submit" class="upr-reset-new-password-submit" value="Reset" />
					</div>
				</td>
			</tr>';	
		endforeach;
	echo'		
			<tfoot>
				<tr>
					<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">Select All</label><input class="cb-select-all-1" type="checkbox">
					</td>	
					<th scope="col" id="username" class="manage-column column-name">Username</th>
					<th scope="col" id="name" class="manage-column column-name">Name</th>
					<th scope="col" id="email" class="manage-column column-name">Email</th>
					<th scope="col" id="role" class="manage-column column-name">Role</th>
					<th scope="col" id="actions" class="manage-column column-name">Actions</th>
				</tr>
			</tfoot>
		</tbody>
	</table>';
	?>

	<script type="text/javascript">

		jQuery(
			function(){

				jQuery('.reset-password-div').hide();

				jQuery('.reset-password-href').on('click', function(){
					jQuery(this).siblings('.reset-password-div').toggle();
				});

				jQuery('.upr-generate-new-password').on('click', function(){ 
					jQuery(this).siblings('.upr-reset-new-password').val(generatePassword());
				});

				jQuery('.upr-generate-new-password-popup').on('click', function(){ 
					jQuery(this).siblings('.upr-reset-new-password-popup').val(generatePassword());
				});

				jQuery('#bulk-action-selector-top').on('change', function(){

					if(jQuery(this).val() == "all-users")
					{
						jQuery('.cb-select-all-1').prop("checked", true);
						jQuery('.upr-cb-checkboxes').prop("checked", true);
					}else{
						jQuery('.cb-select-all-1').prop("checked", false);
						jQuery('.upr-cb-checkboxes').prop("checked", false);
					}
				});

				var userID = [];
				var upr_popup = document.getElementById('upr-bulk-action-popup');
				jQuery('.upr-reset-new-password-submit').on('click', function(){
					userID.length = 0;
					var generatedPassword = jQuery(this).siblings('.upr-reset-new-password').val();
					var security = jQuery(this).siblings('#_wpnonce').val();
					userID.push(jQuery(this).parent().siblings('.reset-password-href').attr('data-id'));
					var data = {
						action   : 'UPR_Ajax_Request',
						userID   : userID,
						security : security,
						generatedPassword : generatedPassword
					};
					var element = this;
					ajaxRequest(data, function(response){
						if(response.trim() == "Password Reset Successfully")
						{
							alert("Password Successfully Reset");
							jQuery(element).siblings('.upr-reset-new-password').val('');
							jQuery(element).parent().hide();
						}
					});
				});

				var e, c, k;
				e = jQuery('#bulk-action-selector-top');
				c = jQuery('.upr-cb-checkboxes');
				jQuery('#upr-doaction').on('click', function(){

					if(e.val() == "" || e.val() == null || e.val() == undefined)
					{
						alert("Please Select Action");
						return;
					}

					k = false;
					c.each(function(){

						if(c.is(':checked') == true)
						{
							k = true;
						}

					});

					if(k == false)
					{
						
						alert("Please Select User");
						return;

					} else {
						
						upr_popup.style.display = "block";
					}

				});

				jQuery('.upr-bulk-action-popup-close').on('click', function(){

					upr_popup.style.display = "none";

				});

				jQuery('.upr-reset-new-password-submit-popup').on('click', function(){
						var element = this;
						userID.length = 0;
						c.each(function(index, elem){
							if(jQuery(elem).is(':checked') == true)
							{
								userID.push(jQuery(elem).val());
							}
						});
						var security = jQuery(this).siblings('#_wpnonce').val();
						var generatedPassword = jQuery(this).siblings('.upr-reset-new-password-popup').val();
						var data = {
							action : 'UPR_Ajax_Request',
							userID : userID,
							security : security,
							generatedPassword : generatedPassword
						};
						ajaxRequest(data, function(response){
							if(response.trim() == "Password Reset Successfully")
							{
								alert("Password Successfully Reset");
								upr_popup.style.display = "none";
								c.prop("checked", false);
								jQuery(element).siblings('.upr-reset-new-password-popup').val('');
								jQuery('.cb-select-all-1').prop("checked", false);
							}
						});
				});
			}
		);

		function generatePassword() {
			var length = 8,
			charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
			retVal = "";
			for (var i = 0, n = charset.length; i < length; ++i) {
				retVal += charset.charAt(Math.floor(Math.random() * n));
			}
			return retVal;
		}

		function ajaxRequest(data, callback)
		{
			jQuery(".upr-overlay-div").css("display", "block");
			jQuery.ajax({
				type : 'POST',
				url: ajaxurl,
				cache: false,
				data : data,
				success: function(response)
				{
					callback(response);
					jQuery(".upr-overlay-div").css("display", "none");
				}

			});

		}

	</script>
<?php
}
