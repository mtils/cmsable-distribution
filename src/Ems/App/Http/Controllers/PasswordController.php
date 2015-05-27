<?php namespace Ems\App\Http\Controllers;

use DateTime;
use URL;
use Notification;
use Permit\Authentication\CredentialsBrokerInterface as CredentialsBroker;
use FormObject\Validator\ValidationException;
use Permit\User\UserNotFoundException;
use Cmsable\Mail\MailerInterface as Mailer;
use Permit\Token\TokenExpiredException;
use Permit\Token\TokenCollisionException;
use Permit\Token\TokenException;
use Permit\Authentication\AuthenticatorInterface as Auth;
use Ems\App\Http\Forms\PasswordEmailForm;
use Ems\App\Http\Forms\PasswordResetForm;
use App\Http\Controllers\Controller;
use Ems\App\Helpers\ProvidesTexts;


class PasswordController extends Controller
{

    use ProvidesTexts;

    public $createEmailView = 'passwords.email';

    public $sentEmailView = 'passwords.email';

    public $createResetView = 'passwords.email';

    /**
     * @var \Permit\Authentication\CredentialsBrokerInterface
     **/
    protected $broker;

    /**
     * @var \Cmsable\Mail\MailerInterface
     **/
    protected $mailer;

    /**
     * @var \Permit\Authentication\AuthenticatorInterface
     **/
    protected $auth;

    /**
     * @param \Permit\Authentication\CredentialsBrokerInterface $broker
     * @param \Cmsable\Mail\MailerInterface $mailer
     * @param \Permit\Authentication\AuthenticatorInterface $auth
     **/
    public function __construct(CredentialsBroker $broker, Mailer $mailer,
                                Auth $auth)
    {
        $this->broker = $broker;
        $this->mailer = $mailer;
        $this->auth = $auth;
    }

    /**
     * Display the form to reset the password
     *
     * @return Response
     */
    public function createEmail(PasswordEmailForm $form)
    {
        return view($this->createEmailView)->withForm($form);
    }

    /**
     * Processes submit of createEmail form, sends the reset email
     *
     * @return Response
     */
    public function sendEmail(PasswordEmailForm $form)
    {

        try {

            $this->broker->reserveReset($form->getData(), function($user, $token) {

                $data = [
                    'subject' => $this->routeMailText('subject'),
                    'body'    => $this->routeMailText('body'),
                    'link'    => URL::route('password.create-reset',[$token]),
                    'user'    => $user,
                    'token'   => $token
                ];

                $this->mailer->to($user->email)->send('emails.empty', $data);

            });

            Notification::success($this->routeMessage('reset-sent'));

            return redirect()->route('password.create-email');

        } catch (ValidationException $e) {

            return redirect()->route('password.create-email')->withErrors($e)->withInput();

        } catch (UserNotFoundException $e) {

            $message = $this->routeMessage('user-not-found');

        } catch (TokenCollisionException $e) {

            if ($e->validUntil instanceof DateTime) {
                $params = ['valid_until' => $this->formatDateTime($e->validUntil)];
                $message = $this->routeMessage('token-collision-until', $params);
            } else {
                $message = $this->routeMessage('token-collision-forever');
            }
        }

        Notification::error($message);
        return redirect()->route('password.create-email');


    }

    /**
     * Display the form which allows to change the password after requesting
     *
     * @return Response
     */
    public function createReset(PasswordResetForm $form, $token)
    {
        $form->fillByArray(['token'=>$token]);
        return view($this->createResetView)->withForm($form);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function storeReset(PasswordResetForm $form, $token)
    {
        try {

            $this->auth->loginUser($this->broker->reset($form->getData()), false);

            Notification::success($this->routeMessage('password-changed'));

            return redirect()->to('fachpartner.personal-area-page');

        } catch (ValidationException $e) {
            return redirect()->route('password.create-reset',[$token])->withErrors($e)->withInput();
        } catch (UserNotFoundException $e) {
            $message = $this->routeMessage('user-not-found');
            return redirect()->route('password.create-email');
        } catch (TokenExpiredException $e) {
            $params = ['expired_at' => $this->formatDateTime($e->expiryDate)];
            $message = $this->routeMessage('token-expired', $params);
        } catch (TokenException $e) {
            $message = $this->routeMessage('token-invalid');
        }

        Notification::error($message);
        return redirect()->route('password.create-email');

    }

}
