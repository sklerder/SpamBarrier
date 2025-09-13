<?php
// Language definitions for AP_SpamBarrier.php
$lang_ap_spambarrier = array(
'Redirect message'				=>	'Options updated. Redirecting &hellip;',
'SB_users'					=>	'Users',
'Col_Username'					=>	'Username',
'Col_Email'					=>	'E-mail',
'Col_Posts'					=>	'Posts',
'Col_Website'					=>	'Website',
'Col_Signature'					=>	'Signature',
'Col_Registered'				=>	'Registered',

'Go_back'					=>	'Go back',

'Description'					=>	'This plugin is used to control the settings of the "<strong>SpamBarrier</strong>" mod.',
'Options'					=>	'Options',
'WL_Settings'					=>	'Whitelist settings',
'WLC_description'				=>	'The WhiteList checks, when registering or login in, permit to accept nicknames, IP adresses or domains you trust. 
These checks bypass StopForumSpam and/or DNSBL results (registering or login phase), but never bypass HoneyPot check (which concerns only the registering).
=> Recognized formats :
	- nickname 
	- nickname@myhost.mydomain.com 
	- nickname@mydomain.com 
	- nickname@1.2.3.4 
	- @myhost.mydomain.com 
	- @mydomain.com 
	- @1.2.3.4. 
	Elements must be separed by commas.
	IP adresses and domain names must be preceded by an \'@\' to identify the adress or the domain name.
=> WhiteList example : 
	modo@mydomain.com, admin, @otherdomain.net, nick@1.2.3.4',
'HP_Settings'					=>	'HoneyPot settings',
'HP_description'				=>	'The HoneyPot is the first barrier against spammers. It only checks the registrations, but permits to verify if this registration is (poorly) \'automated\' or not. It is strongly recommanded to enable this check, because it takes care of almost 100% of bots registrations.',
'SFS_Settings'					=>	'StopForumSpam settings',
'SFS_description'				=>	'StopForumSpam is a database which collects informations about spammers (IP adress, email and nickname). Every client can be contributor to this database, by registering as member.',
'DNSBL_Settings'				=>	'DNSBL settings',
'DNSBL_description'				=>	'The DNSBL service relies on many databases which gather potentially harmfull IP adresses, known to sending mail-spam or for other malicious actions.',
'WLL_check'					=>	'Check login WhiteList',
'WL_Login_List'					=>	'Login WhiteList',
'WL_Login_List_description'			=>	'Authorized adresses and nicknames.',
'WLR_check'					=>	'Check register WhiteList ',
'WLRC_description'				=>	'',
'WL_Register_List'				=>	'Register WhiteList',
'WL_Register_List_description'			=>	'Authorized adresses and nicknames.',

'HP_check'					=>	'HoneyPot check',
'HP_custom_field'				=>	'Custom HoneyPot field',
'HP_custom_field_description'			=>	'If "Yes", fill in the field below.',
'HP_custom_field_name'				=>	'Custom HoneyPot field name',
'HP_custom_field_name_description'		=>	'It\'s a good idea to have a custom HoneyPot field name. If not set, "req_honeypot" will be used as default value.',
'Yes'						=>	'Yes',
'No'						=>	'No',

'SFS_reg_check'					=>	'StopForumSpam registration check',
'SFS_reg_description'				=>	'If the user attempting to register passes the honeypot check, check the user\'s IP and email address (not username) against the StopForumSpam blacklist database. While honeypot takes care of almost 100% of bots, the StopForumSpam service is used as a second barrier against human spammers.',
'SFS_login_check'				=>	'StopForumSpam login check',
'SFS_log_description'				=>	'Check the user\'s IP only (neither the username, nor the email) against the StopForumSpam blacklist database. The StopForumSpam service, at login phase, is used as a first barrier against spammers/bad guys.',
'Enable_SFS_report'				=>	'Enable StopForumSpam report',
'SFS_report_description'			=>	'Do we report spammers to StopForumSpam ? Please read <a href="http://www.stopforumspam.com/legal">"Acceptable use Policy"</a> before using. Requires an API key, and thus, to be registered as member/contributor.',
'SFS_API'					=>	'StopForumSpam API',
'SFS_api_description'				=>	'Your StopForumSpam API key. If left blank blocked spam registration attempts will not be reported to the StopForumSpam blacklist service.',
'DNSBL_login_check'				=>	'DNSBL login check',
'DNSBL_login_description'			=>	'Check the user\'s IP only (neither the username, nor the email) against the DNSBL (DNS BlackList) at login. The DNSBL service is used here as a second barrier against spammers/bad guys. It may help to catch some more spammers.',
'DNSBL_reg_check'				=>	'DNSBL registration check',
'DNSBL_reg_description'				=>	'Check the user\'s IP only (neither the username, nor the email) against the DNSBL (DNS BlackList) at registration. The DNSBL service is used here as a third barrier against spammers/bad guys.It may help to catch some more spammers.',
'DNSBL_list'					=>	'DNSBL servers list',
'DNSBL_list_description'			=>	'The list of DNSBL servers to check (comma separated values). Be careful when using them (latency in the lists, false positives).',

'Save'						=>	'Save changes',

'Search_users'					=>	'Search users',
'Search_description'				=>	'This feature allows you to search for users with an URL in the signature but 0 posts. This could help to find spammers who have succeeded in passsing through "SpamBarrier" checks. Search results will be limited to the latest 50 registered users meeting these criteria.',
'Go!'						=>	'Go!',
'No_match'					=>	'No match.',
'Registration_stats'				=>	'Registration statistics',
'Collecting_since'				=>	'Collecting stats since',
'days'						=>	'days',
'N_A'						=>	'Not available',
'Total'						=>	'Total',
'NS'						=>	'Not spam: ',
'BBH'						=>	'Blocked by Honeypot: ',
'BBS'						=>	'Blocked by SFS: ',
'BBD'						=>	'Blocked by DNSBL: ',
'per_day'					=>	'per day',
'Avg_7d'					=>	'Average last 7 days',
'Max_day'					=> 	'Maximum day',
'Block_14d'					=>	'Blocked last 14 days',
'Unable_14d'					=>	'Unable to fetch 14 day log',
'StatsDate'					=>	'Date',
'StatsTotal'					=>	'Total',
'StatsHoneyPot'					=>	'HoneyPot',
'StatsSFS'					=>	'SFS',
'StatsDNSBL'					=>	'DNSBL',
'Sum14Days'					=>	'Sum last 14 days',
);
?>