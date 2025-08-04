<?php

namespace Hasob\Scola\Apply\Requests\API;

use Hasob\Scola\Apply\Models\BroadcastNotification;
use Hasob\Scola\Apply\Requests\AppBaseFormRequest;


class UpdateBroadcastNotificationAPIRequest extends AppBaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*
        
        */
        return [
            'organization_id' => 'required',
        'notfiable_type' => 'required|max:191',
        'notfiable_type_id' => 'required|max:191',
        'title' => 'required|max:191',
        'description' => 'required|max:191',
        'is_scheduled' => 'nullable|max:100',
        'schedule_date' => 'nullable|max:100',
        'is_sent' => 'nullable|max:100',
        'status' => 'nullable|max:100',
        'end_date' => 'nullable|max:100',
        'reciever_type' => 'nullable|max:20',
        'reciever_user_ids' => 'nullable|max:20'
        ];
    }

    /**
    * @OA\Property(
    *     title="organization_id",
    *     description="organization_id",
    *     type="string"
    * )
    */
    public $organization_id;

    /**
    * @OA\Property(
    *     title="notfiable_type",
    *     description="notfiable_type",
    *     type="string"
    * )
    */
    public $notfiable_type;

    /**
    * @OA\Property(
    *     title="notfiable_type_id",
    *     description="notfiable_type_id",
    *     type="string"
    * )
    */
    public $notfiable_type_id;

    /**
    * @OA\Property(
    *     title="title",
    *     description="title",
    *     type="string"
    * )
    */
    public $title;

    /**
    * @OA\Property(
    *     title="description",
    *     description="description",
    *     type="string"
    * )
    */
    public $description;

    /**
    * @OA\Property(
    *     title="is_scheduled",
    *     description="is_scheduled",
    *     type="boolean"
    * )
    */
    public $is_scheduled;

    /**
    * @OA\Property(
    *     title="schedule_date",
    *     description="schedule_date",
    *     type="string"
    * )
    */
    public $schedule_date;

    /**
    * @OA\Property(
    *     title="is_sent",
    *     description="is_sent",
    *     type="boolean"
    * )
    */
    public $is_sent;

    /**
    * @OA\Property(
    *     title="status",
    *     description="status",
    *     type="string"
    * )
    */
    public $status;

    /**
    * @OA\Property(
    *     title="end_date",
    *     description="end_date",
    *     type="string"
    * )
    */
    public $end_date;

    /**
    * @OA\Property(
    *     title="reciever_type",
    *     description="reciever_type",
    *     type="string"
    * )
    */
    public $reciever_type;

    /**
    * @OA\Property(
    *     title="reciever_user_ids",
    *     description="reciever_user_ids",
    *     type="string"
    * )
    */
    public $reciever_user_ids;


}
