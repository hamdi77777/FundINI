<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
     
    /**
     * @Route("/post", name="post_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request  , AuthenticationUtils $authenticationUtils ,\Swift_Mailer $mailer): Response
    {
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        $error = $authenticationUtils->getLastAuthenticationError();
    
        if ($authChecker->isGranted('ROLE_USER')) {
        
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('thumbnail')->getData(); 
            $cat =$form->get('category')->getData();
            $titre =$form->get('title')->getData();
            $post->setCategory($cat);
            $post->setThumbnailFile($file); 
            $user = $this->getUser();
            $post->setUser($user);
            $author=$user->getUsername();
            $post->setAuthor($author);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $message = (new \Swift_Message('demande de publication du projet'))
            ->setFrom('hamdidallagi7@gmail.com')
            ->setTo('hamdidallagi7@gmail.com')
            
            ->setBody("Mr/Mm ".$author."  veut publier un projet sous le titre: ".$titre);

            $mailer->send($message);

           
            return $this->redirectToRoute('app_home');
        }
        $repositoryTag = $this->getDoctrine()
        ->getRepository(Tag::class);
        $tags = $repositoryTag->findAll();
        return $this->render('post.html.twig', [
            "form" => $form->createView(),'tags' => $tags, 'user'=>$this->getUser()
        ]);
        }
        else {
            return $this->redirectToRoute('fos_user_security_login');
        }

    }

    /**
     * @Route("post/{id}/edit", name="post_edit")
     * @param Post $post
     * @param Request $request
     * @return Response
     */
    public function edit(Post $post, Request $request): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_causes-profile');
        }
        $repositoryTag = $this->getDoctrine()
        ->getRepository(Tag::class);
        $tags = $repositoryTag->findAll();
        return $this->render("edit.html.twig", [
            "form" => $form->createView() ,'tags' => $tags, 'user'=>$this->getUser()
        ]);
    }

    /**
     * @Route("/post/{id}/delete", name="post_delete")
     * @param Post $post
     * @return RedirectResponse
     */
    public function delete(Post $post): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute("app_causes-profile");
    }

    /**
     * @Route("/{id}/show", name="post_show")
     * @param Post $post
     * @return Response
     */
    public function show(Post $post): Response
    {
        $repositoryTag = $this->getDoctrine()
        ->getRepository(Tag::class);
        $tags = $repositoryTag->findAll();
        return $this->render("cause-single-profile.html.twig", [
            "post" => $post ,'tags' => $tags, 'user'=>$this->getUser()
        ]);
    }
     

     
 

     
}