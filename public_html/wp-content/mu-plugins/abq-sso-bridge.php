<?php
/**
 * Plugin Name: ABQ SSO Bridge (Dicionário)
 * Description: Conecta o Dicionário ao SSO central do Abiquifi.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'abq_sso_bridge_central_base' ) ) {
	function abq_sso_bridge_central_base() {
		$base = defined( 'ABQ_SSO_CENTRAL_BASE' ) ? (string) ABQ_SSO_CENTRAL_BASE : 'https://abiquifi.questione.ai';
		$base = trim( $base );
		return rtrim( $base, '/' );
	}
}

if ( ! function_exists( 'abq_sso_bridge_current_path' ) ) {
	function abq_sso_bridge_current_path() {
		$path = wp_parse_url( (string) $_SERVER['REQUEST_URI'], PHP_URL_PATH );
		if ( ! is_string( $path ) ) {
			return '';
		}

		$path      = trim( $path, '/' );
		$home_path = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
		if ( $home_path !== '' ) {
			if ( $path === $home_path ) {
				$path = '';
			} elseif ( strpos( $path, $home_path . '/' ) === 0 ) {
				$path = substr( $path, strlen( $home_path ) + 1 );
			}
		}

		return strtolower( trim( $path, '/' ) );
	}
}

if ( ! function_exists( 'abq_sso_bridge_handle_legacy_routes' ) ) {
	function abq_sso_bridge_get_legacy_redirect_target() {
		$path     = abq_sso_bridge_current_path();
		$login    = array( 'log-in', 'login', 'entrar', 'entre-ou-cadastre-se' );
		$register = array( 'cadastro', 'cadastrar', 'register', 'registro' );

		$target_base = '';
		if ( in_array( $path, $login, true ) ) {
			$target_base = abq_sso_bridge_central_base() . '/login-unificado/';
		} elseif ( in_array( $path, $register, true ) ) {
			$target_base = abq_sso_bridge_central_base() . '/cadastro-unificado/';
		}

		if ( $target_base === '' ) {
			return '';
		}

		$redirect_to = '';
		if ( isset( $_GET['redirect_to'] ) ) {
			$redirect_to = esc_url_raw( wp_unslash( (string) $_GET['redirect_to'] ) );
		}

		if ( $redirect_to === '' ) {
			$referer = wp_get_raw_referer();
			if ( is_string( $referer ) && $referer !== '' ) {
				$redirect_to = esc_url_raw( $referer );
			}
		}

		if ( $redirect_to === '' ) {
			$redirect_to = home_url( '/' );
		}

		return add_query_arg( 'redirect_to', $redirect_to, $target_base );
	}

	function abq_sso_bridge_handle_legacy_routes_early() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		$target = abq_sso_bridge_get_legacy_redirect_target();
		if ( $target === '' ) {
			return;
		}

		wp_safe_redirect( $target, 302 );
		exit;
	}

	add_action( 'init', 'abq_sso_bridge_handle_legacy_routes_early', 0 );

	function abq_sso_bridge_handle_legacy_routes() {
		if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		$target = abq_sso_bridge_get_legacy_redirect_target();
		if ( $target === '' ) {
			return;
		}

		wp_safe_redirect( $target, 302 );
		exit;
	}

	add_action( 'template_redirect', 'abq_sso_bridge_handle_legacy_routes', 0 );
}

if ( ! function_exists( 'abq_sso_bridge_enqueue_client' ) ) {
	function abq_sso_bridge_enqueue_client() {
		if ( is_admin() ) {
			return;
		}

		$client_url = abq_sso_bridge_central_base() . '/wp-content/mu-plugins/abq-unified-auth/assets/abq-sso-client.js';
		$api_base   = abq_sso_bridge_central_base() . '/wp-json/abq-auth/v1';

		wp_enqueue_script( 'abq-sso-client', $client_url, array(), null, true );
		$inline = '(function(){if(!window.AbqSSO){return;}window.AbqSSO.configure({apiBase:' . wp_json_encode( $api_base ) . '});window.AbqSSO.captureTokenFromUrl("abq_sso_token",true);})();';
		wp_add_inline_script( 'abq-sso-client', $inline, 'after' );
	}

	add_action( 'wp_enqueue_scripts', 'abq_sso_bridge_enqueue_client', 5 );
}
