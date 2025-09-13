# SpamBarrier
No more captcha !!

A mod to protect FluxBB forums against forum spammers.</br>
Works with FluxBB 1.5.10 and should work with 1.5.11.</br>
Previous releases worked with previous releases of FluxBB, so this release should work with older releases of FluxBB (as the code has not been bigly modified), but it hasn't been tested.


# [How it works ?]
In registration phase :
- At first level, implements a HoneyPot which permits to detect automated registration attempts.</br>
    If an automated attempt is detected, it's possible to report it to StopForumSpam database.
- At second level, checks if StopForumSpam database knows IP address or Email.
- At third level, checks if IP address is known from DNSBL (DNS blacklists).

In login phase :
- At first level, checks if StopForumSpam database knows IP address or Email.
- At second level, checks if IP address is known from DNSBL (DNS blacklists).

If you chose to report spammers to StopForumSpam, you can also report manually those you detect on your forum.</br>
In this case, it's recommended (but not mandatory) to give a proof of the spam action.

# [Translation]
The mod is available for English and French languages.

It may be translated by creating the subsequent directory in 'files/lang' (for example 'lang/Spanish') and creating the translation files based on either English or French language (spambarrier.php and AP_SpamBarrier.php are to be translated).

Be carefull when translating, the translated files must be well recognised and executed by PHP (file encoding, simple quotes, ...).

# [Installation]
## Manual installation
The mod can be manually installed by following the steps in the 'readme.txt' file.<br/>
The database installation is performed by copying the install_mod.php file in the root of the forum's directory (https://my_site.com/my_forum) and excuting the 'install_mod.php' file with your navigator <b>as admin</b> (https://my_site.com/my_forum/install_mod in the navigator).<br/>

## Semi-automatic installation
The mod can be semi-automatically installed by using 'FluxBB Patcher' by Daris.<br/>
The database installation is performed by the Patcher at the beginning of the installation, before modifying files.

# [Uninstallation]
### [Note]
The database shouldn't be modified if the mod is to be reinstalled.

Uninstallation can be done manually or semi-automatically.<br/>
In case the mod is to be completely removed, including database specific table and fields, the <b>'install_mod.php'</b> file should be modified as following :<br/>
- Set the <b>$mod_restore variable</b> to <b>'true'</b> (it's set to <i>"$mod_restore    = false;"</i> in the script)<br/>
- Rename the <b>'restore_disabled'</b> function to <b>'restore'</b><br/>
- Run the <b>'install_mod.php'</b> file as when installing, a <b>'Restore'</b> button should be available.
## Manual uninstallation
Revert steps from 'readme.txt' files from end to begin of the 'readme.txt' file.
## Semi-automatic uninstallation
Use either the "Deactivate" option or 'Uninstall' option of Daris's Patcher.
