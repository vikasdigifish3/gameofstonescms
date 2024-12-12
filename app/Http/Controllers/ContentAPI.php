<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Portal;
use Illuminate\Validation\Rule;
use App\Models\Content;
use App\Models\Language;


class ContentAPI extends Controller
{

    public function index(Request $request)
    {
        if(!  $request->has('id') ) {
            $response['error'] = "Invalid Request";
            echo json_encode($response);
            die();
        }
        if( $request-> id==6 && $_SERVER['REMOTE_ADDR'] != '3.111.115.180') {
            // Authentication block
            $response['error'] = "Unauthorised Request";
            echo json_encode($response);
            die();
        }
        if($request->id < 6){
            $response['error'] = "Unauthorised Request";
            echo json_encode($response);
            die();
        }
        $portal_id = $request-> id ;
        $languages = Language::all();
        $language[1] = "EN";
        foreach ($languages as $value) {
            $language[$value->id] = strtoupper($value->shortcode) ;
            
        }
        $portal = Portal::findOrFail($portal_id);
        $categories = $portal->categories()->with('translations')->where('status',1)->get()->toArray();

        $category_data = array();
        foreach ($categories as $category) {
            $id = $category['id'];

            $parent_id = $category['parent_category'];
            $current_Category_data = array();
            $current_Category_data['name'] = $category['name'];
           /* foreach ($category['translations'] as $key => $value) {
                $current_Category_data['translations'][$language[$value['language_id']] ] = $value['name'] ; 
            }
            */

            if($parent_id==0){
                $category_data[$id] = $current_Category_data ;
                
            }else{
                 $category_data[$parent_id]['subcategories'][$id] = $current_Category_data ;
               
            }
            
        }
        //print_r($category_data);die();





        $data = [];
        $contents = $portal->contents()->with('translations')->where('status', '1')->get();
        $contents_data = array();
        foreach ($contents as $content) {
            $current_content_data  = array();
            $current_content_data['category_id'] = $content->category_id ;
            $current_content_data['id'] = $content->id ;
            $current_content_data['name'] = $content->name ;
            $current_content_data['type'] = $content->content_type ;
            $current_content_data['thumb'] = "https://cms.df3.club/".$content->thumb_url ;
            $current_content_data['url'] = empty($content->file_path) ? $content->remote_file_path  : "https://cms.df3.club/".$content->file_path ;


            /*foreach ($content['translations'] as  $value) {
                $current_content_data['translations'][$language[$value['language_id']] ] = $value['name'] ; 
            }
            */
            $contents_data[$content->category_id ][$content->id] = $current_content_data ;
        }
        //print_r($contets_data);

        $data['category_data'] = $category_data ;
        $data['category_wise_contents'] = $contents_data ;
        echo json_encode($data,JSON_UNESCAPED_UNICODE);

    }
    
}

