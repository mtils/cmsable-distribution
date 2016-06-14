<?php


namespace Ems\App\Http\Controllers;

use Auth;
use Scaffold;
use Ems\App\Helpers\ProvidesTexts;
use App\Http\Controllers\Controller;
use Cmsable\Mail\MailerInterface as Mailer;
use Cmsable\View\Contracts\Notifier;
use Ems\App\Http\Requests\CleanedRequest;
use Ems\App\Contracts\Messaging\RecipientsProvider as Recipients;

class InquiryController extends Controller
{

    use ProvidesTexts;

    /**
     * @var \Ems\App\Contracts\Messaging\RecipientsProvider
     **/
    protected $recipients;

    /**
     * @var \Cmsable\Mail\MailerInterface
     **/
    protected $mailer;

    /**
     * @var \Cmsable\View\Contracts\Notifier
     **/
    protected $notifier;

    public function __construct(Recipients $recipients, Mailer $mailer,
                                Notifier $notifier)
    {
        $this->recipients = $recipients;
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

        $topic = isset($data['topic']) && $data['topic'] ? $data['topic'] : '1';

        $recipient = $this->recipients->recipientsFor($topic)[0];

        $data['recipient'] = $recipient;

        $data['subject'] = $this->routeMailText('subject');
        $data['body']    = $this->routeMailText('body');

        $this->mailer->to($data['recipient'])->send('inquiries.email', $data, function($msg) use ($data) {
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