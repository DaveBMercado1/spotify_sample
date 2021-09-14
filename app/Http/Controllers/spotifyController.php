<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;


class spotifyController extends Controller
{
    public function getApiToken()
    { 
        // Creates New Guzzle HTTP Client
        // Guzzle is a PHP HTTP client that makes it easy to send HTTP requests and trivial to integrate with web services.
        $client = new Client();

        // Encode Client ID & Secret
        $authCode = base64_encode(env('CLIENT_ID').':'.env('CLIENT_SECRET'));
        
        // Request Settings/Options
        $headers = [
            'Authorization' => 'Basic '.$authCode,
            'Content-type' => 'application/x-www-form-urlencoded'
        ];

        // Form Parameters
        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET')
        ];

        // Send Request to Spotify Auth API
        $response = $client->request('POST', 'https://accounts.spotify.com/api/token', [
            'headers' => $headers,
            'form_params' => $params
        ]);
        
        return $response;
    }

    public function retreiveApiToken()
    { 
        $client = new Client();

        $headers = [
            'Authorization' => 'Basic NGQyODQzOWNhYzY5NGMwZjlkNzNmMjE2NzJiNjA0NDU6NmVjYTc5OWFhMWRjNDI5YTk3MDBiMDcwODY1MDlmYjk=',
        ];

        $params = [
            'client_id' => env('CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri' => 'http://127.0.0.1:8000/spotify/playlists',
            'scope' => 'playlist-read-private user-read-private'
        ];

        $response = $client->request('GET', 'https://accounts.spotify.com/authorize', [
            'headers' => $headers,
            'query' => $params
        ]);
        
        return $response;
    }

    public function search(Request $request)
    {
        // Creates New Guzzle HTTP Client
        $client = new Client();
        // Stores Request into $input array
        $input = $request->all();

        // Request Settings/Options
        $headers = [
            'Authorization' => 'Bearer '.$input['token'],
            'Content-type' => 'application/json',
            'Accept' => 'application/json'
        ];

        // Query Parameters
        $params = [
            'q' => $input['q'],
            'type' => $input['type'],
            'limit' => (!empty($input['limit'])) ? $input['limit'] : 10
        ];

        // Query = https://api.spotify.com/v1/search?q=Kiss&type=artist&limit=10
        $response = $client->request ('GET', 'https://api.spotify.com/v1/search', [
            'headers' => $headers,
            'query' => $params
        ]);

        return json_decode($response->getBody(), true);
    }

    public function fetchPlaylist(Request $request)
    {
        $client = new Client();
        $input = $request->all();

        $headers = [
            'Authorization' => 'Bearer '.$input['token'],
            'Content-type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $params = [
            'limit' => (!empty($input['limit'])) ? $input['limit'] : 10
        ];

        $response = $client->request ('GET', 'https://api.spotify.com/v1/me/playlists', [
            'headers' => $headers,
            'query' => $params
        ]);

        return json_decode($response->getBody(), true);
    }
}
