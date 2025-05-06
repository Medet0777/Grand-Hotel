<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BookingCreationFailedException;
use App\Exceptions\BookingDeletionFailedException;
use App\Exceptions\BookingUpdateFailedException;
use App\Facades\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\CreateBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\BookingNotFoundException;

class BookingController extends Controller
{
    public function index(): JsonResponse
    {
        $bookings = Service::booking()->getPaginated();
        return BookingResource::collection($bookings)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $booking = Service::booking()->getById($id);
            if (!$booking) {
                throw new BookingNotFoundException();
            }
            return response()->json(new BookingResource($booking), Response::HTTP_OK);
        } catch (BookingNotFoundException $e) {
            return $e->render();
        }
    }

    public function store(CreateBookingRequest $request): JsonResponse
    {
        try {
            $dto = $request->toDTO();
            $booking = Service::booking()->create($dto);
            if (!$booking) {
                throw new BookingCreationFailedException();
            }
            return (new BookingResource($booking))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (BookingCreationFailedException $e) {
            return $e->render();
        }
    }

    public function update(UpdateBookingRequest $request, int $id): JsonResponse
    {
        try {
            $dto = $request->toDTO();
            $booking = Service::booking()->update($id, $dto);
            if (!$booking) {
                throw new BookingUpdateFailedException();
            }
            return response()->json(new BookingResource($booking), Response::HTTP_OK);
        } catch (BookingUpdateFailedException $e) {
            return $e->render();
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            if (Service::booking()->delete($id)) {
                return response()->json(['message' => 'Booking deleted successfully'], Response::HTTP_NO_CONTENT);
            }
            throw new BookingDeletionFailedException();
        } catch (BookingDeletionFailedException $e) {
            return $e->render();
        }
    }


}
