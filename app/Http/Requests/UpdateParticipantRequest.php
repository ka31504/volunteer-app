<?php

namespace App\Http\Requests;

class UpdateParticipantRequest extends StoreParticipantRequest
{
    // Tái sử dụng toàn bộ rules từ Store
    // Override nếu cần thay đổi rule khi update (vd: unique ignore)
}