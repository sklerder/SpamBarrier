<?php
/***********************************************************************/

// Some info about your mod.
$mod_title      = 'SpamBarrier';
$mod_version    = '1.0.7';
$release_date   = '2025-09-12';
$author         = 'Sklerder';
$author_email   = 'sklerder@orange.fr';

// Versions of FluxBB this mod was created for. A warning will be displayed, if versions do not match
$fluxbb_versions= array('1.5');

// Set this to false if you do not want to change database on uninstall or have not implemented the restore function (see below)
// If using Daris's Patcher, it's advised to rename the restore function, for example in 'restore_disabled' because Daris's Patcher invokes the restore function even if it is disabled by '$mod_restore = false;'
$mod_restore    = false;


// This following function will be called when the user presses the "Install" button
function install()
{
    global $db, $db_type, $pun_config;

    //New Install
    if (!$db->table_exists('test_registrations'))
    {
        $schema = array(
            'FIELDS'                => array(
                'id'                => array(
                    'datatype'      => 'SERIAL',
                    'allow_null'    => false
                ),
                'username'          => array(
                    'datatype'      => 'VARCHAR(200)',
                    'allow_null'    => false,
                    'default'       => '\'\''
                ),
                'email'             => array(
                    'datatype'      => 'VARCHAR(80)',
                    'allow_null'    => false,
                    'default'       => '\'\''
                ),
                'email_setting'     => array(
                    'datatype'      => 'TINYINT(1)',
                    'allow_null'    => false,
                    'default'       => '1'
                ),
                'timezone'          => array(
                    'datatype'      => 'FLOAT',
                    'allow_null'    => false,
                    'default'       => '0'
                ),
                'ip'                => array(
                    'datatype'      => 'VARCHAR(39)',
                    'allow_null'    => false,
                    'default'       => '\'0.0.0.0\''
                ),
                'referer'           => array(
                    'datatype'      => 'VARCHAR(1000)',
                    'allow_null'    => false,
                    'default'       => '\'\''
                ),
                'user_agent'        => array(
                    'datatype'      => 'VARCHAR(255)',
                    'allow_null'    => false,
                    'default'       => '\'\''
                ),
                'date'              => array(
                    'datatype'      => 'INT(10) UNSIGNED',
                    'allow_null'    => false,
                    'default'       => '0'
                ),
                'spam'              => array(
                    'datatype'      => 'VARCHAR(50)',
                    'allow_null'    => false,
                    'default'       => '\'\''
                ),
                's_errors'          => array(
                    'datatype'      => 'VARCHAR(1000)',
                    'allow_null'    => false,
                    'default'       => '\'\''
                ),
                'count_errors'      => array(
                    'datatype'      => 'MEDIUMINT(8) UNSIGNED',
                    'allow_null'    => false,
                    'default'       => '0'
                ),
                'botcheck'          => array(
                    'datatype'      => 'MEDIUMINT(8) UNSIGNED',
                    'allow_null'    => false,
                    'default'       => '0'
                ),
                'infos'             => array(
                    'datatype'      => 'VARCHAR(1000)',
                    'allow_null'    => false,
                    'default'       => '\'\''
                )
            ),
            'PRIMARY KEY'    => array('id'),
        );

        $db->create_table('test_registrations', $schema) or error('Unable to create table '.$db->prefix.'test_registrations.', __FILE__, __LINE__, $db->error());
    }

    // Insert config data
    $config = array(
        'o_sb_check_hp'                     => "'1'",
        'o_sb_custom_field'                 => "'1'",
        'o_sb_custom_field_name'            => "''",
        'o_sb_check_sfs_register'           => "'1'",
        'o_sb_check_sfs_login'              => "'1'",
        'o_sb_check_dnsbl_register'         => "'1'",
        'o_sb_check_dnsbl_login'            => "'0'",
        'o_sb_dnsbl_names'                  => "'sbl.spamhaus.org, xbl.spamhaus.org, b.barracudacentral.org, opm.tornevall.org'",
        'o_sb_sfs_report'                   => "'0'",
        'o_sb_sfs_api_key'                  => "''",
        'o_sb_sfs_maxcheck'                 => "'20000'",
        'o_sb_out_of_limit_ok'              => "'0'",
        'o_sb_wl_cl'                        => "'0'",
        'o_sb_wl_cr'                        => "'0'",
        'o_sb_wl_login'                     => "''",
        'o_sb_wl_register'                  => "''",
    );
// 'o_sb_sfs_maxcheck' and 'o_sb_out_of_limit_ok' are there for future usage, not implemented at the moment.

    while (list($conf_name, $conf_value) = @each($config))
    {
        if (!array_key_exists($conf_name, $pun_config))
        $db->query('INSERT INTO '.$db->prefix."config (conf_name, conf_value) VALUES('$conf_name', $conf_value)")
            or error('Unable to insert into table '.$db->prefix.'config. Please check your configuration and try again.');
    }

    
    // Regenerate the config cache
    if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
        require PUN_ROOT.'include/cache.php';

    generate_config_cache();
}

