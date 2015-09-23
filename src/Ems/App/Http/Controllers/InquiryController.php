<?php


namespace Ems\App\Http\Controllers;

use Auth;
use Scaffold;
use Ems\App\Helpers\ProvidesTexts;
use App\Http\Controllers\Controller;
use Cmsable\Http\Resource\CleanedRequest;
use Cmsable\Mail\MailerInterface as Mailer;
use Cmsable\View\Contracts\Notifier;

use Notification;


class InquiryController extends Controller
{

    use ProvidesTexts;

    /**
     * @var \Cmsable\Mail\MailerInterface
     **/
    protected $mailer;

    /**
     * @var \Cmsable\View\Contracts\Notifier
     **/
    protected $notifier;

    protected $subject = 'Kontaktanfrage';

    protected $answer = 'Vielen Dank für Ihre Anfrage';

    public function __construct(Mailer $mailer,
                                Notifier $notifier)
    {
        $this->mailer = $mailer;
        $this->notifier = $notifier;

    }

    public function create()
    {
        return view('inquiries.create')->withModel($this->defaultFormData());
    }

    public function store(CleanedRequest $request)
    {

        $data = $request->cleaned();

        $data['subject'] = $this->routeMailText('subject');
        $data['body']    = $this->routeMailText('body');

        $this->mailer->to($data['email'])->send('inquiries.email', $data, function($msg) use ($data) {
            $msg->replyTo($data['email'], $data['name']);
        });

        $this->notifier->success($this->routeMessage('thanks-for-message'));

        return redirect()->route('inquiries.create');

    }

    protected function defaultFormData()
    {

        if (Auth::user()->isGuest()) {
            return [
                'name'  => '',
                'email' => ''
            ];
        }

        $user = Auth::user();

        return [
            'name' => Scaffold::shortName($user),
            'email' => $user->email
        ];

    }

}