<?php

namespace Hasob\Scola\Apply\Requests;

use Hasob\Scola\Apply\Requests\AppBaseFormRequest;
use Hasob\Scola\Apply\Models\BroadcastNotification;

class CreateBroadcastNotificationRequest extends AppBaseFormRequest
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
}
