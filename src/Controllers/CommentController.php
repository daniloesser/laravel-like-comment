<?php

namespace risul\LaravelLikeComment\Controllers;

use Carbon\Carbon;
use risul\LaravelLikeComment\Models\Comment;
use Brewme\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brewme\Http\Requests;
use Auth;
use risul\LaravelLikeComment\Models\Like;
use risul\LaravelLikeComment\Models\TotalLike;

class CommentController extends Controller
{
    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function index(){
    	return view('laravelLikeComment::like');
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function add(Request $request){
    	$userId = Auth::user()->id;
    	$parent = $request->parent;
    	$commentBody = $request->comment;
    	$itemId = $request->item_id;

        $user = self::getUser($userId);

	    $userPic = $user['avatar']?$user['avatar']:'/assets/admin/img/avatars/avatar.png';

	    $comment = new Comment;
	    $comment->user_id = $userId;
	    $comment->parent_id = $parent;
	    $comment->item_id = $itemId;
	    $comment->comment = $commentBody;

	    $comment->save();

	    $created =  Carbon::parse($comment->created_at)->format('d/m/Y à\s h:i:s');

	    $id = $comment->id;
    	return response()->json(['flag' => 1, 'id' => $id, 'comment' => $commentBody, 'item_id' => $itemId, 'userName' => $user['name'], 'userPic' => $userPic, 'created'=>$created]);
    }

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author
	 **/
	public function remove(Request $request){
		$userId = Auth::user()->id;

		$itemId = $request->item_id;

		$itemType = $request->item_type;
		if($itemType=="comment"){
			$comment = Comment::where('user_id',$userId)->where('id',$itemId)->first();
			$commentChildren = Comment::where('parent_id',$comment->id)->delete();
			$commentLikes = Like::where('user_id',$userId)->where('item_id',$itemType."-".$comment->id)->delete();
			$commentTotal = TotalLike::where('item_id',$itemType."-".$comment->id)->delete();
			$comment->delete();
			return response()->json(['status'=>'OK']);

		}
	}

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public static function viewLike($id){
        echo view('laravelLikeComment::like')
                ->with('like_item_id', $id);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public static function getComments($itemId){
        $comments = Comment::where('item_id', $itemId)->orderBy('parent_id', 'asc')->orderBy('created_at', 'desc')->get();
        foreach ($comments as $comment){
            $userId = $comment->user_id;
            $user = self::getUser($userId);
            $comment->name = $user['name'];
            $comment->email = $user['email'];
	        $comment->avatar = '/assets/admin/img/avatars/avatar.png';
        }

        return $comments;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public static function getUser($userId){
        $userModel = config('laravelLikeComment.userModel');
        return $userModel::getAuthor($userId);
    }
}
