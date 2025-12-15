<?php

namespace App\Http\Controllers\Client;

use App\Models\Service;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::active()->paginate(12);
        return view('Clients.service.index', compact('services'));
    }

    public function publicIndex()
    {
        $services = Service::active()->get()->groupBy('category');
        return view('Clients.service.public', compact('services'));
    }

    public function show(Service $service)
    {
        return view('Clients.service.show', compact('service'));
    }
}
