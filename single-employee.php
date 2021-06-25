<?php
session_start();
get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
global $post;
$post_id = $post->ID;
?>

<div id="main-content">
	<div class="emp_container">
		<div id="content-area" class="clearfix">
			
				<?php 

				$generatePassword = get_post_meta( $post_id, $key = 'Password', $single = true );

				$nonce = wp_create_nonce( 'custom-action' );
				$verify = wp_verify_nonce( $_POST['_wpnonce-et-pb-contact-form-submitted-0'], 'custom-action' );
				
				if ( isset( $_POST['et_builder_submit_button'] ) ){

					foreach ($_POST as $pkey => $pvalue) {
						if (in_array($pkey, ['_edit_lock', 'et_pb_contactform_submit_0', 'et_builder_submit_button', '_wpnonce-et-pb-contact-form-submitted-0', '_wp_http_referer'])) {
							continue;
						}

						update_post_meta($post_id, $pkey, $pvalue);
						update_post_meta( $post_id, $pkey = 'lastUpdate', date('Y-m-d H:i:s'), $pvalue );
					}
					
					echo "<meta http-equiv='refresh' content='0'>";
					
				};



				// Pasword Protected
				if(isset($_POST['submit_pass']) && $_POST['pass']){

					$pass=$_POST['pass'];
					// echo "<pre>"; print_r($generatePassword);  echo "</pre>";wp_die();
					
					if($pass == $generatePassword){
					  $_SESSION['password'] = $pass;
					} 
					else {
					  $error="Incorrect Password";
					}
				}
				if(isset($_POST['page_logout']))
				{

				 unset($_SESSION['password']);
				}

				//====  HTML CODE STARTED HERE  ====///

				if($_SESSION['password'] == $generatePassword){
				?>
					

				<div class="emp_contact">
					<form class="emp_form_contact clearfix" method="post" action="">
						<?php
						$isOdd = (count(array_keys(get_post_meta($post_id)))-1)%2;
						$lastElement = array_key_last(get_post_meta($post_id));


						foreach (get_post_meta($post_id) as $key => $value) {
							$isReadOnly = in_array($key, ['LastUpdate', 'ProjID']) || stripos($key, 'Child') === 0;
							$fullClass = ($isOdd and $lastElement == $key) ? 'emp_form_contact_field_full' : 'emp_form_contact_field_half';
							if (in_array($key, ['Password'])) {
								continue;
							}
						?>

						<p class="emp_form_contact_field <?php echo $fullClass; ?>" data-id="<?php echo $key; ?>" data-type="email">
							<label for="et_pb_contact_name_0" class="emp_label"><?php echo str_replace('_', ' ', $key); ?></label>
							<input type="text" id="et_pb_contact_name_0" class="input" value="<?php echo $value[0]; ?>" name="<?php echo $key; ?>" data-required_mark="required" data-field_type="email" data-original_id="<?php echo $key; ?>" <?php echo ($isReadOnly) ? 'readOnly' : ''; ?>>
						</p>

						<?php
						}

						?>
						
						<input type="hidden" value="et_contact_proccess" name="et_pb_contactform_submit_0">
						<div class="et_contact_bottom_container">
							<button type="submit" name="et_builder_submit_button" class="et_pb_contact_submit et_pb_button" data-icon="">Update</button>
						</div>
						<input type="hidden" id="_wpnonce-et-pb-contact-form-submitted-0" name="_wpnonce-et-pb-contact-form-submitted-0" value="<?php echo $nonce ?>"><input type="hidden" name="_wp_http_referer" value="/contact/">
					</form>
				</div>
					<form method="post" action="" id="logout_form" class="emp_condition_form">
						<input type="submit" name="page_logout" value="LOGOUT" class="emp_button">
					</form>
				<?php
				}
				else{
				?>
					<form method="post" action="" id="login_form" class="emp_condition_form">
						<h1>Enter Password to View Profile</h1>
						<input type="password" name="pass" placeholder="*******" class="inputField">
						<input type="hidden" id="emp_generate_password" class="input" value="<?= $generatePassword; ?>" name="generate_password" data-required_mark="required" data-field_type="email" data-original_id="email" >
						<input type="submit" name="submit_pass" value="SUBMIT" class="emp_button">
						<p><font style="color:red;"><?php echo $error;?></font></p>
					</form>
				<?php	
				}
				
				?>
			
		</div>
	</div>
</div>
<?php

get_footer();