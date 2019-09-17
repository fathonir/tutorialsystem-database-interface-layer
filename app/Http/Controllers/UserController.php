<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\UserModel;
use App\Traits\DndbQuery;

class UserController extends Controller
{
    // Include Trait inside Controller
    use DndbQuery;

    /**
     * @param UserModel $model
     * @param Request $request
     */
    public function index(UserModel $model, Request $request)
    {
        $count = $model->count();

        if($count == 0) {
            $message    = ', no data found with this query';
            $result     = [];
        } else {
            $message    = ', data has been found';
            $result     = $model->all();
        }

        return response()->json([
            'status' => [
                'code'      => '200',
                'message'   => 'index list query has been performed'. $message,
                'total'     => $count,
            ],
            'result' => $result,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'email'             => 'required',
            'first_name'        => 'required',
            'last_name'         => 'required',
            'birth_date'        => 'required',
            'gender'            => 'required',
        ]);

        $id             = $request->input('id');
        $name           = $request->input('name');
        $email          = $request->input('email');
        $first_name     = $request->input('first_name');
        $last_name      = $request->input('last_name');
        $birth_date     = $request->input('birth_date');
        $gender         = $request->input('gender');
        $photo_profile  = $request->input('photo_profile') ?? null;
        $about          = $request->input('about') ?? null;
        $website_link   = $request->input('website_link') ?? null;
        $facebook_link  = $request->input('facebook_link') ?? null;
        $twitter_link   = $request->input('twitter_link') ?? null;
        $linkedin_link  = $request->input('linkedin_link') ?? null;
        $following      = $request->input('following') ?? null;
        $follower       = $request->input('follower') ?? null;
        $like_video     = $request->input('like_video') ?? null;
        $dislike_video  = $request->input('dislike_video') ?? null;
        $saved_video    = $request->input('saved_video') ?? null;
        $history_video  = $request->input('history_video') ?? null;
        $playlists      = $request->input('playlists') ?? null;


        $query                  = new UserModel();
        $query->id              = $id ? $id : Str::uuid()->toString();
        $query->name            = $name;
        $query->email           = $email;
        $query->first_name      = $first_name; 
        $query->last_name       = $last_name; 
        $query->birth_date      = $birth_date; 
        $query->gender          = $gender; 
        $query->photo_profile   = $photo_profile; 
        $query->about           = $about;
        $query->website_link    = $website_link; 
        $query->facebook_link   = $facebook_link; 
        $query->twitter_link    = $twitter_link; 
        $query->linkedin_link   = $linkedin_link; 
        $query->following       = $following; 
        $query->follower        = $follower; 
        $query->like_video      = $like_video; 
        $query->dislike_video   = $dislike_video; 
        $query->saved_video     = $saved_video; 
        $query->history_video   = $history_video; 
        $query->playlists       = $playlists; 
        $query->save();

        return response()->json([
            'status' => [
                'code'      => '200',
                'message'   => 'data has been saved',
            ],
            'result' => [
                'id' => $query->id,
            ],
        ], 200);
    }

    public function show(UserModel $model, string $id)
    {
        return $model->findOrFail($id);
    }

    public function search(UserModel $model, Request $request)
    {
        $this->validate($request, [
            'query' => 'required',
        ]);

        $query = $request->input('query');

        // Parse Function From Trait DndbQuery
        $query_eloquent = $this->ReqParse($query);

        // Improve Query Builder Search
        $query = null;
        foreach($query_eloquent as $key => $value) {
            $query = $model->where($key, 'contains', $value);
        }

        // Performe Query
        $count = $query->count();

        if($count == 0) {
            $message    = ', no data found with this query';
            $result     = [];
        } else {
            $message    = ', data has been found';
            $result     = $query->get();
        }

        return response()->json([
            'status' => [
                'code'      => '200',
                'message'   => 'search query has been performed'. $message,
                'total'     => $count,
            ],
            'result' => $result,
        ], 200);
    }

    public function update(UserModel $model, Request $request, string $id)
    {
        $query = $model->findOrFail($id);
        $query->update($request->all());

        return response()->json([
            'status' => [
                'code'      => '200',
                'message'   => 'data has been updated',
            ],
            'result' => [
                'id' => $query->id,
            ],
        ], 200);
    }

    public function delete(UserModel $model, string $id)
    {
        $query = $model->findOrFail($id);
        $query->delete();

        return response()->json([
            'status' => [
                'code'      => '200',
                'message'   => 'data has been deleted',
            ],
        ], 200);
    }
}