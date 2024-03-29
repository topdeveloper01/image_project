<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_list extends BE_Controller {

	/**
	 * Constructs required variables
	 */
	function __construct() {
		parent::__construct( MODULE_CONTROL, 'TASK LIST' );
	}

	function index() {

		//registered tasks filter
		$conds = array( 'is_trashed' => 0 );

		// get rows count
		$this->data['rows_count'] = $this->Task->count_all_by($conds);

		// get tasks
		$this->data['tasks'] = $this->Task->get_all_by($conds, $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();
	}

	/**
	 * Searches for the first match in tasks
	 */
	function search() {

		// breadcrumb urls
		$data['action_title'] = get_msg( 'tasks_search' );

		// handle search term
		$search_term = $this->searchterm_handler( $this->input->post( 'searchterm' ));
		
		// condition
		$conds = array( 'searchterm' => $search_term );

		$this->data['rows_count'] = $this->Task->count_all_by( $conds );

		$this->data['tasks'] = $this->Task->get_all_by( $conds, $this->pag['per_page'], $this->uri->segment( 4 ));
		
		parent::search();
	}

	/**
	 * Create the user
	 */
	function add() {

		// breadcrumb
		$this->data['action_title'] = get_msg( 'add' );

		// call add logic
		parent::add();
	}

	/**
	 * Update the user
	 */
	function edit( $id, $current_tab = 'taskinfo' ) {

		// breadcrumb
		$this->data['action_title'] = get_msg( 'edit' );

		// load task
		$this->data['task'] = $this->Task->get_one( $id );
		// // get team members

		// $conds = array();
		// $this->data['images'] = $this->Team_member->getmembers( $id, $conds );

		// // get idle users to add more
		// $this->data['idle_users'] = $this->Team_member->getIdleUsers();
		// get team tasks
		$this->data['current_tab'] = $current_tab;
		$this->data['path_form'] = '/entry_editform';
		// call update logic
		parent::edit( $id );
	}

	function addmember( $id ) {
		// load team
		$team = $this->Team->get_one( $id );
		
		$team_member_data = array();
		if ( $this->has_data( 'member_user' )) {
			$team_member_data['user_id'] = $this->get_data( 'member_user' );
		}
		$team_member_data['team_id'] = $id;
		if ( $this->Team_member->save_member($team_member_data) ) {
			$this->set_flash_msg( 'success', get_msg( 'success' ));
		} else {
			$this->set_flash_msg( 'error', get_msg( 'failed' ));
		}

		$this->data['current_tab'] = 'members';
		redirect( site_url('/admin/registered_teams/edit/' . $id ."/members") );
	}

	function delete_member( $team_id, $user_id ) {
		
		if ( $this->Team_member->delete( $team_id, $user_id) ) {
			$this->set_flash_msg( 'success', get_msg( 'success' ));
		} else {
			$this->set_flash_msg( 'error', get_msg( 'failed' ));
		}

		$this->data['current_tab'] = 'members';
		redirect( site_url('/admin/registered_teams/edit/' . $team_id ."/members") );
	}
	
	function search_member( $id, $current_tab = 'teaminfo' ) {

		// breadcrumb
		$this->data['action_title'] = get_msg( 'edit' );

		// load team
		$this->data['team'] = $this->Team->get_one( $id );
		// get team members

		$conds = array();
		if ( $this->has_data( 'searchterm' )) {
			// handle search term
			$search_term = $this->searchterm_handler( $this->input->post( 'searchterm' ));
			// condition
			$conds = array( 'searchterm' => $search_term );
		}
		$this->data['members'] = $this->Team_member->getmembers( $id, $conds );

		// get idle users to add more
		$this->data['idle_users'] = $this->Team_member->getIdleUsers();
		// get team tasks
		$this->data['current_tab'] = $current_tab;
		$this->data['path_form'] = '/entry_editform';
		
		// load entry form
		$this->load_form( $this->data );
	}
	/**
	 * Delete the task
	 */
	function delete( $id ) {

		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );
		
		if ( !$this->Task->delete( $id )) {

			// set error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));

			// rollback
			$this->trans_rollback();

			// redirect to list view
			redirect( $this->module_site_url());
		}
			
		/**
		 * Check Transcation Status
		 */
		if ( !$this->check_trans()) {
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));	
		} else {
			$this->set_flash_msg( 'success', get_msg( 'success' ));
			parent::delete( $id );
		}
		
		redirect( $this->module_site_url());
	}

	/**
	 * @param      boolean  $team_id  The user identifier
	 */
	function save( $task_id = false ) {
		// prepare user object and permission objects
		$task_data = array();

		if ( $this->has_data( 'task_name' )) {
			$task_data['name'] = $this->get_data( 'task_name' );
		}

		if( $this->has_data( 'description' )) {
			$task_data['description'] = $this->get_data( 'description' );
		}

		if( $this->has_data( 'priority' )) {
			$task_data['priority'] = $this->get_data( 'priority' );
		}

		if( $this->has_data( 'status' )) {
			$task_data['status'] = $this->get_data( 'status' );
		}
		
		// save data
		if ( ! $this->Task->save( $task_data, $task_id )) {
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
		} else {
			// if no eror in inserting
			$this->set_flash_msg( 'success', get_msg( 'success' ));
		}

		redirect( $this->module_site_url());
	}


	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input() {
		
		$rule = 'required';

		$this->form_validation->set_rules( 'task_name', get_msg( 'name' ), $rule);
		$this->form_validation->set_rules( 'description', get_msg( 'description' ), $rule);

		if ( $this->form_validation->run() == FALSE ) {
		// if there is an error in validating,

			return false;
		}

		return true;
	}
}