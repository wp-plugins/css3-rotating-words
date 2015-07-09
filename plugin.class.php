<?php 
	/**
	* Plugin Main Class
	*/
	class LA_Words_Rotator
	{
		
		function __construct()
		{
			add_action( "admin_menu", array($this,'post_viewer_admin_options'));
			add_action( 'admin_enqueue_scripts', array($this,'admin_enqueuing_scripts'));
			add_action('wp_ajax_la_save_words_rotator', array($this, 'save_admin_options'));
			add_shortcode( 'animated-words-rotator', array($this, 'render_words_rotator') );
		}
	

		function post_viewer_admin_options(){
			add_menu_page( 'CSS3 Rotating Words', 'CSS3 Rotating Words', 'manage_options', 'word_rotator', array($this,'post_previewer_menu_page'), 'dashicons-format-image', $position );
		}

		function admin_enqueuing_scripts($slug){
		if ($slug == 'toplevel_page_word_rotator') {
			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'post-viewer-admin-js', plugins_url( 'admin/admin.js' , __FILE__ ), array('jquery', 'jquery-ui-accordion', 'wp-color-picker') );
			wp_enqueue_style( 'post-viewer-admin-css', plugins_url( 'admin/style.css' , __FILE__ ));
			wp_localize_script( 'post-viewer-admin-js', 'laAjax', array( 'url' => admin_url( 'admin-ajax.php' ),'path' => plugin_dir_url( __FILE__ )));
			}
		}
		function post_previewer_menu_page(){
			$savedmeta = get_option('la_words_rotator');
			?>
			<div class="wrap" id="compactviewer">
				<h1>CSS3 Rotating Words</h1>
				<hr>
				<div id="accordion">
				<?php if (isset($savedmeta['rotwords'])) {?>
					<?php foreach ($savedmeta['rotwords'] as $key => $data) {?>

					<h3 class="tab-head">CSS3 Rotating Words</h3>
					<div class="tab-content">
						<h2>General Settings</h2>
						<table class="form-table">
							<hr>

							<tr>
								<td>
									<strong><?php _e( 'Give Static Sentence', 'la-wordsrotator' ); ?></strong> 
								</td>
								<td class="get-terms">
									
									<textarea cols="10" rows="5" class="static-sen widefat"><?php echo $data['stat_sent']; ?></textarea>		
								</td>

								<td>
									<p class="description"><?php _e( 'Write a static sentence with which rotating words will be shown', 'la-wordsrotator' ); ?>.</p>
								</td>
							</tr>



			  				<tr>
			  					<td> <strong> <?php _e( 'Add Words(these will be rotating)', 'la-wordsrotator' ); ?> </strong></td>
			  					<td>
			  						<textarea cols="10" rows="5" class="rotating-words widefat"><?php echo $data['rot_words']; ?></textarea> 
			  					</td>
			  					<td>
			  						<p class="description"><?php _e( 'Comma separated list of rotating words(maximum 6)', 'la-wordsrotator' ); ?>.</p>
			  					</td>
			  				</tr>
							<tr>
								<td>
									<strong><?php _e( 'Words Font Size', 'la-wordsrotator' ); ?></strong>
								</td>
								<td class="get-terms">
									<input type="number" class="font widefat" value="<?php echo $data['font_size']; ?>"> 		
								</td>

								<td>
									<p class="description"><?php _e( 'Set font size for words and sentence.It will be in percent.', 'la-wordsrotator' ); ?>.</p>
								</td>
							</tr>

							<tr>
			  					<td> 
			  						<strong ><?php _e( 'Sentence Color', 'la-wordsrotator' ); ?></strong>
			  					</td>
			  					<td class="insert-picker">
			  						<input type="text" class="my-colorpicker" value="<?php echo $data['font_color']; ?>">
			  					</td>
			  					<td>
			  						<p class="description"><?php _e( 'Choose color for the sentence', 'la-wordsrotator' ); ?>.</p>
			  					</td>
			  				</tr>

			  				<tr>
			  					<td>
			  						<strong ><?php _e( 'Animation Effect', 'la-wordsrotator' ); ?></strong>
			  					</td>
			  					<td>
			  						<select class="animate widefat">
			  							<option value="top-bottom" <?php if ( $data['animation_effect'] == 'top-bottom' ) echo 'selected="selected"'; ?>>Animate down</option>
			  							<option value="breaking-words" <?php if ( $data['animation_effect'] == 'breaking-words' ) echo 'selected="selected"'; ?>>Fade in and “fall”</option>
			  							<option value="ease-in" <?php if ( $data['animation_effect'] == 'ease-in' ) echo 'selected="selected"'; ?>>Sliding</option>
			  							<option value="left-right" <?php if ( $data['animation_effect'] == 'left-right' ) echo 'selected="selected"'; ?>>Flip</option>
			  						</select>
			  					</td>
			  					<td>
			  						<p class="description"><?php _e( 'Select Animation effect for words', 'la-wordsrotator' ); ?>.</p>
			  					</td>
			  				</tr>

						</table>

						<div class="clearfix"></div>
						<hr style="margin-bottom: 10px;">
						<button class="button btnadd"><span title="Add New" class="dashicons dashicons-plus-alt"></span><?php _e( 'Add New', 'la-wordsrotator' ); ?></button>&nbsp;
						<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete"></span><?php _e( 'Delete', 'la-wordsrotator' ); ?></button>
						<button class="button-primary fullshortcode pull-right" id="1"><?php _e( 'Get Shortcode', 'la-wordsrotator' ); ?></button>
						
					</div>
						<?php } ?>
					<?php } else { ?>
						<h3 class="tab-head">CSS3 Rotating Words</h3>
					<div class="tab-content">
						<h2>General Settings</h2>
						<table class="form-table">
							<hr>

							<tr>
								<td>
									<strong><?php _e( 'Give Static Sentence', 'la-wordsrotator' ); ?></strong> 
								</td>
								<td class="get-terms">
									<textarea cols="10" rows="5" class="static-sen widefat"></textarea>		
								</td>

								<td>
									<p class="description"><?php _e( 'Write a static sentence with which rotating words will be shown', 'la-wordsrotator' ); ?>.</p>
								</td>
							</tr>



			  				<tr>
			  					<td> <strong> <?php _e( 'Add Words(these will be rotating)', 'la-wordsrotator' ); ?> </strong></td>
			  					<td>
			  						<textarea cols="10" rows="5" class="rotating-words widefat"></textarea> 
			  					</td>
			  					<td>
			  						<p class="description"><?php _e( 'Comma separated list of rotating words(maximum 6)', 'la-wordsrotator' ); ?>.</p>
			  					</td>
			  				</tr>
							<tr>
								<td>
									<strong><?php _e( 'Words Font Size', 'la-wordsrotator' ); ?></strong>
								</td>
								<td class="get-terms">
									<input type="number" class="font widefat" value=""> 		
								</td>

								<td>
									<p class="description"><?php _e( 'Set font size for words and sentence.It will be in percent.', 'la-wordsrotator' ); ?>.</p>
								</td>
							</tr>

							<tr>
			  					<td>
			  						<strong ><?php _e( 'Sentence Color', 'la-wordsrotator' ); ?></strong>
			  					</td>
			  					<td class="insert-picker">
			  						<input type="text" class="my-colorpicker" value="<?php echo $data['font_color']; ?>">
			  					</td>
			  					<td>
			  						<p class="description"><?php _e( 'Choose color for the sentence', 'la-wordsrotator' ); ?>.</p>
			  					</td>
			  				</tr>

			  				<tr>
			  					<td>
			  						<strong ><?php _e( 'Animation Effect', 'la-wordsrotator' ); ?></strong>
			  					</td>
			  					<td>
			  						<select class="animate widefat">
			  							<option value="top-bottom">Animate down</option>
			  							<option value="breaking-words">Fade in and “fall”</option>
			  							<option value="ease-in">Sliding</option>
			  							<option value="left-right">Flip</option>
			  						</select>
			  					</td>
			  					<td>
			  						<p class="description"><?php _e( 'Select Animation effect for words', 'la-wordsrotator' ); ?>.</p>
			  					</td>
			  				</tr>	

						</table>

						<div class="clearfix"></div>
						<hr style="margin-bottom: 10px;">
						<button class="button btnadd"><span title="Add New" class="dashicons dashicons-plus-alt"></span><?php _e( 'Add New', 'la-wordsrotator' ); ?></button>&nbsp;
						<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete"></span><?php _e( 'Delete', 'la-wordsrotator' ); ?></button>
						<button class="button-primary fullshortcode pull-right" id="1"><?php _e( 'Get Shortcode', 'la-wordsrotator' ); ?></button>
						
					</div>
					<?php } ?>
				</div>
				<hr style="margin-top: 20px;">
						<button class="button-primary save-meta" ><?php _e( 'Save Settings', 'la-wordsrotator' ); ?></button>
						<span id="la-loader"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/7.gif"></span>
						<span id="la-saved"><strong><?php _e( 'Changes Saved', 'la-wordsrotator' ); ?>!</strong></span>
				</div>
				
	<?php	
	}

	function save_admin_options(){
		if (isset($_REQUEST)) {
			print_r($_REQUEST);
			update_option( 'la_words_rotator', $_REQUEST );
			
		}
	}

	function render_words_rotator($atts, $content, $the_shortcode){
		$savedmeta = get_option( 'la_words_rotator' );
		if (isset($savedmeta['rotwords'])) {
			foreach ($savedmeta['rotwords'] as $key => $data) {
				if ($atts['id']== $data['counter']) {
					wp_enqueue_script( 'modernize-js', plugins_url( 'js/modernizr.custom.72111.js', __FILE__ ));

					$rotate_words = $data['rot_words'];
					$rotate_words_arr = explode(",",$rotate_words);
					// print_r($rotate_words_arr);
					$postContents = '<style>
										.rw-wrapper{
											width: 80%;
											position: relative;
											font-family: "Bree Serif";
											padding: 10px;
										}
										.rw-sentence{
											margin: 0;
											text-align: left;
											text-shadow: 1px 1px 1px rgba(255,255,255,0.8);
										}
										.rw-sentence span{
											color: '.$data["font_color"].';
											font-size: '.$data["font_size"].'%;
											font-weight: normal;
										}
										.rw-words{
											display: inline;
											text-indent: 10px; 
										}

										.rw-words-1 span{
											position: absolute;
											opacity: 0;
											color: #6b969d;
											-webkit-animation: rotateWord 18s linear infinite 0s;
											-ms-animation: rotateWord 18s linear infinite 0s;
											animation: rotateWord 18s linear infinite 0s;
										}
										.rw-words-1 span:nth-child(2) { 
										    -webkit-animation-delay: 3s; 
											-ms-animation-delay: 3s; 
											animation-delay: 3s; 
											color: #6b889d;
										}
										.rw-words-1 span:nth-child(3) { 
										    -webkit-animation-delay: 6s; 
											-ms-animation-delay: 6s; 
											animation-delay: 6s; 
											color: #6b739d;	
										}
										.rw-words-1 span:nth-child(4) { 
										    -webkit-animation-delay: 9s; 
											-ms-animation-delay: 9s; 
											animation-delay: 9s; 
											color: #7a6b9d;
										}
										.rw-words-1 span:nth-child(5) { 
										    -webkit-animation-delay: 12s; 
											-ms-animation-delay: 12s; 
											animation-delay: 12s; 
											color: #8d6b9d;
										}
										.rw-words-1 span:nth-child(6) { 
										    -webkit-animation-delay: 15s; 
											-ms-animation-delay: 15s; 
											animation-delay: 15s; 
											color: #9b6b9d;
										}
										';
						// $result = count($rotate_words_arr);
						// $multiple = $result*3;

						// foreach ($rotate_words_arr as $key => $words) {
						// 	$number = 2;
						// 	$num = 0;
						// print_r($key);
						// 	$postContents.='.rw-words-1 span{
						// 					position: absolute;
						// 					opacity: 0;
						// 					overflow: hidden;
						// 					color: #6b969d;
						// 					-webkit-animation: rotateWord '.$multiple.'s linear infinite 0s;
						// 					-ms-animation: rotateWord '.$multiple.'s linear infinite 0s;
						// 					animation: rotateWord '.$multiple.'s linear infinite 0s;
						// 				}';

						// 		foreach ($rotate_words_arr as $key => $word) {
						// 			$num++;
						// 			$postContents.='.rw-words-1 span:nth-child('.$number++.') { 
						// 				    -webkit-animation-delay: '. $num * 3 .'s; 
						// 					-ms-animation-delay: '.$num * 3 .'s; 
						// 					animation-delay: '.$num * 3 .'s; 
						// 					color: #6b889d;
						// 				}
						// 				';
						// 		}

										
						// }
									if ($data["animation_effect"]=="top-bottom") {
											$postContents.='@-webkit-keyframes rotateWord {
										    0% { opacity: 0; }
										    2% { opacity: 0; -webkit-transform: translateY(-30px); }
											5% { opacity: 1; -webkit-transform: translateY(0px);}
										    17% { opacity: 1; -webkit-transform: translateY(0px); }
											20% { opacity: 0; -webkit-transform: translateY(30px); }
											80% { opacity: 0; }
										    100% { opacity: 0; }
										}
										@-ms-keyframes rotateWord {
										    0% { opacity: 0; }
										    2% { opacity: 0; -ms-transform: translateY(-30px); }
											5% { opacity: 1; -ms-transform: translateY(0px);}
										    17% { opacity: 1; -ms-transform: translateY(0px); }
											20% { opacity: 0; -ms-transform: translateY(30px); } 
											80% { opacity: 0; }
										    100% { opacity: 0; }
										}
										@keyframes rotateWord {
										    0% { opacity: 0; }
										    2% { opacity: 0; -webkit-transform: translateY(-30px); transform: translateY(-30px); }
											5% { opacity: 1; -webkit-transform: translateY(0px); transform: translateY(0px);}
										    17% { opacity: 1; -webkit-transform: translateY(0px); transform: translateY(0px); }
											20% { opacity: 0; -webkit-transform: translateY(30px); transform: translateY(30px); }
											80% { opacity: 0; }
										    100% { opacity: 0; }
										}
										@media screen and (max-width: 768px){
											.rw-sentence { font-size: 18px; }
										}
										@media screen and (max-width: 320px){
											.rw-sentence { font-size: 9px; }
										}
					                </style>';  
										}elseif ($data["animation_effect"]=="left-right") {
											$postContents.='@-webkit-keyframes rotateWord {
											    0% { opacity: 1; -webkit-animation-timing-function: ease-in; width: 0px; }
											    10% { opacity: 0.3; width: 0px; }
												20% { opacity: 1; width: 100%; }
											    27% { opacity: 0; width: 100%; }
											    100% { opacity: 0; }
											}
											@-ms-keyframes rotateWord {
											    0% { opacity: 1; -ms-animation-timing-function: ease-in; width: 0px; }
											    10% { opacity: 0.3; width: 0px; }
												20% { opacity: 1; width: 100%; }
											    27% { opacity: 0; width: 100%; }
											    100% { opacity: 0; }
											}
											@keyframes rotateWord {
											    0% { opacity: 1; -webkit-animation-timing-function: ease-in; animation-timing-function: ease-in; width: 0px; }
											    10% { opacity: 0.3; width: 0px; }
												20% { opacity: 1; width: 100%; }
											    27% { opacity: 0; width: 100%; }
											    100% { opacity: 0; }
											}
										@media screen and (max-width: 768px){
											.rw-sentence { font-size: 18px; }
										}
										@media screen and (max-width: 320px){
											.rw-sentence { font-size: 9px; }
										}
					                </style>';  
										}elseif ($data["animation_effect"]=="breaking-words") {
											$postContents.='@-webkit-keyframes rotateWord {
											    0% { opacity: 0; }
											    5% { opacity: 1; }
											    17% { opacity: 1; -webkit-transform: rotate(0deg); }
												19% { opacity: 1; -webkit-transform: rotate(98deg); }
												21% { opacity: 1; -webkit-transform: rotate(86deg); }
												23% { opacity: 1; -webkit-transform: translateY(85px) rotate(83deg); }
												25% { opacity: 0; -webkit-transform: translateY(170px) rotate(80deg); }
												80% { opacity: 0; }
											    100% { opacity: 0; }
											}
											@-ms-keyframes rotateWord {
											    0% { opacity: 0; }
											    5% { opacity: 1; }
											    17% { opacity: 1; -ms-transform: rotate(0deg); }
												19% { opacity: 1; -ms-transform: rotate(98deg); }
												21% { opacity: 1; -ms-transform: rotate(86deg); }
												23% { opacity: 1; -ms-transform: translateY(85px) rotate(83deg); }
												25% { opacity: 0; -ms-transform: translateY(170px) rotate(80deg); }
												80% { opacity: 0; }
											    100% { opacity: 0; }
											}
											@keyframes rotateWord {
											    0% { opacity: 0; }
											    5% { opacity: 1; }
											    17% { opacity: 1; -webkit-transform: rotate(0deg); transform: rotate(0deg); }
												19% { opacity: 1; -webkit-transform: rotate(98deg); transform: rotate(98deg); }
												21% { opacity: 1; -webkit-transform: rotate(86deg); transform: rotate(86deg); }
												23% { opacity: 1; -webkit-transform: translateY(85px) rotate(83deg); transform: translateY(85px) rotate(83deg); }
												25% { opacity: 0; -webkit-transform: translateY(170px) rotate(80deg); transform: translateY(170px) rotate(80deg); }
												80% { opacity: 0; }
											    100% { opacity: 0; }
											}
											@media screen and (max-width: 768px){
												.rw-sentence { font-size: 18px; }
											}
											@media screen and (max-width: 320px){
												.rw-sentence { font-size: 9px; }
												}
					                		</style>';  
										} elseif ($data["animation_effect"]=="ease-in") {
											$postContents.='@-webkit-keyframes rotateWord {
											    0% { opacity: 0; -webkit-transform: translateZ(600px) translateX(200px);}
											    8% { opacity: 1; -webkit-transform: translateZ(0px) translateX(0px);}
											    17% { opacity: 1; }
											    25% { opacity: 0; }
											    100% { opacity: 0; }
											}
											@-ms-keyframes rotateWord {
											    0% { opacity: 0; -ms-transform: translateZ(600px) translateX(200px);}
											    8% { opacity: 1; -ms-transform: translateZ(0px) translateX(0px);}
											    17% { opacity: 1; }
											    25% { opacity: 0; }
											    100% { opacity: 0; }
											}
											@keyframes rotateWord {
											    0% { opacity: 0; -webkit-transform: translateZ(600px) translateX(200px); transform: translateZ(600px) translateX(200px);}
											    8% { opacity: 1; -webkit-transform: translateZ(0px) translateX(0px); transform: translateZ(0px) translateX(0px);}
											    17% { opacity: 1; }
											    25% { opacity: 0; }
											    100% { opacity: 0; }
											}
											@media screen and (max-width: 768px){
												.rw-sentence { font-size: 18px; }
											}
											@media screen and (max-width: 320px){
												.rw-sentence { font-size: 9px; }
												}
					                		</style>';  
										}
					            
					            $postContents.='<section class="rw-wrapper">';  
					            $postContents.='<h2 class="rw-sentence">';  
					            $postContents.='<span>'.$data["stat_sent"].'</span>';  
					            $postContents.='<div class="rw-words rw-words-1">';  
						           foreach ($rotate_words_arr as $key => $word) {
						             	$postContents.='<span>'.$word.'</span>';  
						             }  
					            $postContents.='</div>	</h2></section>'; 
					return $postContents;

				}	
			}
		}
	}
}
 ?>