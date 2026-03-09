<?php
namespace App\Http\Controllers;
use App\Models\Repository;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    public function index()
    {
        $repositories = Repository::with('user')->latest()->get();
        return view('repository.index', compact('repositories'));
    }

    // Fungsi store dan create bisa ditambahkan nanti sesuai kebutuhan form
}