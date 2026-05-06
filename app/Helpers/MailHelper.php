<?php

namespace App\Helpers;

use App\Mail\GeneralMail;
use App\Jobs\SendMailJob;
use Auth,DB;


class MailHelper
{
    /**
     * Send mail sign up
     * 
     * @param Transaction $transaction
     */
    public static function sendMail($data)
    {
        $data['subject'] = 'Thông báo về tiến độ đặt lịch khám';

        // Kiểm tra nếu có file QR code thì attach
        $hasQrCode = !empty($data['qr_code_file']);
        $mailJob = new GeneralMail($hasQrCode);
        $mailJob->setFromDefault()
                ->setView('emails.confirm_booking', $data)
                ->setSubject($data['subject'])
                ->setTo($data['email']);
        
        if ($hasQrCode) {
            $mailJob->setAttachFile($data['qr_code_file']);
        }
        
        dispatch(new SendMailJob($mailJob));
    }

    public static function sendMailSuccess($data)
    {
        $data['subject'] = 'Email gửi kết quả khám từ bác sĩ';
        $file = null;
        if (!empty($data['file_result'])) {
            $file = public_path() . '/uploads/' .'file-result/'. $data['file_result'];
        }

        if (!empty($data['file_result'])) {
            $mailJob = new GeneralMail(true);
            $mailJob->setFromDefault()
                ->setView('emails.success_booking', $data)
                ->setSubject($data['subject'])
                ->setTo($data['email'])
                ->setAttachFile($file);
        } else {
            $mailJob = new GeneralMail(false);
            $mailJob->setFromDefault()
                ->setView('emails.success_booking', $data)
                ->setSubject($data['subject'])
                ->setTo($data['email']);
        }

        dispatch(new SendMailJob($mailJob));
    }
}
