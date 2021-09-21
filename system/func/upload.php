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

namespace webspell;

abstract class Upload
{

    const UPLOAD_ERR_CANT_READ = 99;

    protected $error;

    public function __construct()
    {

    }

    abstract public function hasFile();
    abstract public function hasError();
    abstract public function getError();
    abstract public function getTempFile();
    abstract public function getFileName();
    abstract public function getSize();
    abstract public function saveAs($newFilePath, $override = true);
    abstract protected function getFallbackMimeType();

    public function getExtension()
    {
        $filename = $this->getFileName();
        if (stristr($filename, ".") !== false) {
            return substr($filename, strrpos($filename, ".") + 1);
        } else {
            return null;
        }
    }

    public function getMimeType()
    {
        $filename = $this->getTempFile();
        if (function_exists("finfo_file")) {
            $handle = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($handle, $filename);
            if (stristr($mime, ";") !== false) {
                $mime = substr($mime, 0, strpos($mime, ";"));
            }
        } elseif (function_exists("mime_content_type")) {
            $mime = mime_content_type($filename);
        }

        if (!isset($mime) || empty($mime)) {
            $mime = $this->getFallbackMimeType();
        }

        return $mime;
    }

    public function supportedMimeType($required_mime)
    {
        $mime = $this->getMimeType();

        if (is_array($required_mime)) {
            foreach ($required_mime as $req_mime) {
                if ($req_mime == $mime) {
                    return true;
                }
            }
        } else {
            if ($required_mime == $mime) {
                return true;
            }
        }

        return false;
    }

    public function translateError()
    {
        global $_language;
        $_language->readModule('upload', true);
        switch ($this->error) {
            case UPLOAD_ERR_INI_SIZE:
                $message = $_language->module[ 'file_too_big' ];
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = $_language->module[ 'file_too_big' ];
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = $_language->module[ 'incomplete_upload' ];
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = $_language->module[ 'no_file_uploaded' ];
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = $_language->module[ 'no_temp_folder_available' ];
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = $_language->module[ 'cant_write_temp_file' ];
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = $_language->module[ 'unexpected_error' ];
                break;
            case self::UPLOAD_ERR_CANT_READ:
                $message = $_language->module[ 'cant_copy_file' ];
                break;
            default:
                $message = $_language->module[ 'unexpected_error' ];
                break;
        }
        return $message;
    }
}
