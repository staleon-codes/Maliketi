<?php
/**
 * Template file for displaying account mobile
 *
 * @package Motta
 */

 if( ! function_exists('wc_get_account_endpoint_url') ) {
	return;
}
?>

<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ) ?>" class="motta-mobile-navigation-bar__icon account-icon" data-toggle="off-canvas" data-target="account-panel">
	<?php echo \Motta\Icon::get_svg( 'account' ); ?>
	<em> <?php echo esc_html__( 'Account', 'motta' ); ?></em>
</a>
