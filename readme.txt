##
##
##        Mod title:  SpamBarrier
##
##      Mod version:  1.0.7
##  Works on FluxBB:  1.5.10 (to be tested with 1.5.11)
##     Release date:  2025-09-13
##           Author:  sklerder (sklerder@orange.fr)
##
##       Ideas from:  - Reines (jamie@jamierf.co.uk),
##                    - blissend (blissend@gmail.com),
##                    - adaur (adaur.underground@gmail.com),
##                    - Koos (pampoen10@yahoo.com),
##                    - Many others having, directly or indirectly, contributed.
##
##      Description:  This mod adds 3 protections against bots:
##                     - The first protection implements a HoneyPot against automated registrations
##                     - The second protection checks IPs and Emails against StopForumSpam database at registration or login
##                     - The third protection checks IPs against DNS BlackLists
##
##   Repository URL:  https://github.com/sklerder/SpamBarrier
##
##   Affected files:  profile.php
##                    register.php
##                    login.php
##                    lang/English/profile.php
##                    lang/English/register.php
##                    style/Air.css (you must repeat modifications of
##                                style/Air.css on all the stylesheets you use)
##
##       Affects DB: Yes
##          New table: 'test_registrations'
##    Modifications:
##          New options in 'config' table:
##           'o_sb_check_sfs_register'
##           'o_sb_sfs_api_key'
##           'o_sb_check_hp'
##           'o_sb_custom_field'
##           'o_sb_custom_field_name'
##           'o_sb_check_dnsbl_login'
##           'o_sb_check_dnsbl_register'
##           'o_sb_check_sfs_login'
##           'o_sb_sfs_report'
##           'o_sb_dnsbl_names'
##           'o_sb_sfs_maxcheck'
##           'o_sb_out_of_limit_ok'
##           'o_sb_wl_cl' (added with 1.0.7) : Whitelist Check at Login (Yes/No)
##           'o_sb_wl_cr' (added with 1.0.7) : Whitelist Check at Registration (Yes/No)
##           'o_sb_wl_login' (added with 1.0.7) : Whitelist for Login (list)
##           'o_sb_wl_register' (added with 1.0.7) : Whitelist for Registration (list)
##
##            Notes:
##               You can skip steps 4-18 and 30-38 if you do not need the
##               ability to manually report spammers to the StopForumSpam
##               database. Following these steps will add an extra option for
##               admin when deleting users called "Delete user & report spam".
##               This release has not been tested on versions of FluxBB
##                prior to 1.5.10, but should work.
##
##       DISCLAIMER:  Please note that "mods" are not officially supported by
##                    PunBB/FluxBB. Installation of this modification is done at your
##                    own risk.
##                    Backup your forum database and any and all
##                    applicable files before proceeding.
##
##
## What's new since 1.0.6:
##           - Removed the link to fluxbb.org as it is no more available.
##           - Correction of a bug on date function in AP_SpamBarrier.php,
##              adding compatibility with pgsql and sqlite.
##           - Correction of a bug on user search in AP_SpamBarrier.php 
##              (parser.php was not loaded, leading to a white page).
##           - Whitelisting system :
##              The two whitelists permit to ignore usernames, IPs, usernames@IPs,
##               domains, usernames@domain.
##              There's one whitelist for registration and another for login,
##               they can have different contents.
##              A username from an IP address (or domain) can be authorized to connect 
##               while this IP (or domain) can be blocked for other usernames.
##              An IP address can be blocked at registration but authorized at login.
##              More details in the "legend" (little "?" near "Whitelist settings" of the plugin).
##           - Cosmetics : Added help on each module of SpamBarrier in AP_SpamBarrier.php (icons
##              "?" near the titles of modules, help visible when "hoving" over the "?" with the mouse).
##           - More explicit error messages when spamming conditions are met
##              (in previous releases, they were RC 1, RC 2, RC 3, now they
##              are IP SFS, IP DNSBL, Email SFS, HP)


#
#---------[ 1. UPLOAD ]-------------------------------------------------------
#

