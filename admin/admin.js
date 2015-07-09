jQuery(document).ready(function($) {
   // jQuery("#post-cat option:first-child").attr("selected", true);
   jQuery('.tab-content:nth-child(2)').addClass('firstelement');
   var sCounter = jQuery('#accordion div:last-child').find('.fullshortcode').attr('id');
    jQuery('#la-saved').hide();
    jQuery('#la-loader').hide();
   // console.log(sCounter);
   var icons = {
        header: "dashicons dashicons-plus",
        activeHeader: "dashicons dashicons-no"
    };
   jQuery( "#accordion" ).accordion({  
      collapsible: true,
      icons: icons,
      // header: '.ui-accordion-header-icon'
    });

   jQuery('.my-colorpicker').wpColorPicker();
    jQuery('#compactviewer').on('click','.save-meta',function(event) {
        event.preventDefault();
        jQuery('#la-saved').hide();
        jQuery('#la-loader').show();
        var allPost = [];
        jQuery('#accordion > div').each(function(index) {
          var that = jQuery(this);
          var rotwords = {};

            // console.log(that);
          
          rotwords.stat_sent =  jQuery(this).find('.static-sen').val(),
          rotwords.rot_words =  jQuery(this).find('.rotating-words').val(),
          rotwords.font_size =  jQuery(this).find('.font').val(),
          rotwords.animation_effect =  jQuery(this).find('.animate').val(),
          rotwords.animation_speed =  jQuery(this).find('.speed').val(),
          rotwords.font_color =  jQuery(this).find('.my-colorpicker').val(),
          rotwords.shortcode = that.find('.fullshortcode').attr('id');
          rotwords.counter = that.find('.fullshortcode').attr('id');

        allPost.push(rotwords);
        });
        // console.log(allPost);

        var data = {
            action : 'la_save_words_rotator',
             rotwords : allPost       
        } 

        // console.log(data);
        jQuery.post(laAjax.url, data, function(resp) {
          console.log(resp);
            jQuery('#la-saved').show();
            jQuery('#la-loader').hide();
             jQuery('#la-saved').delay(2000).fadeOut();
        });
    });

      
    jQuery('#accordion .btnadd').click(function(event) {
        sCounter++;
        jQuery('#accordion').append('<h3>CSS3 Rotating Words</h3>');
        var parent_newly = jQuery(this).closest('.ui-accordion-content').clone(true).removeClass('firstelement').appendTo('#accordion').find('button.fullshortcode').attr('id', sCounter).closest('.tab-content');
        parent_newly.find('.wp-picker-container').remove();
        parent_newly.find('.insert-picker').append('<input type="text" class="colorpicker" value="#333" />');

        parent_newly.find('.colorpicker').wpColorPicker();
        jQuery( "#accordion" ).accordion('refresh');

    });

    jQuery('#accordion .btndelete').click(function(event) {
         if (jQuery(this).closest('.ui-accordion-content').hasClass('firstelement')) {
            alert('You can not delete it as it is first element!');
        } else {
            var head = jQuery(this).closest('.ui-accordion-content').prev();
            var body = jQuery(this).closest('.ui-accordion-content');
            head.remove();
            body.remove();
            jQuery("#accordion").accordion('refresh');
        }

    });

    jQuery('button.fullshortcode').click(function(event) {
        event.preventDefault();
        prompt("Copy and use this Shortcode", '[animated-words-rotator id="'+jQuery(this).attr('id')+'"]');
    });
});