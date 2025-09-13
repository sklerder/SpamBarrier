# SpamBarrier
A mod to protect FluxBB forums against forum spammers
Works with FluxBB 1.5.10 and should work with 1.5.11

No need of a captcha !!

In registration phase :
- At first level, implements a HoneyPot which permits to detect automated registration attempts.
    If an automated attempt is detected, it's possible to report it to StopForumSpam database.
- At second level, checks if StopForumSpam database knows IP address or Email.
- At third level, checks if IP address is know n from DNSBL (DNS blacklists).

In login phase :
- At first level, checks if StopForumSpam database knows IP address or Email.
- At second level, checks if IP address is known from DNSBL (DNS blacklists).

If you chose to report spammers to StopForumSpam, you can also report manually those you detect on your forum.
In this case, it's recommended (but not mandatory) to give a proof of the spam action.
