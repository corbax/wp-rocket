<?php
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

if ( class_exists( 'BuddyPress' ) ) :
	/**
	 * Conflict with BuddyPress: don't apply LazyLoad on BuddyPress profil pages & group creation
	 *
	 * @since 2.8.15 Improve checks for user profile & disable for cover images
	 * @since 2.6.9
	 */
	if ( function_exists( 'bp_is_user_profile' ) ) :
		/**
		 * Prevent lazyload if on a BuddyPress profil page
		 *
		 * @param Bool $run_filter Do lazyload value.
		 * @return Bool False if on a BuddyPress profil page, initial value otherwise
		 */
		function rocket_deactivate_lazyload_on_buddypress_profil_pages( $run_filter ) {
			if ( bp_is_user_profile() ) {
				return false;
			}

			return $run_filter;
		}
		add_filter( 'do_rocket_lazyload', 'rocket_deactivate_lazyload_on_buddypress_profil_pages' );
		add_filter( 'do_rocket_lazyload_iframes', 'rocket_deactivate_lazyload_on_buddypress_profil_pages' );
	endif;

	if ( function_exists( 'bp_is_group_creation_step' ) && function_exists( 'bp_is_group_admin_screen' ) ) :
		/**
		 * Prevent lazyload if on a BuddyPress group creation page
		 *
		 * @param Bool $run_filter Do lazyload value.
		 * @return Bool False if on a BuddyPress profil page, initial value otherwise
		 */
		function rocket_deactivate_lazyload_on_buddypress_group_pages( $run_filter ) {
			if ( bp_is_group_creation_step( 'group-avatar' ) || bp_is_group_creation_step( 'group-cover-image' ) || bp_is_group_admin_screen( 'group-avatar' ) || bp_is_group_admin_screen( 'group-cover-image' ) ) {
				return false;
			}

			return $run_filter;
		}
		add_filter( 'do_rocket_lazyload', 'rocket_deactivate_lazyload_on_buddypress_group_pages' );
		add_filter( 'do_rocket_lazyload_iframes', 'rocket_deactivate_lazyload_on_buddypress_group_pages' );
	endif;

	/**
	 * Excludes BuddyPress's plupload from JS minification
	 *
	 * Exclude it to prevent an error after minification/concatenation preventing the image upload from working correctly
	 *
	 * @since 2.8.10
	 * @author Remy Perona
	 *
	 * @param Array $excluded_handle An array of JS handles enqueued in WordPress.
	 * @return Array the updated array of handles
	 */
	function rocket_exclude_js_buddypress( $excluded_handle ) {
		$excluded_handle[] = 'bp-plupload';

		return $excluded_handle;
	}
	add_filter( 'rocket_excluded_handle_js', 'rocket_exclude_js_buddypress' );
endif;
