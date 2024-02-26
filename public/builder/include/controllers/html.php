<?php
	if (SUPRA !== 1) die();
	
	/*
	 * @autor: MultiFour
	 * @version: 1.0.0
	 *
	 * Class Html
	 */
	class Html {

		public $groups = array();
		public $fontsDropdown = array();

		public function __construct() {
			include_once "include/controllers/section.php";
			$this->_init_sections();
			$this->_init_fonts();
			
			if (isset($this->groups['navigations'])) {
				$item['navigations'] = $this->groups['navigations'];
				unset($this->groups['navigations']);
				$this->groups = array_merge($item,$this->groups);
			}
			if (isset($this->groups['footers'])) {
				$item = $this->groups['footers'];
				unset($this->groups['footers']);
				$this->groups['footers'] = $item;
			}
			$this->view();
		}

		/**
		 * Prepare sections to view by json
		 */
		protected function _init_sections() {
			$sections_base = 'sections';
			$modules_group = scandir($sections_base);
			if (count($modules_group) > 2) {
				for ( $i = 2; $i < count( $modules_group ); $i ++ ) {
					$this->groups[$modules_group[ $i ]]['sections'] = array();
					$modules = scandir( $sections_base . '/' . $modules_group[ $i ] );
					for ( $j = 2; $j < count( $modules ); $j ++ ) {
						if ( is_dir( $sections_base . '/' . $modules_group[ $i ] . '/' . $modules[ $j ] ) ) {
							$this->groups[ $modules_group[ $i ] ]['sections'][] =
								new Section( $sections_base . '/' . $modules_group[ $i ], $modules[ $j ] );
						}
						if ( is_file( $sections_base . '/' . $modules_group[ $i ] . '/' . $modules[ $j ] ) ) {
							$this->groups[ $modules_group[ $i ] ]['overallJs'] =
								file_get_contents( $sections_base . '/' . $modules_group[ $i ] . '/' . $modules[ $j ] );
						}
					}
				}
			}
		}

		/**
		 * Prepare fonts to view in typography dropdown
		 */
		protected function _init_fonts() {
			$fonts_path = 'js/fonts.js';
			$fonts_content = file_get_contents($fonts_path);
			preg_match_all('/\'(?:(.*):([^,]*),([^,\']*).*|(.*)\')/i', $fonts_content, $fonts);
			if (count($fonts[1]) === count($fonts[2])) {
				foreach ( $fonts[1] as $key => $val ) {
					$font_family = $fonts[1][ $key ] !== '' ? $fonts[1][ $key ] : $fonts[4][ $key ];
					$font_weight = $fonts[1][ $key ] !== '' ? $fonts[2][ $key ] : 400;
					array_push( $this->fontsDropdown, array(
						'font_family' => str_replace( '+', ' ', $font_family )
						,'font_weight' => $font_weight
					) );
				}
			}

		}

		protected function view() {
			include_once "include/views/layout.php";
		}
	}