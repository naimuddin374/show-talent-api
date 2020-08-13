<?php
use App\UserModel;
use App\PageModel;
use App\PreferenceModel;
use App\FollowerModel;

// add reward point
function addRewardPoint($userId, $pageId, $points){
    if($points){
        if($pageId > 0){
            $page = PageModel::where('id', $pageId)->first();
            if($page){
                $totalPoint = $page->points + $points;
                PageModel::where('id', $pageId)->update(['points' => $totalPoint]);
            }
        }else{
            $user = UserModel::where('id', $userId)->first();
            if($user){
                $totalPoint = $user->points + $points;
                UserModel::where('id', $userId)->update(['points' => $totalPoint]);
            }
        }
    }
}

// remove reward point
function removeRewardPoint($userId, $pageId, $points){
    if($points){
        if($pageId > 0){
            $page = PageModel::where('id', $pageId)->first();
            if($page){
                $totalPoint = $page->points - $points;
                PageModel::where('id', $pageId)->update(['points' => $totalPoint]);
            }
        }else{
            $user = UserModel::where('id', $userId)->first();
            if($user){
                $totalPoint = $user->points - $points;
                UserModel::where('id', $userId)->update(['points' => $totalPoint]);
            }
        }
    }
}

function getUserPreference(){
    $auth = auth()->user();
    $userId = $auth['id'];
    $preference = PreferenceModel::with(['category'])->where('user_id', $userId)->get();
    $categories = [];
    if($preference){
        foreach($preference as $value){
            array_push($categories, $value->category_id);
        }
    }
    return $categories;
}

function getFollowProfile(){
    $auth = auth()->user();
    $userId = $auth['id'];
    $sel_data = FollowerModel::where(['user_id' => $userId, 'is_page' => 0])->get();
    $profileIds = [];
    if($sel_data){
        foreach($sel_data as $value){
            array_push($profileIds, $value->profile_id);
        }
    }
    return $profileIds;
}

function getFollowPage(){
    $auth = auth()->user();
    $userId = $auth['id'];
    $sel_data = FollowerModel::where(['user_id' => $userId, 'is_page' => 1])->get();
    $profileIds = [];
    if($sel_data){
        foreach($sel_data as $value){
            array_push($profileIds, $value->profile_id);
        }
    }
    return $profileIds;
}

// addRewardPoint($row->user_id, @$post['points']);
// removeRewardPoint($row->user_id, $row->points);