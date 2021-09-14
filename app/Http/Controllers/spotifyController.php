<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;


class spotifyController extends Controller
{
    public function getApiToken()
    { 
        $client = new Client();

        $headers = [
            'Authorization' => 'Basic NGQyODQzOWNhYzY5NGMwZjlkNzNmMjE2NzJiNjA0NDU6NmVjYTc5OWFhMWRjNDI5YTk3MDBiMDcwODY1MDlmYjk=',
            'Content-type' => 'application/x-www-form-urlencoded'
        ];

        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET')
        ];

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
            'scope' => 'playlist-read-private'
        ];

        $response = $client->request('GET', 'https://accounts.spotify.com/authorize', [
            'headers' => $headers,
            'query' => $params
        ]);
        
        return $response;
    }

    public function search(Request $request)
    {
        $client = new Client();
        $input = $request->all();

        $headers = [
            'Authorization' => 'Bearer '.$input['token'],
            'Content-type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $params = [
            'q' => $input['q'],
            'type' => $input['type'],
            'limit' => $input['limit']
        ];

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
