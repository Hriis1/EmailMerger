<?php
// Mailbox connection string for POP3 with SSL/TLS
$mailbox = '{pop3.abv.bg:995/pop3/ssl}INBOX';

// Replace these with your actual email credentials
$username = $env['MAIL_USERNAME'];
$password = $env['MAIL_PASSWORD'];

// Establish a connection to the mailbox
$inbox = imap_open($mailbox, $username, $password)
    or die('Cannot connect to POP3 server: ' . imap_last_error());

// Search for all emails in the mailbox
$emails = imap_search($inbox, 'ALL');

if($emails) {
    // Sort emails so that the latest is on top
    rsort($emails);
    
    foreach($emails as $email_number) {
        // Fetch email overview
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        // Fetch the email body (here, part 2 is assumed to be the main content)
        $message = imap_fetchbody($inbox, $email_number, 2);

        // Display the email details
        echo "<h3>" . htmlspecialchars($overview[0]->subject) . "</h3>";
        echo "<p><strong>From:</strong> " . htmlspecialchars($overview[0]->from) . "</p>";
        echo "<div>" . nl2br(htmlspecialchars($message)) . "</div><hr>";
    }
} else {
    echo "No emails found.";
}

// Close the connection
imap_close($inbox);