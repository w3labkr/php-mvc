<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    /**
     * Configure and return a PHPMailer instance for SMTP.
     *
     * This method sets up the PHPMailer instance with SMTP settings retrieved
     * from the configuration using the config() helper function. It determines
     * the encryption method and port based on the configured SMTP port.
     *
     * @return PHPMailer A configured PHPMailer instance.
     */
    public function smtp()
    {
        // Create a new PHPMailer instance with exceptions enabled.
        $mail = new PHPMailer(true);

        // Server settings
        $mail->SMTPDebug = false;
        $mail->isSMTP();
        $mail->Host = config('mail.mailers.smtp.host');
        $mail->SMTPAuth = true;
        $mail->Username = config('mail.mailers.smtp.username');
        $mail->Password = config('mail.mailers.smtp.password');

        // Retrieve the SMTP port from configuration and cast it to an integer.
        $port = (int) config('mail.mailers.smtp.port');

        // Use if/elseif to determine the encryption method and port.
        if ($port === 465) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
        } elseif ($port === 587) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
        } else {
            $mail->Port = 25;
        }

        return $mail;
    }
}
