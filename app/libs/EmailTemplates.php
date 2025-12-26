<?php

class EmailTemplates {

    private static $styles = "
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; text-align: center;}
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: left; }
        .header { background: #1a1a1a; padding: 30px; text-align: center; }
        .header h1 { color: #C19A6B; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 1px; }
        .content { padding: 40px 30px; }
        .footer { background: #f1f1f1; padding: 20px; text-align: center; font-size: 12px; color: #888; }
        .button { display: inline-block; padding: 12px 24px; background-color: #C19A6B; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
        .label { font-size: 11px; text-transform: uppercase; color: #888; letter-spacing: 1px; font-weight: bold; }
        .highlight { color: #C19A6B; font-weight: bold; }
    ";

    public static function wrap($title, $content, $actionText = '', $actionUrl = '') {
        $year = date('Y');
        $buttonHtml = '';
        if (!empty($actionText) && !empty($actionUrl)) {
            $buttonHtml = "
                <div style='text-align: center; margin-top: 30px;'>
                    <a href='$actionUrl' class='button' style='color: #ffffff;'>$actionText</a>
                </div>
            ";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <title>$title</title>
            <style>" . self::$styles . "</style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>EventHorizons</h1>
                </div>
                <div class='content'>
                    $content
                    $buttonHtml
                </div>
                <div class='footer'>
                    &copy; $year EventHorizons. All rights reserved.<br>
                    You are receiving this email because you are part of our community.
                </div>
            </div>
        </body>
        </html>
        ";
    }

    public static function referralInvite($senderName, $eventName, $eventDate, $eventLocation, $eventLink) {
        $content = "
            <p class='label'>Exclusive Invitation</p>
            <h2 style='margin-top: 5px; color: #111;'>You've been invited!</h2>
            <p><strong>$senderName</strong> thinks you'd love to join them at an upcoming event on EventHorizons.</p>
            
            <div style='background: #fdfbf7; padding: 20px; border-left: 4px solid #C19A6B; margin: 20px 0;'>
                <h3 style='margin: 0 0 10px 0; color: #111;'>$eventName</h3>
                <p style='margin: 0 0 5px 0;'>📅 $eventDate</p>
                <p style='margin: 0;'>📍 $eventLocation</p>
            </div>
            
            <p>Come join us for a memorable experience. Click the button below to view the event details and request your spot.</p>
        ";
        
        return self::wrap("You're invited: $eventName", $content, "View Invitation", $eventLink);
    }

    public static function formalUpdate($organizerName, $subject, $message, $eventName) {
        // Convert newlines to breaks for the message body
        $formattedMessage = nl2br(htmlspecialchars($message));
        
        $content = "
            <p class='label'>Update from Organizer</p>
            <h2 style='margin-top: 5px; color: #111;'>$subject</h2>
            <p><strong>$organizerName</strong> sent a message regarding <strong>$eventName</strong>:</p>
            
            <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
            
            <div style='font-size: 16px; color: #444; line-height: 1.8;'>
                $formattedMessage
            </div>
            
            <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
        ";
        
        return self::wrap($subject, $content);
    }
    public static function rsvpConfirmation($userName, $eventName, $eventLink, $kitDownloadLink) {
        $content = "
            <p class='label'>Registration Confirmed</p>
            <h2 style='margin-top: 5px; color: #111;'>You're In!</h2>
            <p>Hi <strong>$userName</strong>,</p>
            <p>Your spot for <strong>$eventName</strong> has been confirmed. We can't wait to see you there!</p>
            
            <div style='background: #fdfbf7; padding: 20px; border-left: 4px solid #C19A6B; margin: 20px 0;'>
                <h3 style='margin: 0 0 10px 0; color: #111;'>Your Digital Kit</h3>
                <p style='margin: 0;'>We've attached your exclusive <strong>Digital Event Kit</strong> to this email. It contains your entry pass, guide, and more.</p>
            </div>
            
            <p>You can also download it anytime from the event page.</p>
        ";
        
        return self::wrap("Confirmed: $eventName", $content, "View Event Details", $eventLink);
    }
}
?>
