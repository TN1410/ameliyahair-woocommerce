<div class="woosuite-reporting-pending-user" id="woosuite-reporting-pending-user">
    <h2><?php _e( 'Recent customer signups', 'woosuite-core' ); ?></h2>
	<?php if ( ! empty( $pending_users ) ): ?>
        <table>
            <thead>
            <tr>
                <th><?php _e( 'Username', 'woosuite-core' ); ?></th>
                <th><?php _e( 'Name', 'woosuite-core' ); ?></th>
                <th><?php _e( 'Email', 'woosuite-core' ); ?></th>
                <th><?php _e( 'Role', 'woosuite-core' ); ?></th>
                <th><?php _e( 'Date', 'woosuite-core' ); ?></th>
                <th><?php _e( 'Status', 'woosuite-core' ); ?></th>
                <th><?php _e( 'Action', 'woosuite-core' ); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			foreach ( $pending_users as $user_data ):
				$user_meta = get_userdata( $user_data->ID );
				?>
                <tr>
                    <td>
                        <a href="<?php echo woosuite_core_get_admin_edit_user_link( $user_data->ID ); ?>"
                           title="<?php echo $user_data->display_name; ?>">
							<?php echo $user_data->user_login; ?>
                        </a>
                    </td>
                    <td><?php echo $user_data->display_name; ?></td>
                    <td><?php echo $user_data->user_email; ?></td>
                    <td><?php echo implode( ', ', $user_meta->roles ); ?></td>
                    <td><?php echo $user_data->user_registered; ?></td>
                    <td><?php _e( 'Pending', 'woosuite-core' ); ?></td>
                    <td>
                        <a href="<?php echo esc_url( $user_data->approve_link ); ?>">
							<?php echo esc_html__( 'Approve', 'woosuite-core' ); ?>
                        </a>
                        |
                        <a href="<?php echo esc_url( $user_data->unapprove_link ); ?>">
							<?php echo esc_html__( 'Disapprove', 'woosuite-core' ); ?>
                        </a>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
		<?php if ( $show_load_more ): ?>
            <div class="action-button">
                <a class="button btn-blue link-external" target="_blank"
                   href="<?php echo esc_url( admin_url( 'users.php?afreg_approve_new_user_filter-top=pending' ) ) ?>">
					<?php echo esc_html__( 'See more', 'woosuite-core' ); ?>
                </a>
            </div>
		<?php endif; ?>
	<?php else: ?>
        <h4><?php _e( 'Loading...', 'woosuite-core' ); ?></h4>
	<?php endif; ?>
</div>
