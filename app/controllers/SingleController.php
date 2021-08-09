<?php
namespace Rmcc;
use PHPMailer\PHPMailer\PHPMailer;

class SingleController extends CoreController {
  
  /*
  *
  * This class is used to render singular objects like pages, posts, projects etc.
  * Create a new SingleController object with $type & $slug properties.
  * Call the getSingle() method on the object to render it.
  * This is mainly for use within a routing context, see config/routes.
  *
  */
  public function __construct(string $type, string $slug) {
    parent::__construct();
    global $_context;
    global $config;
    $this->type = $type; // e.g 'page' or 'blog' or 'portfolio'
    $this->slug = $slug; // e.g 'about'. this will usually come from the request unless setting for specific pages
    // the $name property is only used for render() to differenciate between archived & non-archived singular objects
    $this->name = ($this->type == 'page') ? $this->type : $config['types'][$this->type]['single'];
    
    $this->init();
  }
  
  if (array_key_exists('name', $_POST)) {
    
    // ajax defaults
    date_default_timezone_set('Etc/UTC');
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    // form fields defaults
    $err = false;
    $display_msg = '';
    $email = '';
    
    // form fields & validations
    if (array_key_exists('name', $_POST)) {
      $name = substr(strip_tags($_POST['name']), 0, 255);
    } else {
      $display_msg = 'Error: no name provided';
      $err = true;
    }
    if (array_key_exists('email', $_POST) && PHPMailer::validateAddress($_POST['email'])) {
      $email = $_POST['email'];
    } else {
      $display_msg .= 'Error: invalid email address provided';
      $err = true;
    }
    if (array_key_exists('phone', $_POST)) {
      $phone = substr(strip_tags($_POST['phone']), 0, 255);
    } else {
      $phone = 'No phone number provided';
    }
    if (array_key_exists('subject', $_POST)) {
      $subject = substr(strip_tags($_POST['subject']), 0, 255);
    } else {
      $subject = 'No subject provided';
    }
    if (array_key_exists('company', $_POST)) {
      $company = substr(strip_tags($_POST['company']), 0, 255);
    } else {
      $company = 'No company provided';
    }
    if (array_key_exists('budget', $_POST) && in_array($_POST['budget'], ['under-5k', '5-10k', 'over-10k', 'not-applicable'], true)) {
      $budget = $_POST['budget'];
    } else {
      $budget = 'No budget provided';
    }
    
    // if no error exists, setup to do the mailer stuff
    if (!$err) {
      
      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->isHTML(true);
      $mail->Host = 'localhost';
      $mail->Port = 25;
      $mail->Host = 'nl1-lr6.supercp.com'; // secret***
      $mail->SMTPAuth   = true; //Enable SMTP authentication
      $mail->Username   = 'secret'; //SMTP username secret***
      $mail->Password   = 'secret'; //SMTP password secret***
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
      $mail->setFrom('info@robertmccormack.com', (empty($name) ? 'Contact form' : $name));
      $mail->addAddress('cv@robertmccormack.com');
      $mail->addReplyTo($email, $name);
      $mail->Subject = $subject ? 'New Contact form: '.$subject : 'New Contact form submission';
      $mail->Body = $mail->msgHTML($this->twig->render('email_template.twig',array(
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'subject' => $subject,
        'company' => $company,
        'budget' => $budget,
      )));

      //Send the message, check for errors
      if (!$mail->send()) {
        
        //The reason for failing to send will be in $mail->ErrorInfo
        //but it's unsafe to display errors directly to users - process the error, log it on your server.
        if ($isAjax) {
          http_response_code(500);
        }

        $response = [
          "status" => false,
          "message" => 'Sorry, something went wrong. Please try again later.'
        ];
          
      } else {
        $response = [
          "status" => true,
          "message" => 'Message sent! Thanks for contacting us.'
        ];
      }
      
    }
    
    //Validate address selection before trying to use it
    if (array_key_exists('dept', $_POST) && array_key_exists($_POST['dept'], $addresses)) {
      $mail->addAddress($addresses[$_POST['dept']]);
    } else {
      //Fall back to a fixed address if dept selection is invalid or missing
      $mail->addAddress('support@example.com');
    }
    
    //Put the submitter's address in a reply-to header
    //This will fail if the address provided is invalid,
    //in which case we should ignore the whole request
    if ($mail->addReplyTo($_POST['email'], $_POST['name'])) {
      $mail->addAddress($addresses[$_POST['dept']]);
    } else {
      $response = [
        "status" => false,
        "message" => 'Invalid email address, message ignored.'
      ];
    }

    if ($isAjax) {
      header('Content-type:application/json;charset=utf-8');
      echo json_encode($response);
      exit();
    }
  }
  
  private function init() {
    global $_context;
    $_context = array(
      'single' => 'Single',
      'type' => $this->type,
      'slug' => $this->slug,
      'name' => $this->name,
    );
  }
  
