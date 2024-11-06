<?php
namespace App\Domain\Interfaces\SupportTicket;

use Illuminate\Http\Request;

interface ISupportTicketService
{

    public function createSupportTicket(Request $request);
    public function getAllSupportTicket();
    public function getSupportTicketbyId(int $id);
    public function updateSupportTicket(Request $request);
    public function deleteSupportTicket(int $id);

    public function getAllMySupportTicket();
    public function resolveTicket(int $ticketId);
    public function closeTicket(int $ticketId);


}