// This following function will be called when the user presses the "Restore" button (only if $mod_restore is true (see above))
// If using Daris's Patcher, it's advised to rename the restore function, for example in 'restore_disabled', because Daris's Patcher invokes the restore function even if it is disabled by '$mod_restore = false;'
function restore_disabled()
{
    global $mod_restore;
    if ($mod_restore)
    {
        global $db, $db_type, $pun_config;

        $db->drop_table('test_registrations') or error('Unable to drop '.$db->prefix.'test_registrations table', __FILE__, __LINE__, $db->error());

        $like_command = ($db_type == 'pgsql') ? 'ILIKE' : 'LIKE';
        $db->query('DELETE FROM '.$db->prefix.'config WHERE conf_name '.$like_command.' \'o_sb%\'') or error('Unable to remove config entries', __FILE__, __LINE__, $db->error());
    
        // Regenerate the config cache
        if (!defined('FORUM_CACHE_FUNCTIONS_LOADED'))
            require PUN_ROOT.'include/cache.php';

        generate_config_cache();
    }
    else
    {
        return true;
    }
}

/***********************************************************************/

// DO NOT EDIT ANYTHING BELOW THIS LINE!


// Circumvent maintenance mode
define('PUN_TURN_OFF_MAINT', 1);
define('PUN_ROOT', './');
require PUN_ROOT.'include/common.php';

// We want the complete error message if the script fails
if (!defined('PUN_DEBUG'))
    define('PUN_DEBUG', 1);

// Make sure we are running a FluxBB version that this mod works with
$version_warning = !in_array($pun_config['o_cur_version'], $fluxbb_versions);

$style = (isset($pun_user)) ? $pun_user['style'] : $pun_config['o_default_style'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo pun_htmlspecialchars($mod_title) ?> installation</title>
<link rel="stylesheet" type="text/css" href="style/<?php echo $style.'.css' ?>" />
</head>
<body>

<div id="punwrap">
<div id="puninstall" class="pun" style="margin: 10% 20% auto 20%">

<?php

if (isset($_POST['form_sent']))
{
    if (isset($_POST['install']))
    {
        // Run the install function (defined above)
        install();

?>
<div class="block">
    <h2><span>Installation successful</span></h2>
    <div class="box">
        <div class="inbox">
            <p>Your database has been successfully prepared for <?php echo pun_htmlspecialchars($mod_title) ?>. See readme.txt for further instructions.</p>
        </div>
    </div>
</div>
<?php

    }
    else
    {
        // Run the restore function (defined above)
        restore();

?>
<div class="block">
    <h2><span>Restore successful</span></h2>
    <div class="box">
        <div class="inbox">
            <p>Your database has been successfully restored.</p>
        </div>
    </div>
</div>
<?php

    }
}
else
{

?>
<div class="blockform">
    <h2><span>Mod installation</span></h2>
    <div class="box">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?foo=bar">
            <div><input type="hidden" name="form_sent" value="1" /></div>
            <div class="inform">
                <p>This script will update your database to work with the following modification:</p>
                <p><strong>Mod title:</strong> <?php echo pun_htmlspecialchars($mod_title.' '.$mod_version) ?></p>
                <p><strong>Author:</strong> <?php echo pun_htmlspecialchars($author) ?> (<a href="mailto:<?php echo pun_htmlspecialchars($author_email) ?>"><?php echo pun_htmlspecialchars($author_email) ?></a>)</p>
                <p><strong>Disclaimer:</strong> Mods are not officially supported by FluxBB. Mods generally can't be uninstalled without running SQL queries manually against the database. Make backups of all data you deem necessary before installing.</p>
<?php if ($mod_restore): ?>
                <p>If you've previously installed this mod and would like to uninstall it, you can click the Restore button below to restore the database.</p>
<?php endif; ?>
<?php if ($version_warning): ?>
                <p style="color: #a00"><strong>Warning:</strong> The mod you are about to install was not made specifically to support your current version of FluxBB (<?php echo $pun_config['o_cur_version']; ?>). This mod supports FluxBB versions: <?php echo pun_htmlspecialchars(implode(', ', $fluxbb_versions)); ?>. If you are uncertain about installing the mod due to this potential version conflict, contact the mod author.</p>
<?php endif; ?>
            </div>
            <p class="buttons"><input type="submit" name="install" value="Install" /><?php if ($mod_restore): ?><input type="submit" name="restore" value="Restore" /><?php endif; ?></p>
        </form>
    </div>
</div>
<?php

}

?>

</div>
</div>

</body>
</html>
<?php

// End the transaction
$db->end_transaction();

// Close the db connection (and free up any result data)
$db->close();
