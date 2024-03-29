<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped table-bordered">
	<tr>
		<th><?php echo get_msg('no')?></th>
		<th><?php echo get_msg('name')?></th>
		<th><?php echo get_msg('description')?></th>
		<th><?php echo get_msg('number_members')?></th>
		<th><?php echo get_msg('View Details')?></th>

		<!-- <?php if ( $this->ps_auth->has_access( EDIT )): ?>
			<th><?php echo get_msg('btn_edit')?></th>
		<?php endif;?> -->

		<?php if ( $this->ps_auth->has_access( DEL )): ?>
			<th><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
		<?php endif; ?>

	</tr>

	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $teams ) && count( $teams->result()) > 0 ): ?>
			
		<?php foreach($teams->result() as $team): ?>
			
			<tr>
				<td><?php echo ++$count;?></td>
				<td><?php echo $team->name;?></td>
				<td><?php echo $team->description;?></td>
				<td><?php echo $this->Team_member->getTeamMemberCnt($team->id);?></td>
				
				<td>
					<a href='<?php echo $module_site_url .'/edit/'. $team->id; ?>'>
						<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
					</a>
				</td>
				<!-- <?php if ( $this->ps_auth->has_access( EDIT )): ?>
				<td>
					<a href='<?php echo $module_site_url .'/edit/'. $team->id; ?>'>
						<i class='fa fa-pencil-square-o'></i>
					</a>
				</td>
				<?php endif; ?> -->

				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td>
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$team->id";?>">
							<i class='fa fa-trash-o'></i>
						</a>
					</td>
				
				<?php endif; ?>

			</tr>
		
		<?php endforeach; ?>

	<?php else: ?>
			
		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

</table>
</div>