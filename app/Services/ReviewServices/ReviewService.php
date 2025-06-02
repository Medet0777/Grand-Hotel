<?php

namespace App\Services\ReviewServices;

use App\Contracts\ReviewContarcts\ReviewServiceContract;
use App\Facades\Repository;
use App\Http\DTO\Review\CreateReviewDTO;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewService implements ReviewServiceContract
{
    public function createReview(CreateReviewDTO $dto): JsonResponse
    {
        try {
            DB::beginTransaction();

            $review = Repository::review()->create($dto);

            $hotel = Repository::hotel()->findById($dto->hotel_id);
            $hotel->updateAverageRating();

            DB::commit();
            return response()->json(['message' => 'Review created successfully'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating review: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create review: ' . $e->getMessage()], 500);
        }
    }
    public function getReviewsByHotelId(int $hotelId): JsonResponse
    {
        $reviews = Repository::review()->getByHotelId($hotelId);
        $reviewData = $reviews->map(function ($review) {
            $avatarData = null;
            $avatarMimeType = null;
            $avatarName = null;

            if ($review->user->avatar) {
                $avatarData = base64_encode($review->user->avatar->data);
                $avatarMimeType = $review->user->avatar->mime_type;
                $avatarName = $review->user->avatar->name;
            }

            return [
                'id' => $review->id,
                'user' => [
                    'id' => $review->user->id,
                    'name' => $review->user->name,
                    'avatar' => $avatarData ? [
                        'data' => $avatarData,
                        'mime_type' => $avatarMimeType,
                        'name' => $avatarName,
                    ] : null,
                ],
                'hotel_id' => $review->hotel_id,
                'rating' => $review->rating,
                'description' => $review->description,
                'created_at' => $review->created_at,
                'updated_at' => $review->updated_at,
            ];
        });
        return response()->json($reviewData, 200);
    }
}
