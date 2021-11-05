//<script>
Ossn.add_hook('ajax', 'request:settings', 'signup_profile_photo');
function signup_profile_photo($hook, $type, $params){
		$params.containMedia = true;
		return $params;
}