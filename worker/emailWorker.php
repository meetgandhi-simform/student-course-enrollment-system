<?
require_once __DIR__ . "./../helper/MailHelper.php";
require_once __DIR__ . "./../class/EmailQueue.php";

$emailQueueObj = new EmailQueue();

while (true) {

    $response = $emailQueueObj->getPendingEmail();

    if (!$response['status']) {
        sleep(5);
        continue;
    }

    $result = $response['data'];

    if (!$result) {
        sleep(5);
        continue;
    }

    $id = $result['id'];
    $email = $result['email'];
    $subject = $result['subject'];
    $body = $result['body'];


    $mail = MailHelper::sendEmail($email, $subject, $body);

    $file = fopen('/var/www/html/course-management/mail.log', 'a');

    if (!$file) {
        die('Unable to open log file');
    }

    $time = date('Y-m-d H:i:s');

    if ($mail['status']) {

        $emailQueueObj->updateStatus('sent', $id);

        fwrite($file, "[$time] SUCCESS - Email ID: $id sent to $email\n");

        echo "Email sent\n";
    } else {

        $emailQueueObj->updateStatus('failed', $id);

        fwrite($file, "[$time] FAILED - Email ID: $id to $email\n");

        echo "Email failed\n";
    }

    fclose($file);

    sleep(2);
}
