<?php

namespace Nuxia\ValuelistBundle\Controller;

use Nuxia\ValuelistBundle\Entity\Valuelist;
use Nuxia\ValuelistBundle\Form\Handler\ValuelistFormHandler;
use Nuxia\ValuelistBundle\Manager\AdminValuelistManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdminController
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var FlashBagInterface
     */
    protected $flashBag;

    /**
     * @var AdminValuelistManagerInterface
     */
    protected $valuelistManager;

    /**
     * @var ValuelistFormHandler
     */
    protected $valuelistFormHandler;

    /**
     * @param EngineInterface $templating
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @param UrlGeneratorInterface $router
     */
    public function setRouter(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param FlashBagInterface $flashBag
     */
    public function setFlashBag(FlashBagInterface $flashBag = null)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * @param AdminValuelistManagerInterface $valuelistManager
     */
    public function setValuelistManager(AdminValuelistManagerInterface $valuelistManager)
    {
        $this->valuelistManager = $valuelistManager;
    }

    /**
     * @param ValuelistFormHandler $valuelistFormHandler
     */
    public function setValuelistFormHandler(ValuelistFormHandler $valuelistFormHandler)
    {
        $this->valuelistFormHandler = $valuelistFormHandler;
    }

    /**
     * @param  string
     *
     * @return ParameterBag
     */
    protected function initControllerBag($category)
    {
        return new ParameterBag(array('category' => $category));
    }

    /**
     * @param  string $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function indexAction($category)
    {
        $parameters = $this->initControllerBag($category);
        $parameters->set('valuelist', $this->valuelistManager->getRepository()->findByCriteria(array('category' => $category)));

        return $this->templating->renderResponse('NuxiaValuelistBundle:Admin:index.html.twig', $parameters->all());
    }

    /**
     * @param  string $category
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction($category)
    {
        $parameters = $this->initControllerBag($category);
        if ($this->valuelistFormHandler->process()) {
            $this->flashBag->add('success', 'valuelist.' . $category . '.new.success');

            return $this->redirectAction($this->valuelistFormHandler->getForm()->getData()->getCategory());
        }

        $parameters->set('form', $this->valuelistFormHandler->getForm()->createView());

        return $this->templating->renderResponse('NuxiaValuelistBundle:Admin:new.html.twig', $parameters->all());
    }

    /**
     * @param  Request $request
     * @param  int     $id
     * @param  string  $category
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, $id, $category)
    {
        $parameters = $this->initControllerBag($request);
        $valuelist = $this->getValuelist(array('id' => $id));

        if ($this->valuelistFormHandler->process($valuelist)) {
            $this->flashBag->add('success', 'valuelist.' . $category . '.edit.success');

            return $this->redirectAction($valuelist->getCategory());
        }
        $parameters->set('form', $this->valuelistFormHandler->getForm()->createView());

        return $this->templating->renderResponse('NuxiaValuelistBundle:Admin:edit.html.twig', $parameters->all());
    }

    /**
     * @param  int     $id
     * @param  string  $category
     *
     * @return RedirectResponse
     */
    public function deleteAction($id, $category)
    {
        $valuelist = $this->getValuelist($id);
        $this->valuelistManager->delete($valuelist);
        $this->flashBag->add('success', 'valuelist.' . $category . '.delete.success');

        return $this->redirectAction($category);
    }

    /**
     * @param  string $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navAction($category)
    {
        $parameters = $this->initControllerBag($category);
        $parameters->set('categories', array_keys($this->valuelistManager->getCategories()));

        return $this->templating->renderResponse('NuxiaValuelistBundle:Admin:nav.html.twig', $parameters->all());
    }

    /**
     * @param  array  $criteria
     *
     * @throws NotFoundHttpException
     *
     * @return Valuelist
     */
    protected function getValuelist($id)
    {
        $valuelist = $this->valuelistManager->getRepository()->find($id);

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
