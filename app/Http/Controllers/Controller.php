<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Storage;
use Illuminate\Http\Request;
use App\Services\CssParserService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public $cssParserService;
    
    public function __construct()
    {
        # Init our CSS Parser Service
        $this->cssParserService = new CssParserService();
    }
    
    public function getIndex()
    {
        return view('index');
    }
    
    public function postUpload(Request $request)
    {
        # Make sure we have a file, that it is valid, and that it is a CSS file
        if ( ! $request->hasFile('css_file') OR ! $request->file('css_file')->isValid() OR $request->file('css_file')->getClientMimeType() != 'text/css') {
            echo 'Error with file!'; exit;
        }
        
        # Create a unique file name for this upload
        $file_name = uniqid(null, true);
        
        # Get our file and save it; force it to be a CSS file
        Storage::put($file_name . '.css', file_get_contents($request->file('css_file')->getRealPath()));

        # Get all our stats for this file
        $data = $this->cssParserService->getAllStatsForString(file_get_contents($request->file('css_file')->getRealPath()));

        # Save those stats for
        Storage::put($file_name . '.json', json_encode($data));
        
        # Go to our results page
        return redirect('result/' . $file_name);
    }
    
    public function getResult(Request $request, $file_name)
    {
        # Get our JSON data created for this file
        $data = json_decode(Storage::get($file_name . '.json'));
        
        # Show our results
        return view('result')->with('data', $data);
    }
    
}
