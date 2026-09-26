<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! is_active_sidebar( 'sidebar-1' ) ) { return; }
?>
<aside id="secondary" class="vetra-sidebar" aria-label="<?php esc_attr_e( 'نوار کناری', 'vetra-portal' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
