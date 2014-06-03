<?php
			class Space3 extends M_Controller {

				public function __construct() {
					parent::__construct();
				}
				
				public function add() {
					$this->space_content_add();
				}
				
				public function edit() {
					$this->space_content_edit();
				}
				
				public function index() {
					$this->space_content_index();
				}
			}