  public function getContact() {
    global $_context;
    if (array_key_exists('name', $_POST)) {
    
      /*
      *
      * Defaults
      *
      */
      $err = false;
      $display_msg = '';
      $email = '';
      
      /*
      *
      * Apply some basic validation and filtering to the name
      *
      */
      if (array_key_exists('name', $_POST)) {
        $name = substr(strip_tags($_POST['name']), 0, 255);
      } else {
        $display_msg = 'Error: no name provided';
        $err = true;
      }
      
      /*
      *
      * Make sure the address they provided is valid before trying to use it
      *
      */
      if (array_key_exists('email', $_POST) && PHPMailer::validateAddress($_POST['email'])) {
        $email = $_POST['email'];
      } else {
        $display_msg .= 'Error: invalid email address provided';
        $err = true;
      }
      
      if (array_key_exists('phone', $_POST)) {
        $phone = substr(strip_tags($_POST['phone']), 0, 255);
      } else {
        $phone = 'No phone number provided';
      }
      
      /*
      *
      * Apply some basic validation and filtering to the subject
      *
      */
      if (array_key_exists('subject', $_POST)) {
        $subject = substr(strip_tags($_POST['subject']), 0, 255);
      } else {
        $subject = 'No subject provided';
      }
      
      /*
      *
      * Apply some basic validation and filtering to the query
      *
      */
      if (array_key_exists('company', $_POST)) {
        $company = substr(strip_tags($_POST['company']), 0, 255);
      } else {
        $company = 'No company provided';
      }
      
      if (array_key_exists('budget', $_POST) && in_array($_POST['budget'], ['under-5k', '5-10k', 'over-10k', 'not-applicable'], true)) {
        $budget = $_POST['budget'];
      } else {
        $budget = 'No budget provided';
      }
      
      /*
      *
      * If not an error!!
      *
      */
      if (!$err) {
        $mail = new PHPMailer();
        $mail->IsHTML(true);
        $mail->isSMTP();
        $mail->Host = 'nl1-lr6.supercp.com'; // secret***
        $mail->SMTPAuth   = true; //Enable SMTP authentication
        $mail->Username   = 'robertm6'; //SMTP username secret***
        $mail->Password   = 'uHAH1(4(c6dGz6'; //SMTP password secret***
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
        $mail->setFrom('info@robertmccormack.com', (empty($name) ? 'Contact form' : $name));
        $mail->addAddress('cv@robertmccormack.com');
        $mail->addReplyTo($email, $name);
        $mail->Subject = $subject ? 'New Contact form: '.$subject : 'New Contact form submission';
        $mail->Body = $mail->msgHTML($this->twig->render('email_template.twig',array(
          'name' => $name,
          'email' => $email,
          'phone' => $phone,
          'subject' => $subject,
          'company' => $company,
          'budget' => $budget,
        )));
        
        /*
        *
        * If mail didnt send for any reason, display an error with info..
        * Or display success message
        *
        */
        if (!$mail->send()) {
          $display_msg .= 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
          $display_msg .= 'Your message has been sent. We will be in touch shortly.';
        }
      }
      
      /*
      *
      * Turn the form data into an array for to add to the context
      *
      */
      $form_array = array(
        'err' => $err,
        'form_display_msg' => $display_msg,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'subject' => $subject,
        'company' => $company,
        'budget' => $budget,
        'mail' => $mail
      );
      foreach($form_array as $key => $value){
        $context[$key] = $value;
      }
    
    }    
    $context['single'] = (new SingleModel($this->type, $this->slug))->single;
    $context['context'] = $_context;
    $this->render($context);
  }
  
  public function getSingle() {
    global $_context;
    $context['single'] = (new SingleModel($this->type, $this->slug))->single;
    $context['context'] = $_context;
    $this->render($context);
  }
  
  /*
  *
  * This method is used to render singular objects according to a template hierarchy.
  *
  */
  protected function render($context) {
    if (isSingleAllowed($context['single'])) {
      
      $_type = (isset($context['single']['type'])) ? $context['single']['type'] : $this->name;
      $_format = (isset($context['single']['format'])) ? $context['single']['format'] : 'default';
      $_slug = $context['single']['slug'];
      
      $format1 = $_slug.'.twig'; // creativo-para-jovenes.twig
      $format2 = $_type.'_'.$_format.'.twig'; // post_video.twig
      $format3 = $_type.'.twig'; // post.twig
      
      if($this->twig->getLoader()->exists($format1)){
        $this->templateRender($format1, $context);
      }
      
      elseif($this->twig->getLoader()->exists($format2)) {
        $this->templateRender($format2, $context);
      }

      elseif($this->twig->getLoader()->exists($format3)) {
        $this->templateRender($format3, $context);
      }
      
      else {
        $this->templateRender('single.twig', $context);
      }
      
    } else {
      $this->error();
    }
  }
}