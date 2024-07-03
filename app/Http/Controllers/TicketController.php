<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Message;
use App\Models\User;
use App\Ticket;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function getTickets(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:11',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        $user = User::where('mobile', $request->mobile)->first();
        // $ticket = Ticket::where('user_id', $user->id)->first();
        $ticket = $user->ticket;
        if(count($ticket) == 0)
            return response()->json([
                'status' => false,
                'message' => 'تیکتی برای این کاربر وجود ندارد.',
            ], 400);

        return response()->json([
            'status' => true,
            'ticket' => $ticket,
        ], 201);
    }

    public function getTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);


        $ticket = Ticket::where('id', $request->id)
                ->with('message.answer')
                ->orderBy('created_at', 'desc')
                ->first();

        return response()->json([
            'status' => true,
            'ticket' => $ticket,
        ], 201);
    }

    public function createMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'mobile' => 'required|numeric|digits:11',
            'title' => ($request->title !== null) ? 'required|max:255|string' : '',
            'message' =>($request->message !== null) ? 'required' : '',
            'ticket_id' => ($request->ticket_id !== null) ? 'required|numeric' : '',
            'answer_id' => ($request->answer_id !== null) ? 'required|numeric' : '',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::where('mobile', $request->mobile)->first();

        if (empty($request->ticket_id)){
            if($user){
                $ticket = Ticket::create([
                    'title' => $request->title,
                    'user_id' => $user->id,
                    'status' => Ticket::NOT_ANSWERED,
                ]);
                $message = Message::create([
                    'message' => $request->message,
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id

                ]);
                return response()->json([
                    'status' => true,
                    'payam' => 'تیکت ثبت شد',
                    // 'ticket_id' => $message->ticket_id,
                    // 'message_id' => $message->id,
                    // 'check-status' => $ticket->status,
                    // 'created_at' => $message->created_at,
                    'ticket' => $ticket,
                    'message' => $message,
                ], 201);
            }
            else
                return response()->json([
                    'status' => false,
                    'message' => 'کاربر وجود ندارد.',
                ], 400);
        }else{
            $ticket = Ticket::findOrFail($request->ticket_id);

            $message = Message::create([
                'message' => $request->message,
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'پاسخ ثبت شد',
                'created_at' => $message->created_at,
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id

            ], 201);
        }
    }

    public function createAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:value',
            'answer' =>'required',
            'ticket_id' => 'required|numeric',
            'message_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::where('mobile', $request->mobile)->first();

        $message = Message::findOrFail($request->message_id);

        $answer = Answer::create([
            'answer' => $request->answer,
            'ticket_id' => $request->ticket_id,
            'message_id' => $request->message_id,
            'user_id' => $message->user_id
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);
        $ticket -> status = Ticket::ANSWERED;
        $ticket->save();

        $message -> answer_id = $answer->id;
        $message->save();

        return response()->json([
            'status' => true,
            'message' => 'پاسخ ثبت شد',
            'ticket_id' => $answer->ticket_id,
            'answer_id' => $answer->id,
        ], 201);
    }

    // public function messageAnswer(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'message' =>'required',
    //         'ticket_id' => 'required',
    //         'answer_id' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation Error',
    //             'errors' => $validator->errors(),
    //         ], 400);
    //     }
    //     // $ticket = Ticket::where('id' , $request->ticket_id)->first();
    //     $ticket = Ticket::findOrFail($request->ticket_id);

    //     $message = Message::create([
    //         'message' => $request->message,
    //         'ticket_id' => $ticket->id,
    //         'answer_id' =>$request->answer_id,
    //         'user_id' => $ticket->user_id
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'پاسخ ثبت شد',

    //     ], 201);
    // }

}
