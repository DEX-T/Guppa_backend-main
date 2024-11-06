<?php

namespace App\Domain\Interfaces\Testimonial;
use App\Models\TestimonialCard;
use Illuminate\Http\Request;


interface ITestimonialCardService
{   

    public function getAllTestimonialCards();
    public function gettestimonialcard_id($id);
    
    public function createTestimonialCard(Request $request);
    public function updateTestimonialCard(Request $request, $id);
    public function deleteTestimonialCard($id);
    // Define your service interface methods here
}