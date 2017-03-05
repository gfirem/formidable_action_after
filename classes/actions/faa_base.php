<?php

class faa_base {
	public $name;
	public $description;
	public $slug;
	private $number;
	
	/**
	 * faa_base constructor.
	 *
	 * @param $name
	 * @param $description
	 * @param $slug
	 */
	public function __construct( $name, $description, $slug ) {
		$this->name        = $name;
		$this->description = $description;
		$this->slug        = $slug;
		
		add_filter( 'faa_action_subscribe', array( $this, 'subscribe' ), 10, 2 );
	}
	
	public function subscribe( $actions ) {
		$actions = array_merge( $actions, array( $this ) );
		
		return $actions;
	}
	
	public function view( $form, $form_action, $action_control ) {
		
	}
	
	public function process( $action, $entry, $form, $event ) {
		
	}
	
	protected function replace_shortcode( $entry, $value ) {
		$shortCodes = FrmFieldsHelper::get_shortcodes( $value, $entry->form_id );
		$content    = apply_filters( 'frm_replace_content_shortcodes', $value, $entry, $shortCodes );
		FrmProFieldsHelper::replace_non_standard_formidable_shortcodes( array(), $content );
		
		return do_shortcode( $content );
	}
	
	public function get_defaults(){
		return array();
	}
	
	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param mixed $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}
	
	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * @param mixed $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}
	
	/**
	 * @return mixed
	 */
	public function getSlug() {
		return $this->slug;
	}
	
	/**
	 * @param mixed $slug
	 */
	public function setSlug( $slug ) {
		$this->slug = $slug;
	}
}