<?php

namespace Nuxia\ValuelistBundle\Controller;

use Nuxia\ValuelistBundle\Form\Handler\ValuelistFormHandler;
use Nuxia\ValuelistBundle\Manager\AdminValuelistManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminController extends AbstractController
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var AdminValuelistManagerInterface
     */
    protected $valuelistManager;

    /**
     * @var PaginatorInterface
     */
    protected $valuelistPaginator;

    /**
     * @var ValuelistFormHandler
     */
    protected $valuelistFormHandler;

    /**
     * @var array
     */
    protected $categories;

    /**
     * @param AdminValuelistManagerInterface $valuelistManager
     */
    public function setValuelistManager(AdminValuelistManagerInterface $valuelistManager)
    {
        $this->valuelistManager = $valuelistManager;
    }

    /**
     * @param PaginatorInterface $valuelistPaginator
     */
    public function setValuelistPaginator(PaginatorInterface $valuelistPaginator)
    {
        $this->valuelistPaginator = $valuelistPaginator;
    }

    /**
     * @param ValuelistFormHandler $valuelistFormHandler
     */
    public function setValuelistFormHandler(ValuelistFormHandler $valuelistFormHandler)
    {
        $this->valuelistFormHandler = $valuelistFormHandler;
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return ParameterBag
     */
    protected function initControllerBag($parameters = array())
    {
        $parameters = array_merge(array('category' => $this->request->attributes->get('category'), $parameters));

        return new ParameterBag($parameters);
    }

    /**
     * @param $category
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($category)
    {
        $parameters = $this->initControllerBag();
        $parameters->set('paginator', $this->valuelistPaginator->createPaginator(array('category' => $category)));

        return $this->templating->renderResponse('NuxiaValuelistBundle:Admin:index.html.twig', $parameters->all());
    }

    /**
     * @param $category
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, $category)
    {
        $parameters = $this->initControllerBag();
        $this->valuelistFormHandler->process();
        $form = $this->valuelistFormHandler->getForm();
        if ($form->isValid()) {
            $request->getSession()->getFlashBag()->add('success', 'valuelist.' . $category . '.new.success');
            return $this->redirectAction($form->getData()->getCategory());
        }

        $parameters->set('form', $form->createView());

        return $this->templating->renderResponse('NuxiaValuelistBundle:Admin:new.html.twig', $parameters->all());
    }

    /**
     * @param $id
     * @param $category
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id, $category)
    {
        $parameters = $this->initControllerBag();
        $valuelist = $this->getValuelist(array('id' => $id, 'category' => $category), 'edit');

        if ($this->valuelistFormHandler->process($valuelist)) {
            $request->getSession()->getFlashBag()->add('success', 'valuelist.' . $category . '.edit.success');
            return $this->redirectAction($valuelist->getCategory());
        }
        $parameters->set('form', $this->valuelistFormHandler->getForm()->createView());

        return $this->templating->renderResponse('NuxiaValuelistBundle:Admin:edit.html.twig', $parameters->all());
    }

    /**
     * @param $id
     * @param $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function deleteAction(Request $request, $id, $category)
    {
        $valuelist = $this->getValuelist(array('id' => $id, 'category' => $category), 'delete');
        $this->valuelistManager->delete($valuelist);
        $request->getSession()->getFlashBag()->add('success', 'valuelist.' . $category . '.delete.success');

        return $this->redirectAction($category);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navAction()
    {
        $parameters = $this->initControllerBag();
        $parameters->set('categories', array_keys($this->categories));

        return $this->templating->renderResponse('NuxiaValuelistBundle:Admin:nav.html.twig', $parameters->all());
    }

    /**
     * @param  array $criteria
     * @param $type
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function getValuelist(array $criteria, $type)
    {
        $valuelist = $this->valuelistManager->getControllerObject($criteria, $type);
        if ($valuelist === null) {
            throw new NotFoundHttpException();
        }
        return $valuelist;
    }

    /**
     * @param string $category
     *
     * @return RedirectResponse
     */
    public function redirectAction($category)
    {
        return new RedirectResponse($this->router->generate('valuelist_index', array('category' => $category)));
    }
}
