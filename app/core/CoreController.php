<?php
namespace Rmcc;
use PHPMailer\PHPMailer\PHPMailer;

class CoreController {
  
  public function __construct() {
    
    global $config;
    
    // twig configs
    $loader = new \Twig\Loader\FilesystemLoader($config['twig_templates_locations'], $config['twig_templates_base_location']);
    $loader->prependPath('/');
    
    $_environ = ['cache' => '../app/cache/compilation'];
    // error reporting
    if($config['enable_debug_mode']) {
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      $_environ['debug'] = true;
    } else {
      error_reporting(0);
      ini_set('display_errors', 0);
      ini_set('display_startup_errors', 0);
    }
    $this->twig = new \Twig\Environment($loader, $_environ);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    
    // remove query params from a given string/url, added to twig
    $strtokparams = new \Twig\TwigFilter('strtokparams', function ($string) {
      return strtok($string);
    });
    $this->twig->addFilter($strtokparams);
    
    // resize filter added to twig
    $resize = new \Twig\TwigFilter('resize', function ($src, $w, $h = null, $crop = 'default') {
      return self::resize($src, $w, $h, $crop);
    });
    $this->twig->addFilter($resize);
    
    // get_terms function added to twig
    $get_terms = new \Twig\TwigFunction('get_terms', function ($tax) {
      $args = array(
        'taxonomy' => $tax,
        'show_all' => true
      );
      $terms_obj = new QueryTermsModel($args);
      return $terms_obj->terms;
    });
    $this->twig->addFunction($get_terms);
    
    // twig globals: Site, Author & Configs
    $this->twig->addGlobal('site', SiteModel::init()->getSite());
    $this->twig->addGlobal('author', AuthorModel::init()->getAuthor());
    $this->twig->addGlobal('configs', $config);
    
    // menus
    $main_menu = new MenuModel('main-menu');
    $main_menu_chunked = array_chunk($main_menu->menu_items, ceil(count($main_menu->menu_items) / 2));
    $main_menu_first = $main_menu_chunked[0];
    $main_menu_second = $main_menu_chunked[1];
    $this->twig->addGlobal('main_menu', $main_menu);
    $this->twig->addGlobal('main_menu_first', $main_menu_first);
    $this->twig->addGlobal('main_menu_second', $main_menu_second);
    
    // url globals
    $this->twig->addGlobal('base_url', $config['base_url']);
    $this->twig->addGlobal('current_url', $config['current_url']);
    $this->twig->addGlobal('current_url_no_params', $config['current_url_clean']);
    $this->twig->addGlobal('get', $config['url_params']);
  }
  
  public static function resize($src, $w, $h, $crop) {
    if (!is_numeric($w) && is_string($w)) return $src;
    // if (!file_exists($src)) return $src; // need to check
    $path = parse_url($src, PHP_URL_PATH);
    $full_path = $_SERVER['DOCUMENT_ROOT'].$path;
    $op = new Resize($w, $h, $crop);
    return pathToURL(self::_resize($full_path, $op));
  }

  private static function _resize($src, $op) {
    $file_extension = substr(strrchr($src, '.'), 1);
    $file_name = basename($src, '.'.$file_extension);
    $destination_path = $op->filename($file_name, $file_extension);
    $dir = dirname($src).'/';
    $destination = $dir.$destination_path;
    if(!file_exists($destination)) {
      $op->run($src, $destination);
    }
    return $destination;
  }
  
  public function __addContactFormToTwig() {
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

  public function error() {
    echo $this->twig->render('404.twig');
  }
  
  protected function templateRender($template, $context) {
    Cache::cacheRender($this->twig->render($template, $context));
  }
  
}