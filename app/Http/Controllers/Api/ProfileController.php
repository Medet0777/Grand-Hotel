<?php
namespace App\Http\Controllers\Api;

use App\Facades\Service;
use App\Http\Controllers\Controller;
use App\Http\DTO\User\UpdateUserDTO;
use App\Http\DTO\User\UploadAvatarDTO;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UploadAvatarRequest;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/users/{userId}/avatar",
     *     tags={"User"},
     *     summary="Upload user avatar",
     *     description="Uploads a new avatar for the specified user.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"avatar", "mime_type", "name"},
     *             @OA\Property(property="avatar", type="string", example="base64encodedstring"),
     *             @OA\Property(property="mime_type", type="string", example="image/jpeg"),
     *             @OA\Property(property="name", type="string", example="avatar.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Avatar uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Avatar uploaded successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to upload avatar"
     *     )
     * )
     */
    public function uploadAvatar(UploadAvatarRequest $request, int $userId): JsonResponse
    {
        try {
            $dto = new UploadAvatarDTO(
                avatar: $request->input('avatar'),
                mime_type: $request->input('mime_type'),
                name: $request->input('name')
            );
            Service::user()->uploadAvatar($dto, $userId);
            return response()->json(['message' => 'Avatar uploaded successfully'], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to upload avatar.'], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/users/{userId}",
     *     tags={"User"},
     *     summary="Get user data with avatar",
     *     description="Returns user's profile data including avatar information (base64).",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User data retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Gustavo"),
     *             @OA\Property(property="nickname", type="string", example="gus123"),
     *             @OA\Property(
     *                 property="avatar",
     *                 type="object",
     *                 nullable=true,
     *                 @OA\Property(property="data", type="string", example="base64encodedstring"),
     *                 @OA\Property(property="mime_type", type="string", example="image/jpeg"),
     *                 @OA\Property(property="name", type="string", example="avatar.jpg")
     *             )
     *         )
     *     )
     * )
     */
    public function getUserData(int $userId): JsonResponse
    {
        $userData = Service::user()->getUserDataWithAvatar($userId);
        return response()->json($userData, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/profile",
     *     summary="Update user profile",
     *     description="Allows the authenticated user to update their name, nickname, and phone number. Email cannot be changed.",
     *     operationId="updateUserProfile",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "nickname", "phone_number"},
     *             @OA\Property(property="name", type="string", example="Gustavo"),
     *             @OA\Property(property="nickname", type="string", example="gus123"),
     *             @OA\Property(property="phone_number", type="string", example="+19003430")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profile updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"phone_number": {"The phone number has already been taken."}}
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */

    public function updateProfile(UpdateUserRequest $request)
    {
        $dto = new UpdateUserDTO(
            name: $request->name,
            nickname: $request->nickname,
            phone_number: $request->phone_number
        );

        $userId = auth()->id();

        Service::user()->updateUser($userId, $dto);

        return response()->json(['message' => 'Profile updated successfully']);
    }
}

