<?php


namespace App\Http\DTO\User;

use Spatie\DataTransferObject\DataTransferObject;

class UploadAvatarDTO extends DataTransferObject
{
    public string $avatar;
    public string $mime_type;
    public ?string $name;
}
