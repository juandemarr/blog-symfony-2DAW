<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\VideogameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\MainController;

/**
 * @Route("/comments")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_index", methods={"GET"})
     */
    /* public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    } */

    /**
     * @Route("/new/{idVideogame}", name="comment_new", methods={"GET", "POST"})
     */
    public function new($idVideogame, Request $request, EntityManagerInterface $entityManager, VideogameRepository $videogameRepository): Response
    {

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        //No se pueden pasar solo id o valores al set de la entidad, tiene que ser el objeto entero. 
        //Para ello usamos el repositorio y lo obtenemos

        $videogame = $videogameRepository->findOneById($idVideogame);

        if($this->isGranted("ROLE_USER"))
            $author = $this->getUser();
        else
            $author = NULL;

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTimedate(date('d-m-Y'));
            $comment->setIdvideogame($videogame);
            $comment->setAuthor($author);

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('main', [], Response::HTTP_SEE_OTHER);
        }
        //los form tienen id, no se puede poner el mismo varias veces
        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="comment_delete", methods={"POST"})
     */
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('main', [], Response::HTTP_SEE_OTHER);
    }
}
