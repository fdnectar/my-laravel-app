<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'pageTitle' => 'All CLients',
            'clients' => Client::all()
        ];
        return view('back.pages.client', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Add CLients',
            'clients' => Client::all()
        ];
        return view('back.pages.add-clients', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCLient(Request $request)
    {
        $request->validate([
            'client_name' => 'required|unique:clients,name',
            'client_phone' => 'required|unique:clients,phone',
            'client_email' => 'required|unique:clients,email',
            'client_address' => 'required'
        ], [
            'client_name.required' => 'Enter client name', 
            'client_name.unique' => 'This client name is already taken',
            'client_phone.required' => 'Enter client phone', 
            'client_phone.unique' => 'This client phone is already taken',
            'client_email.required' => 'Enter client email', 
            'client_email.unique' => 'This client email is already taken',
            'client_address.required' => 'Enter Client Address',
        ]);

        $client = new Client();
        $client->name = $request->client_name;
        $client->phone = $request->client_phone;
        $client->email = $request->client_email;
        $client->address = $request->client_address;
        $saved = $client->save();
        if($saved) {
            return response()->json(['status' => 1, 'msg' => 'Client Added Successfully']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editClient(Request $request)
    {
        $client_id = $request->id;
        $client = Client::findOrFail($client_id);
        // dd($client);
        $data = [
            'pageTitle' => 'Edit Client',
            'client' => $client
        ];

        return view('back.pages.edit-client', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateClient(Request $request)
    {
        $client_id = $request->client_id;
        $client = Client::findOrFail($client_id);

        $request->validate([
            'client_name' => 'required|unique:clients,name,'.$client->id,
            'client_phone' => 'required|unique:clients,phone,'.$client->id,
            'client_email' => 'required|unique:clients,email,'.$client->id,
            'client_address' => 'required'
        ], [
            'client_name.required' => 'Enter client name', 
            'client_name.unique' => 'This client name is already taken',
            'client_phone.required' => 'Enter client phone', 
            'client_phone.unique' => 'This client phone is already taken',
            'client_email.required' => 'Enter client email', 
            'client_email.unique' => 'This client email is already taken',
            'client_address.required' => 'Enter Client Address',
        ]);

        $client->name = $request->client_name;
        $client->phone = $request->client_phone;
        $client->email = $request->client_email;
        $client->address = $request->client_address;
        $updated = $client->save();

        if($updated) {
            return response()->json(['status' => 1, 'msg' => 'Client Updated Successfully']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
