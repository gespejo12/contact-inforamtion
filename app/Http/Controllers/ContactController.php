<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contacts');
    }

    public function contactsDatatable()
    {
        $result = Contact::all();
        return Datatables::of($result)
            ->addColumn('name', function ($contact) {
                return $contact->name;
            })
            ->addColumn('email', function ($contact) {
                return $contact->email;
            })
            ->addColumn('phone', function ($contact) {
                return $contact->phone;
            })
            ->addColumn('action', function ($call) {
                return '<button type="button" data-id="'.$call->id.'" class="btn btn-primary EditContact">Edit</button> <button type="button" data-id="'.$call->id.'" class="deleteContact btn btn-danger" >Delete</button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->id) {
            $contact = Contact::find($request->id);
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->country = $request->country;
            $contact->state = $request->state;
            $contact->city = $request->city;
            $contact->zip = $request->zip;
            $contact->save();
        } else {
            return Contact::create($request->all());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return json_encode($contact);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
    }

    public function checkEmail(Request $request)
    {
        $result = Contact::where('email', $request->email)->first();
        if ($request->id && $result->email == $request->email) {
            $result = null;
        }
        return json_encode($result);
    }
}
