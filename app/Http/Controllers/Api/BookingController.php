<?php

namespace App\Http\Controllers\Api;

use App\Facades\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\CreateBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function index(): JsonResponse
    {
        $bookings = Service::booking()->getPaginatedBookings();
        return BookingResource::collection($bookings)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $booking = Service::booking()->getBookingById($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(new BookingResource($booking), Response::HTTP_OK);
    }

    public function store(CreateBookingRequest $request): JsonResponse
    {
        $dto = $request->toDTO();
        $booking = Service::booking()->createBooking($dto);
        return (new BookingResource($booking))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateBookingRequest $request, int $id): JsonResponse
    {
        $dto = $request->toDTO();
        $booking = Service::booking()->updateBooking($id, $dto);
        return response()->json(new BookingResource($booking), Response::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        if (Service::booking()->deleteBooking($id)) {
            return response()->json(['message' => 'Booking deleted successfully'], Response::HTTP_NO_CONTENT);
        }
        return response()->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
    }


}
