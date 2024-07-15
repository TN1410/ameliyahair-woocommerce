<?php 
$saved_array = get_option( '_optimizeform_core_new_license', '' );
$new_licenses = !empty( $saved_array ) ? $saved_array : array();
if (!empty($new_licenses)) {
	foreach ($new_licenses as $license_key => $license) { 

		$license_messages = array();

		// DEBUG license data.
		if ( ! empty( $license_key ) ) {
			$data = '';
			$data = woosuite_core_api_new_license_data( $license_key );
			
			if ( is_wp_error( $data ) ) {
				$license_heading = __( 'Api Error', 'woosuite-core' );
				if ( 'rest_no_route' === $data->get_error_code() ) {
					$license_messages[] = __( 'Woosuite API server is unreachable right now. Should be back soon.', 'woosuite-core' );
				} else {
					$license_messages[] = sprintf(
						__( 'Error: %s.' ),
						$data->get_error_message()
					);
				}
			}
			else if ( !is_object($data) ) {
				$license_heading = __( 'Api Error', 'woosuite-core' );
				$license_messages[] = __( 'Something went wrong.', 'woosuite-core' );
			}
			else {

				$license_heading = __( 'License Information', 'woosuite-core' );

				# $data->status = 'suspended';

				if ( $data->status === 'active' ) {
					$license_heading = __( 'License status: Active', 'woosuite-core' );


					$license_messages[] = sprintf(
						__( 'This license will be expired on %s.', 'woosuite-core' ),
						mysql2date( 'dS M Y', $data->date_active_through )
					);

					$license_messages[] = sprintf(
						__( 'You have used it on %d sites out of allocated %d sites.', 'woosuite-core' ),
						$data->installs_active,
						$data->installs_allowed
					);

				} elseif ( $data->status === 'expired' ) {
					$license_heading = 'License Expired';

					$license_messages[] = __( 'Automatic updates has been disabled for all of our Woosuite plugins.' );
					$license_messages[] = sprintf(
						__( '<a href="%s">Visit here</a> to renew your license.' ),
						'https://aovup.com/my-account'
					);

				} elseif ( $data->status === 'onhold' ) {
					$license_heading = __( 'License On-hold' );

					$license_messages[] = __( 'Our team is reviewing your license. Till then, you will not receive automatic updates..' );
					$license_messages[] = sprintf(
						__( '<a href="%s">Contact us</a> if the issue is taking longer than expected.' ),
						'https://aovup.com/support'
					);

				} else {
					$license_heading = __( 'License Suspended', 'woosuite-core' );

					$license_messages[] = sprintf(
						__( 'Possible reason: %s' ),
						$data->status_note ? $data->status_note : __( 'Nothing', 'woosuite-core' )
					);
				}
			}

			if ( ! empty( $license_messages ) && is_array( $license_messages ) ) {
				
				echo '<div class="woosuite-core-info-box">';
					echo '<h2>' . $license_heading . '</h2>';
					echo '<ul><li>';
					echo join( '</li><li>',$license_messages );
					echo '</li></ul>';
				echo '</div>';
			}
		}
		
			?>
		<div class="woosuite-core-box">
		<form method="post" action="">
			<table class="form-table" role="presentation">
				<tbody>
						<tr>
							<th scope="row">
								<label for="license_key"><?php _e( 'License Key', 'woosuite-core' ); ?></label>
							</th>
							<td>
								<input name="new_license_key" type="text" value="<?php echo esc_attr( $license_key ); ?>" class="regular-text"  disabled/>
								<p class="submit">
									<button type="submit" name="action" value="woosuite_core_remove_new_license" class="button button-primary deactivate_new_license"><?php _e( 'Deactivate License', 'woosuite-core' ); ?></button>
								</p>
							</td>
						</tr>
				</tbody>
			</table>
		</form>
		</div>
	<?php }
} ?>