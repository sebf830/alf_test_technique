<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\TaskList;
use App\Repository\TaskRepository;
use App\Repository\TaskListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    private FlashyNotifier $flash;
    private EntityManagerInterface $em;
    public function __construct(FlashyNotifier $flash, EntityManagerInterface $em)
    {
        $this->flash = $flash;
        $this->em = $em;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('read-index-lists');
    }

    #[Route('/task/lists', name: 'read-index-lists')]
    public function indexTasks(Request $request, TaskListRepository $taskListRepository): Response
    {
        return $this->render(
            'task/index.html.twig',
            ['taskLists' => $taskListRepository->findBy(['user' => $this->getUser()], ['name' => 'ASC'])]
        );
    }

    #[Route('/task/liste/{id}', name: 'read-list')]
    public function displayTaskList(TaskList $tasklist, TaskRepository $taskRepository): Response
    {
        return $this->render(
            'task/display_Tasks.html.twig',
            ['tasks' => $taskRepository->findBy(['list' => $tasklist->getId()]), 'tasklist' => $tasklist]
        );
    }

    #[Route('/task/new', name: 'create-task')]
    public function createNewTask(Request $request,): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $list = $form->get('list')->getData();
            $task->setList($list);
            $this->em->persist($task);
            $this->em->flush();

            $this->flash->success('Nouvelle tâche crée dans la liste ' . $list->getName(), '');
            return $this->redirectToRoute('read-list', ['id' => $list->getId()]);
        }
        return $this->render('task/task_form_page.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/task/update', name: 'update-task')]
    public function updateTask(Request $request, TaskRepository $taskRepository): Response
    {
        if (!$request->isXmlHttpRequest())
            return new Response('La requete à renvoyé un problème', 400);

        $data = json_decode($request->getContent(), true);

        $task  = $taskRepository->findOneBy(['id' => $data['id']]);
        $task->setTitle($data['title']);
        $this->em->flush();

        return new JsonResponse(json_encode(array('msg' => 'task updated successfully')), 200);
    }

    #[Route('/task/delete/{id}', name: 'delete-task')]
    public function delete_task(Task $task, Request $request): Response
    {
        $this->em->remove($task);
        $this->em->flush();
        $this->flash->success('Tache supprimée avec succès', '');

        return $this->redirect($request->headers->get('referer'));
    }
}
