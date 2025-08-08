<?php
$mail->addAddress("$email", "$firstname");
$mail->Subject = "Password Reset Request";
$mail->isHTML(true);
$mail->Body = "<html>

<body style='margin:0;padding:0;'>
    <div>
        <div style='width:95%;padding:10px;border-radius:5px;border:1px solid #252525;'>
            <div style='margin-top:20px;text-align:center;font-size:22px;'>
                Password Reset Request
            </div>
            <hr style='width:98%;margin:25px 0;' />
            <div style='font-size:16px;line-height:1.5;'>
                Dear <strong>$firstname</strong>,<br /><br />
                We received a request to reset your password for your account associated with <strong>$email</strong>.<br /><br />
                
                To reset your password, please click the link below:<br />
                <a href='$resetLink' style='background-color:#007bff;color:white;margin:10px 0px; width:100%; padding:10px 20px;text-decoration:none;border-radius:5px;'>Reset Password</a><br /><br />
                
                This link will expire in 2 minutes for security reasons.<br /><br />
                
                If you did not request this password reset, please ignore this email. Your password will remain unchanged.<br /><br />
                
                For security, this request was received from IP address: <strong>$_SERVER[REMOTE_ADDR]</strong><br /><br />
                
                If you have any questions, please contact our support team at <a href='mailto:placementportal.ac@gmail.com'>placementportal.ac@gmail.com</a>.<br /><br />
                
                Best regards,<br />
                Smart Placement Portal Team
            </div>
        </div>
    </div>
</body>

</html>";
$mail->AltBody = 'Password Reset Request - Click the link to reset your password';
$mail->send();
