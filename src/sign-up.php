<doctype! html>
<html lang="en">
<body>
<div class = "sign-up-form">
    <h3> SIGN UP HERE </h3>
    <!-- skeleton of basic sign up form -->
    <form action = "" method ="post" enctype="multipart/form-data">
        <input type="text" name="fname" placeholder="First name"> &nbsp;
        <input type="text" name="lname" placeholder="Last name"><br> <br>
        <input type="text" name="email" placeholder="e-Mail ID"><br> <br>
        <input type="text" name="mobile" placeholder="Mobile Number"><br> <br>
        <input type="text" name="ref" placeholder="Enter referral code"><br><br>
        <input type ="file" name="photo"><br>
        <input type="submit" name="submit" value="Sign Up">
    </form>
</div>

<!-- added inline css to give a decent look to the webpage -->
<style>
    body{
        color: #3B3B3B;
        background: linear-gradient(to left, #7fe8ba, #94ecc5, #aaf0d1, #c0f4dd);
        font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .sign-up-form{
    margin-top: 230px;
    padding: 40px;
    text-align: center;
    }
    input[type=text] {
    background: transparent;
    border: none;
    border-bottom: 1px solid black;
    }
    input[type=submit]{
    max-width: 30%;
    min-width: 20%;
    
    color: white;
    background-color: #3B3B3B;
    border-color: grey;
    border-radius: 5px;
    padding: .75% 1.25%;
    margin: 1% 0 0 0;
    
    align-content: center;
    align-self: auto;
    text-align: center;
    }
</style>

<?php

#used PHPMailer email sending libary: https://github.com/PHPMailer/PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

#check if the user presses submit button (POST method is used for the html form)
if(isset($_POST["submit"])){

try {
    #connect to the database
    require_once 'connect.php';
     #check if file was uploaded without errors 
     if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) { 
            
        $file_name	 = $_FILES["photo"]["name"]; 
        $file_type	 = $_FILES["photo"]["type"]; 
        $file_size	 = $_FILES["photo"]["size"]; 
        $file_tmp_name = $_FILES["photo"]["tmp_name"]; 
        $file_error = $_FILES["photo"]["error"]; 
        
        #move the uploaded files to a permanent location
        #move the uploaded file to github repository
        if (move_uploaded_file($file_tmp_name, "/Users/nandiniproothi/honeymint/".$file_name)) {
            shell_exec('cd ~');
            shell_exec('cd honeymint');
            shell_exec('git add -A ');
            shell_exec('git commit -m "new"');
            shell_exec('git push origin master');
        } else {
           echo "File was not uploaded";
        }
    } 

    #store all values from the form using post method into php variables
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $ref = $_POST['ref'];
    $r = uniqid(); #generates a 13 character long referral code
    #prepare sql query statement to insert data into mysql database 
    $sql = $conn->prepare("INSERT INTO users (first_name, last_name, mobile_number, email_add, referral, img_name)
    VALUES (:firstname, :lastname, :mobile, :email, :ref, :img_name)"); #using placeholders
    #adding actual values 
    $sql->bindParam(':firstname', $firstname);
    $sql->bindParam(':lastname', $lastname);
    $sql->bindParam(':email', $email);
    $sql->bindParam(':mobile', $mobile);
    $sql->bindParam(':ref', $r);
    $sql->bindParam(':img_name', $file_name);
    #execute query
    $sql->execute();
    #prepare sql query statement to decrement entries_remaining for current user  
    $sql1 = $conn->prepare("UPDATE users SET entries_remaining = entries_remaining - 1 WHERE mobile_number = :mobile and entries_remaining > -1");
    $sql1->bindParam(':mobile', $mobile);
    $sql1->execute();
    #prepare sql query statement to increment entries_remaining for user whose referral code has been entered 
    $sql2 = $conn->prepare("UPDATE users SET entries_remaining = entries_remaining + 1 WHERE referral = :refcode and entries_remaining > -1");
    $sql2->bindParam(':refcode', $ref);
    $sql2->execute();

    #instantiate mail client
    $mail = new PHPMailer(true);

    try {
    #server settings
    #used mailgun's SMTP service to send emails
    #credentials haven't been added for safety purposes
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.mailgun.org';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = '';                     // SMTP username
    $mail->Password   = '';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    #recipients
    $mail->setFrom('do-not-reply@honeymint.com', 'Honeymint');     
    $mail->addAddress('nandiniproothi@gmail.com');               #fixed receipent because Mailgun's testing service requires you to verify your e-Mail
    

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = "Your referral code is {$r}";
    $mail->Body    = "<body>Your referral code is {$r}</body>";
    $mail->msgHTML(file_get_contents('mail.html')); #load mail.html which consists of mail template to be sent
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
    } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }



#close the connection
$conn = null;
}
#end of php
?>
</body>
<!-- end of html -->
</html>
</doctype>
