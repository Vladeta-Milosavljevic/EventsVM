<?php

namespace App\Http\Controllers;

use App\Mail\StripeSuccess;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\StripeTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StripeController extends Controller
{
    public function buyTicket(Request $request)
    {

        $validated = $request->validate([
            'event_id' => 'required',
        ]);

        $event = Event::where('id', '=', $validated['event_id'])->first();
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => env('STRIPE_CURRENCY'),
                        'product_data' => [
                            'name' => $event->name . ' - ' . $event->price,
                            'description' => $event->description,
                        ],
                        'unit_amount' => $event->price * 100,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success', $event->id, true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel', $event->id, true),
        ]);
        StripeTransaction::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'session_id' => $checkout_session->id,
            'status' => 'pending',
            'price' => $event->price,

        ]);
        return Inertia::location($checkout_session->url);
    }

    public function success(Event $event, Request $request)
    {
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));
        $sessionId = $request->get('session_id');
        $transaction = StripeTransaction::where('session_id', '=', $sessionId)->first();
        if (!$transaction) {
            throw new NotFoundHttpException();
        }
        if ($transaction->status === 'pending') {
            $transaction->status = 'paid';
        }
        $transaction->save();
        return to_route('event.show', $event->id)->with('stripeSuccess', 'Ticket bought successfully.');
    }

    public function cancel(Event $event)
    {
        return to_route('event.show', $event->id)->with('stripeError', 'Error ocured during the payment process, please try again.');
    }


    public function webHook()
    {
        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET_KEY"));

        $endpoint_secret = env('STRIPE_WEBHOOK_KEY');
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;
                $transaction = StripeTransaction::where('session_id', '=', $paymentIntent->id)->first();
                if ($transaction && $transaction->status === 'pending') {
                    $transaction->status = 'paid';
                    $transaction->save();
                }
                if ($transaction && $transaction->status === 'paid') {
                    $eventName = Event::where('id', $transaction->event_id)->get('name')[0]->name;
                    $userName = User::where('id', $transaction->user_id)->get('name')[0]->name;
                    Mail::to(env('TEST_EMAIL'))->queue(new StripeSuccess($eventName, $userName, $transaction->price));
                }
            default:
                echo 'Received unknown event type ' . $event->type;
        }
        http_response_code(200);
    }
}
