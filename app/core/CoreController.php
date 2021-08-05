<?php
namespace Rmcc;
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

// mainly sets up the twig environment for rendering templates. includes custom caching with rendering
class CoreController {
  
  // construct twig environment, twig globals & anything else. All other controllers will extend from CoreController
  public function __construct() {
      
    // enable for error reporting in cases of fatal errors
    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);
    
    // (new Resize)->resize('C:\xampp\htdocs\robertmccormack.com\public\img\test.jpg', 500, 500);
    // self::resize('C:\xampp\htdocs\robertmccormack.com\public\img\test.jpg', 500, 500);
    // self::resize('https://robertmccormack.com/public/img/test.jpg', 500, 500);
    
    // twig stuff
    $loader = new \Twig\Loader\FilesystemLoader(
      array(
        '../app/views/',
        '../app/views/archive',
        '../app/views/archive/blog',
        '../app/views/archive/portfolio',
        '../app/views/parts',
        '../app/views/single',
        '../app/views/single/page',
        '../app/views/single/post',
        '../app/views/single/project',
        '../app/views/parts',
      )
    );
    $this->twig = new \Twig\Environment($loader, ['cache' => '../app/cache/compilation', 'debug' => true ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    
    // remove query params from a given string/url
    $filter = new \Twig\TwigFilter('strtokparams', function ($string) {
      return strtok($string);
    });
    $this->twig->addFilter($filter);
    
    $resize_filter = new \Twig\TwigFilter('resize', function ($src, $w, $h = null, $crop = 'default') {
      return self::resize($src, $w, $h, $crop);
    });
    $this->twig->addFilter($resize_filter);
    
    $function = new \Twig\TwigFunction('get_terms', function ($tax) {
      $args = array(
        'taxonomy' => $tax,
        'show_all' => true
      );
      $terms_obj = new QueryTermsModel($args);
      return $terms_obj->terms;
    });
    $this->twig->addFunction($function);
    
    // contact form
    $this->addContactFormToTwig();
    
    // twig globals
    $this->twig->addGlobal('site', SiteModel::init()->getSite());
    $this->twig->addGlobal('author', AuthorModel::init()->getAuthor());
    $main_menu = new MenuModel('main-menu');
    $main_menu_chunked = array_chunk($main_menu->menu_items, ceil(count($main_menu->menu_items) / 2));
    $main_menu_first = $main_menu_chunked[0];
    $main_menu_second = $main_menu_chunked[1];
    $this->twig->addGlobal('main_menu', $main_menu);
    $this->twig->addGlobal('main_menu_first', $main_menu_first);
    $this->twig->addGlobal('main_menu_second', $main_menu_second);
    $this->twig->addGlobal('base_url', $GLOBALS['config']['base_url']);
    $this->twig->addGlobal('current_url', $GLOBALS['config']['base_url'].$_SERVER['REQUEST_URI']);
    $this->twig->addGlobal('current_url_no_params', strtok($GLOBALS['config']['base_url'].$_SERVER['REQUEST_URI'], "?"));
    $this->twig->addGlobal('get', $_GET);
  }
  
  public static function resize($src, $w, $h, $crop) {
		if (!is_numeric($w) && is_string($w)) return $src;
    
    $path = parse_url($src, PHP_URL_PATH);
    $full_path = $_SERVER['DOCUMENT_ROOT'].$path;
    
		$op = new Resize($w, $h, $crop);
		return pathToURL(self::_operate($full_path, $op));
	}
  
  // this function will call methods from the Resize class
  // filename() takes the src filename & produces the new filename
  // run() will do the image processing & saving stuff.. just calling it will save the new image
  // run() requires the src filename & new filename
  // this method will return a url, which will either be the src url or the new cropped url
  // we should check here if the new filename exists before we do the run() processing
  private static function _operate($src, $op) {
    $destination_path = $op->filename($src);
    if(!file_exists($destination_path)) {
      $op->run($src, $destination_path);
    }
    return $destination_path;
  }
  
  public function addContactFormToTwig() {
    if (array_key_exists('to', $_POST)) {
      
      /*
      *
      * Defaults
      *
      */
      $err = false;
      $msg = '';
      $email = '';
      
      /*
      *
      * Apply some basic validation and filtering to the subject
      *
      */
      if (array_key_exists('subject', $_POST)) {
        $subject = substr(strip_tags($_POST['subject']), 0, 255);
      } else {
        $subject = 'No subject given';
      }
      
      /*
      *
      * Apply some basic validation and filtering to the query
      *
      */
      if (array_key_exists('query', $_POST)) {
        //Limit length and strip HTML tags
        $query = substr(strip_tags($_POST['query']), 0, 16384);
      } else {
        $query = '';
        $msg = 'No query provided!';
        $err = true;
      }
      
      /*
      *
      * Apply some basic validation and filtering to the name
      *
      */
      if (array_key_exists('name', $_POST)) {
        //Limit length and strip HTML tags
        $name = substr(strip_tags($_POST['name']), 0, 255);
      } else {
        $name = '';
      }
      
      /*
      *
      * Validate to address
      * Never allow arbitrary input for the 'to' address as it will turn your form into a spam gateway!
      * Substitute appropriate addresses from your own domain, or simply use a single, fixed address
      *
      */
      if (array_key_exists('to', $_POST) && in_array($_POST['to'], ['info', 'jobs', 'me'], true)) {
        $to = $_POST['to'] . '@robertmccormack.com';
      } else {
        $to = 'support@example.com';
      }
      
      /*
      *
      * Make sure the address they provided is valid before trying to use it
      *
      */
      if (array_key_exists('email', $_POST) && PHPMailer::validateAddress($_POST['email'])) {
        $email = $_POST['email'];
      } else {
        $msg .= 'Error: invalid email address provided';
        $err = true;
      }
      
      /*
      *
      * If not an error!!
      *
      */
      if (!$err) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'secret';
        $mail->SMTPAuth   = true; //Enable SMTP authentication
        $mail->Username   = 'secret'; //SMTP username
        $mail->Password   = 'secret'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
        $mail->Port       = 587; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        
        /*
        *
        * It's important not to use the submitter's address as the from address as it's forgery,
        * which will cause your messages to fail SPF checks.
        * Use an address in your own domain as the from address, put the submitter's address in a reply-to
        *
        */
        $mail->setFrom('cv@robertmccormack.com', (empty($name) ? 'Contact form' : $name));
        $mail->addAddress($to);
        $mail->addReplyTo($email, $name);
        $mail->Subject = 'Contact form: ' . $subject;
        $mail->Body = "Contact form submission\n\n" . $query;
        
        /*
        *
        * If mail didnt send for any reason, display an error with info..
        * Or display success message
        *
        */
        if (!$mail->send()) {
          $msg .= 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
          $msg .= 'Message sent!';
        }
      }
      
      /*
      *
      * Turn the form data into an array for to add to twig
      *
      */
      $form_array = array(
        'err' => $err,
        'msg' => $msg,
        'email' => $email,
        'subject' => $subject,
        'query' => $query,
        'name' => $name,
        'to' => $to,
        'mail' => $mail
      );
      
      foreach($form_array as $key => $value){
        $this->twig->addGlobal($key, $value);
      }
    }
  }

  // 404 errors
  public function error() {
    echo $this->twig->render('404.twig');
  }
  
  // render twig template with custom caching step-in
  protected function templateRender($template, $context) {
    Cache::cacheRender($this->twig->render($template, $context));
  }
  
}