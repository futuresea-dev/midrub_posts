<?php
/**
 * Plans Helper
 *
 * This file contains the class Plans
 * with methods to manage the plans's data
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
 */

// Define the page namespace
namespace MidrubBase\Admin\Components\Collection\Members\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Plans class provides the methods to manage the plans's data
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/midrub_cms Midrub’s License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.3
*/
class Plans {
    
    /**
     * Class variables
     *
     * @since 0.0.8.3
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.3
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load Base Plans Model
        $this->CI->load->ext_model( MIDRUB_BASE_PATH . 'models/', 'Base_plans', 'base_plans' );
        
    }

    /**
     * The public method reload_members_dropdowns reloads the dropdown for members dropdowns
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function reload_members_dropdowns() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('field_id', 'Field ID', 'trim|required');
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('member_id', 'Member ID', 'trim');
            
            // Get received data
            $field_id = $this->CI->input->post('field_id');
            $key = $this->CI->input->post('key');
            $member_id = $this->CI->input->post('member_id');
          
            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Verify if are required plans
                if ( $field_id === 'plan' ) {

                    // Response array
                    $response = array();

                    // Prepare arguments for request
                    $args = array(
                        'start' => 0,
                        'limit' => 10,
                        'key' => $key
                    );
                    
                    // Get plans
                    $plans = $this->CI->base_plans->get_plans($args);

                    // Verify if plans exists
                    if ( $plans ) {

                        // Items array
                        $items = array();

                        // Default member's plan
                        $member_plan = 0;

                        // Get member's plan
                        $get_member_plan = $this->CI->base_model->get_data_where('users_meta', 'meta_value', array('user_id' => $member_id, 'meta_name' => 'plan'));

                        // Verify if member has a plan
                        if ( $get_member_plan ) {

                            // Set member's plan
                            $member_plan = $get_member_plan[0]['meta_value'];

                        }

                        // List all plans
                        foreach ( $plans as $plan ) {

                            // Verify if this plan is selected
                            if ( $member_plan === $plan['plan_id'] ) {

                                // Set selected
                                $response['selected'] = array(
                                    'id' => $plan['plan_id'],
                                    'name' => $plan['plan_name']
                                );

                            }

                            // Set item
                            $items[] = array(
                                'id' => $plan['plan_id'],
                                'name' => $plan['plan_name']
                            );

                            // Only 10
                            if ( count($items) > 9 ) {
                                break;
                            }

                        }

                        // Set items
                        $response['items'] = $items;

                    } else {

                        // Set default mesage
                        $response['no_items_message'] = $this->CI->lang->line('members_no_plans_found');

                    }

                    // Prepare the success message
                    $data = array(
                        'success' => TRUE,
                        'response' => $response,
                        'field_id' => $field_id
                    );

                    // Display the success message
                    echo json_encode($data);
                    exit();

                } else if ( $field_id === 'country' ) {

                    // Response array
                    $response = array();
                    
                    // Get countries
                    $countries_array = array(
                        "AF" => "Afghanistan",
                        "AL" => "Albania",
                        "DZ" => "Algeria",
                        "AS" => "American Samoa",
                        "AD" => "Andorra",
                        "AO" => "Angola",
                        "AI" => "Anguilla",
                        "AQ" => "Antarctica",
                        "AG" => "Antigua and Barbuda",
                        "AR" => "Argentina",
                        "AM" => "Armenia",
                        "AW" => "Aruba",
                        "AU" => "Australia",
                        "AT" => "Austria",
                        "AZ" => "Azerbaijan",
                        "BS" => "Bahamas",
                        "BH" => "Bahrain",
                        "BD" => "Bangladesh",
                        "BB" => "Barbados",
                        "BY" => "Belarus",
                        "BE" => "Belgium",
                        "BZ" => "Belize",
                        "BJ" => "Benin",
                        "BM" => "Bermuda",
                        "BT" => "Bhutan",
                        "BO" => "Bolivia",
                        "BA" => "Bosnia and Herzegovina",
                        "BW" => "Botswana",
                        "BV" => "Bouvet Island",
                        "BR" => "Brazil",
                        "BQ" => "British Antarctic Territory",
                        "IO" => "British Indian Ocean Territory",
                        "VG" => "British Virgin Islands",
                        "BN" => "Brunei",
                        "BG" => "Bulgaria",
                        "BF" => "Burkina Faso",
                        "BI" => "Burundi",
                        "KH" => "Cambodia",
                        "CM" => "Cameroon",
                        "CA" => "Canada",
                        "CT" => "Canton and Enderbury Islands",
                        "CV" => "Cape Verde",
                        "KY" => "Cayman Islands",
                        "CF" => "Central African Republic",
                        "TD" => "Chad",
                        "CL" => "Chile",
                        "CN" => "China",
                        "CX" => "Christmas Island",
                        "CC" => "Cocos Islands",
                        "CO" => "Colombia",
                        "KM" => "Comoros",
                        "CG" => "Congo - Brazzaville",
                        "CD" => "Congo - Kinshasa",
                        "CK" => "Cook Islands",
                        "CR" => "Costa Rica",
                        "HR" => "Croatia",
                        "CU" => "Cuba",
                        "CY" => "Cyprus",
                        "CZ" => "Czech Republic",
                        "CI" => "Côte d’Ivoire",
                        "DK" => "Denmark",
                        "DJ" => "Djibouti",
                        "DM" => "Dominica",
                        "DO" => "Dominican Republic",
                        "NQ" => "Dronning Maud Land",
                        "DD" => "East Germany",
                        "EC" => "Ecuador",
                        "EG" => "Egypt",
                        "SV" => "El Salvador",
                        "GQ" => "Equatorial Guinea",
                        "ER" => "Eritrea",
                        "EE" => "Estonia",
                        "ET" => "Ethiopia",
                        "FK" => "Falkland Islands",
                        "FO" => "Faroe Islands",
                        "FJ" => "Fiji",
                        "FI" => "Finland",
                        "FR" => "France",
                        "GF" => "French Guiana",
                        "PF" => "French Polynesia",
                        "TF" => "French Southern Territories",
                        "FQ" => "French Southern and Antarctic Territories",
                        "GA" => "Gabon",
                        "GM" => "Gambia",
                        "GE" => "Georgia",
                        "DE" => "Germany",
                        "GH" => "Ghana",
                        "GI" => "Gibraltar",
                        "GR" => "Greece",
                        "GL" => "Greenland",
                        "GD" => "Grenada",
                        "GP" => "Guadeloupe",
                        "GU" => "Guam",
                        "GT" => "Guatemala",
                        "GG" => "Guernsey",
                        "GN" => "Guinea",
                        "GW" => "Guinea-Bissau",
                        "GY" => "Guyana",
                        "HT" => "Haiti",
                        "HM" => "Heard Island and McDonald Islands",
                        "HN" => "Honduras",
                        "HK" => "Hong Kong SAR China",
                        "HU" => "Hungary",
                        "IS" => "Iceland",
                        "IN" => "India",
                        "ID" => "Indonesia",
                        "IR" => "Iran",
                        "IQ" => "Iraq",
                        "IE" => "Ireland",
                        "IM" => "Isle of Man",
                        "IL" => "Israel",
                        "IT" => "Italy",
                        "JM" => "Jamaica",
                        "JP" => "Japan",
                        "JE" => "Jersey",
                        "JT" => "Johnston Island",
                        "JO" => "Jordan",
                        "KZ" => "Kazakhstan",
                        "KE" => "Kenya",
                        "KI" => "Kiribati",
                        "KW" => "Kuwait",
                        "KG" => "Kyrgyzstan",
                        "LA" => "Laos",
                        "LV" => "Latvia",
                        "LB" => "Lebanon",
                        "LS" => "Lesotho",
                        "LR" => "Liberia",
                        "LY" => "Libya",
                        "LI" => "Liechtenstein",
                        "LT" => "Lithuania",
                        "LU" => "Luxembourg",
                        "MO" => "Macau SAR China",
                        "MK" => "Macedonia",
                        "MG" => "Madagascar",
                        "MW" => "Malawi",
                        "MY" => "Malaysia",
                        "MV" => "Maldives",
                        "ML" => "Mali",
                        "MT" => "Malta",
                        "MH" => "Marshall Islands",
                        "MQ" => "Martinique",
                        "MR" => "Mauritania",
                        "MU" => "Mauritius",
                        "YT" => "Mayotte",
                        "FX" => "Metropolitan France",
                        "MX" => "Mexico",
                        "FM" => "Micronesia",
                        "MI" => "Midway Islands",
                        "MD" => "Moldova",
                        "MC" => "Monaco",
                        "MN" => "Mongolia",
                        "ME" => "Montenegro",
                        "MS" => "Montserrat",
                        "MA" => "Morocco",
                        "MZ" => "Mozambique",
                        "MM" => "Myanmar [Burma]",
                        "NA" => "Namibia",
                        "NR" => "Nauru",
                        "NP" => "Nepal",
                        "NL" => "Netherlands",
                        "AN" => "Netherlands Antilles",
                        "NT" => "Neutral Zone",
                        "NC" => "New Caledonia",
                        "NZ" => "New Zealand",
                        "NI" => "Nicaragua",
                        "NE" => "Niger",
                        "NG" => "Nigeria",
                        "NU" => "Niue",
                        "NF" => "Norfolk Island",
                        "KP" => "North Korea",
                        "VD" => "North Vietnam",
                        "MP" => "Northern Mariana Islands",
                        "NO" => "Norway",
                        "OM" => "Oman",
                        "PC" => "Pacific Islands Trust Territory",
                        "PK" => "Pakistan",
                        "PW" => "Palau",
                        "PS" => "Palestinian Territories",
                        "PA" => "Panama",
                        "PZ" => "Panama Canal Zone",
                        "PG" => "Papua New Guinea",
                        "PY" => "Paraguay",
                        "YD" => "People's Democratic Republic of Yemen",
                        "PE" => "Peru",
                        "PH" => "Philippines",
                        "PN" => "Pitcairn Islands",
                        "PL" => "Poland",
                        "PT" => "Portugal",
                        "PR" => "Puerto Rico",
                        "QA" => "Qatar",
                        "RO" => "Romania",
                        "RU" => "Russia",
                        "RW" => "Rwanda",
                        "RE" => "Réunion",
                        "BL" => "Saint Barthélemy",
                        "SH" => "Saint Helena",
                        "KN" => "Saint Kitts and Nevis",
                        "LC" => "Saint Lucia",
                        "MF" => "Saint Martin",
                        "PM" => "Saint Pierre and Miquelon",
                        "VC" => "Saint Vincent and the Grenadines",
                        "WS" => "Samoa",
                        "SM" => "San Marino",
                        "SA" => "Saudi Arabia",
                        "SN" => "Senegal",
                        "RS" => "Serbia",
                        "CS" => "Serbia and Montenegro",
                        "SC" => "Seychelles",
                        "SL" => "Sierra Leone",
                        "SG" => "Singapore",
                        "SK" => "Slovakia",
                        "SI" => "Slovenia",
                        "SB" => "Solomon Islands",
                        "SO" => "Somalia",
                        "ZA" => "South Africa",
                        "GS" => "South Georgia and the South Sandwich Islands",
                        "KR" => "South Korea",
                        "ES" => "Spain",
                        "LK" => "Sri Lanka",
                        "SD" => "Sudan",
                        "SR" => "Suriname",
                        "SJ" => "Svalbard and Jan Mayen",
                        "SZ" => "Swaziland",
                        "SE" => "Sweden",
                        "CH" => "Switzerland",
                        "SY" => "Syria",
                        "ST" => "São Tomé and Príncipe",
                        "TW" => "Taiwan",
                        "TJ" => "Tajikistan",
                        "TZ" => "Tanzania",
                        "TH" => "Thailand",
                        "TL" => "Timor-Leste",
                        "TG" => "Togo",
                        "TK" => "Tokelau",
                        "TO" => "Tonga",
                        "TT" => "Trinidad and Tobago",
                        "TN" => "Tunisia",
                        "TR" => "Turkey",
                        "TM" => "Turkmenistan",
                        "TC" => "Turks and Caicos Islands",
                        "TV" => "Tuvalu",
                        "UM" => "U.S. Minor Outlying Islands",
                        "PU" => "U.S. Miscellaneous Pacific Islands",
                        "VI" => "U.S. Virgin Islands",
                        "UG" => "Uganda",
                        "UA" => "Ukraine",
                        "SU" => "Union of Soviet Socialist Republics",
                        "AE" => "United Arab Emirates",
                        "GB" => "United Kingdom",
                        "US" => "United States",
                        "ZZ" => "Unknown or Invalid Region",
                        "UY" => "Uruguay",
                        "UZ" => "Uzbekistan",
                        "VU" => "Vanuatu",
                        "VA" => "Vatican City",
                        "VE" => "Venezuela",
                        "VN" => "Vietnam",
                        "WK" => "Wake Island",
                        "WF" => "Wallis and Futuna",
                        "EH" => "Western Sahara",
                        "YE" => "Yemen",
                        "ZM" => "Zambia",
                        "ZW" => "Zimbabwe",
                        "AX" => "Åland Islands"
                    );
                    // Items array
                    $items = array();

                    // List all countries
                    foreach ( $countries_array as $code => $name ) {

                        // Verify if the $name meets the request
                        if ( preg_match("/{$key}/i", $name ) ) {

                            // Set item
                            $items[] = array(
                                'id' => $code,
                                'name' => $name
                            );

                        }

                        // Verify if $items has 10 items
                        if ( count($items) > 9 ) {
                            break;
                        }

                    }

                    // Get member's country
                    $member_country = $this->CI->base_model->get_data_where('users_meta', 'meta_value', array('user_id' => $member_id, 'meta_name' => 'country', 'meta_value !=' => ''));

                    // Verify if member has a country
                    if ( $member_country ) {

                        // Set selected
                        $response['selected'] = array(
                            'id' => $member_country[0]['meta_value'],
                            'name' => $countries_array[$member_country[0]['meta_value']]
                        );

                    }

                    // Verify if items exists
                    if ( $items ) {

                        // Set items
                        $response['items'] = $items;

                    } else {

                        // Set default mesage
                        $response['no_items_message'] = $this->CI->lang->line('members_no_countries_found');

                    }

                    // Prepare the success message
                    $data = array(
                        'success' => TRUE,
                        'response' => $response,
                        'field_id' => $field_id
                    );

                    // Display the success message
                    echo json_encode($data);
                    exit();                    

                }

            }
            
        }

        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('members_error_has_occurred')
        );

        // Display the error message
        echo json_encode($data);

    }

}

/* End of file plans.php */