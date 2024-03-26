<?
    function sendMail($email, $subject, $body, $user=null) {
        require __DIR__ . '/phpmailer/PHPMailer.php';
        require __DIR__ . '/phpmailer/SMTP.php';
        require __DIR__ . '/phpmailer/Exception.php';
        require __DIR__ . '/db.php';

        $json = file_get_contents('./src/secret.json');
        $secret = json_decode($json, true)['mail'];
    
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = $secret['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $secret['auth']['user'];
        $mail->Password = $secret['auth']['password'];
        $mail->SMTPSecure = $secret['secure'];
        $mail->Port = $secret['port'];
    
        $mail->setFrom('no-reply@sarxzer.xyz', 'no-reply');
    
        $mail->isHTML(true);
    
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mysqlClient->prepare("INSERT INTO emails (sender, email, subject, body) VALUES (:user, :email, :subject, :body)")->execute(['user' => $user, 'email' => $email, 'subject' => $subject, 'body' => $body]);
        
        $mail->send();
    }

?>