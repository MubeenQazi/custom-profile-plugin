<?php

/*
 * Plugin Name: Employee Plugin
 * Description: A simple wordpress plugin that allows admin to upload excel files and generate post type name Employee
 * Version: 1.0
 * Author: Custom
 * Author URI: http://squadcoders.com
 * Text Domain: employee
 */


// security checck wordpress available from function exists function


// includes_url( './vendor/autoload.php', $scheme = null );
// includes_url( './vendor/autoload.php', $scheme = null );
require_once('vendor/autoload.php');


if(!function_exists('add_action')){
	die("hi there ! i'm just a plugin, not  much i can do when called directly.");
}

//Setup
// constant variables
define('BASE_PLUGIN_URL',__FILE__);



function employee_Cpt(){

	$labels = array(
       'name'               =>   _x('Employees', 'post type general name'),
       'singular_name'=>   _x('Employee', 'post type singular name'),
       'menu_name'          =>   _x('Employees', 'admin menu'),
       'name_admin_bar'     =>   _x('Employee', 'add new on admin bar'),
       'add_new'                    =>   _x('Add New', ''),
       'add_new_item'        =>   __('Add New employee'),
       'edit_item'           =>   __('Edit Employee'),
       'new_item'            =>   __('New Employee'),
       'all_items'           =>   __('All Employee'),
       'view_item'           =>   __('View Employee'),
       'search_items'        =>   __('Search Employees'),
       'not_found'           =>   __('No Employees found'),
       'not_found_in_trash' =>   __('No Employees found in Trash'),
       'parent_item_colon'  =>   __('Parent employees:'),                     
  	);

    $args = array(
       'hierarchical'       =>  true,    
       'labels'             =>  $labels,
       'public'             =>  true,
       'publicly_queryable' =>  true, 
       'description'        => __('Description.'),
       'show_ui'            =>  true,
       'show_in_menu'       =>  true,
       'show_in_nav_menus'  =>  true,                
       'query_var'          =>  true,
       'rewrite'            =>  true,
       'query_var'          =>  true,
       'rewrite'            =>  array('slug' => 'employee'),
       'capability_type'    =>  'page',
       'has_archive'        =>  true,
       'menu_position'      =>   22,
       "show_in_rest"       =>  true,
       'supports'           =>  array( 'title', 'editor', 'author', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields' )
    );

    register_post_type('employee', $args);   

}

add_action('admin_menu', 'employee_cpt_submenu');

//admin_menu callback function

function employee_cpt_submenu(){

    add_submenu_page(
        'edit.php?post_type=employee', //$parent_slug
        'Employee Import',  //$page_title
        'Import',        //$menu_title
        'manage_options',           //$capability
        'import',//$menu_slug
        'import_render_page'//$function
    );
    add_submenu_page(
        'edit.php?post_type=employee', //$parent_slug
        'Employee Export',  //$page_title
        'Export',        //$menu_title
        'manage_options',           //$capability
        'export',//$menu_slug
        'export_render_page'//$function
    );
    add_submenu_page(
        'edit.php?post_type=employee', //$parent_slug
        'Employees Report',  //$page_title
        'Get Report',        //$menu_title
        'manage_options',           //$capability
        'get_report',//$menu_slug
        'get_report_render_page'//$function
    );

}

//add_submenu_page callback function

function import_render_page() {

     ?>
     <h2> Here you can Uploads xlsx files! </h2>
	<form action="<?php echo admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="import_excel">
	<input type="hidden" name="data" value="foobarid">
	<div>
		<span>CSV Files:</span>
		<input type="file" name="upload">
		<button  type="submit" class="btn btn-primary">Import</button>
	</div>
	</form>
	
	<?php

}
function export_render_page() {

	?>

    <h2> Export xlsx files! </h2>
	<form action="<?php echo admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="export_excel">
	<div>
		<button  type="submit" class="btn btn-primary">Export</button>
	</div>
	</form>
	
	<?php
}

function get_report_render_page(){

	?>
	
	<h2> Generate Report! </h2>
	<form action="<?php echo admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="report_excel">
		<div>
			<button  type="submit" class="btn btn-primary">Get Report</button>
		</div>
	</form>

	<?php
}


add_action( 'admin_post_import_excel', 'prefix_admin_import_excel' );
add_action( 'admin_post_nopriv_import_excel', 'prefix_admin_import_excel' );
 
function prefix_admin_import_excel() {
	
	if ( $xlsx = SimpleXLSX::parse($_FILES['upload']['tmp_name']) ) {
		$xlsxData = $xlsx->rows();

		$headings = $xlsxData[0];
    	//echo "<pre>"; print_r($headings);  echo "</pre>";wp_die();
    	
		if(!is_wp_error($post_id)){
		  //the post is valid
			wp_redirect( admin_url('edit.php?post_type=employee') );
		}else{
		  //there was an error in the post insertion, 
		  echo $post_id->get_error_message();
		}
		
		foreach ($xlsxData as $fkey => $fileData) {
			if ($fkey == 0) {
				# Should be blank as per condition...
			}else{

				$projId                     = $fileData[0]; 
				// $lastUpdate                 = $fileData[1];
				// $firstName                  = $fileData[2];
				// $legalName                  = $fileData[3];
				// $lastName                   = $fileData[4];
				// $spouseName                 = $fileData[5];
				// $mappeDesired               = $fileData[6];
				// $currentAddress             = $fileData[7];
				// $secondAddress              = $fileData[8];
				// $furloughAddress            = $fileData[9];
				// $currentPhone               = $fileData[10];
				// $secondPhone                = $fileData[11];
				// $furloughPhone              = $fileData[12];
				// $fieldCellPhone             = $fileData[13];
				// $spousePhone                = $fileData[14];
				// $spousePhoneUS              = $fileData[15];
				// $vonageNumber               = $fileData[16];
				// $skypeNumber                = $fileData[17];
				// $magicJackNumber            = $fileData[18];
				// $furloughCell               = $fileData[19];
				// $preferredShippingCo        = $fileData[20];
				// $shippingAdress             = $fileData[21];
				// $websiteAddress             = $fileData[22];
				// $personalEmail              = $fileData[23];
				// $homeChurch                 = $fileData[24];
				// $homeChurchPhone            = $fileData[25];
				// $homeChurchPastor           = $fileData[26];
				// $homeChurchEmail            = $fileData[27];
				// $serviceInformation         = $fileData[28];
				// $fieldInformation           = $fileData[29];
				// $emergencyContact           = $fileData[30];
				// $scheduledFurloughStartDate = $fileData[31];
				// $scheduledFurloughEndDate   = $fileData[32];
				// $generatePassword           = $fileData[33];
				//echo "<pre>"; print_r($fileData); echo "</pre>";wp_die();


				$check_title=get_page_by_title($projId, 'OBJECT', 'employee');

				//also var_dump($check_title) for testing only

				if (empty($check_title) ){
				$args = array(
					'post_type' => 'employee',
					'post_title'    => $projId,
				    'post_content'  => '',
				    'post_status'   => 'publish',
				    'post_author'   => 1,
				    'post_category' => array( 8,39 ),
				    
				);

				$post_id = wp_insert_post( $args );

				}

				else {

				$args = array(
					'post_type' => 'employee',
					'ID' =>  $check_title->ID,
					'post_title'    => $projId,
				    'post_content'  => '',
				    'post_status'   => 'publish',
				    'post_author'   => 1,
				    'post_category' => array( 8,39 ),
				    
				);

				$post_id = wp_update_post( $args );

				}

				// $args = array(
				// 	'post_type' => 'employee',
				// 	'post_title'    => $projId,
				//     'post_content'  => '',
				//     'post_status'   => 'publish',
				//     'post_author'   => 1,
				//     'post_category' => array( 8,39 ),
				    
				// );
				// $post_id = wp_insert_post($args);

				//update_post_meta( $post_id, 'ProjID', $projId );
				foreach ($fileData as $fileDataKey => $fileDataValue) {
					// echo "<pre>"; print_r([$headings[$fileDataKey],$fileDataValue]); echo "</pre>";
					$customHeading = str_replace(' ', '_', $headings[$fileDataKey]);
					update_post_meta($post_id, $customHeading, $fileDataValue);
				}

				// update_post_meta( $post_id, 'lastUpdate', $lastUpdate );
				// update_post_meta( $post_id, 'firstName', $firstName );
				// update_post_meta( $post_id, 'legalName', $legalName );
				// update_post_meta( $post_id, 'lastName', $lastName );
				// update_post_meta( $post_id, 'spouseName', $spouseName );
				// update_post_meta( $post_id, 'mappeDesired', $mappeDesired );
				// update_post_meta( $post_id, 'currentAddress', $currentAddress );
				// update_post_meta( $post_id, 'secondAddress', $secondAddress );
				// update_post_meta( $post_id, 'furloughAddress', $furloughAddress );
				// update_post_meta( $post_id, 'currentPhone', $currentPhone );
				// update_post_meta( $post_id, 'secondPhone', $secondPhone );
				// update_post_meta( $post_id, 'furloughPhone', $furloughPhone );
				// update_post_meta( $post_id, 'fieldCellPhone', $fieldCellPhone );
				// update_post_meta( $post_id, 'spousePhone', $spousePhone );
				// update_post_meta( $post_id, 'spousePhoneUS', $spousePhoneUS );
				// update_post_meta( $post_id, 'vonageNumber', $vonageNumber );
				// update_post_meta( $post_id, 'skypeNumber', $skypeNumber );
				// update_post_meta( $post_id, 'magicJackNumber', $magicJackNumber );
				// update_post_meta( $post_id, 'furloughCell', $furloughCell );
				// update_post_meta( $post_id, 'preferredShippingCo', $preferredShippingCo );
				// update_post_meta( $post_id, 'shippingAdress', $shippingAdress );
				// update_post_meta( $post_id, 'websiteAddress', $websiteAddress );
				// update_post_meta( $post_id, 'personalEmail', $personalEmail );
				// update_post_meta( $post_id, 'homeChurch', $homeChurch );
				// update_post_meta( $post_id, 'homeChurchPhone', $homeChurchPhone );
				// update_post_meta( $post_id, 'homeChurchPastor', $homeChurchPastor );
				// update_post_meta( $post_id, 'homeChurchEmail', $homeChurchEmail );
				// update_post_meta( $post_id, 'serviceInformation', $serviceInformation );
				// update_post_meta( $post_id, 'fieldInformation', $fieldInformation );
				// update_post_meta( $post_id, 'emergencyContact', $emergencyContact );
				// update_post_meta( $post_id, 'scheduledFurloughStartDate', $scheduledFurloughStartDate );
				// update_post_meta( $post_id, 'scheduledFurloughEndDate', $scheduledFurloughEndDate );
				// update_post_meta( $post_id, 'generatePassword', $generatePassword );
			}
		}

	} else {
		echo SimpleXLSX::parseError();
	}
	die;

    $file = $_FILES['file']['tmp_name'];
		$handle = fopen($file, "r");
		$c = 0;
		while(($filesop = fgetcsv($handle, 1000, ",")) !== false){
			$fname = $filesop[0];
			$lname = $filesop[1];

			wp_die();
			// $sql = "insert into excel(fname,lname) values ('$fname','$lname')";
			// $stmt = mysqli_prepare($conn,$sql);
			// mysqli_stmt_execute($stmt);

			$c = $c + 1;
	   	}


    wp_die();
}

// Export EXCEL File
function prefix_admin_export_excel(){


	// $employees = [
	//     ["ProjID","LastUpdate","FirstName","FirstLegalName","LastName","SpouseName","MapperDesired","Address1","Address2","Address3","Phone1","Phone2","Phone3","Cellphone","Spouse's Cellphone","Spouse's US Cell","VonageNumber","SkypeNumber","MagicJackJumber","FurloughCell","Preferred Shipping Co","Shipping Notes","WebsiteAddress","Personal Email Address","Home Church","Home Church Phone","Home Church Pastor","Home Church e-mail","Field Information","Emergency Contact","Scheduled Furlough Start Date","Scheduled Furlough End Date","Password"]
	// ];
	$args = array(  
        'post_type' => 'employee',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title', 
        'order' => 'ASC',
    );

    $loop = new WP_Query( $args ); 
    $result = [];
    $employees = [];
    $i = 1;
    $testResult = [];

    //setting max headings
    while ( $loop->have_posts() ) : $loop->the_post();
    	$test = [];
    	foreach (get_post_meta(get_the_ID()) as $pkey1 => $pvalue1) { 
    		$test[] = $pkey1;
    	}
    endwhile;

    while ( $loop->have_posts() ) : $loop->the_post();
    	$data = [];
    	foreach (get_post_meta(get_the_ID()) as $pkey => $pvalue) {
 			if ($i == 1) {
    			$employees = str_replace('_', ' ', array_unique($test));
 			}
    		$data[] = $pvalue[0];
    	}
    	if ($i == 1) {
    		array_push($result, $employees);
 		}
    	array_push($result, $data);
    	$i++;
    	//$result = [$employees, $data];
    		//echo "<pre>"; print_r([$employees, $data]); echo "</pre>"; wp_die();
		// $projId = get_post_meta( get_the_ID(), $key = 'ProjID', $single = true);
		// $lastUpdate = get_post_meta( get_the_ID(), $key = 'lastUpdate', $single = true );
		// $firstName = get_post_meta( get_the_ID(), $key = 'firstName', $single = true );
		// $legalName = get_post_meta( get_the_ID(), $key = 'legalName', $single = true );
		// $lastName = get_post_meta( get_the_ID(), $key = 'lastName', $single = true );
		// $spouseName = get_post_meta( get_the_ID(), $key = 'spouseName', $single = true );
		// $mappeDesired = get_post_meta( get_the_ID(), $key = 'mappeDesired', $single = true );
		// $currentAddress = get_post_meta( get_the_ID(), $key = 'currentAddress', $single = true );
		// $secondAddress = get_post_meta( get_the_ID(), $key = 'secondAddress', $single = true );
		// $furloughAddress = get_post_meta( get_the_ID(), $key = 'furloughAddress', $single = true );
		// $currentPhone = get_post_meta( get_the_ID(), $key = 'currentPhone', $single = true );
		// $secondPhone = get_post_meta( get_the_ID(), $key = 'secondPhone', $single = true );
		// $furloughPhone = get_post_meta( get_the_ID(), $key = 'furloughPhone', $single = true );
		// $fieldCellPhone = get_post_meta( get_the_ID(), $key = 'fieldCellPhone', $single = true );
		// $spousePhone = get_post_meta( get_the_ID(), $key = 'spousePhone', $single = true );
		// $spousePhoneUS = get_post_meta( get_the_ID(), $key = 'spousePhoneUS', $single = true );
		// $vonageNumber = get_post_meta( get_the_ID(), $key = 'vonageNumber', $single = true );
		// $skypeNumber = get_post_meta( get_the_ID(), $key = 'skypeNumber', $single = true );
		// $magicJackNumber = get_post_meta( get_the_ID(), $key = 'magicJackNumber', $single = true );
		// $furloughCell = get_post_meta( get_the_ID(), $key = 'furloughCell', $single = true );
		// $preferredShippingCo = get_post_meta( get_the_ID(), $key = 'preferredShippingCo', $single = true );
		// $shippingAdress = get_post_meta( get_the_ID(), $key = 'shippingAdress', $single = true );
		// $websiteAddress = get_post_meta( get_the_ID(), $key = 'websiteAddress', $single = true );
		// $personalEmail = get_post_meta( get_the_ID(), $key = 'personalEmail', $single = true );
		// $homeChurch = get_post_meta( get_the_ID(), $key = 'homeChurch', $single = true );
		// $homeChurchPhone = get_post_meta( get_the_ID(), $key = 'homeChurchPhone', $single = true );
		// $homeChurchPastor = get_post_meta( get_the_ID(), $key = 'homeChurchPastor', $single = true );
		// $homeChurchEmail = get_post_meta( get_the_ID(), $key = 'homeChurchEmail', $single = true );
		// $serviceInformation = get_post_meta( get_the_ID(), $key = 'serviceInformation', $single = true );
		// $fieldInformation = get_post_meta( get_the_ID(), $key = 'fieldInformation', $single = true );
		// $emergencyContact = get_post_meta( get_the_ID(), $key = 'emergencyContact', $single = true );
		// $scheduledFurloughStartDate = get_post_meta( get_the_ID(), $key = 'scheduledFurloughStartDate', $single = true );
		// $scheduledFurloughEndDate = get_post_meta( get_the_ID(), $key = 'scheduledFurloughEndDate', $single = true );
		// $generatePassword = get_post_meta( get_the_ID(), $key = 'generatePassword', $single = true );

		// array_push($employees, [$projId, $lastUpdate, $firstName, $legalName, $lastName, $spouseName, $mappeDesired, $currentAddress, $secondAddress, $furloughAddress, $currentPhone, $secondPhone, $furloughPhone, $fieldCellPhone, $spousePhone, $spousePhoneUS, $vonageNumber, $skypeNumber, $magicJackNumber, $furloughCell, $preferredShippingCo, $shippingAdress, $websiteAddress, $personalEmail, $homeChurch, $homeChurchPhone, $homeChurchPastor, $homeChurchEmail, $serviceInformation, $fieldInformation, $emergencyContact, $scheduledFurloughStartDate, $scheduledFurloughEndDate, $generatePassword]
		// );
		
		// array_push($employees, $data);

	endwhile;
	
	$expXlsx = SimpleXLSXGen::fromArray( $result );
	$expXlsx->downloadAs('employees.xlsx');
	// echo "<pre>"; print_r($employees);  echo "</pre>";

}
add_action( 'admin_post_export_excel', 'prefix_admin_export_excel' );


// Export EXCEL File
function prefix_admin_report_excel(){


	$employees = [
	    ["LastUpdate","FirstName","LastName","Personal Email Address"]
	];
	$args = array(  
        'post_type' => 'employee',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'modified', 
        'order' => 'DESC',
    );

    $loop = new WP_Query( $args ); 

    while ( $loop->have_posts() ) : $loop->the_post();

		$lastUpdate = get_post_meta( get_the_ID(), $key = 'LastUpdate', $single = true );
		$firstName = get_post_meta( get_the_ID(), $key = 'FirstName', $single = true );
		$lastName = get_post_meta( get_the_ID(), $key = 'LastName', $single = true );
		$personalEmail = get_post_meta( get_the_ID(), $key = 'Personal_Email_Address', $single = true );

		array_push($employees, [$lastUpdate, $firstName, $lastName, $personalEmail]
		);
	

	endwhile;

	$expXlsx = SimpleXLSXGen::fromArray( $employees );
	$expXlsx->downloadAs('employees-report.xlsx');
	// echo "<pre>"; print_r($employees);  echo "</pre>";

}
add_action( 'admin_post_report_excel', 'prefix_admin_report_excel' );


add_action('init',  "employee_Cpt");



function r_activate_plugin(){

	
}

function r_deactivate_plugin() {
	remove_menu_page('employee');
}

// Single template call
function single_employee_template( $template ) {
    global $post;

    if ( 'employee' === $post->post_type && locate_template( array( 'single-employee.php' ) ) !== $template ) {
        /*
         * This is a 'movie' post
         * AND a 'single movie template' is not found on
         * theme or child theme directories, so load it
         * from our plugin directory.
         */
        return plugin_dir_path( __FILE__ ) . 'single-employee.php';
    }

    return $template;
}

add_filter( 'single_template', 'single_employee_template' );


// register jquery and style on initialization
function callback_for_setting_up_scripts() {
    wp_register_style( 'custom_style', plugins_url('css/custom-style.css',__FILE__ ) );
    wp_enqueue_style( 'custom_style' );
}
add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');


//Hooks
register_activation_hook(__FILE__,'r_activate_plugin');
register_deactivation_hook(__FILE__,'r_deactivate_plugin'); 