<?php
declare(strict_types=1);

class MailService
{
    public function sendPasswordResetEmail(string $recipientEmail, string $resetUrl): bool
    {
        $safeResetUrl = htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8');

        $subject = 'Reset your NSBT Student Portal password';

        $message = '
            <html>
            <body style="font-family: Arial, sans-serif; color: #212529; line-height: 1.6;">
                <h2 style="color: #0d6efd;">NSBT Student Portal</h2>

                <p>We received a request to reset your password.</p>

                <p>
                    <a href="' . $safeResetUrl . '"
                       style="display: inline-block; padding: 12px 18px; background: #0d6efd;
                              color: #ffffff; text-decoration: none; border-radius: 5px;">
                        Reset My Password
                    </a>
                </p>

                <p>
                    This link expires in one hour and can be used only once.
                </p>

                <p>
                    If you did not request a password reset, you can safely ignore this email.
                </p>

                <p>NSBT Student Portal</p>
            </body>
            </html>
        ';

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: NSBT Student Portal <no-reply@nsbt-portal.test>',
        ];

        return mail(
            $recipientEmail,
            $subject,
            $message,
            implode("\r\n", $headers)
        );
    }
}