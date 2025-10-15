<?php
/***********************************************************************

  This software is free software; you can redistribute it and/or modify it
  under the terms of the GNU General Public License as published
  by the Free Software Foundation; either version 2 of the License,
  or (at your option) any later version.

  This software is distributed in the hope that it will be useful, but
  WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston,
  MA  02111-1307  USA

************************************************************************/
// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
    exit;

// Evaluate MySQL's function 'DATE(FROM_UNIXTIME(date))' depending on $db_type
// No known function compatible between these systems

switch ($db_type)
{
	case 'mysql':
	case 'mysql_innodb':
	case 'mysqli':
	case 'mysqli_innodb':
		$SB_Date_From_UnixTime = 'DATE(FROM_UNIXTIME(date))';
		break;

	case 'pgsql':
		$SB_Date_From_UnixTime = 'DATE(pg_catalog.to_timestamp(date)::timestamp without time zone)';
		break;

	case 'sqlite':
		$SB_Date_From_UnixTime = 'STRFTIME("%Y-%m-%d",date,"unixepoch")';
		break;

	default:
		error('\''.$db_type.'\' is not a valid database type. Please check settings in config.php.', __FILE__, __LINE__);
		$SB_Date_From_UnixTime = 'DATE(FROM_UNIXTIME(date))';
		break;
}


// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define('PUN_PLUGIN_LOADED', 1);
define('PLUGIN_VERSION', '1.0.7.1');

// Load the AP_SpamBarrier.php language file
if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/AP_SpamBarrier.php'))
	$langPatcher = require PUN_ROOT.'lang/'.$pun_user['language'].'/AP_SpamBarrier.php';
else
	$langPatcher = require PUN_ROOT.'lang/English/AP_SpamBarrier.php';

