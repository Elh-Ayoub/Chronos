<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use App\Models\Calendar;
use App\Models\User;

class emailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notitfictaion about events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $eventController = new EventController();
        foreach(Event::all() as $event){
            $watchers = $eventController->getAllEventWatchers($event->id);
            foreach($watchers as $user){
                $data = array(
                    'calendar' => Calendar::find($event->calendar_id),
                    'event' => $event,
                    'user' => (User::where('email', $user)->first()) ? (User::where('email', $user)->first()) : (null),
                    'created_by' => User::find($event->user_id),
                    'email' => $user,
                    'msg' => '',
                );
                if(date('D M d Y H:i', strtotime($event->start)) === date('D M d Y H:i', strtotime("+1 hour"))){
                    $data['msg'] = "Hello" . (($data['user']) ? (" ". $data['user']->username) : ('')) . ", event started";
                    Mail::send('Emails.event-notification', $data, function($message) use($data) {
                        $message->to($data['email'], 'Event notification')->subject
                            ('Event notification');
                        $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
                    }); 
                }
                //info(date('D M d Y H:i', strtotime($event->start)) . " --- " . date('D M d Y H:i', strtotime("+70 minutes")));
                if(date('D M d Y H:i', strtotime($event->start)) === date('D M d Y H:i', strtotime("+70 minutes"))){
                    $data['msg'] = "Hello" . (($data['user']) ? (" ". $data['user']->username) : ('')) . ", 10 min to start an event. Get your self ready!";
                    Mail::send('Emails.event-notification', $data, function($message ) use($data) {
                        $message->to($data['email'], 'Event notification')->subject
                        ('Event notification');
                        $message->from(env('MAIL_USERNAME'), env('APP_NAME'));
                    });
                }
            }
        }
        return 0;
    }
}
