<?php
// Language definitions for AP_SpamBarrier.php
$lang_ap_spambarrier = array(
'Redirect message'				=>	'Options mises à jour. Redirection &hellip;',
'SB_users'					=>	'Utilisateurs',
'Col_Username'					=>	'Pseudo',
'Col_Email'					=>	'E-mail',
'Col_Posts'					=>	'Messages',
'Col_Website'					=>	'Site Web',
'Col_Signature'					=>	'Signature',
'Col_Registered'				=>	'Inscription',

'Go_back'					=>	'Retour',

'Description'					=>	'Ce plugin est utilisé pour modifier les réglages de la mod "<strong>SpamBarrier</strong>".',
'Options'					=>	'Options',
'WL_Settings'					=>	'Réglages Whitelist ',
'WLC_description'				=>	'Les contrôles de WhiteList à l\'ìnscription ou à la connexion permettent d\'autoriser des pseudos, des adresses IP ou des domaines qui ont votre confiance. 
Ces contrôles peuvent outrepasser les résultats de StopForumSpam et/ou DNSBL (inscription et connexion), mais ne peuvent outrepasser les résultats de HoneyPot (qui ne concerne que l\'inscription).
=> Formats reconnus :
	- pseudo 
	- pseudo@myhost.mydomain.com 
	- pseudo@mydomain.com 
	- pseudo@1.2.3.4 
	- @myhost.mydomain.com 
	- @mydomain.com 
	- @1.2.3.4. 
	Les éléments doivent être séparés par des virgules.
	Les adresses IP et noms de domaine doivent être précédés du caractère \'@\' pour signaler l\' adresse ou le nom de domaine.
=> Exemple de contenu d\'une WhiteList : 
	modo@mydomain.com, admin, @otherdomain.net, nick@1.2.3.4',
'HP_Settings'					=>	'Réglages HoneyPot',
'HP_description'				=>	'HoneyPot est la première barrière contre les spammeurs. Il ne contrôle que les inscriptions, mais permet de détecter si celle-ci est (mal) \'automatisée\' ou non. Il est fortement recommandé d\'activer ce contrôle, car il traite presque 100% des inscriptions de robots.',
'SFS_Settings'					=>	'Réglages StopForumSpam',
'DNSBL_Settings'				=>	'Réglages DNSBL',
'DNSBL_description'				=>	'Le service DNSBL s\'appuie sur plusieurs base de données qui collectent les adresses IP potentiellement dangereuses, connues pour envoyer des  e-mail de spam ou pour d\'autres actions malicieuses .',
'WLL_check'					=>	'Vérifier la WhiteList de connexion.',
'WL_Login_List'					=>	'WhiteList de connexion',
'WL_Login_List_description'			=>	'Adresses et pseudos autorisés.',
'WLR_check'					=>	'Vérifier la WhiteList d\'inscription',
'WLRC_description'				=>	'',
'WL_Register_List'				=>	'WhiteList d\'inscription',
'WL_Register_List_description'			=>	'Adresses et pseudos autorisés.',
'HP_check'					=>	'Contrôle HoneyPot',
'HP_custom_field'				=>	'Champ HoneyPot personnalisé',
'HP_custom_field_description'			=>	'Si "Oui", compléter le champ ci-dessous.',
'HP_custom_field_name'				=>	'Nom du champ HoneyPot personnalisé',
'HP_custom_field_name_description'		=>	'C\'est une bonne idée de personnaliser le nom du champ HoneyPot. Si vide, "req_honeypot" sera utilisé comme valeur par défaut.',
'Yes'						=>	'Oui',
'No'						=>	'Non',
'SFS_description'				=>	'StopForumSpam est une base de donnée qui recense les spammeurs (adresse IP, email et pseudo). Chaque client peut être contributeur de cette base, par inscription comme membre.',

'SFS_reg_check'					=>	'Contrôle StopForumSpam à l\'inscription',
'SFS_reg_description'				=>	'Si l\'utilisateur qui tente de s\'inscrire passe le contrôle HoneyPot, vérifier l\'adresse IP et l\'adresse email (pas le pseudo) auprès de la base de données StopForumSpam. Même si le HoneyPot traite presque 100% des robots, le service StopForumSpam est utilisé comme seconde barrière contre les spammeurs humains.',
'SFS_login_check'				=>	'Contrôle StopForumSpam à la connexion',
'SFS_log_description'				=>	'Contrôle uniquement l\'adresse IP de l\'utilisateur (ni le pseudo, ni l\'email) auprès de la base de données  StopForumSpam. Le service StopForumSpam, en phase de connexion, est utilisé comme première barrière contre les spammeurs/mauvais plaisants.',
'Enable_SFS_report'				=>	'Activer les soumissions à StopForumSpam',
'SFS_report_description'			=>	'Soumettons-nous les spammeurs à StopForumSpam ? Veuillez lire <a href="http://www.stopforumspam.com/legal">"Acceptable use Policy"</a> avant utilisation. Nécessite une "API key", et donc, d\'être enregistré comme membre/contributeur.',
'SFS_API'					=>	'StopForumSpam "API key"',
'SFS_api_description'				=>	'Votre clé StopForumSpam. Si vide, les tentatives d\'inscription bloquées ne seront pas soumises à la base de données StopForumSpam.',
'DNSBL_login_check'				=>	'Contrôle DNSBL à la connexion',
'DNSBL_login_description'			=>	'Contrôle uniquement l\'adresse IP de l\'utilisateur (ni le pseudo, ni l\'email) auprès des DNSBL (DNS BlackList) lors de la connexion. Le service DNSBL est utilisé ici comme seconde barrière contre les spammeurs/mauvais plaisants. Peut permettre de bloquer quelques spammeurs de plus.',
'DNSBL_reg_check'				=>	'Contrôle DNSBL à l\'inscription',
'DNSBL_reg_description'				=>	'Contrôle uniquement l\'adresse IP de l\'utilisateur (ni le pseudo, ni l\'email) auprès des DNSBL (DNS BlackList) lors de l\'inscription. Le service DNSBL est utilisé ici comme troisième barrière contre les spammeurs/mauvais plaisants. Peut permettre de bloquer quelques spammeurs de plus.',
'DNSBL_list'					=>	'Liste de serveurs DNSBL',
'DNSBL_list_description'			=>	'La liste des serveurs DNSBL à consulter (séparés par des virgules). Soyez prudents en les utilisant (latence dans les listes, faux positifs).',

'Save'						=>	'Enregistrer',

'Search_users'					=>	'Recherche d\'utilisateurs',
'Search_description'				=>	'Cette fonctionnalité permet de rechercher les utilisateurs avec une URL dans leur signature et n\'ayant jamais posté. Ceci peut aider à trouver les spammeurs qui auraient passé au travers des contrôles "SpamBarrier". Les résultats seront limités au 50 derniers utilisateurs enregistrés répondant à ces critères.',
'Go!'						=>	'Chercher !',
'No_match'					=>	'Aucune correspondance.',
'Registration_stats'				=>	'Statistiques d\'inscription',
'Collecting_since'				=>	'Début des statistiques',
'days'						=>	'jours',
'N_A'						=>	'Non disponible',
'Total'						=>	'Total',
'NS'						=>	'Non SPAM : ',
'BBH'						=>	'Bloqué(s) par le HoneyPot : ',
'BBS'						=>	'Bloqué(s) par SFS : ',
'BBD'						=>	'Bloqué(s) par DNSBL : ',
'per_day'					=>	'par jour',
'Avg_7d'					=>	'Moyenne des 7 derniers jours',
'Max_day'					=> 	'Jour de pointe',
'Block_14d'					=>	'Bloqués 14 derniers jours',
'Unable_14d'					=>	'Impossible de récupérer les logs sur 14 jours',
'StatsDate'					=>	'Date',
'StatsTotal'					=>	'Total',
'StatsHoneyPot'					=>	'HoneyPot',
'StatsSFS'					=>	'SFS',
'StatsDNSBL'					=>	'DNSBL',
'Sum14Days'					=>	'Cumul 14 derniers jours',
);
?>