if (isset($_POST['form_sent']))
{
	// Lazy referer check (in case base_url isn't correct)
	if (!preg_match('#/admin_loader\.php#i', $_SERVER['HTTP_REFERER']))
		message($lang_common['Bad referrer']);

	$form = array_map('trim', $_POST['form']);

	while (list($key, $input) = @each($form))
	{
		// Only update values that have changed
		if ((isset($pun_config['o_'.$key])) || ($pun_config['o_'.$key] == NULL))
		{
			if ($pun_config['o_'.$key] != $input)
			{
				// It's better to sanitize the Whitelist lists
				if ($key == 'sb_wl_login' || $key == 'sb_wl_register')
				{
					$input = trim($input, ',');
					$input = preg_replace('/,\s*/', ',', $input);
					$input = preg_replace('/\s*,/', ',', $input);
					$input = preg_replace('/,+/', ',', $input);
					$input = preg_replace('/,/', ', ', $input);
				}
				if ($input != '' || is_int($input))
					$value = '\''.$db->escape($input).'\'';
				else
					$value = 'NULL';

				$db->query('UPDATE '.$db->prefix.'config SET conf_value='.$value.' WHERE conf_name=\'o_'.$db->escape($key).'\'') or error('Unable to update board config', __FILE__, __LINE__, $db->error());
			}
		}
	}

	// Regenerate the config cache
	require_once PUN_ROOT.'include/cache.php';
	generate_config_cache();

	redirect('admin_loader.php?plugin=AP_SpamBarrier.php', $lang_ap_spambarrier['Redirect message']);
}
else if (isset($_POST['search_users']))
{
	// Display the admin navigation menu
    ?>
    <div class="linkst">
        <div class="inbox">
            <div><a href="javascript:history.go(-1)"><?php echo $lang_ap_spambarrier['Go_back'] ?></a></div>
        </div>
    </div>

    <div id="users2" class="blocktable">
        <h2><span><?php echo $lang_ap_spambarrier['SB_users'] ?></span></h2>
        <div class="box">
            <div class="inbox">
                <table cellspacing="0">
                <thead>
                    <tr>
                        <th class="tcl" scope="col"><?php echo $lang_ap_spambarrier['Col_Username'] ?></th>
                        <th class="tcr" scope="col"><?php echo $lang_ap_spambarrier['Col_Email'] ?></th>
                        <th class="tc3" scope="col"><?php echo $lang_ap_spambarrier['Col_Posts'] ?></th>
                        <th class="tc2" scope="col"><?php echo $lang_ap_spambarrier['Col_Signature'] ?></th>
                        <th class="tc2" scope="col"><?php echo $lang_ap_spambarrier['Col_Website'] ?></th>
                        <th class="tc3" scope="col"><?php echo $lang_ap_spambarrier['Col_Registered'] ?></th>
                    </tr>
                </thead>
                <tbody>
    <?php
    // $result = $db->query('SELECT * FROM '.$db->prefix.'users WHERE id > 1 AND num_posts = 0 AND signature IS NOT NULL ORDER BY registered DESC LIMIT 50') or error('Unable to fetch users', __FILE__, __LINE__, $db->error());
    $result = $db->query('SELECT * FROM '.$db->prefix.'users WHERE id > 1 AND num_posts = 0 AND signature like "%http%" ORDER BY registered DESC LIMIT 50') or error('Unable to fetch users', __FILE__, __LINE__, $db->error());

    // If there are users with informations in their signatures but 0 posts
    if ($db->num_rows($result))
    {
        require PUN_ROOT.'include/parser.php';
        while ($cur_user = $db->fetch_assoc($result))
        {
            if (isset($signature_cache[$cur_user['id']]))
                $signature = $signature_cache[$cur_user['id']];
            else
            {
                $signature = parse_signature($cur_user['signature']);
                $signature_cache[$cur_user['id']] = $signature;
            }

            echo "\t\t\t\t\t\t".'<tr>
            <td class="tcl"><a href="profile.php?id='.$cur_user['id'].'">'.pun_htmlspecialchars($cur_user['username']).'</a></td>
            <td class="tcr">'.$cur_user['email'].'</td>
            <td class="tc3">'.forum_number_format($cur_user['num_posts']).'</td>
            <td class="tc2">'.$signature.'</td>
            <td class="tc2">'.pun_htmlspecialchars($cur_user['url']).'</td>
            <td class="tc3">'.format_time($cur_user['registered'], true).'</td></tr>'."\n";
        }
    }
        else
            echo "\t\t\t\t".'<tr><td class="tcl" colspan="6">'.$lang_ap_spambarrier['No_match'].'</td></tr>'."\n";
    ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="linksb">
        <div class="inbox">
            <div><a href="javascript:history.go(-1)"><?php echo $lang_ap_spambarrier['Go_back'] ?></a></div>
        </div>

    </div>
    <?php
}
else if (isset($_POST['get_stats']))
{
	// Collect some statistics from the database
	$stats = array();
    
	$result = $db->query('SELECT count(*) FROM '.$db->prefix.'test_registrations') or error('Error0', __FILE__, __LINE__, $db->error());
	$stats['collecting_total'] = $db->result($result);
	
	$result = $db->query('SELECT MIN(date) FROM '.$db->prefix.'test_registrations') or error('Error1', __FILE__, __LINE__, $db->error());
	$stats['collecting_since'] = $db->result($result);

	$result = $db->query('SELECT COUNT(id) FROM '.$db->prefix.'test_registrations WHERE spam=\'0\'') or error('Error2', __FILE__, __LINE__, $db->error());
	$stats['num_nospam'] = $db->result($result);
	
	$result = $db->query('SELECT COUNT(id) FROM '.$db->prefix.'test_registrations WHERE spam=\'1\'') or error('Error3', __FILE__, __LINE__, $db->error());
	$stats['num_honeypot'] = $db->result($result);
	
	$result = $db->query('SELECT COUNT(id) FROM '.$db->prefix.'test_registrations WHERE spam=\'2\'') or error('Error4', __FILE__, __LINE__, $db->error());
	$stats['num_blacklist'] = $db->result($result);
	
	$result = $db->query('SELECT COUNT(id) FROM '.$db->prefix.'test_registrations WHERE spam=\'3\'') or error('Error5', __FILE__, __LINE__, $db->error());
	$stats['num_dnsbl'] = $db->result($result);
	
	$result = $db->query('SELECT COUNT(id)/7 FROM '.$db->prefix.'test_registrations WHERE spam=\'0\' AND date > '.(time() - 7*24*60*60)) or error('Error6', __FILE__, __LINE__, $db->error());
	$stats['avg_nospam'] = $db->result($result);
	
	$result = $db->query('SELECT COUNT(id)/7 FROM '.$db->prefix.'test_registrations WHERE spam=\'1\' AND date > '.(time() - 7*24*60*60)) or error('Error7', __FILE__, __LINE__, $db->error());
	$stats['avg_honeypot'] = $db->result($result);
	
	$result = $db->query('SELECT COUNT(id)/7 FROM '.$db->prefix.'test_registrations WHERE spam=\'2\' AND date > '.(time() - 7*24*60*60)) or error('Error8', __FILE__, __LINE__, $db->error());
	$stats['avg_blacklist'] = $db->result($result);
	
	$result = $db->query('SELECT COUNT(id)/7 FROM '.$db->prefix.'test_registrations WHERE spam=\'3\' AND date > '.(time() - 7*24*60*60)) or error('Error9', __FILE__, __LINE__, $db->error());
	$stats['avg_dnsbl'] = $db->result($result);

	
	$result = $db->query('SELECT ' . $SB_Date_From_UnixTime . ' AS day, COUNT(date) AS num_blocked FROM '.$db->prefix.'test_registrations WHERE spam = \'0\' GROUP BY ' . $SB_Date_From_UnixTime . ' ORDER BY num_blocked DESC LIMIT 1') or error('Error10', __FILE__, __LINE__, $db->error());
	list($stats['most_nospam_date'], $stats['most_nospam_num']) = $db->fetch_row($result);
	
	$result = $db->query('SELECT ' . $SB_Date_From_UnixTime . ' AS day, COUNT(date) AS num_blocked FROM '.$db->prefix.'test_registrations WHERE spam = \'1\' GROUP BY ' . $SB_Date_From_UnixTime . ' ORDER BY num_blocked DESC LIMIT 1') or error('Error11', __FILE__, __LINE__, $db->error());
	list($stats['most_honeypot_date'], $stats['most_honeypot_num']) = $db->fetch_row($result);
	
	$result = $db->query('SELECT ' . $SB_Date_From_UnixTime . ' AS day, COUNT(date) AS num_blocked FROM '.$db->prefix.'test_registrations WHERE spam = \'2\' GROUP BY ' . $SB_Date_From_UnixTime . ' ORDER BY num_blocked DESC LIMIT 1') or error('Error12', __FILE__, __LINE__, $db->error());
	list($stats['most_blacklist_date'], $stats['most_blacklist_num']) = $db->fetch_row($result);
	
	$result = $db->query('SELECT ' . $SB_Date_From_UnixTime . ' AS day, COUNT(date) AS num_blocked FROM '.$db->prefix.'test_registrations WHERE spam = \'3\' GROUP BY ' . $SB_Date_From_UnixTime . ' ORDER BY num_blocked DESC LIMIT 1') or error('Error13', __FILE__, __LINE__, $db->error());
	list($stats['most_dnsbl_date'], $stats['most_dnsbl_num']) = $db->fetch_row($result);

	$result = $db->query('SELECT ' . $SB_Date_From_UnixTime . ' AS day, COUNT(date) AS num_blocked FROM '.$db->prefix.'test_registrations WHERE spam = \'1\' AND date > '.(time()-14*24*60*60).' GROUP BY ' . $SB_Date_From_UnixTime . '') or error('Unable to fetch honeypot 14 day log', __FILE__, __LINE__, $db->error());
	while ($cur_date = $db->fetch_assoc($result))
		$stats['last_14days_honeypot'][$cur_date['day']] = $cur_date['num_blocked'];
	
	$result = $db->query('SELECT ' . $SB_Date_From_UnixTime . ' AS day, COUNT(date) AS num_blocked FROM '.$db->prefix.'test_registrations WHERE spam = \'2\' AND date > '.(time()-14*24*60*60).' GROUP BY ' . $SB_Date_From_UnixTime . '') or error('Unable to fetch sfs 14 day log', __FILE__, __LINE__, $db->error());
	while ($cur_date = $db->fetch_assoc($result))
		$stats['last_14days_sfs'][$cur_date['day']] = $cur_date['num_blocked'];
	
	$result = $db->query('SELECT ' . $SB_Date_From_UnixTime . ' AS day, COUNT(date) AS num_blocked FROM '.$db->prefix.'test_registrations WHERE spam = \'3\' AND date > '.(time()-14*24*60*60).' GROUP BY ' . $SB_Date_From_UnixTime . '') or error('Unable to fetch dnsbl 14 day log', __FILE__, __LINE__, $db->error());
	while ($cur_date = $db->fetch_assoc($result))
		$stats['last_14days_dnsbl'][$cur_date['day']] = $cur_date['num_blocked'];
?>
    <div class="linkst">
        <div class="inbox">
            <div><a href="javascript:history.go(-1)"><?php echo $lang_ap_spambarrier['Go_back'] ?></a></div>
        </div>
    </div>
    <div class="blockform block2">
		<h2><span><?php echo $lang_ap_spambarrier['Registration_stats'] ?></span></h2>
		<div id="adstats" class="box">
			<div class="inbox">
				<dl>
					<dt><?php echo $lang_ap_spambarrier['Collecting_since'] ?></dt>
					<dd>
						<?php echo ($stats['collecting_since'] != '') ? date($pun_config['o_date_format'], $stats['collecting_since']).' ('.floor((time()-$stats['collecting_since'])/(60*60*24)).' '.$lang_ap_spambarrier['days'].')' : $lang_ap_spambarrier['N_A'] ?><br />
					</dd>
					<dt><?php echo $lang_ap_spambarrier['Total'] ?></dt>
					<dd>
                        <?php echo $lang_ap_spambarrier['Global total'] ?> <?php echo $stats['collecting_total'] ?><br/>
						<?php echo $lang_ap_spambarrier['NS'] ?> <?php echo $stats['num_nospam'] ?><br />
						<?php echo $lang_ap_spambarrier['BBH'] ?> <?php echo $stats['num_honeypot'] ?><br />
						<?php echo $lang_ap_spambarrier['BBS'] ?> <?php echo $stats['num_blacklist'] ?><br />
						<?php echo $lang_ap_spambarrier['BBD'] ?> <?php echo $stats['num_dnsbl']."\n" ?>
					</dd>
					<dt><?php echo $lang_ap_spambarrier['Avg_7d'] ?></dt>
					<dd>
						<?php echo $lang_ap_spambarrier['NS'] ?><?php echo round($stats['avg_nospam'], 2) ?> <?php echo $lang_ap_spambarrier['per_day'] ?><br />
						<?php echo $lang_ap_spambarrier['BBH'] ?><?php echo round($stats['avg_honeypot'], 2) ?> <?php echo $lang_ap_spambarrier['per_day'] ?><br />
						<?php echo $lang_ap_spambarrier['BBS'] ?><?php echo round($stats['avg_blacklist'], 2) ?> <?php echo $lang_ap_spambarrier['per_day'] ?><br />
						<?php echo $lang_ap_spambarrier['BBD'] ?><?php echo round($stats['avg_dnsbl'], 2) ?> <?php echo $lang_ap_spambarrier['per_day'] ?>
					</dd>
					<dt><?php echo $lang_ap_spambarrier['Max_day'] ?></dt>
					<dd>
						<?php echo $lang_ap_spambarrier['NS'] ?><?php echo ($stats['most_nospam_num'] > 0) ? $stats['most_nospam_num'].' ('.date($pun_config['o_date_format'], strtotime($stats['most_nospam_date'])).')' : '0' ?><br />
						<?php echo $lang_ap_spambarrier['BBH'] ?><?php echo ($stats['most_honeypot_num'] > 0) ? $stats['most_honeypot_num'].' ('.date($pun_config['o_date_format'], strtotime($stats['most_honeypot_date'])).')' : '0' ?><br />
						<?php echo $lang_ap_spambarrier['BBS'] ?><?php echo ($stats['most_blacklist_num'] > 0) ? $stats['most_blacklist_num'].' ('.date($pun_config['o_date_format'], strtotime($stats['most_blacklist_date'])).')'."\n" : '0' ?><br />
						<?php echo $lang_ap_spambarrier['BBD'] ?><?php echo ($stats['most_dnsbl_num'] > 0) ? $stats['most_dnsbl_num'].' ('.date($pun_config['o_date_format'], strtotime($stats['most_dnsbl_date'])).')'."\n" : '0'."\n" ?>
					</dd>
                    <dt><?php echo $lang_ap_spambarrier['Block_14d'] ?></dt>
					<dd>
<?php
$result = $db->query('SELECT ' . $SB_Date_From_UnixTime . ' AS day, COUNT(date) AS num_blocked FROM '.$db->prefix.'test_registrations WHERE spam > \'0\' AND date > '.(time()-14*24*60*60).' GROUP BY ' . $SB_Date_From_UnixTime . '
') or error($lang_ap_spambarrier['Unable_14d'], __FILE__, __LINE__, $db->error());

    // If there are topics in this forum.
    if ($db->num_rows($result))
    {
        $total_total = 0;
        $total_honeypot = 0;
        $total_sfs = 0;
        $total_dnsbl = 0;

        echo "\t\t\t\t\t\t".'<table>'."\n";
        echo "\t\t\t\t\t\t".'<tr>'."\n";
        echo '<td style="padding: 0; border: 0; width:24%">'.$lang_ap_spambarrier['StatsDate'].'</td>'."\n";
        echo '<td style="padding: 0; border: 0; width:12%">'.$lang_ap_spambarrier['StatsTotal'].'</td>'."\n";
        echo '<td style="padding: 0; border: 0; width:12%">'.$lang_ap_spambarrier['StatsHoneyPot'].'</td>'."\n";
        echo '<td style="padding: 0; border: 0; width:12%">'.$lang_ap_spambarrier['StatsSFS'].'</td>'."\n";
        echo '<td style="padding: 0; border: 0; width:40%">'.$lang_ap_spambarrier['StatsDNSBL'].'</td></tr>'."\n";

        while ($cur_date = $db->fetch_assoc($result))
        {
            $day_honeypot = ($stats['last_14days_honeypot'][$cur_date['day']] != '') ? $stats['last_14days_honeypot'][$cur_date['day']] : '0';
            $day_sfs = ($stats['last_14days_sfs'][$cur_date['day']] != '') ? $stats['last_14days_sfs'][$cur_date['day']] : '0';
            $day_dnsbl = ($stats['last_14days_dnsbl'][$cur_date['day']] != '') ? $stats['last_14days_dnsbl'][$cur_date['day']] : '0';
            echo "\t\t\t\t\t\t".'<tr><td style="padding: 0; border: 0">'.date($pun_config['o_date_format'], strtotime($cur_date['day'])).'</td><td style="padding: 0; border: 0">'.$cur_date['num_blocked'].'</td><td style="padding: 0; border: 0">'.$day_honeypot.'</td><td style="padding: 0; border: 0">'.$day_sfs.'</td><td style="padding: 0; border: 0">'.$day_dnsbl.'</td></tr>'."\n";

            $total_honeypot += $day_honeypot;
            $total_sfs += $day_sfs;
            $total_dnsbl += $day_dnsbl;
            $total_total += $cur_date['num_blocked'];
        }
        
        echo "\t\t\t\t\t\t".'<tr style="border-top: 1px solid #CCC;"><td style="padding: 0; border: 0"><strong>'.$lang_ap_spambarrier['Sum14Days'].'</strong></td><td style="padding: 0; border: 0"><strong>'.$total_total.'</strong></td><td style="padding: 0; border: 0"><strong>'.$total_honeypot.'</strong></td><td style="padding: 0; border: 0"><strong>'.$total_sfs.'</strong></td><td style="padding: 0; border: 0"><strong>'.$total_dnsbl.'</strong></td></tr>'."\n";

        echo "\t\t\t\t\t\t".'</table>'."\n";
    }
    else
    {
        echo $lang_ap_spambarrier['N_A'];
    }
?>
                    </dd>
                </dl>
			</div>
		</div>
    </div>

    <div class="linkst">
        <div class="inbox">
            <div><a href="javascript:history.go(-1)"><?php echo $lang_ap_spambarrier['Go_back'] ?></a></div>
        </div>
    </div>

    <?php
} else {
    
	// Display the admin navigation menu  
	generate_admin_menu($plugin);
?>

	<div class="block">
		<h2><span>SpamBarrier - v<?php echo PLUGIN_VERSION ?></span></h2>
		<div class="box">
			<div class="inbox">
				<p><?php echo $lang_ap_spambarrier['Description'] ?></p>
			</div>
		</div>
	</div>
	<div class="blockform">
		<h2 class="block2"><span><?php echo $lang_ap_spambarrier['Options'] ?></span></h2>
		<div class="box">
			<form method="post" action="admin_loader.php?plugin=AP_SpamBarrier.php">
				<p class="submittop"><input type="submit" name="save" value="<?php echo $lang_ap_spambarrier['Save'] ?>" /></p>
				<div class="inform">
					<input type="hidden" name="form_sent" value="1" />
					<fieldset>
						<legend><?php echo $lang_ap_spambarrier['WL_Settings'] .'  <a><img src="style/'.$pun_user['style'].'/img/help.png" alt="Aide" title="'.$lang_ap_spambarrier['WLC_description'].'"/> </a>'?></legend>
						<div class="infldset">
									<!--<span><?php echo $lang_ap_spambarrier['WLC_description'] ?></span>-->
						<table class="aligntop" cellspacing="0">
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['WLL_check'] ?></th>
								<td>
									<input type="radio" name="form[sb_wl_cl]" value="1"<?php if ($pun_config['o_sb_wl_cl'] == '1') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['Yes'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[sb_wl_cl]" value="0"<?php if ($pun_config['o_sb_wl_cl'] == '0') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['No'] ?></strong>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['WL_Login_List'] ?></th>
								<td>
									<span><?php echo $lang_ap_spambarrier['WL_Login_List_description'] ?></span>
									<textarea name="form[sb_wl_login]" rows="5" cols="55"><?php echo pun_htmlspecialchars($pun_config['o_sb_wl_login']) ?></textarea>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['WLR_check'] ?></th>
								<td>
									<input type="radio" name="form[sb_wl_cr]" value="1"<?php if ($pun_config['o_sb_wl_cr'] == '1') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['Yes'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[sb_wl_cr]" value="0"<?php if ($pun_config['o_sb_wl_cr'] == '0') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['No'] ?></strong>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['WL_Register_List'] ?></th>
								<td>
									<span><?php echo $lang_ap_spambarrier['WL_Register_List_description'] ?></span>
									<textarea name="form[sb_wl_register]" rows="5" cols="55"><?php echo pun_htmlspecialchars($pun_config['o_sb_wl_register'])?></textarea>
								</td>
							</tr>
						</table>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php echo $lang_ap_spambarrier['HP_Settings'] .'  <a><img src="style/'.$pun_user['style'].'/img/help.png" alt="Aide_HP" title="'.$lang_ap_spambarrier['HP_description'].'"/> </a>'?></legend>
						<div class="infldset">
						<table class="aligntop" cellspacing="0">
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['HP_check'] ?></th>
								<td>
									<input type="radio" name="form[sb_check_hp]" value="1"<?php if ($pun_config['o_sb_check_hp'] == '1') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['Yes'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[sb_check_hp]" value="0"<?php if ($pun_config['o_sb_check_hp'] == '0') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['No'] ?></strong>
									<!-- <span><?php echo $lang_ap_spambarrier['HP_description'] ?></span> -->
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['HP_custom_field'] ?></th>
								<td>
									<input type="radio" name="form[sb_custom_field]" value="1"<?php if ($pun_config['o_sb_custom_field'] == '1') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['Yes'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[sb_custom_field]" value="0"<?php if ($pun_config['o_sb_custom_field'] == '0') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['No'] ?></strong>
									<span><?php echo $lang_ap_spambarrier['HP_custom_field_description'] ?></span>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['HP_custom_field_name'] ?></th>
								<td>
									<input type="text" name="form[sb_custom_field_name]" size="20" maxlength="30" value="<?php echo pun_htmlspecialchars($pun_config['o_sb_custom_field_name']) ?>" />
									<span><?php echo $lang_ap_spambarrier['HP_custom_field_name_description'] ?></span>
								</td>
							</tr>
						</table>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php echo $lang_ap_spambarrier['SFS_Settings'] .'  <a><img src="style/'.$pun_user['style'].'/img/help.png" alt="Aide_SFS" title="'.$lang_ap_spambarrier['SFS_description'].'"/> </a>'?></legend>
						<div class="infldset">
						<table class="aligntop" cellspacing="0">
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['SFS_reg_check'] ?></th>
								<td>
									<input type="radio" name="form[sb_check_sfs_register]" value="1"<?php if ($pun_config['o_sb_check_sfs_register'] == '1') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['Yes'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[sb_check_sfs_register]" value="0"<?php if ($pun_config['o_sb_check_sfs_register'] == '0') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['No'] ?></strong>
									<span><?php echo $lang_ap_spambarrier['SFS_reg_description'] ?></span>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['SFS_login_check'] ?></th>
								<td>
									<input type="radio" name="form[sb_check_sfs_login]" value="1"<?php if ($pun_config['o_sb_check_sfs_login'] == '1') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['Yes'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[sb_check_sfs_login]" value="0"<?php if ($pun_config['o_sb_check_sfs_login'] == '0') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['No'] ?></strong>
									<span><?php echo $lang_ap_spambarrier['SFS_log_description'] ?></span>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['Enable_SFS_report'] ?></th>
								<td>
									<input type="radio" name="form[sb_sfs_report]" value="1"<?php if ($pun_config['o_sb_sfs_report'] == '1') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['Yes'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[sb_sfs_report]" value="0"<?php if ($pun_config['o_sb_sfs_report'] == '0') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['No'] ?></strong>
									<span><?php echo $lang_ap_spambarrier['SFS_report_description'] ?></span>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['SFS_API'] ?></th>
								<td>
									<input type="text" name="form[sb_sfs_api_key]" size="20" maxlength="30" value="<?php echo pun_htmlspecialchars($pun_config['o_sb_sfs_api_key']) ?>" />
									<span><?php echo $lang_ap_spambarrier['SFS_api_description'] ?></span>
								</td>
							</tr>
						</table>
						</div>
					</fieldset>
					<fieldset>
						<legend><?php echo $lang_ap_spambarrier['DNSBL_Settings'] .'  <a><img src="style/'.$pun_user['style'].'/img/help.png" alt="Aide_DNSBL" title="'.$lang_ap_spambarrier['DNSBL_description'].'"/> </a>'?></legend>
						<div class="infldset">
						<table class="aligntop" cellspacing="0">
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['DNSBL_login_check'] ?></th>
								<td>
									<input type="radio" name="form[sb_check_dnsbl_login]" value="1"<?php if ($pun_config['o_sb_check_dnsbl_login'] == '1') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['Yes'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[sb_check_dnsbl_login]" value="0"<?php if ($pun_config['o_sb_check_dnsbl_login'] == '0') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['No'] ?></strong>
									<span><?php echo $lang_ap_spambarrier['DNSBL_login_description'] ?></span>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['DNSBL_reg_check'] ?></th>
								<td>
									<input type="radio" name="form[sb_check_dnsbl_register]" value="1"<?php if ($pun_config['o_sb_check_dnsbl_register'] == '1') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['Yes'] ?></strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="form[sb_check_dnsbl_register]" value="0"<?php if ($pun_config['o_sb_check_dnsbl_register'] == '0') echo ' checked="checked"' ?> />&nbsp;<strong><?php echo $lang_ap_spambarrier['No'] ?></strong>
									<span><?php echo $lang_ap_spambarrier['DNSBL_reg_description'] ?></span>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php echo $lang_ap_spambarrier['DNSBL_list'] ?></th>
								<td>
									<span><?php echo $lang_ap_spambarrier['DNSBL_list_description'] ?></span><br />
									<textarea name="form[sb_dnsbl_names]" rows="5" cols="55"><?php echo pun_htmlspecialchars($pun_config['o_sb_dnsbl_names']) ?></textarea>
								</td>
							</tr>
						</table>
						</div>
					</fieldset>
				</div>
			<p class="submitend"><input type="submit" name="save" value="<?php echo $lang_ap_spambarrier['Save'] ?>" /></p>
			</form>
		</div>
	</div>

	<div class="blockform">
		<h2><span><?php echo $lang_ap_spambarrier['Search_users'] ?></span></h2>
		<div class="box">
			<form method="post" action="admin_loader.php?plugin=AP_SpamBarrier.php">
				<div class="inbox">
					<p><?php echo $lang_ap_spambarrier['Search_description'] ?>
					</p>
				</div>
				<p class="submitend">
					<input type="submit" name="search_users" value="<?php echo $lang_ap_spambarrier['Go!'] ?>" >
                    </input>
				</p>
			</form>
		</div>
	</div>

	<div class="blockform">
		<h2><span><?php echo $lang_ap_spambarrier['Display stats'] ?></span></h2>
		<div class="box">
			<form method="post" action="admin_loader.php?plugin=AP_SpamBarrier.php">
				<div class="inbox">
					<p><?php echo $lang_ap_spambarrier['Stats explanation']?>
					</p>
				</div>
				<p class="submitend">
					<input type="submit" name="get_stats" value="<?php echo $lang_ap_spambarrier['Display the statistics'] ?>" />
				</p>
			</form>
		</div>
	</div>


    <?php
}
    ?>
    
<div>
