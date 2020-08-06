<?php
use App\UserModel;
use App\PageModel;

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

// addRewardPoint($row->user_id, @$post['points']);
// removeRewardPoint($row->user_id, $row->points);