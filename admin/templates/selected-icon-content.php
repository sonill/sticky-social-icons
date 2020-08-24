<div class="icon-row">

    <button type="button" class="drag" title="<?php _e( 'Click & drag up or down to change position', 'sticky-social-icons' );?>"><?php _e( 'Drag', 'sticky-social-icons' );?></button>
    <div class="icon-holder"><i class="<?php echo esc_html($icon);?>"></i></div>
    <input type="text" name="url_input" class="url-input" placeholder="URL to open" >
    <button type="button" class="more-options-btn"><?php _e( 'More Options', 'sticky-social-icons' );?></button>
    <button type="button" class="remove-item-btn" ><?php _e( 'Remove', 'sticky-social-icons' );?></button>

    <div class="more-options-container" style="display: none;" >

        <h4 class="title"><?php _e( 'More Options', 'sticky-social-icons' );?><button type="button" class="close-moc" ><?php _e( 'Close', 'sticky-social-icons' );?></button></h4>

        <div class="form-group">
            <label><?php _e( 'Open in New Tab', 'sticky-social-icons' );?></label>
            <div class="moc-input-wrapper">
                <input type="checkbox" value="1" name="open_in_new_tab" checked >
            </div><!--moc-input-wrapper-->
        </div>


        <div class="form-group">
            <label><?php _e( 'Colors', 'sticky-social-icons' );?></label>
            <div class="moc-input-wrapper has-color-picker">
                <?php 
                    $this->color_picker_group( 
                        array(
                            array( 
                                'name'      => 'icon_color', 
                                'default'   => '#000',
                                'label'     => __('Icon Color', 'sticky-social-icons') 
                            ),
                            array( 
                                'name'      => 'icon_color_on_hover', 
                                'default'   => '#fff',
                                'label'     => __('Icon Color On Hover', 'sticky-social-icons') 
                            ),
                            array( 
                                'name'      => 'bck_color', 
                                'default'   => '#fff',
                                'label'     => __('Background Color', 'sticky-social-icons') 
                            ),
                            array( 
                                'name'      => 'bck_color_on_hover', 
                                'default'   => '#000',
                                'label'     => __('Background Color On Hover', 'sticky-social-icons') 
                            ),
                        ) 
                    ); 
                ?>
            </div><!--moc-input-wrapper-->
        </div>
        
    </div>
</div>