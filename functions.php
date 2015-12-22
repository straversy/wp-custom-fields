<?php
    
//To include in functions.php
include 'helpers/meta_box.php';
include 'helpers/option_page.php';



$optionPage = new ST\Components\Wordpress\Helpers\OptionPage( "St-Custom-Settings", "Custom Settings" );

$optionPage->AddField('store_url', 'text', 'Store URL');
$optionPage->AddField('blog_ignore_cat', 'cat_dropdown', 'Category to ignore on blogroll', array('hide_empty' => 0));
$optionPage->AddField('open_hours', 'text', 'Open Hours');

$optionPage->AddField('Sec2Title', 'title', 'Social Networks');
$optionPage->AddField('facebook', 'text', 'Facebook');
$optionPage->AddField('twitter', 'text', 'Twitter');
$optionPage->AddField('pinterest', 'text', 'Pinterest');
$optionPage->AddField('instagram', 'text', 'Instagram');

$optionPage->AddField('Sec3Title', 'title', 'Policies');
$optionPage->AddField('terms_of_use', 'editor', 'Terms of Use');
$optionPage->AddField('privacy_policy', 'editor', 'Privacy Policy');



$metaBox = new ST\Components\Wordpress\Helpers\PostMetaBox("Custom Options", "edh_sectionid", array('post'));

$metaBox->AddField('is_featured', 'checkbox', 'Is Featured Post');
$metaBox->AddField('extra_content', 'editor', 'Is Featured Post');