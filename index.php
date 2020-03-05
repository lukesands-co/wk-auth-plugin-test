<?php
/**
 * Plugin Name: Auth test
 * Plugin URI: http://www.cabinetoffice.gov.uk/wp
 * Description: Creates two virtual endpoints for login and authentication
 * Author: Luke Sands
 * Version: 1.0
 */

require_once plugin_dir_path( __FILE__ ) . 'auth.php';

add_filter( 'generate_rewrite_rules', function ( $wp_rewrite ){
    $wp_rewrite->rules = array_merge(
        ['auth/?$' => 'auth.php?action=auth&token=1&user=1&time=1'],
        ['login/?$' => 'auth.php?action=login&user=1'],
        $wp_rewrite->rules
    );
    return $wp_rewrite;
} );

add_filter( 'query_vars', function( $query_vars ){
    $query_vars[] = 'token';
    $query_vars[] = 'user';
    $query_vars[] = 'time';
    $query_vars[] = 'action';
    return $query_vars;
} );


add_action( 'template_redirect', function(){
    $action = get_query_var('action');

    if ($action === 'login') {
    	$result = AuthManager::login();
    	if ($result) {
    		// redirect to token sent confirmation
    	} else {
    		// redirect to error
    	}
    }

    if ($action === 'auth') {
    	$result = AuthManager::auth();
    	if ($result) {
    		// set auth cookie and redirect to home
    		wp_set_auth_cookie(1, 1, is_ssl());
        	wp_redirect('sample-page');
			exit();
		} else {
			// Sign out
			// Redirect to login
		}
    }
} );