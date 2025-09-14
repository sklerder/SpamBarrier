# SpamBarrier
Fini les captcha !!

Ceci est une mod pour protéger FluxBB contre les spammeurs de forum.</br>
# Compatibilité
Testé avec PHP 7.x, à tester avec des versions plus récentes de PHP.</br>
Fonctionne avec FluxBB 1.5.10, et devrait fonctionner avec 1.5.11 (bien que ça n'ait pas été testé).</br>
Les précédentes versions de la mod fonctionnaient avec de plus anciennes version de FluxBB,</br>
 ça devrait donc être le cas pour cette version (puisque le code n'a pas été profondément modifié), mais ça n'a pas été testé.

# Comment ça marche ?
En phase d'inscription :
- Au premier niveau, implémente un HoneyPot qui permet de détecter les tentatives automatisées d'inscription.</br>
  Si une tentative automatisée est détectée, il est possible de signaler automatiquement le spammeur à la base de données StopForumSpam.</br>
- Au second niveau, vérifie si l'IP ou l'Email du client est connue de StopForumSpam.</br>
- Au troisième niveau, vérifie si l'IP du client est connue de DNSBL (DNS blacklists).</br>

La tentative d'inscription est bloquée dès qu'un contrôle détecte un spammeur potentiel, avec un message expliquant la raison du blocage.

En phase de connexion :
- Au premier niveau, vérifie si l'IP ou l'Email du client est connue de StopForumSpam.</br>
- Au second niveau, vérifie si l'IP du client est connue de DNSBL (DNS blacklists).</br>

La tentative de connexion est bloquée dès qu'un contrôle détecte un spammeur potentiel, avec un message expliquant la raison du blocage.

# Signalement à StopForumSpam
StopForumSpam est une base de données gratuite (et très efficace) qui recense les spammeurs de forum.</br>
Pour signaler un spammeur à StopForumSpam, vous aurez besoin d'une clé API de StopForumSpam (par la création d'un compte, qui est gratuit).</br>
Cette clé d'API doit être enregistrée par le plugin 'AP_SpamBarrier.php' pour activer la fonctionnalité.</br>
Si vous choisissez de signaler les spammeurs à StopForumSpam, vous pourrez également reporter manuellement des spammeurs que vous détectez</br>
sur votre forum en utilisant l'outil de signalement de StopForumSpam (https://www.stopforumspam.com/add).</br>
Dans ce cas, bien que ce ne soit pas obligatoire, il est recommandé de fournir une preuve (evidence) de l'action de spam.

# Liste blanche (Whitelisting)
Avec la version 1.0.7, la mod fournit un système de liste blanche.</br>
Le mécanisme peut être utilisé pour accepter un nom d'utilisateur, une addresse IP ou un domaine DNS même s'il</br>
est listé sur StopForumSpam ou DNSBL, mais seulement si vous avez confiance dans l'utilisateur, l'adresse IP ou le domaine.</br>
Le mécanisme de liste blanche gère deux listes, une pour les phases d'inscription, l'autre pour les phases de connexion.</br>
Le mécanisme de liste blanche n'est pas utilisé pour court-circuiter le champ HoneyPot, car ce champ a pour but de détecter les tentatives automatisées.</br>
Des informations détaillées concernant les listes blanches peuvent être trouvées dans le plugin d'administration (AP_SpamBarrier.php).

# Traduction
La mod est disponible en anglais et en français.</br>
Elle peut être traduite en créant un répertoire subséquent dans 'files/lang' (par exemple 'files/lang/Spanish')</br>
et en créant les fichiers de traduction (spambarrier.php et AP_SpamBarrier.php sont à traduire), en se basant sur l'anglais ou le français.</br>
Soyez prudent en traduisant, les fichiers de traduction doivent être parfaitement reconnus et exécutés par PHP (encodage UTF-8, apostrophes dans les textes, ...).

# Installation de la mod  
L'installation peut être faite manuellement ou semi-automatiquement.  
## Installation dans la base de données
Si c'est la première fois que vous installez la mod, ou si vous avez complètement désinstallé la mod, vous devez installer la base de données.  
Le fichier <i>'install_mod.php'</i> prend soin de ne pas réinstaller la base de données si elle est correctement installée.</br>
## Installation manuelle
La mod peut être installée manuellement en suivant les étapes du fichier <i>'readme.txt'</i>.</br>
L'installation dans la base de données est faite en copiant le fichier <i>'install_mod.php'</i> à la racine</br>
du forum (https://my_site.com/my_forum) et en exécutant <b>en tant qu'administrateur</b> le fichier <i>'install_mod.php'</i>
avec votre navigateur (https://my_site.com/my_forum/install_mod dans le navigateur).</br>
## Installation semi-automatique
La mod peut être installée semi-automatiquement en utilisant 'FluxBB Patcher' de Daris.</br>
L'installation de la base de données est effectuée par Patcher au début de l'installation, avant les modifications des fichiers,  
mais Patcher permet de sauter l'installation de la base de données. 

# Désinstallation
### [Note]
Si la mod doit être réinstallée, <b>vous ne devriez pas modifier</b> la base de donnéees lors de la désintallation.  

Si la mod doit être complètement désinstallée, y compris ses tables et champs de la base de données, le fichier <b>'install_mod.php'</b> devrait être modifié comme suit :</br>
- Mettre la variable <b>$mod_restore</b> à <b>'true'</b> (elle est mise à <i>"$mod_restore    = false;"</i> dans le script)<br/>
- Renommer la fonction <b>'restore_disabled'</b> en <b>'restore'</b><br/>

La désinstallation peut être faite manuellement ou semi-automatiquement.<br/>

## Désinstallation manuelle
Annulez les étapes du fichier <i>'readme.txt'</i> en commençant par la fin du fichier.</br>
Si les informations en base de données doivent être supprimées, lancez le fichier <b>'install_mod.php'</b>  comme lors de l'installation,</br>
un bouton <b>'Restore'</b> devrait être disponible <b>si</b> vous avez modifié <i>'install_mod.php'</i> comme décrit dans la <b>Note</b> au-dessus.
## Semi-automatic uninstallation
Utilisez soit l'option <b>'Désactiver'</b> soit l'option <b>'Désintaller'</b> du Patcher de Daris.</br>
L'option <b>'Désactiver'</b> supprime les modifications telles que décrites dans le fichier <i>'readme.txt'</i>, mais ne supprime aucune fichier de <i>'include'</i>, <i>'lang'</i> et <i>'plugins'</i>.</br>
L'option <b>'Désintaller'</b> supprime les modifications, supprime les fichiers qui ont été copiés dans <i>'include'</i>, <i>'lang'</i> et <i>'plugins'</i>, et
supprime les modifications faites en base de données <b>si</b> vous avez modifié <i>'install_mod.php'</i> comme décrit dans la <b>Note</b> au-dessus.



