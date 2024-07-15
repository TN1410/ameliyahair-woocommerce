<?php
/**
 * Admin Settings Page Class.
 *
 * @package Woosuite_Core
 * @class Woosuite_Core_Admin_Modules_Page
 */

class Woosuite_Core_Admin_Dashboard_Page {

    private $report_data;
    private $widget_data;
    private $pending_users;
    private $cache_user_roles;

    private $woosuite_core_report;
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 5 );
		add_action( 'wp_ajax_woosuite_update_chart_report', array( $this, 'update_chart_report' ) );
		add_action( 'wp_ajax_woosuite_update_user_roles', array( $this, 'update_user_roles' ) );
		add_action( 'wp_ajax_woosuite_report_b2b_customer', array( $this, 'report_b2b_customer' ) );
		add_action( 'wp_ajax_woosuite_report_pending_application', array( $this, 'report_pending_application' ) );
		add_action( 'wp_ajax_woosuite_report_new_quotes', array( $this, 'report_new_quotes' ) );
		add_action( 'wp_ajax_woosuite_report_recently_user_table', array( $this, 'report_recently_user_table' ) );
        $this->init_report_class();
	}

	public function report_new_quotes() {
		wp_send_json_success( [ 'value' => $this->get_number_of_quotes() ] );
	}

	public function report_recently_user_table() {
		ob_start();
		$this->build_recently_pending_users();
		$result = ob_get_clean();
		wp_send_json_success( [ 'value' => $result ] );
	}

	public function report_b2b_customer() {
		$newly_created_users_query = $this->get_newly_created_user();
		wp_send_json_success( [ 'value' => $this->get_number_of_b2b_customer( $newly_created_users_query ) ] );
	}

	public function report_pending_application() {
		$this->get_all_pending_users();
		wp_send_json_success( [ 'value' => $this->get_number_of_pending_users() ] );
	}

	private function init_report_class() {
		global $woocommerce;
		if ( empty( $woocommerce ) ) {
			return false;
		}
		include_once( $woocommerce->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
		$this->woosuite_core_report = new WC_Admin_Report();
	}

	/**
	 * Sanitize settings option
	 */
	public function admin_menu() {
		// Access capability.
		$access_cap = apply_filters( 'woosuite_core_admin_page_access_cap', 'manage_options' );

		// Register menu.
		$admin_page = add_submenu_page(
			'woosuite-core',
			__( 'Woosuite Dashboard', 'woosuite-core' ),
			__( 'Dashboard', 'woosuite-core' ),
			$access_cap,
			'woosuite-core',
			array( $this, 'render_page' )
		);

		add_action( "admin_print_styles-{$admin_page}", array( $this, 'print_scripts' ) );
	}

	public function render_page() {
		if ( class_exists( 'Woosuite_White_Label' ) && isset( $_GET['enable-whitelabel-submenu'] ) ) {
			$data = Woosuite_Core::get_whitelabel_data();
			if ( isset( $data['whitelabel-submenu-disabled'] ) ) {
				unset( $data['whitelabel-submenu-disabled'] );
			}
            update_option( 'woosuite_core_whitelabel_data', $data );
		}
        $this->process_user_approve_unapprove();
		?>

		<!-- render html -->

		<!-- reander html wrap -->
		<div class="opt-desh-wrap">
			<?php include __DIR__ . '/views/dashboard-header.php'; ?>


			<!-- dashboard body -->
			<div class="opt-desh-body-wrap">
				<div class="opt-desh-body">
					<div class="opt-desh-container">
						<?php include __DIR__ . '/views/dashboard-menu.php'; ?>

                        <div class="opt-desh-main-reporting-section">
							<?php
                            $this->build_widget();

							$this->build_recently_pending_users();
							?>
                        </div>

						<div class="opt-desh-main-content-wrap">
							<!-- dashboard main content -->
							<div class="opt-desh-main-content" <?php echo Woosuite_Core::get_content_style(); ?>>
								<div class="opt-desh-main-inner">
									<?php if ( empty( $this->widget_data ) ): ?>
                                        <!-- main content banner -->
                                        <div class="opt-main-content-banner">
                                            <h3 class="opt-banner-title"> <?php _e( 'Now let&apos;s customize your store', 'woosuite-core' ); ?></h3>
                                            <p><?php _e( 'Stand out. Sell more. Make more happy customers.', 'woosuite-core' ); ?></p>
                                        </div>
                                        <!-- main content banner -->
									<?php endif; ?>
									<!-- feature plugin section -->
									<div class="opt-feature-plugins-wrap">
										<div class="opt-feature-plugins-head">
											<h2 class="opt-pf-title"><?php _e('Active Plugins','woosuite-core');?></h2>
										</div>
										<div class="opt-features-plugins-grid">

											<?php
											$active_parent_plugins = 0;
											$all_plugins = woosuite_core_get_modules();
											$recommended_plugins = array();
											if ( is_array( $all_plugins ) ) {
												// Exclude core plugin to be displayed here.
                                                $all_plugins = wp_list_filter( $all_plugins, array( 'slug' => 'woosuite-core' ), 'NOT' );

                                                foreach ($all_plugins as $plugin) {
													if ( is_object( $plugin ) ) {
														$plugin = (array) $plugin;
													}
													if ( isset( $plugin['path']) && is_plugin_active( $plugin['path'] ) ) {
														$active_parent_plugins++;
														$plugin['icons'] = (array)$plugin['icons'];
														$plugin_icon = isset( $plugin['icons']['1x'] ) ? $plugin['icons']['1x'] : '';
														$recommended_plugins[] = maybe_unserialize( $plugin['recommended_plugins'] );
														$settings_url = woosuite_core_get_module_settings_url( $plugin['slug'] );
														?>
														<!-- feature plugin -->
														<div class="opt-feature-plugin active-plugin">
															<div class="opt-ft-inner-grid">
																<div class="opt-plugin-thumb">
																	<img src="<?php echo esc_url($plugin_icon); ?>" alt="" class="opt-plugin-img">
																</div>
																<div class="opt-plugin-content">
																	<div class="opt-plugin-content-head">
																		<h3 class="opt-plugin-title"><?php echo esc_html( $plugin['name'] ); ?></h3>
																		<span class="plugin-status active-status"><?php _e('Active', 'woosuite-core');?></span>
																	</div>
																	<div class="opt-plugin-text-content">
																		<p><?php echo $plugin['short_description']; ?></p>
																	</div>
																	<div class="opt-plugin-links opt-active-plugin-link">
																		<a href="<?php echo $settings_url; ?>" class="opt-plugin-link"><?php _e('Settings','woosuite-core');?></a>
																		<a href="https://aovup.com/docs/?ref=active-plugin" class="opt-plugin-link"><?php _e('Docs','woosuite-core');?></a>
																	</div>
																</div>
															</div>
														</div>
														<!-- feature plugin -->
														<?php
													}
												}
											}


											// Print all recommended plugins
											$already_showed_plugins = array();
											foreach ( $recommended_plugins as $total_plugins ) {
												$total_plugins = maybe_unserialize( $total_plugins );
												foreach ( $total_plugins as $recommended_plugin ) {
													if ( ! isset( $recommended_plugin['plugin'] ) || in_array( $recommended_plugin['plugin'], $already_showed_plugins ) ) {
														continue;
													}

													$recommended_plugin_slug = $recommended_plugin['plugin'];
													$plugin_data =  array_filter( $all_plugins, function( $item ) use ( $recommended_plugin_slug ) {
														if ( $item->slug == $recommended_plugin_slug ) {
															return $item;
														}
													} );

													if ( $plugin_data ) {
														sort( $plugin_data );
														$plugin_data = (array) $plugin_data[0];
														if ( isset( $plugin_data['path'] ) && !is_plugin_active( $plugin_data['path'] )) {

															$already_showed_plugins[] = $recommended_plugin['plugin'];

															$title       = ! empty( $recommended_plugin['plugin']['title'] ) ? $recommended_plugin['plugin']['title'] : $plugin_data['name'];
															$description = ! empty( $recommended_plugin['plugin']['description'] ) ? $recommended_plugin['plugin']['description'] : $plugin_data['short_description'];
															$image_link  = ! empty( $recommended_plugin['plugin']['image_link'] ) ? $recommended_plugin['plugin']['image_link'] : $plugin_data['icons']->{'1x'};
															$link        = ! empty( $recommended_plugin['plugin']['link'] ) ? $recommended_plugin['plugin']['link'] : $plugin_data['homepage'];

															?>
															<!-- feature plugin -->
															<div class="opt-feature-plugin">
																<div class="opt-ft-inner-grid">
																	<div class="opt-plugin-thumb">
																		<img src="<?php echo esc_url( $image_link ); ?>" alt="" class="opt-plugin-img">
																	</div>
																	<div class="opt-plugin-content">
																		<div class="opt-plugin-content-head">
																			<h3 class="opt-plugin-title">
																				<?php echo esc_html( $title ); ?>
																			</h3>
																			<span class="plugin-status"><?php _e( 'Recommended', 'woosuite-core' );?></span>
																		</div>
																		<div class="opt-plugin-text-content">
																			<p>
																				<?php echo $description; ?>
																			</p>
																		</div>
																		<div class="opt-plugin-links">
																			<a target="_blank" href="<?php echo esc_url( $link ); ?>" class="opt-plugin-link update-clicks" data-slug="<?php echo $recommended_plugin_slug; ?>">
																				<?php _e( 'Download' ,'woosuite-core' );?>
																			</a>
																		</div>
																	</div>
																</div>
															</div>
															<!-- feature plugin -->
															<?php
														}
													}

												}
											}


											// Display no plugin if no plugin is active
											if ( $active_parent_plugins == 0 ) {
												include __DIR__ . '/views/dashboard-no-plugins.php';
											}
											else {
												update_option( 'woosuite_core_addon_installed_once', true );
											}
											?>

										</div>
										<div class="oop-feature-plugin-space"></div>
									</div>
									<!-- feature plugin section -->
								</div>
							</div>
							<!-- dashboard main content -->

							<?php include __DIR__ . '/views/dashboard-sidebar.php'; ?>
						</div>

						<?php include __DIR__ . '/views/dashboard-footer.php'; ?>
					</div>

				</div>
			</div>

			<!-- dashboard body -->
		</div>
		<!-- reander html wrap -->





		<!-- render html -->

		<?php
	}

	public function print_scripts() {
		do_action( 'woosuite_core_admin_page_scripts' );
	}

	/**
	 * @return array
	 */
	public function get_widget_data() {
		$data = array();
		if ( $this->is_wholesale_plugin_activated() ) {
			$data[0] = array(
				'title'     => __( 'B2B Customers', 'woosuite-core' ),
				'desc'      => __( 'Since the start of the current month', 'woosuite-core' ),
				'value'     => __( 'Loading...', 'woosuite-core' ),
				'url'       => '',
				'filter_by' => 'user_role'
			);
			$data[1] = array(
				'title' => __( 'Pending Application', 'woosuite-core' ),
				'desc'  => __( 'All Pending Registration', 'woosuite-core' ),
				'value' => __( 'Loading...', 'woosuite-core' ),
				'url'   => '#woosuite-reporting-pending-user'
			);

			$data[3] = array(
				'title' => 'B2B Revenue',
				'desc'  => '',
				'value' => __( 'Loading...', 'woosuite-core' ),
				'url'   => ''
			);
		}

		if ( $this->is_request_a_quotes_activated() ) {
			$data[2] = array(
				'title' => 'New Quotes',
				'desc' => 'Over the last thirty calendar days',
				'value' => __( 'Loading...', 'woosuite-core' ),
				'url' => admin_url( 'edit.php?post_status=pending&post_type=of_quote' )
			);
		}

		if ( ! empty( $data ) ) {
			ksort( $data );
		}

		return $data;
	}

	/**
	 * @param WP_User_Query $user_query
	 *
	 * @return int
	 */
	private function get_number_of_b2b_customer( WP_User_Query $user_query ) {
		return $user_query->get_total();
	}

	/**
	 * @return WP_User_Query
	 */
	private function get_newly_created_user() {
		$applied_roles =  $this->get_applied_user_roles();
		$user_args = array(
			'role__in' => $applied_roles,
			'date_query' => array(
				array( 'after' => 'first day of this month', 'inclusive' => true )
			)
		);

		return new WP_User_Query( $user_args );
	}

	/**
	 * @return int
	 */
	private function get_number_of_quotes() {
		$quote_args = array(
			'post_type' => 'of_quote',
			'numberposts' => - 1,
			'date_query' => array(
				array(
					'after' => 'first day of previous month',
					'inclusive' => true
				)
			),
			'fields' => 'ids',
			'post_status' => array( 'pending', 'waiting' )

		);

		return count( get_posts( $quote_args ) );
	}

	/**
	 * @param $date_range
	 *
	 * @return array
	 */
	private function get_revenue_in_date_range() {
		global $wpdb;

		$this->add_filter_report_query();

		// Set date parameters for the current month
		$this->woosuite_core_report->calculate_current_range( $this->get_chart_date_range() );

		// Avoid max join size error
		$wpdb->query( 'SET SQL_BIG_SELECTS=1' );

		$raw_data= (array) $this->woosuite_core_report->get_order_report_data(
			array(
				'data' => array(
					'_order_total' => array(
						'type' => 'meta',
						'function' => 'SUM',
						'name' => 'total_sales',
					)
				),
				'group_by' => $this->woosuite_core_report->group_by_query,
				'order_by' => 'post_date ASC',
				'query_type' => 'get_results',
				'filter_range' => 'month',
				'order_types' => wc_get_order_types( 'sales-reports' ),
				'order_status' => array( 'completed', 'processing', 'on-hold', 'refunded' ),
			)
		);

        //Convert the class into array
		$raw_data = array_map( function ( $value ) {
			return (array) $value;
		}, $raw_data );

		$this->remove_filter_report_query();
        return $this->remove_order_not_belong_to_selected_user_roles($raw_data);
	}

	public function query_customer_for_report( $query ) {
		global $wpdb;
		$query['select'] .= ", meta__customer.meta_value customer_id, usermeta.meta_value user_roles";
		$query['join']   .= $wpdb->prepare( " INNER JOIN $wpdb->postmeta AS meta__customer ON (posts.ID = meta__customer.post_id
				AND meta__customer.meta_key = %s)
		LEFT JOIN $wpdb->usermeta AS usermeta ON (meta__customer.meta_value = usermeta.user_id
				AND usermeta.meta_key = %s)", '_customer_user', 'wp_capabilities' );

		return $query;
	}

	/**
	 * @return float|int
	 */
	private function get_total_sales() {
		if ( empty( $this->report_data ) ) {
			return 0;
		}
		$total_sales = 0;
		foreach ( $this->report_data as $report_item ) {
			$total_sales += floatval( $report_item['total_sales'] );
		}

		return round( $total_sales, 2 );
	}

	/**
	 * @return array
	 */
	private function get_chart_data() {
		$tmp_chart_data= $this->prepare_chart_data( $this->report_data, 'post_date', 'total_sales', $this->woosuite_core_report->chart_interval, $this->woosuite_core_report->start_date, $this->woosuite_core_report->chart_groupby );

		$chart_data = array();
		$result = array();

		foreach ( $tmp_chart_data as $user_role =>$chart_item){
            foreach ( $chart_item as $timestamp => $chart_item_data ) {
				$timestamp /= 1000;
				if ( 'month' === $this->woosuite_core_report->chart_groupby ) {
					$chart_data[$user_role][ date( 'F', $timestamp ) ] = $chart_item_data[1];
				} else {
					$chart_data[$user_role][ date( 'd M', $timestamp ) ] = $chart_item_data[1];
				}
			}
		}

		foreach ( $chart_data as $user_role => $chart_item_data ) {
			$color = $this->rand_color();
			$result[] = array(
				'label' => $user_role,
				'backgroundColor' => $color,
				'borderColor' => $color,
				'data' => $chart_item_data,
			);
		}
		return $result;
    }

	/**
	 * @return string[]
	 */
	private function get_applied_user_roles() {
		return get_option( 'woosuite_core_widget_b2b_applied_role', array_diff( $this->get_all_user_roles(), $this->get_excluded_user_roles() ) );
	}

	/**
	 * @return string[]
	 */
	private function set_applied_user_roles( $user_roles ) {
		update_option( 'woosuite_core_widget_b2b_applied_role', $user_roles );
	}

	private function get_chart_date_range() {
		return get_option( 'woosuite_core_widget_b2b_applied_date_range', 'year' );
	}

	private function set_chart_date_range( $date_range = 'year' ) {
		return update_option( 'woosuite_core_widget_b2b_applied_date_range', $date_range );
	}

	/**
	 * @return string[]
	 */
	private function get_all_user_roles() {

		global $wp_roles;
		if ( ! $wp_roles instanceof WP_Roles ) {
			return array();
		}

		return array_merge( array_keys( $wp_roles->roles ), array( 'unregistered' ) );
	}

	/**
	 * @return string[]
	 */
	private function get_excluded_user_roles() {
		return array( 'customer', 'shop_manager' );
	}

	/**
	 * @return bool
	 */
	private function is_wholesale_plugin_activated() {
		return woosuite_check_module_active_by_path( 'woosuite-wholesale-pricing/woosuite-wholesale-pricing.php' );
	}

	/**
	 * @return bool
	 */
	private function is_request_a_quotes_activated() {
		return woosuite_check_module_active_by_path( 'woosuite_request_a_quote/of-request-a-quote.php' );
	}

	/**
	 * @return bool
	 */
	private function is_user_registration_activated() {
		return woosuite_check_module_active_by_path( 'woosuite-user-registration-for-woocommerce/woosuite-user-registration-for-woocommerce.php' );
	}

	/**
	 * @return void
	 */
	public function update_chart_report() {
		$data = $_POST;
		if ( ! isset( $data['range'] ) ) {
			wp_send_json_error( array(
				'message' => __( 'Invalid date range', 'woosuite-core' )
			) );
		}
        $this->set_chart_date_range(sanitize_text_field( $data['range'] ));
		$this->report_data = $this->get_revenue_in_date_range( );

		wp_send_json_success( array(
			'chartData' => $this->get_chart_data(),
			'totalRevenue' => wc_price( $this->get_total_sales() )
		) );

	}

	public function update_user_roles() {
		$data = $_POST;
		if ( ! isset( $data['selected_user_roles'] ) || empty( $data['selected_user_roles'] ) ) {
			wp_send_json_error( array(
				'message' => __( 'Invalid user roles', 'woosuite-core' )
			) );
		}

		$this->set_applied_user_roles( $data['selected_user_roles'] );

		$this->set_chart_date_range(sanitize_text_field( $data['range'] ));

		$this->report_data = $this->get_revenue_in_date_range();

		$newly_created_users_query = $this->get_newly_created_user();

		wp_send_json_success( array(
			'b2bCustomer' => $this->get_number_of_b2b_customer( $newly_created_users_query ),
			'b2bRevenue' => wc_price( $this->get_total_sales() ),
			'chartData' => $this->get_chart_data(),
		) );
	}

	private function build_recently_pending_users() {
		$show_load_more = false;
		if ( wp_doing_ajax() ) {
			if ( empty( $this->pending_users ) ) {
				$this->get_all_pending_users();
			}
			$pending_users = $this->pending_users;

			if ( ! empty( $pending_users ) ) {
				foreach ( $pending_users as $index => $pending_user_obj ) {
					$pending_users[ $index ]->approve_link   = $this->generate_user_approval_link( $pending_user_obj->ID );
					$pending_users[ $index ]->unapprove_link = $this->generate_user_unapproval_link( $pending_user_obj->ID );
				}

				if ( count( $pending_users ) > 5 ) {
					$show_load_more = true;
				}
			}
		} else {
			$pending_users  = array();
			$show_load_more = false;
		}
		include __DIR__ . '/views/reporting-pending-user-table.php';
	}

	/**
	 * @return void
	 */
	private function get_all_pending_users() {
		global $wpdb;
		if ( ! $this->is_user_registration_activated() ) {
			$this->pending_users = array();
		}

		$this->pending_users = $wpdb->get_results(
			"SELECT
                            usr.*
                        FROM
                            $wpdb->users AS usr
                            INNER JOIN $wpdb->usermeta AS usrmeta ON usr.ID = usrmeta.user_id
                        WHERE
                            usrmeta.meta_key = 'afreg_new_user_status'
                            AND usrmeta.meta_value = 'pending'
                        ORDER BY
                            usr.ID DESC
                        LIMIT 0, 10"
		);
	}

	/**
	 * @return int
	 */
	private function get_number_of_pending_users() {
		return count( $this->pending_users );
	}

	/**
	 * @param $query
	 *
	 * @return array
	 */
    public function modify_report_query($query){
	    $query['select']='SELECT posts.ID,posts.post_date, meta__order_total.meta_value total_sales';
        $query['group_by']='';
        return $query;
    }

	/**
	 * @param $report_data
	 *
	 * @return array|mixed
	 */
	private function remove_order_not_belong_to_selected_user_roles( $report_data ) {
		if ( empty( $report_data ) ) {
			return array();
		}

		$available_user_roles = $this->get_applied_user_roles();

		$key = array_keys($report_data);
		$size = sizeOf($key);
		for ($i=0; $i<$size; $i++){
			if ( ! $this->is_order_belong_to_selected_user_roles( $report_data, $i, $available_user_roles ) ) {
				unset( $report_data[ $key[$i] ] );
			}
		}
		return $report_data;
	}

	/**
	 * @param $report_data
	 * @param $index
	 * @param $order_id
	 * @param $available_user_roles
	 *
	 * @return bool
	 */
	private function is_order_belong_to_selected_user_roles( &$report_data, $index, $available_user_roles ) {
		$report_item = $report_data[ $index ];

		$customer_id = (int)$report_item['customer_id'];

		if ( ( empty( $customer_id ) || $customer_id < 1 ) ) {
			if ( in_array( 'unregistered', $available_user_roles ) ) {
				$report_data[ $index ]['roles'] = 'unregistered';

				return true;
			}

			return false;
		}
		if ( ! isset( $this->cache_user_roles[ $customer_id ] ) ) {
			$user_roles                             = unserialize( $report_item['user_roles'] );
			$user_roles                             = is_array( $user_roles ) ? array_keys( $user_roles ) : array();
			$this->cache_user_roles[ $customer_id ] = $user_roles;
		}
		$report_data[ $index ]['roles'] = $this->cache_user_roles[ $customer_id ][0];

		return ! empty( array_intersect( $this->cache_user_roles[ $customer_id ], $available_user_roles ) );
	}

	/**
	 * Put data with post_date's into an array of times.
	 *
	 * @param  array  $data array of your data
	 * @param  string $date_key key for the 'date' field. e.g. 'post_date'
	 * @param  string $data_key key for the data you are charting
	 * @param  int    $interval
	 * @param  string $start_date
	 * @param  string $group_by
	 * @return array
	 */
	private function prepare_chart_data( $data, $date_key, $data_key, $interval, $start_date, $group_by ) {
		$prepared_data = array();

		$applied_user_roles = $this->get_applied_user_roles();

		if ( empty( $applied_user_roles ) ) {
			return $prepared_data;
		}

		foreach ( $applied_user_roles as $user_role ) {
			$prepared_data[ $user_role ] = array();
		}

		//Group the Data by user roles
		$grouped_data_by_user_role = array();
		foreach ( $data as $d ) {
			$grouped_data_by_user_role[ $d['roles'] ][] = $d;
		}

		// Ensure all days (or months) have values in this range.
		if ( 'day' === $group_by ) {
			foreach ( $applied_user_roles as $user_role ) {
				for ( $i = 0; $i <= $interval; $i ++ ) {
					$time = strtotime( date( 'Ymd', strtotime( "+{$i} DAY", $start_date ) ) ) . '000';

					if ( ! isset( $prepared_data[ $user_role ][ $time ] ) ) {
						$prepared_data[ $user_role ][ $time ] = array( esc_js( $time ), 0 );
					}
				}
			}

		} else {
			foreach ( $applied_user_roles as $user_role ) {
				$current_yearnum = date( 'Y', $start_date );
				$current_monthnum = date( 'm', $start_date );
				for ( $i = 0; $i <= $interval; $i ++ ) {
					$time = strtotime( $current_yearnum . str_pad( $current_monthnum, 2, '0', STR_PAD_LEFT ) . '01' ) . '000';

                    if ( ! isset( $prepared_data[ $user_role ][ $time ] ) ) {
						$prepared_data[ $user_role ][ $time ] = array( esc_js( $time ), 0 );
					}

					$current_monthnum ++;

					if ( $current_monthnum > 12 ) {
						$current_monthnum = 1;
						$current_yearnum ++;
					}
				}
			}
		}
		foreach ( $grouped_data_by_user_role as $user_role => $list_orders ) {
			foreach ( $list_orders as $d ) {
				switch ( $group_by ) {
					case 'day':
						$time = strtotime( date( 'Ymd', strtotime( $d[ $date_key ] ) ) ) . '000';
						break;
					case 'month':
					default:
						$time = strtotime( date( 'Ym', strtotime( $d[ $date_key ] ) ) . '01' ) . '000';
						break;
				}

				if ( ! isset( $prepared_data[ $user_role ][ $time ] ) ) {
					continue;
				}
				if ( $data_key ) {
					$prepared_data[ $user_role ][ $time ][1] += $d[ $data_key ];
				} else {
					$prepared_data[ $user_role ][ $time ][1] ++;
				}
			}
		}

		return $prepared_data;
	}

    private function rand_color() {
	    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    private function add_filter_report_query(){
	    add_filter( 'woocommerce_reports_get_order_report_query', array(
		    $this,
		    'modify_report_query'
	    ) );
	    add_filter( 'woocommerce_reports_get_order_report_query', array( $this, 'query_customer_for_report' ) );
    }

	private function remove_filter_report_query(){
		remove_filter( 'woocommerce_reports_get_order_report_query', array(
			$this,
			'modify_report_query'
		) );

		remove_filter( 'woocommerce_reports_get_order_report_query', array( $this, 'query_customer_for_report' ) );
	}

	private function generate_user_approval_link( $user_id ) {
		$link = add_query_arg(
			array(
				'action' => 'approved',
				'user' => $user_id,
			)
		);
		$link = remove_query_arg( array( 'new_role' ), $link );

		return wp_nonce_url( $link, 'woosuite-afreg-fields' );
	}

	private function generate_user_unapproval_link( $user_id ) {
		$link = add_query_arg(
			array(
				'action' => 'disapproved',
				'user' => $user_id,
			)
		);
		$link = remove_query_arg( array( 'new_role' ), $link );

		return wp_nonce_url( $link, 'woosuite-afreg-fields' );
	}

	private function process_user_approve_unapprove() {
		if ( isset( $_GET['action'] ) &&
		     in_array( $_GET['action'], array( 'approved', 'disapproved' ) ) &&
		     ! isset( $_GET['new_role'] ) &&
		     class_exists( 'AFREG_Admin_Users' ) &&
		     method_exists( 'AFREG_Admin_Users', 'afreg_approve_unapprove_user' )) {

			wp_redirect( AFREG_Admin_Users::afreg_approve_unapprove_user() );
			exit;

		}
	}

    private function build_widget(){
	    $this->widget_data = $this->get_widget_data();
	    $widget_data = $this->widget_data;

	    $all_user_roles = $this->get_all_user_roles();
	    $available_user_roles = $this->get_applied_user_roles();
	    $selected_date_range = $this->get_chart_date_range();
	    $is_wholesale_plugin_activated = $this->is_wholesale_plugin_activated();

	    include __DIR__ . '/views/reporting.php';
    }
}
