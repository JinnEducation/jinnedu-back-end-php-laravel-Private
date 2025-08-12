<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use \App\Models\NotificationInfo;

class GeneralNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $info_id;
    private $op_id;
    private $more_details;
    
    public function __construct($info_id , $op_id , $more_details=null)
    {
        //
        $this->info_id =$info_id;
        $this->op_id= $op_id;
        $this->more_details=$more_details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    
    public function toDatabase(){
        $info_id=$this->info_id;
        $notification_info =NotificationInfo::find($info_id);
    	$urlexten='';
    	$ids='';
    	
	    if($this->info_id==-1) $urlexten='/edit'; 
	    $ids = ' '.$this->op_id;
        if($this->op_id ==0){

          return[
            'title'     =>  $notification_info->n_title,
            'details'   =>  $notification_info->n_details,
            'url'       =>  $notification_info->n_url,
            ];

        }else if($this->info_id==-2){
    		return[
    			'title'     =>  $notification_info->n_title,
    			'details'   =>  $notification_info->n_details." - ".$this->more_details,
    			'url'       =>  $notification_info->n_url.$this->op_id,
    		];

    	} else {
    		return[
    			'title'     =>  $notification_infos->n_title,
    			'details'   =>  $notification_infos->n_details,
    			'url'       =>  $notification_infos->n_url.'/'.$this->op_id,
    		];


        }

   }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
