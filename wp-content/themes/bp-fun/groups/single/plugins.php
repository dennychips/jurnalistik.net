<?php get_header() ?>

	<div id="content" <?php if(bp_is_group()){?> class="group-content"<?php }?>>
		<div class="padder <?php if(bp_is_group()){?>group-padder<?php }?>">
			<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

			<?php do_action( 'bp_before_group_plugin_template' ) ?>

			<!--
<div id="item-header">
				<?php //locate_template( array( 'groups/single/group-header.php' ), true ) ?>
			</div>
-->

			<!--
<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<?php bp_get_options_nav() ?>

						<?php do_action( 'bp_group_plugin_options_nav' ) ?>
					</ul>
				</div>
			</div>
-->

			<div id="item-body">

				<?php do_action( 'bp_before_group_body' ) ?>

				<?php do_action( 'bp_template_content' ) ?>

				<?php do_action( 'bp_after_group_body' ) ?>
			</div><!-- #item-body -->

			<?php endwhile; endif; ?>

			<?php do_action( 'bp_after_group_plugin_template' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'groups/sidebar-group.php' ), true ) ?>

<?php get_footer() ?>