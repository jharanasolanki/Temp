<?php
/**
 * @author @owner A Lowney, May 2014
 */
$pageTitle = "Register";

include 'UserHandler.class.php';
include 'User.class.php';

$registerView = new RegisterView(); 

class RegisterView
{
    private $username = ""; // Max length 20
    private $firstName = ""; // Max length 35
    private $lastName = ""; // Max length 35
    private $email = ""; // Max length 320
    private $password = ""; 
    private $confirmPassword = "";
    private $passwordHash = "";
    private $salt = ""; 
    private $link = "";
   
 /**
 * @constructor
 */
    public function RegisterView()
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        if($_POST != "")
        {
            $this->processRegistrationRequest();
        }          
    }
        
 /**
 * @functions
 */
 
    public function processRegistrationRequest()
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        
        $username = filter_input(INPUT_POST, 'username'); 
         
        if (mysqli_connect_errno($this->link))
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else
        {
            $this->link->select_db("codeshastra");
            if ($result = mysqli_query($this->link, "SELECT username FROM user_accounts where username = '$username';")) 
            {
                $row = mysqli_fetch_row($result);
                
                if(sizeof($row) > 0)
                {
                    $message = "Username already in use!";
                    $this->redirectToErrorView($message);
                }
                elseif((filter_input(INPUT_POST, 'lastName') && filter_input(INPUT_POST, 'firstName') 
                        && filter_input(INPUT_POST, 'username') && filter_input(INPUT_POST, 'email') 
                        && filter_input(INPUT_POST, 'password') && filter_input(INPUT_POST, 'confirmPassword')) != "")
                {
                    $this->setLastName(mysqli_real_escape_string($this->link, filter_input(INPUT_POST, 'lastName')));
                    $this->setFirstName(mysqli_real_escape_string($this->link, filter_input(INPUT_POST, 'firstName')));
                    $this->setUsername(mysqli_real_escape_string($this->link, filter_input(INPUT_POST, 'username')));
                    $this->setEmail(mysqli_real_escape_string($this->link, filter_input(INPUT_POST, 'email')));
                    $this->setPassword(mysqli_real_escape_string($this->link, filter_input(INPUT_POST, 'password')));
                    $this->setConfirmPassword(mysqli_real_escape_string($this->link, filter_input(INPUT_POST, 'confirmPassword')));
                    
                    if($this->getPassword() != $this->getConfirmPassword())
                    {
                        $this->redirectToErrorView("Passwords do not match!");
                    }
                    else
                    {
                        $userHandler = new UserHandler();
                        
                        $this->setSalt(rand(10000000, 10000000000000));
                        $this->setEncryptedPassword($userHandler->generateEncryptedPassword($this->getSalt(), $this->getPassword()));  
                        
                        $newUser = new User();
                        
                        $newUser->setFirstName($this->getFirstName());$newUser->setLastName($this->getLastName());
                        $newUser->setEmail($this->getEmail());$newUser->setUsername($this->getUsername());
                        $newUser->setEncryptedPassword($this->getEncryptedPassword());$newUser->setSalt($this->getSalt());
                      
                        $userHandler->processRegistration($newUser); 
                   
                        $this->redirectToDeviceView();
                    }
                }
                elseif(isset($_POST['username']))
                {
                        $this->redirectToErrorView("Enter missing information!");
                }  
            }
        }      
    }
    
    public function redirectToErrorView($message)
    {
        echo '<script type="text/javascript">alert("' . $message . '"); </script>';
    }
    
    public function redirectToDeviceView()
    {       
        header( 'Location: ../ver1.0/login.php' ); 
    }
    
 /**
 * @accessors
 */   
    public function getUsername()
    {
    	return $this->username;
    }

    public function getFirstName()
    {
    	return $this->firstName;
    }
    
    public function getLastName()
    {
    	return $this->lastName;
    }
   
    public function getEmail()
    {
    	return $this->email;
    }
    
    public function getPassword()
    {
    	return $this->password;
    }
    
    public function getConfirmPassword()
    {
    	return $this->confirmPassword;
    }
    
    public function getEncryptedPassword()
    {
    	return $this->passwordHash;
    }
    
    public function getSalt()
    {
    	return $this->salt;
    }

/**
 * @setters
 */
    public function setUsername($username)
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        
    	$this->username = mysqli_real_escape_string($this->link, $username);
    }
 
    public function setFirstName($firstName)
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        
    	$this->firstName = mysqli_real_escape_string($this->link, $firstName);
    }
    
    public function setLastName($lastName)
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        
    	$this->lastName = mysqli_real_escape_string($this->link, $lastName);
    }
    
    public function setEmail($email)
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        
    	$this->email = mysqli_real_escape_string($this->link, $email);
    }
    
    public function setPassword($password)
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        
    	$this->password = mysqli_real_escape_string($this->link, $password);
    }
    
    public function setConfirmPassword($confirmPassword)
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        
    	$this->confirmPassword = mysqli_real_escape_string($this->link, $confirmPassword);
    }
    
    public function setSalt($salt)
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        
    	$this->salt = mysqli_real_escape_string($this->link, $salt);
    }
        
    public function setEncryptedPassword($passwordHash)
    {
        $this->link=new mysqli("db4free.net","codeshastra","codeshastra","codeshastra");  
        
        $this->passwordHash = mysqli_real_escape_string($this->link, $passwordHash);	
    }

}
?>