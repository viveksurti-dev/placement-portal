<?php
$mail->addAddress("$email", "$firstname");
$mail->Subject = "Password Successfully Updated";
$mail->isHTML(true);
$mail->Body = "<html>

<body style='margin:0;padding:0;'>
    <div>
        <div style='width:95%;padding:10px;border-radius:5px;border:1px solid #252525;'>
            <div style='margin:0 auto;max-width:150px;text-align:center;'>
                <img src='https://cdni.iconscout.com/illustration/premium/thumb/password-changed-3305943-2757111.png'
                    alt='password changed image' style='border-radius:5px;max-width:100%;height:auto;'>
            </div>
            <div style='margin-top:20px;text-align:center;font-size:22px;'>
                Password Successfully Updated
            </div>
            <hr style='width:98%;margin:25px 0;' />
            <div style='font-size:16px;line-height:1.5;'>
                Dear <strong>$firstname</strong>,<br /><br />
                Your password has been successfully updated for your account associated with <strong>$email</strong>.<br /><br />
                
                You can now log in to your account using your new password.<br /><br />
                
                If you did not make this change, please contact our support team immediately at <a href='mailto:placementportal.ac@gmail.com'>placementportal.ac@gmail.com</a>.<br /><br />
                
                Best regards,<br />
                Smart Placement Portal Team
            </div>
        </div>
    </div>
</body>

</html>";
$mail->AltBody = 'Your password has been successfully updated';
$mail->send();
