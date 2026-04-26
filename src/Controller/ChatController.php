<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Mercure\Update;


final class ChatController extends AbstractController
{
    // src/Controller/ChatController.php
   #[Route('/chat/open', name: 'app_chat_open')]
   public function open(EntityManagerInterface $em, ConversationRepository $repo, JWTTokenManagerInterface $jwtManager): JsonResponse
   {
       $user = $this->getUser();
       $conversation = $repo->findOneBy(['customer' => $user, 'status' => 'open']);
       if (!$conversation) {
           $conversation = new Conversation();
           $conversation->setCustomer($user);
           $conversation->setSubject('Nouvelle conversation');
           $conversation->setStatus('open');
           $conversation->setCreatedAt(new \DateTimeImmutable());
           $conversation->setUpdatedAt(new \DateTimeImmutable());
           $em->persist($conversation);
           $em->flush();
       }

       //$token = $this->generateJwtTokenForUser($user);
       $token = $jwtManager->create($user);
        //dump($user); die;
       return $this->json([
           'conversationId' => $conversation->getId(),
           'jwtToken' => $token, // Envoyer le token au frontend
       ]);
   }

   #[Route('/chat/{id}/messages', name: 'app_chat_messages', methods: ['GET'])]
    public function messages(Conversation $conversation): JsonResponse
    {
        $messages = [];
        $currentUser = $this->getUser();

        foreach ($conversation->getMessages() as $message) {
            $messages[] = [
                'id' => $message->getId(),
                'text' => $message->getContent(),
                'fromUser' => $message->getSender()->getId() === $currentUser,
                'createdAt' => $message->getCreatedAt()->format('H:i'),
                'isAdmin' => in_array('ROLE_ADMIN', $message->getSender()->getRoles()),
                'senderName' => $message->getSender()->getFirstname(),
            ];
        }

        return $this->json($messages);
    }

   #[Route('/chat/{id}/send', name: 'app_message_send', methods: ['POST'])]
   public function send(Conversation $conversation, Request $request, EntityManagerInterface $em, HubInterface $hub): JsonResponse
   {
       $user = $this->getUser();
       
       $data = json_decode($request->getContent(), true);

        if (!isset($data['content']) || !is_string($data['content']) || trim($data['content']) === '') {
            return $this->json(['error' => 'Invalid content'], 400);
        }

       $message = new Message();
       $message->setSender($user);
       $message->setContent($data['content']);
       $message->setConversation($conversation);
       $message->setCreatedAt(new \DateTimeImmutable());
       $message->setIsRead(false);
       $conversation->setUpdatedAt(new \DateTimeImmutable());
       $em->persist($message);
       
       $em->flush();
       
       

       $topic = sprintf('conversation/%d', $conversation->getId());
       $update = new Update($topic, json_encode([
           'id' => $message->getId(),
           'content' => $message->getContent(),
           'createdAt' => $message->getCreatedAt()->format('H:i'),
           'senderId' => $user,
       ]));

       try {
            $hub->publish($update);
        } catch (\Exception $e) {
            dump($e->getMessage());
            die;
        }

       $hub->publish($update);

       return $this->json(['success' => true]);
   }
}
