<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function __construct()    {
        
    }
    public function longest_duration_movies(){
        $response=Movie::orderBy('runtimeMinutes','desc')->limit(10)->get();
        return json_encode($response);
    }
    public function top_rated_movies(){
        $response=DB::table('ratings')
        ->select('movies.tconst','movies.primaryTitle','movies.genres','ratings.averageRating')
        ->join('movies','movies.tconst','=','ratings.tconst')
        ->where('ratings.averageRating','>',6)
        ->orderBy('ratings.averageRating','asc')->get();
        return json_encode($response);
    }
    public function genre_movies_with_subtotals(){
        $response=DB::table('ratings')
        ->select(DB::raw("if(`movies`.`primaryTitle` is null,'',`movies`.`genres`) as Genre"),DB::raw("if(`movies`.`primaryTitle` is null,'TOTAL',`movies`.`primaryTitle`) as primaryTitle"),DB::raw("sum(ratings.numVotes) as numVotes"))
        ->join('movies','movies.tconst','=','ratings.tconst')
        ->groupBy('movies.genres')
        ->groupBy(DB::raw('movies.primaryTitle WITH ROLLUP'))->get();
        return json_encode($response);
    }
    public function new_movie(Request $request){
        $movie=new Movie();
        $movie->tconst=$request->tconst;
        $movie->titleType=$request->titleType;
        $movie->primaryTitle=$request->primaryTitle;
        $movie->runtimeMinutes=$request->runtimeMinutes;
        $movie->genres=$request->genres;
        $movie->save();
        return 'success';
    }
    public function update_runtime_minutes(Request $request){
        if($request->genre=="Documentary"){
            $inc=15;
        }else if($request->genre=="Animation"){
            $inc=30;
        }else{
            $inc=45;
        }
        $result=DB::table('movies')->where('genres',$request->genre)
        ->update(['runtimeMinutes'=>DB::raw("runtimeMinutes+$inc")]);
        $response=Movie::where('genres',$request->genre)->get();
        return  json_encode($response);
    }
}
