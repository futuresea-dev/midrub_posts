<?php
/**
 * Plugins Helpers
 *
 * This file contains the class Plugins
 * with methods to manage the plugins
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.4
 */

// Define the page namespace
namespace MidrubBase\Admin\Components\Collection\Plugins\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Require the Plugins Inc
require_once MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'inc/plugins.php';

/*
 * Plugins class provides the methods to manage install and manage the plugins
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.4
*/
class Plugins {
    
    /**
     * Class variables
     *
     * @since 0.0.8.4
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.4
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
    }

    /**
     * The public method plugins_activate_plugin activates a plugin
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function plugins_activate_plugin() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('plugin_slug', 'Plugin Slug', 'trim|required');
            
            // Get received data
            $plugin_slug = $this->CI->input->post('plugin_slug');

            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Get the plugins
                $plugins = array_column(the_plugins_list(), 'plugin_slug', 'plugin_slug');

                // Verify if the plugin exists
                if ( isset($plugins[$plugin_slug]) ) {

                    // Try to activate the plugin
                    if ( update_option('plugin_' . trim($plugin_slug) . '_enable', 1) ) {

                        // Prepare the success response
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('plugins_the_plugin_was_activated')
                        );

                        // Display the success response
                        echo json_encode($data);                        

                    } else {

                        // Prepare the error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('plugins_the_plugin_was_not_activated')
                        );

                        // Display the error response
                        echo json_encode($data);

                    }

                    exit();

                } else {

                    // Prepare the error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('plugins_the_plugin_was_not_found')
                    );

                    // Display the error response
                    echo json_encode($data);

                }

                exit();

            }

        }

        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('plugins_the_plugin_was_not_activated')
        );

        // Display the error response
        echo json_encode($data);

    }

    /**
     * The public method plugins_deactivate_plugin deactivates a plugin
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function plugins_deactivate_plugin() {

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('plugin_slug', 'Plugin Slug', 'trim|required');
            
            // Get received data
            $plugin_slug = $this->CI->input->post('plugin_slug');

            // Check form validation
            if ($this->CI->form_validation->run() !== false ) {

                // Get the plugins
                $plugins = array_column(the_plugins_list(), 'plugin_slug', 'plugin_slug');

                // Verify if the plugin exists
                if ( isset($plugins[$plugin_slug]) ) {

                    // Try to deactivate the plugin
                    if ( delete_option('plugin_' . trim($plugin_slug) . '_enable') ) {

                        // Prepare the success response
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('plugins_the_plugin_was_deactivated')
                        );

                        // Display the success response
                        echo json_encode($data);                        

                    } else {

                        // Prepare the error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('plugins_the_plugin_was_not_deactivated')
                        );

                        // Display the error response
                        echo json_encode($data);

                    }

                    exit();

                } else {

                    // Prepare the error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('plugins_the_plugin_was_not_found')
                    );

                    // Display the error response
                    echo json_encode($data);

                }

                exit();

            }

        }

        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('plugins_the_plugin_was_not_deactivated')
        );

        // Display the error message
        echo json_encode($data);


    }
    
    /**
     * The public method plugins_upload_plugin uploads an plugin to be installed
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function plugins_upload_plugin() {

        // Verify if post data was sent
        if ( $this->CI->input->post() ) {
            
            // Get file type
            $type = $this->CI->security->xss_clean($_FILES['file']['type']);

            // Supported formats
            $check_format = array('application/x-zip-compressed', 'application/zip');  
            
            if ( !in_array($type, $check_format) ) {
                
                // Prepare the error message
                $message = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('plugins_error_occurred') . ': ' . $this->CI->lang->line('plugins_no_supported_format')
                );

                // Display the error message
                echo json_encode($message);
                exit();
                
            }

            // Generate a new file name
            $zip_file = time();

            // Delete all files first
            $this->delete_files();

            // Verify the if the folder temp exists
            if ( !is_dir(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp') ) {
                mkdir(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp', 0777);
            }

            // Upload the app
            $config['upload_path'] = MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp';
            $config['file_name'] = $zip_file;
            $config['file_ext_tolower'] = TRUE;
            $this->CI->load->library('upload', $config);
            $this->CI->upload->initialize($config);
            $this->CI->upload->set_allowed_types('*');

            // Upload file 
            if ( $this->CI->upload->do_upload('file') ) {
                
                // Verify if the ZIP file was upoaded
                if ( file_exists($config['upload_path'] . '/' . $zip_file . '.zip') ) {
                    
                    // Prepare the response
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('plugins_unzipping'),
                        'file_name' => $zip_file
                    );

                    // Display the response
                    echo json_encode($data);
                    exit();
                    
                }

            }


        }

        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('plugins_error_occurred_request')
        );

        // Display the error message
        echo json_encode($data);

    }

    /**
     * The public method plugins_unzipping_zip extract the plugin from the zip
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function plugins_unzipping_zip() {

        // Verify if file_name exists
        if ( is_numeric($this->CI->input->get('file_name')) ) {

            // Verify if file exists
            if ( file_exists(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/' . $this->CI->input->get('file_name') . '.zip') ) {

                // Call the ZipArchive class
                $zip = new \ZipArchive;

                // Try to open the zip
                if ($zip->open(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/' . $this->CI->input->get('file_name') . '.zip') === TRUE) {

                    // Extract the zip
                    $zip->extractTo(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/');

                    // Close the ZipArchive class
                    $zip->close();

                    // Verify if the installation.json exists
                    if ( file_exists(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/installation.json') ) {

                        // Delete the uploaded app
                        unlink(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/' . $this->CI->input->get('file_name') . '.zip');

                        // Get the installation file
                        $installation = json_decode(file_get_contents(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/installation.json'), true);

                        // Verify if the type parameter exists
                        if ( empty($installation['type']) ) {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('plugins_plugin_installation_json_wrong_parameters')
                            );

                            // Display the error message
                            echo json_encode($data);
                            exit();

                        }

                        // Verify if the type is plugins
                        if ( $installation['type'] !== 'plugins' ) {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('plugins_not_valid_plugin')
                            );

                            // Display the error message
                            echo json_encode($data);
                            exit();

                        }                        

                        // Verify if the php file exists
                        if ( isset($installation['php']) ) {

                            // Verify if the php zip file exists
                            if ( file_exists(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/' . $installation['php']) ) {

                                // Call the ZipArchive class
                                $zip = new \ZipArchive;

                                // Try to open the zip
                                if ($zip->open(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/' . $installation['php']) === TRUE) {

                                    // First verify if files exists
                                    if ( $zip->numFiles > 0 ) {

                                        // Verify if the app is already installed
                                        if ( is_dir(MIDRUB_BASE_PATH . 'plugins/collection/' . $zip->getNameIndex(0)) ) {

                                            // Prepare the error message
                                            $data = array(
                                                'success' => FALSE,
                                                'message' => $this->CI->lang->line('plugins_plugin_already_installed')
                                            );

                                            // Display the error message
                                            echo json_encode($data);

                                            // Close the ZipArchive class
                                            $zip->close();                                            
                                            exit();
                                            
                                        } else {

                                            // Extract the zip
                                            $zip->extractTo(MIDRUB_BASE_PATH . 'plugins/collection/');

                                            // Verify if models directory exists
                                            if ( is_dir(MIDRUB_BASE_PATH . 'plugins/collection/' . $zip->getNameIndex(0) . '/models/') ) {

                                                // List all available models
                                                foreach ( glob(MIDRUB_BASE_PATH . 'plugins/collection/' . $zip->getNameIndex(0) . '/models/*.php') as $filename ) {

                                                    // Get the class's name
                                                    $className = str_replace(array(MIDRUB_BASE_PATH . 'plugins/collection/' . $zip->getNameIndex(0) . '/models/', '.php'), '', $filename);

                                                    // Load the model
                                                    $this->CI->load->ext_model(MIDRUB_BASE_PATH . 'plugins/collection/' . $zip->getNameIndex(0) . '/models/', ucfirst($className), $className );

                                                }

                                            }

                                            // Verify if the app is installed
                                            if ( is_dir(dirname(MIDRUB_BASE_PATH . 'plugins/collection/' . $zip->getNameIndex(0) . '/')) ) {

                                                // Verify if the assets file exists
                                                if ( isset($installation['assets']) ) {

                                                    // Verify if the assets file exists
                                                    if ( file_exists(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/' . $installation['assets']) ) {

                                                        // Call the ZipArchive class
                                                        $zip = new \ZipArchive;

                                                        // Try to open the zip
                                                        if ($zip->open(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/' . $installation['assets']) === TRUE) {

                                                            // First verify if files exists
                                                            if ( $zip->numFiles > 0 ) {

                                                                // Verify if the app is already installed
                                                                if ( is_dir(dirname(FCPATH . 'assets/base/plugins/collection/' . $zip->getNameIndex(0) . '/')) ) {

                                                                    // Prepare the error message
                                                                    $data = array(
                                                                        'success' => FALSE,
                                                                        'message' => $this->CI->lang->line('plugins_php_installed')
                                                                    );

                                                                    // Display the error message
                                                                    echo json_encode($data);

                                                                    // Close the ZipArchive class
                                                                    $zip->close();                                            
                                                                    exit();
                                                                    
                                                                } else {

                                                                    // Extract the zip
                                                                    $zip->extractTo(FCPATH . 'assets/base/plugins/collection/');

                                                                    // Verify if the assets were installed
                                                                    if ( !is_dir(dirname(FCPATH . 'assets/base/plugins/collection/' . $zip->getNameIndex(0) . '/')) ) {

                                                                        // Prepare the error message
                                                                        $data = array(
                                                                            'success' => FALSE,
                                                                            'message' => $this->CI->lang->line('plugins_php_installed')
                                                                        );

                                                                        // Display the error message
                                                                        echo json_encode($data);

                                                                        // Close the ZipArchive class
                                                                        $zip->close();                                            
                                                                        exit();

                                                                    }

                                                                }

                                                            }

                                                        }

                                                    }

                                                }

                                            }

                                            // Prepare the succes message
                                            $data = array(
                                                'success' => TRUE,
                                                'message' => $this->CI->lang->line('plugins_the_plugin_was_installed')
                                            );

                                            // Display the success message
                                            echo json_encode($data);                                    

                                            // Close the ZipArchive class
                                            $zip->close();

                                            exit();

                                        }

                                    }

                                    // Close the ZipArchive class
                                    $zip->close();

                                }

                            }

                        } else {

                            // Prepare the error message
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('plugins_plugin_installation_json_wrong_parameters')
                            );

                            // Display the error message
                            echo json_encode($data);
                            exit();
                            
                        }

                    } else {

                        // Prepare the error message
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('plugins_plugin_installation_json_not_found')
                        );

                        // Display the error message
                        echo json_encode($data);
                        exit();

                    }

                }

            }

        }

        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('plugins_error_occurred_request')
        );

        // Display the error message
        echo json_encode($data);

    }

    /**
     * The public method delete_files deletes all files from the temp directory
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */ 
    public function delete_files() {

        // List of name of files inside 
        // specified folder 
        $files = glob(MIDRUB_BASE_ADMIN_COMPONENTS_PLUGINS . 'temp/*');  
        
        // Deleting all the files in the list 
        foreach($files as $file) { 
        
            if( is_file($file) ) { 
            
                // Delete the given file 
                unlink($file);  

            }

        } 

    }

}

/* End of file plugins.php */