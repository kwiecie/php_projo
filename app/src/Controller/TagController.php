<?php

/**
 * Tag Controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Form\TagType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController.
 *
 * @Route("/tag")
 */
class TagController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\TagRepository             $tagRepository Tag repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator          Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="tag_index",
     * )
     */
    public function index(Request $request, TagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $tagRepository->queryAll(),
            $request->query->getInt('page', 1),
            TagRepository::PAGINATOR_ITEMS_PER_PAGE
        );
        return $this->render('tag/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Tag $tag Tag entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="tag_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', ['tag' => $tag]);
    }
    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\TagRepository             $tagRepository Tag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="tag_create",
     * )
     */
    public function create(Request $request, TagRepository $tagRepository): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        //$category->setCreatedAt(new \DateTime());
            //$category->setUpdatedAt(new \DateTime());
            $tagRepository->save($tag);
            $this->addFlash('success', 'message_created_successfully');
            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/create.html.twig', ['form' => $form->createView()]);
    }
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\Tag                           $tag           Tag entity
     * @param \App\Repository\TagRepository             $tagRepository Tag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="tag_edit",
     * )
     */
    public function edit(Request $request, Tag $tag, TagRepository $tagRepository): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        //$category->setUpdatedAt(new \DateTime());
            $tagRepository->save($tag);
            $this->addFlash('success', 'message_updated_successfully');
            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/edit.html.twig', [
                'form' => $form->createView(),
                'tag' => $tag,
            ]);
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\Tag                           $tag           Tag entity
     * @param \App\Repository\TagRepository             $tagRepository Tag repository
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
     *     name="tag_delete",
     * )
     */
    public function delete(Request $request, Tag $tag, TagRepository $tagRepository): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'DELETE']);
        $form->handleRequest($request);
        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository->delete($tag);
            $this->addFlash('success', 'message.deleted_successfully');
            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/delete.html.twig', [
                'form' => $form->createView(),
                'tag' => $tag,
            ]);
    }
}
