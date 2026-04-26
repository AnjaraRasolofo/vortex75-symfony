<?php

namespace App\Controller\Admin;

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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Update;


final class ChatController extends AbstractController
{
    #[Route('/admin/messages', name: 'admin_messages')]
    public function messages(): Response
    {
        return $this->render('admin/chat/conversation.html.twig', [
            'title' => 'Conversation',  
        ]);
    }

   #[Route('/admin/chat', name: 'admin_chat_list', methods: ['GET'])]
    public function list(ConversationRepository $repo): JsonResponse
    {
        $conversations = $repo->findBy(
            [],
            ['updatedAt' => 'DESC']
        );

        

        foreach ($conversations as $c) {
            $messages = $c->getMessages();

            $lastMessage = $messages->isEmpty()
            ? null
            : $messages->last();

            $data[] = [
                'id' => $c->getId(),
                'customer' => $c->getCustomer()->getUserIdentifier(),
                'status' => $c->getStatus(),
                'updatedAt' => $c->getUpdatedAt()?->format('Y-m-d H:i'),
                'lastMessage' => $lastMessage?->getContent(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/admin/chat/{id}', name: 'admin_chat_show', methods: ['GET'])]
    public function show(Conversation $conversation): JsonResponse
    {
        $messages = [];

        foreach ($conversation->getMessages() as $m) {
            $messages[] = [
                'id' => $m->getId(),
                'content' => $m->getContent(),
                'sender' => $m->getSender()->getFirstname(),
                'isAdmin' => in_array('ROLE_ADMIN', $m->getSender()->getRoles()),
                'createdAt' => $m->getCreatedAt()->format('H:i'),
            ];
        }

        return $this->json([
            'conversationId' => $conversation->getId(),
            'messages' => $messages
        ]);
    }

    #[Route('/admin/chat/{id}/send', name: 'admin_chat_send', methods: ['POST'])]
    public function send(
        Conversation $conversation,
        Request $request,
        EntityManagerInterface $em,
        HubInterface $hub
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $admin = $this->getUser();

        $message = new Message();
        $message->setConversation($conversation);
        $message->setSender($admin);
        $message->setContent($data['content']);
        $message->setCreatedAt(new \DateTimeImmutable());

        $em->persist($message);

        $conversation->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        try {
            $hub->publish(new Update(
            sprintf('conversation/%d', $conversation->getId()),
            json_encode([
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'senderId' => $admin,
                'createdAt' => $message->getCreatedAt()->format('H:i'),
            ])
        ));
        } catch (\Exception $e) {
            dump($e->getMessage());
            die();
        }

        return $this->json(['success' => true]);
    }

}
