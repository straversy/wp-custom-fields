<?php

namespace ST\Components\Wordpress\Helpers;

require_once('fields.php');

use ST\Components\Wordpress\Helpers\Fields as Fields;

/**
 * PostMetaBox
 *
 * Handles custom meta for wordpress post edit
 *
 * @package    ST Wordpress
 * @author     Sebastien Traversy <sebastientraversy@gmail.com>
 */
class PostMetaBox {
    private $fields = array();

    private $boxTitle;
    private $boxId;
    private $postTypes;

    function __construct( $_boxTitle, $_boxId, $_postTypes = array('post') ){
        $this->boxTitle = $_boxTitle;
        $this->boxId = $_boxId;
        $this->postTypes = $_postTypes;


        add_action( 'add_meta_boxes',  array($this, 'AddMetaBox') );
        add_action( 'save_post', array($this, 'Save')  );
    }

    /**
     * AddField
     *
     * Save field parameters in private var to generate form output and save logic
     *
     * @param $FieldID  name of the meta field
     * @param $Type  refers to the function name in the field class for field callback
     * @param $Label
     * @param array $Options
     */
    public function AddField($FieldID, $Type, $Label, $Options = array()) {
        $this->fields[$FieldID] = array('type' => $Type, 'label' => $Label, 'options' => $Options);
    }

    /**
     * AddMetaBox
     *
     * Callback for add_meta_box hook, adds the meta_box object to the admin page
     */
    public function AddMetaBox() {

        foreach ( $this->postTypes as $postType ) {

            add_meta_box(
                $this->boxId,
                $this->boxTitle,
                array($this, 'AdminDisplay'),
                $postType
            );
        }
    }

    /**
     * Save
     *
     * Handles save post hook when custom fields applied
     *
     * @param $post_id
     */
    public function Save( $post_id ) {

        // Check if our nonce is set.
        if ( ! isset( $_POST[$this->boxId.'_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST[$this->boxId.'_nonce'], $this->boxId ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }

        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        foreach($this->fields as $fieldKey => $fieldData) {
            if ( ! isset( $_POST[$fieldKey])) {
                delete_post_meta($post_id, $fieldKey);
            } else {
                update_post_meta( $post_id, $fieldKey, $_POST[$fieldKey] );
            }
        }
    }

    /**
     * AdminDisplay
     *
     * @param $post
     */
    public function AdminDisplay($post){

        wp_nonce_field( $this->boxId, $this->boxId.'_nonce' );
        ?>
        <table class="form-table">
        <?php
        foreach($this->fields as $fieldKey => $fieldData) {
            ?>

            <tr valign="top">
                <?php
                if($fieldData['type'] == 'title') {
                    ?>
                    <th colspan="2"><h2 class="title"><?= $fieldData['label'] ?></h2></th>
                    <?php
                } else {
                    ?>
                    <th scope="row"><label for="<?= $fieldKey ?>"><?= $fieldData['label'] ?></label></th>
                    <td>
                        <?php
                        $value = get_post_meta( $post->ID, $fieldKey, true );
                        if(!empty($fieldData['type']) && method_exists('ST\Components\Wordpress\Helpers\Fields', $fieldData['type'])) {
                            call_user_func('ST\Components\Wordpress\Helpers\Fields::' . $fieldData['type'], $fieldKey, $value, $fieldData  );
                        } else {
                            Fields::text($fieldKey, $value, $fieldData);
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>

            <?php
        }
        ?>
        </table>
        <?php
    }

}

?>