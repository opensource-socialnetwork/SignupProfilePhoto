<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
define('__SignupProfilePhoto__', ossn_route()->com . 'SignupProfilePhoto/');

function signup_profile_photo_init() {
		if(!ossn_isLoggedin()) {
				ossn_register_callback('action', 'load', 'singup_profile_photo_action');
				ossn_register_callback('user', 'created', 'signup_profile_photo_set');

				ossn_extend_view('forms/signup', 'signup_profile_photo/input');
		}
		ossn_extend_view('js/ossn.site.public', 'signup_profile_photo/js');
}
function signup_profile_photo_set($callback, $type, $params) {
		if(isset($params['guid'])) {
				$user = ossn_user_by_guid($params['guid']);
				if($user) {
						$file             = new OssnFile();
						$file->owner_guid = $user->guid;
						$file->type       = 'user';
						$file->subtype    = 'profile:photo';
						$file->setFile('profilephoto');
						$file->setPath('profile/photo/');
						$file->setExtension(array(
								'jpg',
								'png',
								'jpeg',
								'jfif',
								'gif',
						));
						if($fileguid = $file->addFile()) {
								//update user icon time, this time has nothing to do with photo entity time
								$user->data->icon_time = time();

								//Default profile picture #1647
								$user->data->icon_guid = $fileguid;
								$user->save();
								
								//get a all user photo files
								$resize = $file->getFiles();
								if(isset($resize->{0}->value)) {
										$guid      = $user->guid;
										$datadir   = ossn_get_userdata("user/{$guid}/{$resize->{0}->value}");
										$file_name = str_replace('profile/photo/', '', $resize->{0}->value);

										//create sub photos
										$sizes = ossn_user_image_sizes();
										foreach($sizes as $size => $params) {
												$params  = explode('x', $params);
												$width   = $params[1];
												$height  = $params[0];
												$resized = ossn_resize_image($datadir, $width, $height, true);
												file_put_contents(ossn_get_userdata("user/{$guid}/profile/photo/{$size}_{$file_name}"), $resized);
										}
								}
						}
				}
		}
}
function singup_profile_photo_action($callback, $type, $params) {
		global $Ossn;
		if($params['action'] == 'user/register') {
				header('Content-Type: application/json');
				$Ossn->signupProfilePhoto = true;
				$extensions               = array(
						'jpg',
						'png',
						'jpeg',
						'jfif',
						'gif',
				);
				$file = new OssnFile();
				$file->setFile('profilephoto');
				$file->setExtension($extensions);
				$extension = strtolower($file->getFileExtension($file->file['name']));
				if(!isset($file->file) || (isset($file->file) && ($file->file['error'] !== UPLOAD_ERR_OK || $file->file['size'] == 0 || !in_array($extension, $extensions)))) {
						$em['dataerr'] = ossn_print('singupprofilephoto:error:matching');
						echo json_encode($em);
						exit();
				}
		}
}
ossn_register_callback('ossn', 'init', 'signup_profile_photo_init');
