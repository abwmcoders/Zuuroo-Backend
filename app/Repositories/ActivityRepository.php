<?php
namespace App\Repositories;

use App\Models\Activity;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class ActivityRepository
{
    public function getallActivities()
    {
        return Activity::all();
    }

    public function getActivityByStatus($status)
    {
        return Activity::where('status', $status);
    }

    public function createActivity(array $ActivityDetails)
    {
        return Activity::create($ActivityDetails);
    }

    public function updateActivity($AccId, array $ActivityDetails)
    {
        return Activity::whereId($AccId)->update($ActivityDetails);
    }

    public function deleteActivityRecord($AccId)
    {
        Activity::destroy($AccId);
    }

}