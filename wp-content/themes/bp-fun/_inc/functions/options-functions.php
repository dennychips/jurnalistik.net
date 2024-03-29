<?php
////////////////////////////////////////////////////////////////////////////////
// multiple option page
////////////////////////////////////////////////////////////////////////////////
function _g($str) { return __($str, 'option-page'); }
////////////////////////////////////////////////////////////////////////////////
// theme option menu for Community
////////////////////////////////////////////////////////////////////////////////

$themename = get_current_theme();
$theme_data = get_theme_data( WP_CONTENT_DIR . '/themes/bp-fun/style.css');
$theme_version = $theme_data['Version'];

$shortname = "tn";
$shortprefix = "_buddyfun_";

// get design style
$alt_stylesheet_path = TEMPLATEPATH . '/_inc/preset-styles/';
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) {
while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
if(stristr($alt_stylesheet_file, ".css") !== false) {
$alt_stylesheets[] = $alt_stylesheet_file;
}
}
}
}
$category_bulk_list = array_unshift($alt_stylesheets, "default.css");


// get featured category
$wp_dropdown_rd_admin = get_categories('hide_empty=0');
$wp_getcat = array();
foreach ($wp_dropdown_rd_admin as $category_list) {
$wp_getcat[$category_list->cat_ID] = $category_list->category_nicename;
}
$category_bulk_list = array_unshift($wp_getcat, "Choose a category");
$choose_count = array("Select a number","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20");

if(function_exists('bp_get_root_domain()')) {
$the_privacy_root = bp_get_root_domain(); } else {
$the_privacy_root = site_url();
}


////////////////////////////////////////////////////////////////////
///// start theme setting
///////////////////////////////////////////////////////////////////

