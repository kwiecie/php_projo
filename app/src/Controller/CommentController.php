<?php
/**
 * Comment Controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class CommentController
 * @package App\Controller
 *
 * @Route ("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\CommentRepository         $commentRepository  Comment repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator          Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="comment_index",
     * )
     */
    public function index(Request $request, CommentRepository $commentRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $commentRepository->queryAll(),
            $request->query->getInt('page', 1),
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'comment/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Comment $comment Comment entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="comment_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     */
    public function show(Comment $comment): Response
    {
        return $this->render(
            'comment/show.html.twig',
            ['comment' => $comment]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\CommentRepository         $commentRepository Comment repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/comment",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_create",
     * )
     */
    public function create(Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$comment->setRecipe();
            $comment->setCreatedAt(new \DateTime());
            $commentRepository->save($comment);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('comment_index');
        }

        return $this->render(
            'comment/create.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\Comment                       $comment            Comment entity
     * @param \App\Repository\CommentRepository        $commentRepository Comment repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_delete",
     * )
     */
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
            return $this->redirectToRoute('comment_index');
        }

}