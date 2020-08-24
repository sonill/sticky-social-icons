(function( $ ) {
	'use strict';
	
	var default_icons = [ 'fab_fa-facebook', 'fab_fa-twitter', 'fab_fa-youtube', 'fab_fa-tripadvisor', 'fab_fa-linkedin', 'fab_fa-instagram', 'fab_fa-whatsapp', 'fas_fa-envelope' ];

	var selected_icons = [];

	$(document).ready(function(){

		init();


		//search
		$('#icon-search').keyup( delay( function(){
			var query = $.trim( $(this).val() );

			if( query.length < 1 ){
				// show default icons
				generate_new_icons( default_icons );
			}
			else{
				generate_new_icons( filterItems( query, ssi_icons ) );
			}


		}, 500 ) );


		// on icon click
		$('body').on('click', '.icon-wrapper', function(){

			var sel_icon = $(this).data('icon');

			//prevent duplicates
			if( ! selected_icons.includes( sel_icon) ){

				selected_icons.push( sel_icon );

				// add icon to selected icons section
				add_to_selected_icons( sel_icon.replace('_', ' ') );

				$(this).addClass('selected');

			}
		})



		// show / hide .more-options-container
		$('body').on('click', 'button.more-options-btn', function(){
			var $sibling = $(this).siblings('.more-options-container');

			if( $sibling.is(":visible") ){
				$sibling.slideUp();
			}
			else{
				$sibling.slideDown();
			}
		})


		// hide .more-options-container
		$('body').on('click', 'button.close-moc', function(){
			$(this).parents('.more-options-container').slideUp();
			
		})

		
		// remove item from selected icons list
		$('body').on('click', '.remove-item-btn', function(){
			var $this_parents = $(this).parents('.icon-row');
			$this_parents.slideUp('fast', function(){
				$this_parents.remove();
			});
		})


		$('#sanil-ssi-form').on('submit', function(e){
			e.preventDefault();

			var selected_icon_data = {};
			$('#selected-icons-container .icon-row').each(function(index){
				var url = $('.url-input', this).val();
				var icon = $('.selected-icon', this).val();
				var new_tab = $('input[name="open_in_new_tab"]', this).val();
				var icon_color = $('input[name="icon_color"]', this).val();
				var icon_color_on_hover = $('input[name="icon_color_on_hover"]', this).val();
				var bck_color = $('input[name="bck_color"]', this).val();
				var bck_color_on_hover = $('input[name="bck_color_on_hover"]', this).val();

				selected_icon_data[index] =  JSON.stringify({
					url: url,
					icon: icon,
					new_tab: new_tab,
					icon_color: icon_color,
					icon_color_on_hover: icon_color_on_hover,
					bck_color: bck_color,
					bck_color_on_hover: bck_color_on_hover
				});

			})


			// insert icon options into selected icons input field
			$('#sanil_ssi_db_selected_icons').attr( 'value', JSON.stringify(selected_icon_data) );

			// submit form
			$('#sanil-ssi-form')[0].submit();

		})


		$('body').on('change', '.color-picker', delay(function(){
			// console.log($(this).val());

			var $parent = $(this).parents('.icon-row');
			var this_icon_id = $parent.data('icon');

			console.log(this_icon_id);
			// generate_stylesheet(this_icon_id);

			// continue here --------------------------------------------



		}, 500))



		// on init
		// first function to run
		function init(){

			// show default icons
			generate_new_icons( default_icons );

			// if values exists in db
			// currently, it is in string type
			var selected_icons_from_db = sanil_ssi_objects.selected_icons_from_db;
			if( selected_icons_from_db.length ){
				// convert into object
				selected_icons_from_db = JSON.parse(selected_icons_from_db);
			}
			
			// loop though object to get inner object
			Object.keys(selected_icons_from_db).forEach(function(index){
				// get inner object
				var icon_obj = JSON.parse(selected_icons_from_db[index]);

				// generate default icons from object data
				add_to_selected_icons(icon_obj.icon, icon_obj);

				// add to selected icon array
				if( ! selected_icons.includes( get_icon_id(icon_obj.icon)) ){
					selected_icons.push( get_icon_id(icon_obj.icon) );
				}

			})


			// mark icons as selected, if already selected
			mark_icons_as_selected();


		}


		// genereate icon template
		function generate_new_icons( icon_names ){

			console.log(icon_names);

			if( icon_names.length < 1 ){
				$('#available-icons-container').html( '<p><strong>0 result found</strong></p>' );
				return;	
			}

			var template = '';
			icon_names.forEach( function(icon_name) {
				template += '<a data-icon="' + icon_name + '" class="icon-wrapper" ><div class="icon-holder">';
				template += '<i class="' + icon_name.replace('_', ' ') + '"></i>';
				template += '</div></a>';
			});

			$('#available-icons-container').html( template );

			// mark icons as selected, if already selected
			mark_icons_as_selected();
		}


		// search array 
		function filterItems(needle, heystack) {
			var query = needle.toLowerCase();
			var return_data = [];
			if( query.length < 1 ) return_false;
			heystack.filter(function(item) {
				if( return_data.length > 43 ) return return_data;
				if(item.toLowerCase().indexOf(query) >= 0 ){
					return_data.push(item);
				}
			})

			return return_data;
			
		}


		// delay search keyup
		function delay(callback, ms) {
			var timer = 0;
			return function() {
				var context = this, args = arguments;
				clearTimeout(timer);
				timer = setTimeout(function () {
					callback.apply(context, args);
				}, ms || 0);
			};
		}


		// generate markup and append to dom
		function add_to_selected_icons( icon, icon_obj ){

			if(icon_obj === undefined ){
				icon_obj = {
					url: '',
					icon: icon,
					new_tab: 1,
					icon_color: '#000',
					icon_color_on_hover: '#fff',
					bck_color: '#fff',
					bck_color_on_hover: '#000'
				};
			}

			var is_checked = (icon_obj.new_tab == 1) ? 'checked' : '';


			// show parent container
			$('#selected-icons-section').show();

			var template = '<div class="icon-row" data-icon="'+ get_icon_id(icon) +'">';
				template += '<button type="button" class="drag" title="' + sanil_ssi_objects.text_drag_msg +'">' + sanil_ssi_objects.text_drag +'</button>';
				template += '<div class="icon-holder"><i class="' + icon + '"></i></div>';
				template += '<input type="text" name="url_input" class="url-input" placeholder="' + sanil_ssi_objects.text_url_to_open +'" value="'+ icon_obj.url +'">';
				template += '<input type="hidden" name="icon" class="selected-icon" value="' + icon + '" >';
				template += '<button type="button" class="more-options-btn">' + sanil_ssi_objects.text_more_options  +'</button>';
				template += '<button type="button" class="remove-item-btn">' + sanil_ssi_objects.text_remove  +'</button>';
				template += '<div class="more-options-container" style="display: none;">';
				template += '<h4 class="title">' + sanil_ssi_objects.text_more_options +'<button type="button" class="close-moc">' + sanil_ssi_objects.text_close +'</button></h4>';
				template += '<div class="form-group"><label>' + sanil_ssi_objects.text_open_in_new_tab +'</label><div class="moc-input-wrapper"><input type="checkbox" value="1" name="open_in_new_tab" '+ is_checked +'></div></div>';
				template += '<div class="form-group"><label>' + sanil_ssi_objects.text_colors +'</label>';
				template += '<div class="moc-input-wrapper has-color-picker">';
				template += '<div class="colorpicker-group"><p>' + sanil_ssi_objects.text_icon_color +'</p><input type="text" value="'+ icon_obj.icon_color +'" name="icon_color"  class="color-picker" data-alpha="true"></div>';
				template += '<div class="colorpicker-group"><p>' + sanil_ssi_objects.text_icon_color_on_hover +'</p><input type="text" value="'+ icon_obj.icon_color_on_hover +'" name="icon_color_on_hover"  class="color-picker" data-alpha="true"></div>';
				template += '<div class="colorpicker-group"><p>' + sanil_ssi_objects.text_bck_color +'</p><input type="text" value="'+ icon_obj.bck_color +'" name="bck_color"  class="color-picker" data-alpha="true"></div>';
				template += '<div class="colorpicker-group"><p>' + sanil_ssi_objects.text_bck_color_on_hover +'</p><input type="text" value="'+ icon_obj.bck_color_on_hover +'" name="bck_color_on_hover"  class="color-picker" data-alpha="true"></div>';
				template += '</div></div></div></div>';

			$('#selected-icons-container').append(template);

			// scroll down
			$("html, body").animate({ scrollTop: $(document).height() }, 500 );

			// delay for smoother animation
			setTimeout(function(){
				// initiate wp color picker
				$('.icon-row .color-picker').wpColorPicker();

			}, 500)
			
		}

		function generate_stylesheet(icon_id, styles){
			var template = '<style id="'+ icon_id +'">' + styles + '</style>';
		}

		function get_icon_id(icon){
			return icon.replace(' ', '_');
		}

		// loop through all  .icon-wrapper and mark appropriate as selected
		function mark_icons_as_selected(){

			$('#available-icons-container a').each(function(){

				if( selected_icons.includes(  $(this).data('icon')  ) ){
					$(this).addClass('selected');
				}
				
			})
		}

	})



})( jQuery );

