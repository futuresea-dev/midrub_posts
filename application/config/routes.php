<?php

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Auth routes
$route['logout'] = 'base/logout';
$route['error/(:any)'] = 'error_page/show_error/$1';
$route['cron-job'] = 'cron/run';

// User routes
$route['user/tool/(:any)'] = 'userarea/tool/$1';
$route['user/tools'] = 'userarea/tools';
$route['user/tools/(:any)'] = 'userarea/tools/$1';
$route['user/bots'] = 'bots/bots';
$route['user/bots/(:any)'] = 'bots/bots/$1';
$route['user/bot/(:any)'] = 'bots/bot/$1';
$route['bot-cron'] = 'bots/bot_cron';
$route['user/success-payment'] = 'userarea/success_payment';
$route['user/option/(:any)'] = 'userarea/set_option/$1';
$route['user/delete-account'] = 'userarea/delete_user_account';
$route['user/save-token/(:any)/(:any)'] = 'userarea/save_token/$1/$2';
$route['user/connect/(:any)'] = 'userarea/connect/$1';
$route['user/disconnect/(:num)'] = 'userarea/disconnect/$1';
$route['user/callback/(:any)'] = 'userarea/callback/$1';
$route['user/search-posts/(:any)'] = 'userarea/search_posts/$1';
$route['user/statistics/(:num)'] = 'userarea/get_statistics/$1';
$route['user/verify-coupon/(:any)'] = 'coupons/verify_coupon/$1';
$route['user/ajax/(:any)'] = 'userarea/ajax/$1';
$route['bots/(:any)'] = 'userarea/bots/$1';

// Admin routes
$route['admin/home'] = 'adminarea/dashboard';
$route['admin/plans'] = 'plans_manager/admin_plans';
$route['admin/plans/(:num)'] = 'plans_manager/admin_plans/$1';
$route['admin/delete-plan/(:num)'] = 'adminarea/delete_plan/$1';
$route['admin/payment/(:any)'] = 'adminarea/payment/$1';
$route['admin/option/(:any)'] = 'adminarea/set_option/$1';
$route['admin/option/(:any)/(:any)'] = 'adminarea/set_option/$1/$2';
$route['admin/coupon-codes/(:num)'] = 'coupons/get_all_codes/$1';
$route['admin/delete-code/(:any)'] = 'coupons/delete_code/$1';

$route['admin/ajax/options'] = 'adminarea/ajax/options';
$route['admin/ajax/plans'] = 'adminarea/ajax/plans';
$route['admin/ajax/faq'] = 'adminarea/ajax/faq';

$route['user/show-accounts/(:any)/(:num)'] = 'userarea/show_accounts/$1/$2';
$route['user/show-accounts/(:any)/(:num)/(:any)'] = 'userarea/show_accounts/$1/$2/$3';
$route['user/search-accounts/(:any)/(:any)'] = 'userarea/search_accounts/$1/$2';

// Default
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = 'base/init';
$route['(:any)'] = 'base/init/$1';
$route['(:any)/(:any)'] = 'base/init/$1/$2';
$route['(:any)/(:any)/(:any)'] = 'base/init/$1/$2/$3';
$route['(:any)/(:any)/(:any)/(:any)'] = 'base/init/$1/$2/$3/$4';

/* End of file routes.php */