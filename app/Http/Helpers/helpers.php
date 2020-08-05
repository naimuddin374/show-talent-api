<?php
use App\UserModel;

// add reward point
function addRewardPoint($userId, $points){
    if($points){
        $user = UserModel::where('id', $userId)->first();
        if($user){
            $totalPoint = $user->points + $points;
            UserModel::where('id', $userId)->update(['points' => $totalPoint]);
        }
    }
}

// remove reward point
function removeRewardPoint($userId, $points){
    if($points){
        $user = UserModel::where('id', $userId)->first();
        if($user){
            $totalPoint = $user->points - $points;
            UserModel::where('id', $userId)->update(['points' => $totalPoint]);
        }
    }
}

// addRewardPoint($row->user_id, @$post['points']);
// removeRewardPoint($row->user_id, $row->points);