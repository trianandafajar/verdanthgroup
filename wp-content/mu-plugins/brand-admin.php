<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brand_admin_palette() {
	return array(
		'base'   => '#183C32',
		'accent' => '#C49A5A',
	);
}

function brand_admin_contrast( $hex ) {
	$hex = ltrim( $hex, '#' );
	$r   = hexdec( substr( $hex, 0, 2 ) );
	$g   = hexdec( substr( $hex, 2, 2 ) );
	$b   = hexdec( substr( $hex, 4, 2 ) );
	$lum = ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) / 255;
	return $lum > 0.6 ? '#111111' : '#ffffff';
}

function brand_admin_shade( $hex, $amount ) {
	$hex = ltrim( $hex, '#' );
	$out = '#';
	foreach ( array( 0, 2, 4 ) as $i ) {
		$c    = hexdec( substr( $hex, $i, 2 ) );
		$c    = max( 0, min( 255, (int) round( $c + $amount * 255 ) ) );
		$out .= str_pad( dechex( $c ), 2, '0', STR_PAD_LEFT );
	}
	return $out;
}

function brand_admin_css() {
	$p       = brand_admin_palette();
	$base    = $p['base'];
	$accent  = $p['accent'];
	$on_base = brand_admin_contrast( $base );
	$on_acc  = brand_admin_contrast( $accent );
	$deep    = brand_admin_shade( $base, brand_admin_contrast( $base ) === '#ffffff' ? -0.06 : -0.1 );
	$lift    = brand_admin_shade( $base, brand_admin_contrast( $base ) === '#ffffff' ? 0.08 : -0.06 );
	$link    = brand_admin_contrast( $accent ) === '#111111' ? brand_admin_shade( $accent, -0.35 ) : $accent;
	$css     = ':root{--wp-admin-theme-color:' . $link . ';--wp-admin-theme-color--rgb:' . implode( ',', sscanf( $link, '#%02x%02x%02x' ) ) . ';--wp-admin-theme-color-darker-10:' . brand_admin_shade( $link, -0.06 ) . ';--wp-admin-theme-color-darker-20:' . brand_admin_shade( $link, -0.12 ) . ';}';
	$css    .= '#wpadminbar,#adminmenuback,#adminmenuwrap,#adminmenu{background:' . $base . ';}';
	$css    .= '#adminmenu a,#adminmenu div.wp-menu-image:before,#wpadminbar .ab-item,#wpadminbar a.ab-item,#wpadminbar>#wp-toolbar span.ab-label,#wpadminbar #adminbarsearch:before,#wpadminbar .ab-icon:before{color:' . $on_base . ';}';
	$css    .= '#adminmenu li.menu-top:hover,#adminmenu li.opensub>a.menu-top,#adminmenu li>a.menu-top:focus,#wpadminbar .ab-top-menu>li:hover>.ab-item,#wpadminbar .ab-top-menu>li.hover>.ab-item{background:' . $lift . ';color:' . $on_base . ';}';
	$css    .= '#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,#adminmenu li.current a.menu-top,.folded #adminmenu li.current.menu-top,#adminmenu .wp-menu-arrow,#adminmenu .wp-menu-arrow div{background:' . $accent . ';color:' . $on_acc . ';}';
	$css    .= '#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,#adminmenu li.current div.wp-menu-image:before,#adminmenu li.menu-top:hover div.wp-menu-image:before{color:' . $on_acc . ';}';
	$css    .= '#adminmenu li.menu-top:hover div.wp-menu-image:before{color:' . $on_base . ';}';
	$css    .= '#adminmenu .wp-submenu,#adminmenu .wp-has-current-submenu .wp-submenu,#adminmenu a.wp-has-current-submenu:focus+.wp-submenu,.folded #adminmenu .wp-has-current-submenu .wp-submenu{background:' . $deep . ';}';
	$css    .= '#adminmenu .wp-submenu a,#adminmenu .wp-submenu .wp-submenu-head{color:' . $on_base . ';opacity:.78;}';
	$css    .= '#adminmenu .wp-submenu a:hover,#adminmenu .wp-submenu li.current a,#adminmenu .wp-submenu li.current a:hover{color:' . $accent . ';opacity:1;}';
	$css    .= '#adminmenu .awaiting-mod,#adminmenu .update-plugins{background:' . $accent . ';color:' . $on_acc . ';}';
	$css    .= '#collapse-button,#collapse-button:hover{color:' . $on_base . ';}';
	$css    .= '#wpadminbar .ab-submenu,#wpadminbar .quicklinks .menupop ul.ab-sub-secondary{background:' . $deep . ';}';
	$css    .= '#wpadminbar .menupop .ab-sub-wrapper a{color:' . $on_base . ';}';
	$css    .= '.wp-core-ui .button-primary{background:' . $link . ';border-color:' . $link . ';color:' . brand_admin_contrast( $link ) . ';}';
	$css    .= '.wp-core-ui .button-primary:hover,.wp-core-ui .button-primary:focus{background:' . brand_admin_shade( $link, -0.08 ) . ';border-color:' . brand_admin_shade( $link, -0.08 ) . ';color:' . brand_admin_contrast( $link ) . ';}';
	$css    .= 'a{color:' . $link . ';}a:hover{color:' . brand_admin_shade( $link, -0.12 ) . ';}';
	return $css;
}

function brand_admin_print_css() {
	echo '<style id="brand-admin-colors">' . brand_admin_css() . '</style>';
}
add_action( 'admin_head', 'brand_admin_print_css', 99 );

function brand_admin_login_css() {
	$p      = brand_admin_palette();
	$base   = $p['base'];
	$accent = $p['accent'];
	$logo   = get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '';
	$css    = 'body.login{background:' . $base . ';}';
	$css   .= 'body.login a,body.login #backtoblog a,body.login #nav a{color:' . brand_admin_contrast( $base ) . ';}';
	$css   .= 'body.login a:hover{color:' . $accent . ';}';
	$css   .= '.login form{border:0;border-radius:10px;}';
	$css   .= '.login .button-primary{background:' . $accent . ';border-color:' . $accent . ';color:' . brand_admin_contrast( $accent ) . ';}';
	$css   .= '.login input:focus{border-color:' . $accent . ';box-shadow:0 0 0 1px ' . $accent . ';}';
	if ( $logo ) {
		$css .= '.login h1 a{background-image:url(' . esc_url( $logo ) . ');background-size:contain;background-position:center;background-repeat:no-repeat;width:280px;height:70px;}';
	}
	echo '<style id="brand-login-colors">' . $css . '</style>';
}
add_action( 'login_head', 'brand_admin_login_css' );

function brand_admin_login_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'brand_admin_login_url' );

function brand_admin_login_title() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'brand_admin_login_title' );