$options = array (
//preset style
array(
"name" => __("Choose Your Fun Theme Preset Style:", TEMPLATE_DOMAIN),
"id" => $shortname. $shortprefix . "custom_style",
"inblock" => "preset-style",
"std" => "default.css",
"type" => "custom-radio",
"options" => $alt_stylesheets),


//adminbar
array (
"name" => __("Choose your adminbar <strong>background color</strong>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "adminbar_bg_color",
"inblock" => "adminbar",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Choose your adminbar <strong>subpage hover color</strong>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "adminbar_hover_bg_color",
"inblock" => "adminbar",
"std" => "",
"type" => "colorpicker"),


//homepage
array(
"name" => __("Do you want show the <strong>Home Articles Block</strong> in homepage? <em>* if 'show', home recent post will be showed in left content. 'show' by default</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "home_featured_block",
"inblock" => "homepage",
"type" => "select",
"std" => "show",
"options" => array("show","hide")),

array(
"name" => __("Choose your <strong>Featured Articles Style</strong> in homepage? <em>* if 'slideshow', you'll have a featured slideshow gallery</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "home_featured_block_style",
"inblock" => "homepage",
"type" => "select",
"hide" => $bp_front_is_activity,
"std" => "article",
"options" => array("article","slideshow")),


array(
"name" => __("Enter your <strong>Featured Articles</strong> Category ID<br /><em>*seperate by commas (,) - sample: 1,4,6,7</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "home_featured_block_cat",
"inblock" => "homepage",
"hide" => $hide_option,
"type" => "text",
"std" => "",
),


array(
"name" => __("How many <strong>Featured Articles</strong> post you want to show?", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "home_featured_block_count",
"inblock" => "homepage",
"type" => "select",
"hide" => $bp_front_is_activity,
"options" => $choose_count,
),


array(
"name" => __("Choose your <strong>Featured Articles Thumbnail Options</strong> in homepage? <em>* if 'attachment', thumbnail will be auto fetch from post attachment gallery first image</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "home_featured_block_attach_type",
"inblock" => "homepage",
"type" => "select",
"hide" => $bp_front_is_activity,
"std" => "attachment",
"options" => array("attachment", "custom-field")),



array(
"name" => __("If you choose <strong>'custom-field'</strong>, enter your desired custom field key for thumbnails here", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "home_featured_block_custom_field",
"inblock" => "homepage",
"type" => "text",
"std" => "",
),



//post
array(
"name" => __("Choose your blog post style<br /><em>*Choose your post style for archives and category</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "blog_post_style",
"inblock" => "post",
"type" => "select",
"std" => "full post",
"options" => array("full post","excerpt post","featured thumbnail with excerpt post")),

array(
"name" => __("Enable or disable post meta<br /><em>*optional - you can remove post category, post tag, post date and post author</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "post_meta_status",
"inblock" => "post",
"type" => "select",
"std" => "enable",
"options" => array("enable","disable")),

array(
"name" => __("Enable Facebook like in single post", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "facebook_like_status",
"inblock" => "post",
"type" => "select",
"std" => "disable",
"options" => array("disable", "enable")),




// css
array(
"name" => __("Choose your body font", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "body_font",
"type" => "select-preview",
"inblock" => "css",
"std" => "Lucida Grande, Lucida Sans, sans-serif",
			"options" => array(
           "Lucida Grande, Lucida Sans, sans-serif",
											"Cantarell, arial, serif",
                                            "Arial, sans-serif",
											"Cardo, arial, serif",
										    "Courier New, Courier, monospace",
											"Crimson Text, arial, serif",
											"Droid Sans, arial, serif",
											"Droid Serif, arial, serif",
								            "Garamond, Georgia, serif",
											"Georgia, arial, serif",
								            "Helvetica, Arial, sans-serif",
											"IM Fell SW Pica, arial, serif",
											"Josefin Sans Std Light, arial, serif",
											"Lobster, arial, serif",
											"Lucida Sans Unicode, Lucinda Grande, sans-serif",
											"Molengo, arial, serif",
											"Neuton, arial, serif",
											"Nobile, arial, serif",
											"OFL Sorts Mill Goudy TT, arial, serif",
											"Old Standard TT, arial, serif",
											"Reenie Beanie, arial, serif",
											"Tahoma, sans-serif",
											"Tangerine, arial, serif",
								            "Trebuchet MS, sans-serif",
								            "Verdana, sans-serif",
											"Vollkorn, arial, serif",
											"Yanone Kaffeesatz, arial, serif",
                                            "Just Another Hand, arial, serif",
                                            "Terminal Dosis Light, arial, serif",
                                            "Ubuntu, arial, serif"
            )
            ),

array(
"name" => __("Choose your headline font", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "headline_font",
"type" => "select-preview",
"inblock" => "css",
"std" => "Lucida Grande, Lucida Sans, sans-serif",
			"options" => array(
            "Lucida Grande, Lucida Sans, sans-serif",
											"Cantarell, arial, serif",
                                            "Arial, sans-serif",
											"Cardo, arial, serif",
										    "Courier New, Courier, monospace",
											"Crimson Text, arial, serif",
											"Droid Sans, arial, serif",
											"Droid Serif, arial, serif",
								            "Garamond, Georgia, serif",
											"Georgia, arial, serif",
								            "Helvetica, Arial, sans-serif",
											"IM Fell SW Pica, arial, serif",
											"Josefin Sans Std Light, arial, serif",
											"Lobster, arial, serif",
											"Lucida Sans Unicode, Lucinda Grande, sans-serif",
											"Molengo, arial, serif",
											"Neuton, arial, serif",
											"Nobile, arial, serif",
											"OFL Sorts Mill Goudy TT, arial, serif",
											"Old Standard TT, arial, serif",
											"Reenie Beanie, arial, serif",
											"Tahoma, sans-serif",
											"Tangerine, arial, serif",
								            "Trebuchet MS, sans-serif",
								            "Verdana, sans-serif",
											"Vollkorn, arial, serif",
											"Yanone Kaffeesatz, arial, serif",
                                            "Just Another Hand, arial, serif",
                                            "Terminal Dosis Light, arial, serif",
                                            "Ubuntu, arial, serif"
            )
            ),

array(
"name" => __("Choose your font size", TEMPLATE_DOMAIN),
"box"=> "1",
"inblock" => "css",
"id" => $shortname . $shortprefix . "font_size",
"type" => "select",
"std" => "normal",
"options" => array("normal", "small", "medium", "bigger", "largest")),


array (
"name" => __("Choose your global body text color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "body_text_color",
"inblock" => "typo",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Global links color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "global_links",
"inblock" => "css",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Global links <strong>hover</strong> color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "global_hover_links",
"inblock" => "css",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Post title links color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "post_title_links",
"inblock" => "css",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Post title links <strong>hover</strong> color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "post_title_hover_links",
"inblock" => "css",
"std" => "",
"type" => "colorpicker"),

array(
"name" => __("Do you want to enable gloss effect to sidebar header and top navigation?", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "header_gloss_on",
"inblock" => "css",
"type" => "select",
"std" => "disable",
"options" => array("disable","enable")),


//background setting
array (
"name" => __("Choose your background color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "bg_color",
"inblock" => "bg",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Choose your content body background color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "content_bg_color",
"inblock" => "bg",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Choose your content gridline color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "content_line_bg_color",
"inblock" => "bg",
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("Insert your <strong>background image</strong> full url here<br /><em>*you can upload your image in <a href='media-new.php'>media panel</a> and copy paste the url here</em>", TEMPLATE_DOMAIN),
"inblock" => "bg",
"id" => $shortname . $shortprefix . "bg_image",
"std" => "",
"type" => "text"),


array(
"name" => __("Background Images Repeat", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "bg_image_repeat",
"inblock" => "bg",
"type" => "select",
"std" => "no-repeat",
"options" => array("no-repeat", "repeat", "repeat-x", "repeat-y")),

array(
"name" => __("Background Images Attachment", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "bg_image_attachment",
"inblock" => "bg",
"type" => "select",
"std" => "fixed",
"options" => array("fixed", "scroll")),

array(
"name" => __("Background Images Horizontal Position", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "bg_image_horizontal",
"inblock" => "bg",
"type" => "select",
"std" => "left",
"options" => array("left", "center", "right")),


array(
"name" => __("Background Images Vertical Position", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "bg_image_vertical",
"inblock" => "bg",
"type" => "select",
"std" => "top",
"options" => array("top", "center", "bottom")),



//welcome message
array(
"name" => __("Check this box to disable the welcome message", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "call_signup_on",
"box"=> "2",
"inblock" => "signup",
"type" => "checkbox",
"std" => "disable"),

array(
"name" => __("Edit your welcome message text here&nbsp;&nbsp;&nbsp;<em>*html allowed</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "call_signup_text",
"inblock" => "signup",
"type" => "textarea",
"std" => "Welcome to your BuddyPress Fun theme!<br />
<small>Change or remove the text here using the theme options</small>",
),

array(
"name" => __("Enter your new signup or register full url (http://sitename.com/register) link here", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "call_signup_button_text_link",
"inblock" => "signup",
"type" => "text",
"std" => "",
),

array(
"name" => __("Edit your welcome message button text here", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "call_signup_button_text",
"inblock" => "signup",
"type" => "text",
"std" => "Join Here",
),



//header
array(
"name" => __("Insert your <strong>logo</strong> full url here<br /><em>*you can upload your logo in <a href='media-new.php'>media panel</a> and copy paste the url here</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "header_logo",
"inblock" => "header",
"type" => "text",
"std" => "",
),


array(
"name" => __("Do you want to enable custom image header?", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "header_on",
"box"=> "2",
"inblock" => "header",
"type" => "select",
"std" => "disable",
"options" => array("disable","enable")),


array(
"name" => __("Your prefered custom image header height", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "image_height",
"inblock" => "header",
"type" => "text",
"std" => "150",
),


array (
"name" => __("Header background color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "header_color",
"inblock" => "header",
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("Header text color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "header_text_color",
"inblock" => "header",
"std" => "",
"type" => "colorpicker"),


//Navigation
array (
"name" => __("Top navigation block color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "topnav_block_color",
"inblock" => "nav",
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("Top navigation links color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "topnav_block_link_color",
"inblock" => "nav",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Navigation background color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "nav_bg_color",
"inblock" => "nav",
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("Navigation links color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "nav_text_link_color",
"inblock" => "nav",
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("Navigation links hover color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "nav_text_link_hover_color",
"inblock" => "nav",
"std" => "",
"type" => "colorpicker"),



// footer
array (
"name" => __("Footer background color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "footer_color",
"inblock" => "footer",
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("Footer text color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "footer_text_color",
"inblock" => "footer",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Footer text link color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "footer_text_link_color",
"inblock" => "footer",
"std" => "",
"type" => "colorpicker"),



// sidebar
array (
"name" => __("Sidebar box background color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "sidebar_box_color",
"inblock" => "sidebar",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Sidebar gridline color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "sidebar_line_bg_color",
"inblock" => "sidebar",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Sidebar box text color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "sidebar_box_text_color",
"inblock" => "sidebar",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Sidebar box text link color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "sidebar_box_text_link_color",
"inblock" => "sidebar",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Sidebar box text link <strong>hover</strong> color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "sidebar_box_text_link_hover_color",
"inblock" => "sidebar",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Sidebar H2 header background color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "sidebar_header_color",
"inblock" => "sidebar",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Sidebar H2 header text color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "sidebar_header_text_color",
"inblock" => "sidebar",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("Sidebar H2 header text link color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "sidebar_header_text_link_color",
"inblock" => "sidebar",
"std" => "",
"type" => "colorpicker"),






//buddypress privacy
//community navigation dropdown
array(
"name" => __("Enable or disable <strong>Community Dropdown</strong> Navigation for BuddyPress Component<br /><em>* if disable, there will be no Community Dropdown for members, groups, forums etc</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "community_dropdown",
"box"=> "1",
"inblock" => "buddypress",
"type" => "select",
"std" => "enable",
"options" => array("enable","disable")),

array(
"name" => __("Do you want to enable <strong>privacy</strong> on all members profile for not logged in user<br /><em>* only logged in user can view members profile and members directory. 'disable' by default</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "privacy_status",
"header-title" => __("Global Privacy Setting", TEMPLATE_DOMAIN),
"box"=> "1",
"bp-only" => $bp_existed,
"inblock" => "buddypress",
"type" => "select",
"std" => "disable",
"options" => array("disable","enable")),

array(
"name" => __("if you enable the <strong>privacy</strong> on all members profile for none logged in user, insert the full url link they will be redirect to for non logged in users<br /><em>*optional - leave empty for default<br />default are buddypress register link<br /> " . site_url() . '/' .BP_REGISTER_SLUG . '/' . "</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "privacy_redirect",
"box"=> "1",
"inblock" => "buddypress",
"type" => "text",
"std" => "",
),



array(
"name" => __("Do you want to enable <strong>friend only privacy</strong> for user profile<br /><em>* only friend can view friend profile. network/super admin were exclude from this condition. 'disable' by default</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "friend_privacy_status",
"header-title" => __("Users Privacy Setting", TEMPLATE_DOMAIN),
"box"=> "1",
"bp-only" => $bp_existed,
"inblock" => "buddypress",
"type" => "select",
"std" => "disable",
"options" => array("disable","enable")),

array(
"name" => __("if you enable the <strong>friend privacy</strong> for user profile, insert the full url link they will be redirect when viewing a none friend user<br /><em>*optional - leave empty for default<br />default are buddypress homepage link<br /> " . site_url() . '/' . "</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "friend_privacy_redirect",
"box"=> "1",
"inblock" => "buddypress",
"type" => "text",
"std" => "",
),

array(
"name" => __("Do you want to allowed only <strong>admin and moderators</strong> to create group? <em>* if yes, normal users cannot create group and can only join groups created by admin and editors. 'no' by default</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "create_group_status",
"header-title" => __("Groups Privacy Setting", TEMPLATE_DOMAIN),   
"box"=> "1",
"inblock" => "buddypress",
"bp-only" => $bp_existed,
"type" => "select",
"std" => "no",
"options" => array("no","yes")),


array(
"name" => __("if you enable for the only <strong>admins and editors</strong> to create group, insert the full url link they will be redirect to for non admins and editors users when they click <strong>create group</strong> button<br /><em>*optional - leave empty for default<br />default are buddypress root domain<br /> " . $the_privacy_root . '/' . "</em>", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "create_group_redirect",
"box"=> "1",
"inblock" => "buddypress",
"type" => "text",
"std" => "",
),


array(
"name" => __("Do you want to enable facebook LIKE in activity stream", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "stream_facebook_like_status",
"inblock" => "buddypress",
"type" => "select",
"std" => "disable",
"options" => array("disable","enable")),


//buddypress meta
array (
"name" => __("<strong>SPAN Meta block</strong> background color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "span_meta_color",
"inblock" => "buddypress",
"header-title" => __("BuddyPress Span Meta CSS Setting", TEMPLATE_DOMAIN),
"header-img-link" => '/_inc/admin/spanmeta.png',
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("<strong>SPAN Meta block</strong> border color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "span_meta_border_color",
"inblock" => "buddypress",
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("<strong>SPAN Meta block</strong> text color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "span_meta_text_color",
"inblock" => "buddypress",
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("<strong>SPAN Meta block</strong> background hover color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "span_meta_hover_color",
"inblock" => "buddypress",
"std" => "",
"type" => "colorpicker"),

array (
"name" => __("<strong>SPAN Meta block</strong> hover border color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "span_meta_border_hover_color",
"inblock" => "buddypress",
"std" => "",
"type" => "colorpicker"),


array (
"name" => __("<strong>SPAN Meta block</strong> hover text color", TEMPLATE_DOMAIN),
"id" => $shortname . $shortprefix . "span_meta_text_hover_color",
"inblock" => "buddypress",
"std" => "",
"type" => "colorpicker")

);



function buddyfun_admin_panel() {
global $themename, $theme_version, $shortname, $options, $blog_id, $bp_existed, $bp_front_is_activity;
if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'. $themename . __(' settings saved.', TEMPLATE_DOMAIN) . '</strong></p></div>';
if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'. $themename . __(' settings reset.', TEMPLATE_DOMAIN) . '</strong></p></div>';
?>


<div id="options-panel">
<div id="options-head"><h2><?php echo $themename; ?> <?php _e("Theme Options", TEMPLATE_DOMAIN); ?></h2>
<div class="theme-versions"><?php _e("Version",TEMPLATE_DOMAIN); ?> <?php echo $theme_version; ?></div>
</div>

<div id="note-box">
<strong><?php _e('Optional Features:', TEMPLATE_DOMAIN); ?></strong><br />
<?php _e('If you want to customize the theme options you MUST have default.css selected in the BuddyPress Fun Preset Styles section.', TEMPLATE_DOMAIN); ?>
</div>


<div id="sbtabs">

<div class="tabmc">
<ul class="ui-tabs-nav" id="tabm">
<?php
$value_var_global = array('adminbar','homepage','css', 'bg', 'nav', 'header', 'post', 'sidebar', 'footer','buddypress','signup','preset-style');
?>

<?php if($bp_existed == 'true') { if($blog_id == BP_ROOT_BLOG) {  ?>
<li><a href="#tab1"><?php _e("BP Admin Bar Settings", TEMPLATE_DOMAIN); ?></a></li>
<?php } } ?>
<li><a href="#tab2"><?php _e("Featured Settings",TEMPLATE_DOMAIN); ?></a></li>
<li><a href="#tab3"><?php _e("CSS Settings",TEMPLATE_DOMAIN); ?></a></li>
<li><a href="#tab4"><?php _e("Background Settings",TEMPLATE_DOMAIN); ?></a></li>
<li><a href="#tab5"><?php _e("Navigation Settings",TEMPLATE_DOMAIN); ?></a></li>
<li><a href="#tab6"><?php _e("Header Settings",TEMPLATE_DOMAIN); ?></a></li>
<li><a href="#tab7"><?php _e("Post Settings",TEMPLATE_DOMAIN); ?></a></li>
<li><a href="#tab8"><?php _e("Sidebar Settings",TEMPLATE_DOMAIN); ?></a></li>
<li><a href="#tab9"><?php _e("Footer Settings",TEMPLATE_DOMAIN); ?></a></li>
<?php if($bp_existed == 'true') { //only showed if buddypress installed ?>
<li><a href="#tab10"><?php _e("BuddyPress Settings",TEMPLATE_DOMAIN); ?></a></li>
<?php } ?>
<li><a href="#tab11"><?php _e("Welcome Message Settings",TEMPLATE_DOMAIN); ?></a></li>
<li><a href="#tab12"><?php _e("Preset Styles",TEMPLATE_DOMAIN); ?></a></li>
</ul>
</div>


<div class="tabmc-right">

<div class="tabc">

<form action="" method="post">

<?php $vc = 1; foreach ($value_var_global as $value_var) { ?>
<ul style="" class="ui-tabs-panel<?php if($vc > 0) { ?> ui-tabs-hide<?php } ?>" id="tab<?php echo $vc; ?>">
<li>
<?php foreach ($options as $value) { ?>

<?php if (($value['inblock'] == $value_var) && ($value['header-title'] != "")) { // if we got header title for option ?>
<h4><?php echo stripslashes($value['header-title']); ?><?php if(!empty($value['header-img-link'])) { ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="whatsthis" href="#thumb">(What's this?)<span><img src="<?php echo get_template_directory_uri(); ?><?php echo stripslashes($value['header-img-link']); ?>" /></span></a><?php } ?></h4>
<?php } ?>

<?php if (($value['inblock'] == $value_var) && ($value['type'] == "text")) { // setting ?>
<div class="tab-option">
<div class="description"><?php echo $value['name']; ?><br /><span><?php echo $value['description']; ?></span></div>
<div class="input-option"><p><input name="<?php echo $value['id']; ?>" class="myfield" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id']) ); } else { echo stripslashes($value['std']); } ?>" /></p></div>
</div>

<?php } else if (($value['inblock'] == $value_var) && ($value['type'] == "ajax-file-upload")) { // setting ?>

<div class="tab-option">
<div class="description"><?php echo $value['name']; ?><br /><span><?php echo $value['description']; ?></span></div>
<div class="input-option">
</div>
</div>

<?php } elseif (($value['inblock'] == $value_var) && ($value['type'] == "checkbox") ) { // setting ?>

<?php if(get_option($value['id'])) { $checked = "checked=\"checked\""; } else { $checked = ""; } ?>
<div class="checkbox-box">
<div class="description"><p><input type="<?php echo $value['type']; ?>" class="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php echo $value['id']; ?>" <?php echo $checked; ?> />&nbsp;&nbsp;<?php echo $value['name']; ?></p></div></div>

<?php } elseif (($value['inblock'] == $value_var) && ($value['type'] == "textarea")) { // setting ?>

<div class="tab-option">
<?php
$valuex = $value['id'];
$valuey = stripslashes($valuex);
$video_code = get_option($valuey);
?>
<div class="description"><?php echo $value['name']; ?><br /><span><?php echo $value['description']; ?></span></div>
<div class="input-option"><p><textarea name="<?php echo $valuey; ?>" class="mytext" cols="40%" rows="8" /><?php if ( get_option($valuey) != "") { echo stripslashes($video_code); } else { echo $value['std']; } ?>
</textarea></p></div>
</div>
<?php } elseif (($value['inblock'] == $value_var) && ($value['type'] == "colorpicker") ) { // setting ?>
<?php $i == $i++ ; ?>
<div class="tab-option">
<div class="description"><?php echo $value['name']; ?><br /><span><?php echo $value['description']; ?></span></div>
<div class="input-option"><p><input class="color {required:false,hash:true}" name="<?php echo $value['id']; ?>" id="colorpickerField<?php echo $i; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" /></p></div>
</div>

<?php } elseif (($value['inblock'] == $value_var) && ($value['type'] == "select-preview") ) { // setting ?>
<div class="tab-option">
<div class="description"><?php echo $value['name']; ?><br /><span><?php echo $value['description']; ?></span></div>
<div class="input-option"><p><select name="<?php echo $value['id']; ?>" class="myselect" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $option) { ?>
<option style="font-family:<?php echo $option; ?>;" <?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == get_option( $value['std']) ) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
<?php } ?>
</select>
</p>
</div>
</div>

<?php } elseif (($value['inblock'] == $value_var) && ($value['type'] == "select") ) { // setting ?>
<div class="tab-option">
<div class="description"><?php echo $value['name']; ?><br /><span><?php echo $value['description']; ?></span></div>
<div class="input-option"><p><select name="<?php echo $value['id']; ?>" class="myselect" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $option) { ?>
<option<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
<?php } ?>
</select>
</p>
</div>
</div>
<?php } elseif (($value['inblock'] == $value_var) && ($value['type'] == "custom-radio") ) { // setting ?>
<div class="tab-option">
<div class="description"><?php echo $value['name']; ?><br /><span><?php echo $value['description']; ?></span></div>
<div class="input-option"><ul id="preset-ul">
<?php foreach ($value['options'] as $option) {
$screenshot_img = substr($option,0,-4);
$radio_setting = get_option($value['id']);
if($radio_setting != '') {
if (get_option($value['id']) == $option) { $checked = "checked=\"checked\""; } else { $checked = ""; }
} else {
if(get_option($value['id']) == $value['std'] ){ $checked = "checked=\"checked\""; } else { $checked = ""; }
} ?>

<li>
<div class="theme-img"><img src="<?php echo get_template_directory_uri(); ?>/_inc/preset-styles/images/<?php echo $screenshot_img . '.png'; ?>" alt="<?php echo $screenshot_img; ?>" /></div>
<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $option; ?>" <?php echo $checked; ?> /><?php echo $option; ?>
</li>

<?php } ?>
</ul>
</div>
</div>
<?php } ?>
<?php } ?>
</li></ul>
<?php $vc++; } ?>

<div class="submit">
<input name="save" type="submit" class="button-primary sbutton" value="<?php echo esc_attr(__('Save All Options',TEMPLATE_DOMAIN)); ?>" />
<input type="hidden" name="action" value="save" />
</div>
</form>

</div>

</div><!-- tabmc-right -->

</div><!-- sbtabs -->

<div id="reset-box">
<form method="post">
<div class="submit">
<input name="reset" type="submit" class="sbutton" onclick="return confirm('Are you sure you want to reset all saved settings?. This action cannot be restore.')" value="<?php echo esc_attr(__('Reset All Options',TEMPLATE_DOMAIN)); ?>" />
<input type="hidden" name="action" value="reset" />&nbsp;&nbsp;<?php _e("by pressing this reset button, all your saved setting for this theme will be deleted and restore to factory default.",TEMPLATE_DOMAIN); ?>
</div>
</form>
</div>

</div><!-- end option-panel -->
<?php
}




function buddyfun_admin_register() {
global $themename, $shortname, $options;
if ( $_GET['page'] == 'options-functions.php' ) {
if ( 'save' == $_REQUEST['action'] ) {
foreach ($options as $value) {
update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
foreach ($options as $value) {
if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }
header("Location: themes.php?page=options-functions.php&saved=true");
die;
} else if( 'reset' == $_REQUEST['action'] ) {
foreach ($options as $value) {
delete_option( $value['id'] ); }
header("Location: themes.php?page=options-functions.php&reset=true");
die;
}
}

add_theme_page(_g ($themename . __(' Theme Options', TEMPLATE_DOMAIN)),  _g (__('Theme Options', TEMPLATE_DOMAIN)),  'edit_theme_options', 'options-functions.php', 'buddyfun_admin_panel');
}

add_action('admin_menu', 'buddyfun_admin_register');

?>