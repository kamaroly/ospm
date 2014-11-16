<?php
/*
SimpleTest + CodeIgniter

test.php
the test runner - loads all needed files,
integrates with CodeIgniter and runs the tests

by Jamie Rumbelow
http://jamierumbelow.net/
*/
//Configure and load files
define('ROOT', dirname(__file__).'/');
define('APPLICATION',ROOT.'application/');
define('APPINDEX',ROOT.'index.php');
define('SIMPLETEST',ROOT.'unit_test/simpletest/');
define('TESTS',ROOT.'unit_test/tests/');
$global_array = array("ROOT" => ROOT, "SIMPLETEST" => SIMPLETEST, "APPLICATION" =>
    APPLICATION, "TESTS" => TESTS, "APPINDEX" => APPINDEX);
foreach ($global_array as $global_name => $dir_check):
    if (!file_exists($dir_check))
    {
        echo "Cannot Find ".$global_name." File / Directory: ".$dir_check;
        exit;
    }
endforeach;
require_once SIMPLETEST.'unit_tester.php';
require_once SIMPLETEST.'web_tester.php';
require_once SIMPLETEST.'reporter.php';

function get_loader_id($file, $type)
{
    $loader_url = ROOT.'application/tests/'.$type.'/';
    $loader_start = strpos($file, $loader_url) + strlen($loader_url) + 1;
    $loader_length = strpos($file, '_'.$type.'_test.php', $loader_start) - $loader_start;
    $loader_id = substr($file, $loader_start, $loader_length);
    return $loader_id;
}
function add_test($file, &$test)
{
    $implementation = '';
    if (preg_match('/_controller/', $file))
    {
        $controller = get_loader_id($file, 'controller');
        $implementation = APPLICATION.'controllers/'.$controller.'.php';
    } elseif (preg_match('/_model/', $file))
    {
        $model = get_loader_id($file, 'model');
        $implementation = APPLICATION.'models/'.$model.'_model.php';
    } elseif (preg_match('/_view/', $file))
    {
        $view = get_loader_id($file, 'views');
        $implementation = APPLICATION.'views/'.$view.'.php';
    } elseif (preg_match('/_helper/', $file))
    {
        $helper = get_loader_id($file, 'helpers');
        $implementation = APPLICATION.'helpers/'.$helper.'.php';
    } elseif (preg_match('/_library/', $file))
    {
        $library = get_loader_id($file, 'libraries');
        $implementation = APPLICATION.'libraries/'.$library.'.php';
    }
    if (file_exists($implementation))
    {
        require_once ($implementation);
    }
    $test->addFile($file);
}
class CodeIgniterUnitTestCase extends UnitTestCase
{
    protected $ci;
    public function __construct()
    {
        parent::UnitTestCase();
        $this->ci = &get_instance();
    }
}
class CodeIgniterWebTestCase extends WebTestCase
{
    protected $ci;
    public function __construct()
    {
        parent::WebTestCase();
        $this->ci = &get_instance();
    }
}
//Capture CodeIgniter output, discard and load system into $CI variable
ob_start();
include (APPINDEX);
$CI = &get_instance();
ob_end_clean();
//Setup the test suite
$test = new TestSuite();
$test->_label = 'CodeIgniter Application Test Suite';
if ((isset($_POST['test_name'])) && ($_POST['test_name'] <> ''))
{
    add_test(TESTS.$_POST['test_name'].'.php', $test);
} elseif (isset($_POST['test_type']))
{
    //What are we testing?
    $files = array();
    if ($_POST['test_type'] == "controllers")
    {
        $files = @scandir(TESTS.'/controllers');
    } elseif ($_POST['test_type'] == "models")
    {
        $files = @scandir(TESTS.'/views');
    } elseif ($_POST['test_type'] == "views")
    {
        $files = @scandir(TESTS.'/models');
    } elseif ($_POST['test_type'] == "helpers")
    {
        $files = array_merge($files, @scandir(TESTS.'helpers'));
    } elseif ($_POST['test_type'] == "libraries")
    {
        $files = array_merge($files, @scandir(TESTS.'libraries'));
    } elseif ($_POST['test_type'] == "all")
    {
        $files = @scandir(TESTS.'/controllers');
        $files = array_merge($files, @scandir(TESTS.'models'));
        $files = array_merge($files, @scandir(TESTS.'views'));
        $files = array_merge($files, @scandir(TESTS.'helpers'));
        $files = array_merge($files, @scandir(TESTS.'libraries'));
    }
    else
    {
        //Use all by default
        $files = @scandir(TESTS.'/controllers');
        $files = array_merge($files, @scandir(TESTS.'models'));
        $files = array_merge($files, @scandir(TESTS.'views'));
        $files = array_merge($files, @scandir(TESTS.'helpers'));
        $files = array_merge($files, @scandir(TESTS.'libraries'));
    }
    //Remove ., .. and any .whatever files, and add the full path
    function prepare_array($value, $key)
    {
        global $files;
        if (preg_match('/^\./', $value))
        {
            unset($files[$key]);
        }
        if (preg_match('/_model/', $value))
        {
            $files[$key] = TESTS.'models/'.$value;
        }
        if (preg_match('/_controller/', $value))
        {
            $files[$key] = TESTS.'controllers/'.$value;
        }
        if (preg_match('/_view/', $value))
        {
            $files[$key] = TESTS.'views/'.$value;
        }
        if (preg_match('/_helper/', $value))
        {
            $files[$key] = TESTS.'helpers/'.$value;
        }
        if (preg_match('/_library/', $value))
        {
            $files[$key] = TESTS.'libraries/'.$value;
        }
    }
    array_walk($files, 'prepare_array');
    //Add each file to the test suite
    foreach ($files as $file)
    {
        add_test($file, $test);
    }
}
//just above the $test->run() call
include (SIMPLETEST.'custom_test_gui.php');
//Run tests!
$test->run(new HtmlReporter());
/* End of file */