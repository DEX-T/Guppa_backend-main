<?php

namespace App\Domain\Interfaces\Testimonial;
use App\Domain\Models\Testimonial;
use App\Domain\Entities\TestimonialEntity;
use App\Domain\DTOs\Response\Testimonial\TestimonialsDto;
use Illuminate\Http\Request;

interface ITestimonialsService



{
    public function getAllTestimonials();
    public function getTestimonial($id);
    public function createTestimonial(Request $request);
    public function updateTestimonial(Request $request, $id);
    public function deleteTestimonial($id);
}