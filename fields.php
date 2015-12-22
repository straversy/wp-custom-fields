<?php

namespace ST\Components\Wordpress\Helpers;

/**
 * Fields
 *
 * Handles fields for custom option pages and custom post meta classes
 *
 * @package    ST Wordpress
 * @author     Sebastien Traversy <sebastientraversy@gmail.com>
 */
class Fields {

    /**
     * editor
     *
     * Handles wordpress editor field
     *
     * @param string $fieldKey  name of field
     * @param string $value  prepopulated value for field
     */
    public static function editor($fieldKey, $value) {
        wp_editor( $value , $fieldKey );
    }


    /**
     * cat_dropdown
     *
     * Handles wordpress category dropdown field
     *
     * @param string $fieldKey  name of field
     * @param string $value  prepopulated value for field
     * @param array $fieldData  extra-configs
     */
    public static function cat_dropdown($fieldKey, $value, $fieldData) {

        $args = array(
            'name' => $fieldKey,
            'selected' => $value,
            'show_option_none' => 'Choose...'
        );

        $args = array_merge($args, $fieldData['options']);

        wp_dropdown_categories( $args );
    }

    /**
     * checkbox
     *
     * Handles checkbox field
     *
     * @param string $fieldKey  name of field
     * @param string $value  prepopulated value for field
     */
    public static function checkbox($fieldKey, $value) {
        ?>
        <input type="checkbox" id="<?= $fieldKey ?>" name="<?= $fieldKey ?>" <?= $value == 1 ? 'checked' : '' ?> value="1" />
        <?php
    }

    /**
     * text
     *
     * Handles text/default field
     *
     * @param string $fieldKey  name of field
     * @param string $value  prepopulated value for field
     */
    public static function text($fieldKey, $value, $fieldData) {
        ?>
        <input size="40" type="text" id="<?= $fieldKey ?>" name="<?= $fieldKey ?>" value="<?php echo esc_attr( $value ); ?>" />
        <?php
    }

}

?>