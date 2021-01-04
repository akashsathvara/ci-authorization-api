<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'registration/table';
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//$route['user/code-data/(:num)/(:any)'] =  'user/codeData/$1/$2';
//$route['Registration/edit/(:num)'] =  'Registration/add/$1';

################################## User api ##################################

$route['testdata'] =  'user/test';
$route['user'] =  'user/register';
$route['user/login'] =  'user/login';
$route['user/info'] =  'user/getUserInfo';
$route['user/email'] =  'user/checkEmailExist';
$route['user/password/change'] =  'user/changePassword';
$route['user/status'] =  'user/updateUserStatus'; // soft delete a user by updating is_active flag in db. No hard delete.
$route['user/password/reset'] =  'user/forgotPassword';
$route['user/verify'] =  'user/verifyUser';

///////////////////////////////////// User Event ////////////////////////////////
$route['event'] =  'user/addEvent';
$route['event/info'] =  'user/getEventInfo';
$route['event/update'] =  'user/updateEvent';

///////////////////////////////////// User Blog /////////////////////////////////
$route['blog'] =  'user/addBlog';
$route['blog/list/public/(:num)'] =  'user/getBlogList';
$route['blog/list/(:num)'] =  'user/getUserBlogList';
$route['blog/info/public/(:num)'] =  'user/getBlogInfo';
$route['blog/info/(:num)'] =  'user/getUserBloginfo';
$route['blog/update'] =  'user/updateBlog';
$route['blog/delete'] =  'user/deleteBlog';

//////////////////////////////////// Like & Dislike /////////////////////////////
$route['blog/like'] =  'user/likeBlog';
$route['blog/dislike'] =  'user/dislikeBlog';

//////////////////////////////////// Comments ///////////////////////////////////
$route['comment'] =  'user/addComment';
$route['comment/info/(:num)'] =  'user/getUserComment';
$route['comment/update'] =  'user/updateComment';
$route['comment/remove/(:num)'] =  'user/removeComment';
$route['comment/list/(:num)/(:any)'] =  'user/listComment';// pagination

