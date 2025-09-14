# SpamBarrier
No more captcha !!

This is a mod to protect FluxBB forums against forum spammers.</br>
# Compatibility
Tested with PHP 7.x, to be tested with more recent releases of PHP.<br/>
It works with FluxBB 1.5.10, and should work with 1.5.11 (though it has not been tested).</br>
Previous releases of the mod were working with older releases of FluxBB,<br/>
 so this release should work with older releases of FluxBB (as the code has not been highly modified), but this has not been tested.

# How it works ?
In registration phase :
- At first level, implements a HoneyPot which permits to detect automated registration attempts.</br>
  If an automated attempt is detected, it's possible to report it automatically to the StopForumSpam database.</br>
- At second level, checks if poster's IP address or Email is known by StopForumSpam database.</br>
- At third level, checks if poster's IP address is known by DNSBL (DNS blacklists).</br>

The registration attempt is stopped as soon as a check detects a potential spammer, with a message explaining the reason of blocking.

In login phase :
- At first level, checks if client's IP address or Email is known by StopForumSpam database.</br>
- At second level, checks if client's IP address is known by DNSBL (DNS blacklists).</br>

The connection attempt is blocked as soon as a check detects a potential spammer, with a message explaining the reason of blocking.

# Reporting to StopForumSpam
StopForumSpam is a free (and very efficient) database that tracks forum spammers.</br>
To report a spammer to StopForumSpam, you'll need an API key from StopForumSpam (by creating an account, which is free).</br>
This API key must be registered with 'AP_SpamBarrier.php' to activate the functionality.</br>
If you chose to report spammers to StopForumSpam, you will also be able to report manually spammers you detect</br>
on your forum by using the StopForumSpam reporting tool (https://www.stopforumspam.com/add).</br>
In this case, though it's not mandatory, it's recommended to furnish a proof (evidence) of the spam action.

# Whitelisting
With version 1.0.7, the mod provides a whitelisting mechanism.</br>
This mechanism can be used to accept a username, an IP address or a DNS domain even if it</br>
is listed on StopForumSpam or DNSBL, but only if you trust the user, the IP or the domain.</br>
The whitelisting mechanism handles two whitelists, one for registration phase and the other for login phase.</br>
The whitelisting mechanism is not used to bypass the HoneyPot field, as the HoneyPot field aims to detect automated attempts.</br>
Detailed informations about whitelisting can be found in the administration plugin (AP_SpamBarrier.php).

# Translation
The mod is available in English and in French.</br>
It may be translated by creating the subsequent directory in 'files/lang' (for example 'files/lang/Spanish')</br>
and creating the translation files (spambarrier.php and AP_SpamBarrier.php are to be translated), based on either English or French language.</br>
Be carefull when translating, the translated files must be well recognised and executed by PHP (UTF-8 file encoding, simple quotes in translation, ...).

# Installation of the mod  
Installation can be done manually or semi-automatically.  
## Database installation
If it's the first time you install the mod, or you have completely removed the mod, you must install the database.  
The <i>'install_mod.php'</i> takes care to not reinstall database if it's correctly installed.</br>
## Manual installation
The mod can be manually installed by following the steps in the <i>'readme.txt'</i> file.<br/>
The database installation is performed by copying the <i>'install_mod.php'</i> file in the root of the</br>
forum's directory (https://my_site.com/my_forum) and excuting <b>as admin</b> the <i>'install_mod.php'</i> file</br>
with your navigator (https://my_site.com/my_forum/install_mod in the navigator).<br/>
## Semi-automatic installation
The mod can be semi-automatically installed by using 'FluxBB Patcher' by Daris.<br/>
The database installation is performed by the Patcher at the beginning of the installation, before modifying files,  
but Patcher permits to skip database installation.

# Uninstallation
### [Note]
If the mod is to be reinstalled, you shouldn't modify the database when uninstalling. 

In case the mod is to be completely removed, including database specific table and fields, the <b>'install_mod.php'</b> file should be modified as following :<br/>
- Set the <b>$mod_restore variable</b> to <b>'true'</b> (it's set to <i>"$mod_restore    = false;"</i> in the script)<br/>
- Rename the <b>'restore_disabled'</b> function to <b>'restore'</b><br/>

Uninstallation can be done manually or semi-automatically.<br/>

## Manual uninstallation
Revert steps from <i>'readme.txt'</i> file starting from the end of the file.</br>
If the database informations are to be removed, run the <b>'install_mod.php'</b> file as when installing,</br>
a <b>'Restore'</b> button should be available if you modified the <i>'install_mod.php'</i> as described in above <b>Note</b>.
## Semi-automatic uninstallation
Use either the <b>'Disable'</b> option or <b>'Uninstall'</b> option of Daris's Patcher.</br>
The <b>'Disable'</b> option removes modifications made as described in <i>'readme.txt'</i>, but doesn't remove files from <i>'include'</i>, <i>'lang'</i> and <i>'plugins'</i>.</br>
The <b>'Uninstall'</b>option removes modifications, files that were copied in <i>'include'</i>, <i>'lang'</i> and <i>'plugins'</i>, and</br>
uninstalls modifications in database if you modified the <i>'install_mod.php'</i> as described in above <b>Note</b>.
