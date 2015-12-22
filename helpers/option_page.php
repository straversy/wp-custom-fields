<?php

namespace ST\Components\Wordpress\Helpers;

require_once('fields.php');

use ST\Components\Wordpress\Helpers\Fields as Fields;

/**
 * OptionPage
 *
 * Handles custom option pages for wordpress administration
 *
 * @package    ST Wordpress
 * @author     Sebastien Traversy <sebastientraversy@gmail.com>
 */
class OptionPage {
    private $fields = array();
    
    private $pageTitle;
    private $optionSlug;
    
    function __construct( $_optionSlug, $_pageTitle ){
        $this->pageTitle = $_pageTitle;
        $this->optionSlug = $_optionSlug;
        
        add_action('admin_menu', array($this, 'CreateMenu'));
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
     * CreateMenu
     *
     * Adds the menu_page to the wordpress admin menu
     */
    public function CreateMenu() {
        
        //create new top-level menu
        add_menu_page($this->pageTitle, $this->pageTitle, 'manage_options', $this->optionSlug, array($this, 'SettingsPage'), 'dashicons-universal-access-alt');

        //call register settings function
        add_action( 'admin_init', array($this, 'RegisterSettings') );
    }

    /**
     * RegisterSettings
     *
     * Save the custom options as wordpress settings to enable option save
     */
    public function RegisterSettings() {		
        //register our settings
        foreach($this->fields as $fieldKey => $fieldData) {
            if($fieldData['type'] != 'title')
                register_setting( $this->optionSlug, $fieldKey );
        }
    }

    /**
     * SettingsPage
     * Wordpress admin page display (form)
     */
    public function SettingsPage() {
        ?>
        <div class="wrap">
            <h2><?= $this->pageTitle ?></h2>
            
            <form method="post" action="options.php">
                <?php settings_fields( $this->optionSlug ); ?>
                <?php do_settings_sections( $this->optionSlug ); ?>
                <table class="form-table">
                    <?php
                        foreach($this->fields as $fieldKey => $fieldData) {
                        ?>
                        <tr valign="top">
                            <?php
                                if($fieldData['type'] == 'title') {
                                ?>
                                <th colspan="2"><h3 class="title"><?= $fieldData['label'] ?></h3></th>
                                <?php
                                    } else {
                                ?>
                                <th scope="row"><label for="<?= $fieldKey ?>"><?= $fieldData['label'] ?></label></th>
                                <td>
                                    <?php
                                        $value = get_option($fieldKey);

                                        if(!empty($fieldData['type']) && method_exists('ST\Components\Wordpress\Helpers\Fields', $fieldData['type'])) {
                                            call_user_func( 'ST\Components\Wordpress\Helpers\Fields::' . $fieldData['type'], $fieldKey, $value, $fieldData  );
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
                
                <?php submit_button(); ?>
                
            </form>
        </div>
        <?php 
    }
}
    
?>