install_mod.php to /
files/include/spambarrier.php to /include/spambarrier.php
files/lang/* to /lang
files/plugins/AP_SpamBarrier.php to /plugins/AP_SpamBarrier.php

#
#---------[ 2. RUN ]----------------------------------------------------------
#

install_mod.php

#
#---------[ 3. DELETE ]-------------------------------------------------------
#

install_mod.php

#
#---------[ 4. OPEN ]---------------------------------------------------------
#

profile.php

#
#---------[ 5. FIND ]-------------------------------------------------
#

else if (isset($_POST['delete_user']) || isset($_POST['delete_user_comply']))

#
#---------[ 6. REPLACE WITH ]---------------------------------------------------
#

else if (isset($_POST['delete_user']) || isset($_POST['delete_spammer']) || isset($_POST['delete_user_comply']) || isset($_POST['delete_spammer_comply']))

#
#---------[ 7. FIND ]-------------------------------------------------
#

	$result = $db->query('SELECT group_id, username FROM '.$db->prefix.'users WHERE id='.$id) or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());
	list($group_id, $username) = $db->fetch_row($result);

#
#---------[ 8. REPLACE WITH ]---------------------------------------------------
#

	$result = $db->query('SELECT group_id, username, email, registration_ip FROM '.$db->prefix.'users WHERE id='.$id) or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());
	list($group_id, $username, $email, $registration_ip) = $db->fetch_row($result);

#
#---------[ 9. FIND ]-------------------------------------------------
#

	if (isset($_POST['delete_user_comply']))
	{

#
#---------[ 10. REPLACE WITH ]---------------------------------------------------
#

	if (isset($_POST['delete_user_comply']) || isset($_POST['delete_spammer_comply']))
	{
		if (isset($_POST['delete_spammer_comply']))
		{
			// Include the antispam library
			require PUN_ROOT.'include/spambarrier.php';

			// Lets report the bastard!
			sb_stopforumspam_report($registration_ip, $email, $username, '');
		}


#
#---------[ 11. FIND ]-------------------------------------------------
#

		redirect('index.php', $lang_profile['User delete redirect']);

#
#---------[ 12. REPLACE WITH ]---------------------------------------------------
#

		redirect('index.php', isset($_POST['delete_spammer_comply']) ? $lang_profile['Spammer delete redirect'] : $lang_profile['User delete redirect']);

#
#---------[ 13. FIND ]-------------------------------------------------
#

							<label><input type="checkbox" name="delete_posts" value="1" checked="checked" /><?php echo $lang_profile['Delete posts'] ?><br /></label>
						</div>
						<p class="warntext"><strong><?php echo $lang_profile['Delete warning'] ?></strong></p>

#
#---------[ 14. REPLACE WITH ]---------------------------------------------------
#

							<label><input type="checkbox" name="delete_posts" value="1" /><?php echo $lang_profile['Delete posts'] ?><br /></label>
						</div>
						<?php if (isset($_POST['delete_spammer'])): ?><p><?php echo $lang_profile['Delete spammer note'] ?></p><?php endif; ?>
						<p class="warntext"><strong><?php echo $lang_profile['Delete warning'] ?></strong></p>


#---------[ 15. FIND ]-------------------------------------------------
#

			<p class="buttons"><input type="submit" name="delete_user_comply" value="<?php echo $lang_profile['Delete'] ?>" /> <a href="javascript:history.go(-1)"><?php echo $lang_common['Go back'] ?></a></p>

#
#---------[ 16. REPLACE WITH ]---------------------------------------------------
#

			<p class="buttons"><input type="submit" name="<?php echo (isset($_POST['delete_spammer']) ? 'delete_spammer_comply' : 'delete_user_comply'); ?>" value="<?php echo $lang_profile['Delete'] ?>" /> <a href="javascript:history.go(-1)"><?php echo $lang_common['Go back'] ?></a></p>

#---------[ 17. FIND ]-------------------------------------------------
#

							<input type="submit" name="delete_user" value="<?php echo $lang_profile['Delete user'] ?>" /> <input type="submit" name="ban" value="<?php echo $lang_profile['Ban user'] ?>" />

#
#---------[ 18. REPLACE WITH ]---------------------------------------------------
#

							<input type="submit" name="delete_user" value="<?php echo $lang_profile['Delete user'] ?>" /> <input type="submit" name="delete_spammer" value="<?php echo $lang_profile['Delete spammer'] ?>" /> <input type="submit" name="ban" value="<?php echo $lang_profile['Ban user'] ?>" />

#
#---------[ 19. OPEN ]---------------------------------------------------------
#

register.php

#
#---------[ 20. FIND ]-------------------------------------------------
#

// User pressed the cancel button

#
#---------[ 21. BEFORE, ADD ]---------------------------------------------------------
#

// HoneyPot Field Name
	if (($pun_config['o_sb_custom_field'] == 0))
		$reqfield = 'req_honeypot';
	else
		$reqfield=(!empty($pun_config['o_sb_custom_field_name'])) ? $pun_config['o_sb_custom_field_name'] : 'req_honeypot';
//End of HoneyPot Field Name

#
#---------[ 22. FIND ]-------------------------------------------------
#

	$username = pun_trim($_POST['req_user']);

#
#---------[ 23. REPLACE WITH ]---------------------------------------------------------
#

	$username = pun_trim($_POST[$reqfield]);

#
#---------[ 24. FIND ]-------------------------------------------------
#

	// Did everything go according to plan?

#
#---------[ 25. BEFORE, ADD ]---------------------------------------------------
#

//
// Begin of SpamBarrier check
//
	
	// Include the antispam library
	require PUN_ROOT.'include/spambarrier.php';

	$req_username = $username;
	
	sb_check_spam_registration($req_username,$email1);

//
// End of SpamBarrier check
//

#
#---------[ 26. FIND ]-------------------------------------------------
#

$required_fields = array('req_user' => $lang_common['Username'], 'req_password1' => $lang_common['Password'], 'req_password2' => $lang_prof_reg['Confirm pass'], 'req_email1' => $lang_common['Email'], 'req_email2' => $lang_common['Email'].' 2');
$focus_element = array('register', 'req_user');

#
#---------[ 27. REPLACE WITH ]---------------------------------------------------------
#

$required_fields = array($reqfield => $lang_common['Username'], 'req_password1' => $lang_common['Password'], 'req_password2' => $lang_prof_reg['Confirm pass'], 'req_email1' => $lang_common['Email'], 'req_email2' => $lang_common['Email'].' 2');
$focus_element = array('register', $reqfield);

#
#---------[ 28. FIND ]-------------------------------------------------
#

						<label class="required"><strong><?php echo $lang_common['Username'] ?> <span><?php echo $lang_common['Required'] ?></span></strong><br /><input type="text" name="req_user" value="<?php if (isset($_POST['req_user'])) echo pun_htmlspecialchars($_POST['req_user']); ?>" size="25" maxlength="25" /><br /></label>

#
#---------[ 29. REPLACE WITH ]---------------------------------------------------------
#

						<label class="required usernamefield"><strong><?php echo $lang_register['If human'] ?></strong><br /><input type="text" name="req_user" value="" size="25" maxlength="25" /><br /></label>
						<label class="required"><strong><?php echo $lang_common['Username'] ?> <span><?php echo $lang_common['Required'] ?></span></strong><br /><input type="text" name="<?php echo $reqfield ?>" value="<?php if (isset($_POST[$reqfield])) echo pun_htmlspecialchars($_POST[$reqfield]); ?>" size="25" maxlength="25" /><br /></label>

#
#---------[ 30. OPEN ]----------------------------------------------
#

login.php

#
#---------[ 31. FIND ]----------------------------------------------
#

	// Remove this user

#
#---------[ 32. BEFORE, ADD ]---------------------------------------------------
#

//
// SpamBarrier BEGIN
//
	// Include the antispam library
	require PUN_ROOT.'include/spambarrier.php';
	
	$membersIP= get_remote_address();

	sb_check_spam_login($membersIP,$form_username,$cur_user['email']);
	
//
// SpamBarrier END
//

#
#---------[ 33. OPEN ]---------------------------------------------------------
#

lang/English/profile.php

#
#---------[ 34. FIND ]-------------------------------------------------
#

'Delete user'				=>	'Delete user',

#
#---------[ 35. AFTER, ADD ]---------------------------------------------------
#

'Delete spammer'			=>	'Delete user &amp; report spam',
'Delete spammer note'		=>	'After deletion this user will be reported as a spammer. This is intended for reporting spam bots, <strong>not</strong> annoying users!',
'Spammer delete redirect'	=>	'User deleted and reported. Redirecting…',

#
#---------[ 36. OPEN ]---------------------------------------------------------
#

lang/French/profile.php

#
#---------[ 37. FIND ]---------------------------------------------------------
#

'Delete user'					=>	'Supprimer l\'utilisateur',
#
#---------[ 38. AFTER, ADD ]-------------------------------------------------
#

 
'Delete spammer'				=>	'Supprimer l\'utilisateur &amp; signaler le spammeur',
'Delete spammer note'			=>	'Après suppression, cet utilisateur sera signalé comme spammeur. Ceci est prévu pour signaler les robots de spam, <strong>pas</strong> pour les enquiquineurs !',
'Spammer delete redirect'		=>	'Utilisateur supprimé et signalé. Redirection …',


#
#---------[ 39. OPEN ]---------------------------------------------------------
#

lang/English/register.php

#
#---------[ 40. FIND  ]-------------------------------------------------
#

'Confirm email'				=>	'Confirm email address',

#
#---------[ 41. AFTER, ADD ]---------------------------------------------------
#

'If human'					=>	'If you want to be reported as a spammer, please fill in this field!',


#
#---------[ 42. OPEN ]---------------------------------------------------------
#

lang/French/register.php

#
#---------[ 43. FIND ]---------------------------------------------------------
#


'Confirm email'				=>	'Confirmez votre adresse électronique',

#
#---------[ 44. AFTER, ADD ]-------------------------------------------------
#

'If human'					=>	'Si vous voulez être recensé comme spammeur, remplissez ce champ !',

#
#---------[ 45. OPEN ]---------------------------------------------------------
#

style/Air.css

#
#---------[ 46. AT THE END, ADD ]---------------------------------------------------
#

/* Something extra for the honeypot spam mod */

.pun .usernamefield {
	display: none;
}

#
#---------[ 47. REPEAT steps 45 and 46 for every style you use on your forum ]---------------------------------------------------
#

#
#---------[ 48. SAVE, UPLOAD ]------------------------------------------------
#
