<?php

    /*
    * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
    * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
    * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
    * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
    * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
    * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
    * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
    * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
    * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
    * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
    */

    set_include_path(dirname(__FILE__));

    define('IS_AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

    define('MIKRON_START_TIME', microtime(true));
    define('M_DIR', dirname(__FILE__));
    define('M_ETC_DIR', M_DIR.'/etc');

    ini_set('display_errors', E_ALL);
    // error_reporting(E_ALL | E_STRICT);

    date_default_timezone_set('Europe/Moscow');
    setlocale(LC_ALL, 'ru_RU.UTF8','ru_RU.UTF-8');
    iconv_set_encoding('internal_encoding', 'UTF-8');
    iconv_set_encoding('output_encoding', 'UTF-8');
    ini_set('default_charset', 'utf-8');   
    header('Content-Type: text/html; charset=UTF-8');

    // Константы
    class Constants {
        const _DB_ERROR_CONNECT_ID = 100;
    }

    // Язык
    class Language {
        const _DB_ERROR_CONNECT = 'Ошибка соединения с базой данных';
    }

    function dump($var) {
        echo '<pre>';
        if(is_string($var)) {
            echo $var;
        } else {
            var_export($var);
        }
        exit;
    }

    function dumpr($var) {
        echo '<pre>';
        if(is_string($var)) {
            echo $var;
        } else {
            print_r($var);
        }
        exit;
    }

    function dumpre($var) {
        throw new Exception('<pre>'.var_export($var, 1));
    }

    class Mik {

        public static $cur_app = 'index';
        public static $layouts_directory = null;

        /**
        * @var MikronLayout
        */
        // public static $Layouts;

        public static function autoload($className) {
            switch($className) {
                case 'currentsite': {
                    // Because this file contains two classes 'site' and 'currentsite'
                    require_once(M_WWW_ROOT.'/index.php');
                    break;
                }
                case 'mvc': case 'site': case 'db': case 'baseobject': case 'criteria': case 'route': case 'orm': case 'routes': case 'routines': case 'controller': {
                    // Load framework classes
                    include(M_ETC_DIR.'/'.$className.'.inc');
                    break;
                }
                default: {
                    // Load site entities
                    if(substr($className, 0, 2) == 't_') {
                        $classFile = M_WWW_ROOT.'/entities/'.$className.'.inc';
                        include($classFile);                        
                    }
                    else {
                        $dirPath = str_replace('_', DIRECTORY_SEPARATOR, $className);
                        $dirs = explode(PATH_SEPARATOR, get_include_path());
                        
                        foreach($dirs as $dir) {
                            $dir = rtrim($dir, '\\/');
                            $classFile = $dir . DIRECTORY_SEPARATOR . $dirPath . '.php';
                            if(file_exists($classFile)) {
                                include($classFile);
                            }
                            $classFile = $dir . DIRECTORY_SEPARATOR . $dirPath . '.inc';
                            if(file_exists($classFile)) {
                                include($classFile);
                            }
                            if (class_exists($className, false) || interface_exists($className, false)) {
                                break;
                            }
                        }
                        if (!class_exists($className, false) && !interface_exists($className, false)) {
                            die("Class declaration '{$className}' not found");
                        }
                    }
                    break;
                }
            }
        }

        /**
        * Site www-root directory
        * 
        * @param string $rootDirectory
        * 
        * @return bool
        */
        public static function boot($rootDirectory) {
            define('M_WWW_ROOT', str_replace('\\', '/', $rootDirectory));
            set_include_path(get_include_path() . PATH_SEPARATOR . realpath($rootDirectory).PATH_SEPARATOR.realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'));
        }

    }

    // Register autoloader method
    spl_autoload_register('Mik::autoload');

    // Templating as Smarty
    // include(M_ETC_DIR.'/layout.inc');
    // Mik::$Layouts = new MikronLayout();
    // Mik::$Layouts->assign('33', 77);

    // Page
    // include(M_ETC_DIR.'/page.inc');
