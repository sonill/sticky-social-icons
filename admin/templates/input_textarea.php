<?php
    
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    printf(
        '<textarea name="%1$s" id="%1$s" %2$s >%3$s</textarea>',
        $args['name'],
        $attr,
        $db_value
    );