<div class='row my-3'>
	<div class='col-6'>
		<?php
			$attributes = array('class' => 'form-inline');

			echo form_open( $module_site_url . '/search', $attributes );
		?>
			<div class="form-group" style="padding-right: 3px;">
				
				<?php echo form_input(array(
					'name' => 'searchterm',
					'value' => set_value( 'searchterm' ),
					'class' => 'form-control form-control-sm',
					'placeholder' => get_msg( 'btn_search' ),
					'id' => ''
				)); ?>

		  	</div>

			<div class="form-group" style="padding-right: 2px;">
			  	<button type="submit" class="btn btn-sm btn-primary">
			  		<?php echo get_msg( 'btn_search' ); ?>
			  	</button>
		  	</div>

		  	<div class="form-group">
	  			<a href="<?php echo site_url() . '/admin/registered_teams'; ?>" class="btn btn-sm btn-primary">
			  		<?php echo get_msg( 'btn_reset' ); ?>
			  	</a>
		  	</div>
		
		<?php echo form_close(); ?>

	</div>

	<div class='col-6'>
			<a href='<?php echo $module_site_url .'/add';?>' class='btn btn-sm btn-primary pull-right'>
				<i class='fa fa-plus'></i> 
				<?php echo get_msg( 'btn_add_new' ); ?>
			</a>
		</div>
</div>