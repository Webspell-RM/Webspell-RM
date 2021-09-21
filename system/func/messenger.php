<?php
/*-----------------------------------------------------------------\
| _    _  ___  ___  ___  ___  ___  __    __      ___   __  __       |
|( \/\/ )(  _)(  ,)/ __)(  ,\(  _)(  )  (  )    (  ,) (  \/  )      |
| \    /  ) _) ) ,\\__ \ ) _/ ) _) )(__  )(__    )  \  )    (       |
|  \/\/  (___)(___/(___/(_)  (___)(____)(____)  (_)\_)(_/\/\_)      |
|                       ___          ___                            |
|                      |__ \        / _ \                           |
|                         ) |      | | | |                          |
|                        / /       | | | |                          |
|                       / /_   _   | |_| |                          |
|                      |____| (_)   \___/                           |
\___________________________________________________________________/
/                                                                   \
|        Copyright 2005-2018 by webspell.org / webspell.info        |
|        Copyright 2018-2019 by webspell-rm.de                      |
|                                                                   |
|        - Script runs under the GNU GENERAL PUBLIC LICENCE         |
|        - It's NOT allowed to remove this copyright-tag            |
|        - http://www.fsf.org/licensing/licenses/gpl.html           |
|                                                                   |
|               Code based on WebSPELL Clanpackage                  |
|                 (Michael Gruber - webspell.at)                    |
\___________________________________________________________________/
/                                                                   \
|                     WEBSPELL RM Version 2.0                       |
|           For Support, Mods and the Full Script visit             |
|                       webspell-rm.de                              |
\------------------------------------------------------------------*/

function getnewmessages($userID)
{
    return mysqli_num_rows(
        safe_query(
            "SELECT
                messageID
            FROM
                `" . PREFIX . "plugins_messenger`
            WHERE
                `touser` = " . (int)$userID . " AND
                `userID` = " . (int)$userID . " AND
                `viewed` = 0"
        )
    );
}

function sendmessage($touser, $title, $message, $from = '0')
{

    global $hp_url, $admin_email, $admin_name, $hp_title;
    $_language_tmp = new \webspell\Language();
    $systemmail = false;
    if (!$from) {
        $systemmail = true;
        $from = '1';
    }

    if (!$systemmail) {
        safe_query(
            "INSERT INTO
                `" . PREFIX . "plugins_messenger` (`userID`, `date`, `fromuser`, `touser`, `title`, `message`, `viewed`)
            values (
                '$from',
                '" . time() . "',
                '$from',
                '$touser',
                '$title',
                '" . $message . "',
                '0'
            )"
        );
        safe_query("UPDATE " . PREFIX . "user SET pmsent=pmsent+1 WHERE userID='$from'");
    }
    #if (!isignored($touser, $from) || $systemmail) {
        if ($touser != $from || $systemmail) {
            safe_query(
                "INSERT INTO
                    `" . PREFIX . "plugins_messenger` (`userID`, `date`, `fromuser`, `touser`, `title`, `message`, `viewed`)
                VALUES (
                    '$touser',
                    '" . time() . "',
                    '$from',
                    '$touser',
                    '$title',
                    '" . $message . "',
                    '0'
                )"
            );
        }
        safe_query("UPDATE " . PREFIX . "user SET pmgot=pmgot+1 WHERE userID='$touser'");
        if (wantmail($touser) && isonline($touser) == "offline") {
            $ds = mysqli_fetch_array(
                safe_query(
                    "SELECT `email`, `language` FROM `" . PREFIX . "user` WHERE `userID` = " . (int)$touser
                )
            );
            $_language_tmp->setLanguage($ds['language']);
            $_language_tmp->readModule('messenger');
            $mail_body = str_replace("%nickname%", getnickname($touser), $_language_tmp->module['mail_body']);
            $mail_body = str_replace("%hp_url%", $hp_url, $mail_body);
            $subject = $hp_title . ': ' . $_language_tmp->module['mail_subject'];
            \webspell\Email::sendEmail($admin_email, 'Messenger', $ds['email'], $subject, $mail_body);
        }
    #}
}
