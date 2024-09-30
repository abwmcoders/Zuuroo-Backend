<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\LoanHistory;
use Alert;

class MessageDebtorsCondtroller extends Controller
{
    //
    public function message_debtors(Request $request)
    {
        $sender = $request->sender;
        $message = $request->message;
        $subject = $request->subject;

        if( $sender == 'email' )
        {
            $sql = LoanHistory::join('users', 'users.id', 'loan_histories.user_id')->where('payment_status', 'pending')->get();

            foreach($sql as $User)
            {
                $Email = $User->email;

            // ========== [ Compose Email ] ================
                require base_path("vendor/autoload.php");
                // $mail = new PHPMailer(true);     // Passing `true` enables exceptions

                try {

                    // Email server settings
                    // $mail->SMTPDebug = 0;
                    // $mail->isSMTP();
                    // $mail->Host = 'smtp.mailtrap.io';             //  smtp host
                    // $mail->SMTPAuth = true;
                    // $mail->Username = 'dfcce6d558d12e';   //  sender username
                    // $mail->Password = 'd80b7ca0239573';       // sender password
                    // $mail->SMTPSecure = 'tls';                  // encryption - ssl/tls
                    // $mail->Port = 2525;                          // port - 587/465

                    $mail = new PHPMailer();
                    $mail->isSMTP();
                    $mail->Host = 'sandbox.smtp.mailtrap.io';
                    $mail->SMTPAuth = true;
                    $mail->Port = 2525;
                    $mail->Username = 'dfcce6d558d12e';
                    $mail->Password = 'd80b7ca0239573';

                    $mail->setFrom('info@zuuroo.com', 'Zuuroo Telecommunications');
                    $mail->addAddress( $Email );
                    // $mail->addCC($request->emailCc);
                    // $mail->addBCC($request->emailBcc);

                    $mail->addReplyTo('sender@example.com', 'No Reply');

                    // if(isset($_FILES['emailAttachments'])) {
                    //     for ($i=0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    //         $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                    //     }
                    // }


                    $mail->isHTML(true);                // Set email content format to HTML

                    $mail->Subject = $subject;
                    $mail->Body    = $message;

                    // $mail->AltBody = plain text version of email body;

                    if( !$mail->send() ) {
                        Alert::warning("Oops!", "Email not sent to: ".$Email);
                        return back();
                    }

                    else {
                        Alert::success("Success!", "Email has been sent.");
                        return back();
                    }

                } catch (Exception $e) {
                    Alert::error("Error!", "Message could not be sent.");
                    return back();
                }
            }
        }
        else
        {
            $sql = LoanHistory::join('users', 'users.id', 'loan_histories.user_id')->where('payment_status', 'pending')->get();
            $Mobiles = $sql->mobile;
        }
    }
}
