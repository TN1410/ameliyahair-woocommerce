<div class="row ">
	<?php if ( ! empty( $widget_data ) ): ?>
		<?php foreach ( $widget_data as $widget ): ?>
            <div class="col-xs-12 col-lg-6 col-xl-4 woosuite-widget"
                 id="woosuite-widget-<?php echo sanitize_title( $widget['title'] ); ?>">
                <div class="admin-card scrollable-table-wrap">
                    <div class="admin-card-header">
                        <h4 class="admin-card-title"><?php echo $widget['title']; ?></h4>
						<?php if ( isset($widget['filter_by']) && 'user_role' === $widget['filter_by'] ): ?>
                            <span class="dashicons dashicons-filter user-roles-filter-icon"></span>
                            <div class="woosuite-user-roles-filter" style="display:none";>
                                <span class="dashicons dashicons-exit user-roles-filter-icon-close"></span>
								<?php foreach ( $all_user_roles as $user_role ): ?>
                                    <label for="role-<?php echo $user_role; ?>">
                                        <input type="checkbox" name="user-roles-filter[]"
                                               id="role-<?php echo $user_role; ?>"
                                               value="<?php echo $user_role; ?>" <?php checked( in_array( $user_role, $available_user_roles ) ); ?>>
                                        <?php echo $user_role; ?>
                                    </label>
								<?php endforeach; ?>
                                <button class="thd-button button" id="user-role-filter-apply"><?php _e('Apply','woosuite-core'); ?></button>
                            </div>
						<?php endif; ?>
                    </div>
                    <div class="admin-card-content" style="text-align:center;">
                        <h1 class="card-stat" style="display:inline-block;vertical-align:middle;">
							<?php if ( ! empty( $widget['url'] ) ): ?>
                                <a href="<?php echo $widget['url']; ?>"
                                   class="card-value"><?php echo $widget['value']; ?></a>
							<?php else: ?>
                                <span class="card-value"><?php echo $widget['value']; ?></span>
							<?php endif; ?>
                        </h1>
                        <p class="stat-desc"><?php echo $widget['desc']; ?></p>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<?php if ( $is_wholesale_plugin_activated ): ?>
    <div class="woosuite-chart-container">
        <div class="woosuite-chart-filter">
            <select id="ws-filter-date-range">
                <option value="7day" <?php selected( '7day' === $selected_date_range ); ?>><?php _e( 'Last 7 days', 'woosuite-core' ); ?></option>
                <option value="last_month" <?php selected( 'last_month' === $selected_date_range ); ?>><?php _e( 'Last month', 'woosuite-core' ); ?></option>
                <option value="month" <?php selected( 'month' === $selected_date_range ); ?>><?php _e( 'This month', 'woosuite-core' ); ?></option>
                <option value="year" <?php selected( 'year' === $selected_date_range ); ?>><?php _e( 'This year', 'woosuite-core' ); ?></option>
            </select>
        </div>
        <canvas id="woosuite-core-chart"></canvas>
    </div>
<?php endif; ?>