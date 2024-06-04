<?php

namespace App\Http\Controllers;

use App\Models\LogFacialEnvivoUnoAUno;
use Illuminate\Http\Request;

class LogFacialEnvivoUnoAUnoController extends Controller
{

    public function lista()
    {
        $logs = LogFacialEnvivoUnoAUno::get();
        return view('log/facial')->with('logs', $logs);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogFacialEnvivoUnoAUno $logFacialUno)
    {
        //
    }
}
