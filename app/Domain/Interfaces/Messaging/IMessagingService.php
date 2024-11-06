<?php

namespace App\Domain\Interfaces\Messaging;


use Illuminate\Http\Request;

interface IMessagingService
{
    public function getMessage(int $chatId);
    public function getMyChats();
    public function sendMessage(Request $request);

    public function sendAttachment(Request $request);
    public function initiateChat(Request $request);

    public function deleteChat(int $chatId);
    public function deleteMessage(int $messageId);
    public function getLatestMessage();
}
