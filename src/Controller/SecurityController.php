<?php

namespace App\Controller;
use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

use Dompdf\Dompdf;
use Dompdf\Options;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Environment $twig )
  { 
    $repository = $this->getDoctrine()
    ->getRepository(Post::class);
    $posts = $repository->findAll();
    
    $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
    $content = $twig->render('home.html.twig',['posts' => $posts , 'tags' => $tags, 'user' =>$this->getUser()]);

    return new Response($content);
  }


  

    /**
     * @Route("/home", name="app_login")
     */

    public function loginAction(Request $request , AuthenticationUtils $authenticationUtils)
{   
     
    $authChecker = $this->container->get('security.authorization_checker');
    $router = $this->container->get('router');
    $error = $authenticationUtils->getLastAuthenticationError();

    if ($authChecker->isGranted('ROLE_ADMIN')) {
        return $this->render('@EasyAdmin/default/layout.html.twig');
    } 

    if ($authChecker->isGranted('ROLE_USER')) {
      $repository = $this->getDoctrine()
    ->getRepository(Post::class);
    $posts = $repository->findAll();
    #$posts = $repository->findBy(['published' => 1]);
    
    $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
        return $this->render('home.html.twig',['posts' => $posts , 'tags' => $tags, 'user'=>$this->getUser()]);
    }
     
     
}

     /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/projet-list", name="app_causes-list")
     */
    public function aff_list(Environment $twig)
    {
      $repositoryPost = $this->getDoctrine()
    ->getRepository(Post::class);
    $repositoryCategory = $this->getDoctrine()
    ->getRepository(Category::class);
    $posts = $repositoryPost->findAll(); 
    #$posts = $repository->findBy(['published' => 1]);
    $categories = $repositoryCategory->findAll(); 
    
    $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
    $content = $twig->render('causes-list.html.twig',['posts' => $posts ,'categories' => $categories ,'cat' => 'tout' ,'tags' => $tags, 'user'=> $this->getUser()]);

    return new Response($content);
    }


        /**
     * @Route("/projet-list-category", name="app_causes-list-category")
     */
    public function aff_list_category(Environment $twig ,Request $request)
    {
    $repositoryPost = $this->getDoctrine()
    ->getRepository(Post::class);
    $repositoryCategory = $this->getDoctrine()
    ->getRepository(Category::class);
    $cat= $request->request->get("categorie");
    if ( $cat == 'tout' )
    {$posts = $repositoryPost->findAll();}
    #$posts = $repository->findBy(['published' => 1]);} 
    else { 
    $catTitle= $this->getDoctrine()
        ->getRepository(Category::class)
        ->findOneBy(['title'=> $cat]);
    $catId= $catTitle ->getId();
    $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->find($catId);
    $posts = $category->getPosts();
    }  
    $categories = $repositoryCategory->findAll(); 
    
    $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
    $content = $twig->render('causes-list.html.twig',['user'=> $this->getUser(),'posts' => $posts ,'categories' => $categories ,'cat' => $cat ,'tags' => $tags ]);

    return new Response($content);
    }

    /**
     * @Route("/projet-grid", name="app_causes-grid")
     */
    public function aff_grid(Environment $twig)
    {
      $repositoryPost = $this->getDoctrine()
    ->getRepository(Post::class);
    $repositoryCategory = $this->getDoctrine()
    ->getRepository(Category::class);
    $posts = $repositoryPost->findAll(); 
    #$posts = $repository->findBy(['published' => 1]);
    $categories = $repositoryCategory->findAll(); 
    
    $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
    $content = $twig->render('causes-grid.html.twig',['user'=> $this->getUser(),
        'posts' => $posts ,'categories' => $categories ,'cat' => 'tout' ,'tags' => $tags]);

    return new Response($content);
    }


     /**
     * @Route("/projet-grid-category", name="app_causes-grid-category")
     */
    public function aff_grid_category(Environment $twig ,Request $request)
    {
    $repositoryPost = $this->getDoctrine()
    ->getRepository(Post::class);
    $repositoryCategory = $this->getDoctrine()
    ->getRepository(Category::class);
    $cat= $request->request->get("categorie");
    if ( $cat == 'tout' )
    {$posts = $repositoryPost->findAll();}
    #$posts = $repository->findBy(['published' => 1]);} 
    else { 
    $catTitle= $this->getDoctrine()
        ->getRepository(Category::class)
        ->findOneBy(['title'=> $cat]);
    $catId= $catTitle ->getId();
    $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->find($catId);
    $posts = $category->getPosts();
    }  
    $categories = $repositoryCategory->findAll();  
    
    $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
    $content = $twig->render('causes-grid.html.twig',['user'=> $this->getUser(),
        'posts' => $posts ,'categories' => $categories ,'cat' => $cat ,'tags' => $tags]);

    return new Response($content);
    }
  
   /**
     * @Route("/projet-profile", name="app_causes-profile")
     */
    public function proj_profile(Environment $twig)
    {
      $repository = $this->getDoctrine()
    ->getRepository(Post::class);  
    $user = $this->getUser();
    $posts = $repository->findBy(['user' => $user ,'published'=>1]); 
    
    $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
    $content = $twig->render('causes-list-profile.html.twig',['user'=> $this->getUser(),
        'posts' => $posts ,'tags' => $tags]);

    return new Response($content);
    }

    /**
     * @Route("/{id}/show-donation", name="post_show_donation")
     * @param Post $post
     * @return Response
     */
    public function show(Post $post): Response
    {
        
      $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
      return $this->render("cause-single-donation.html.twig", [
          'user'=>$this->getUser(),
          "post" => $post ,'tags' => $tags
        ]);
    }

    /**
     * @Route("/{id}/fund", name="app_fund")
     */
    public function app_fund(Environment $twig , $id, Request $request ,\Swift_Mailer $mailer, PublisherInterface $publisher)
    {
      $repository = $this->getDoctrine()
    ->getRepository(Post::class);
    $post = $repository->find($id);
    $title=$post->getTitle();
    $userPost=$post->getUser();
    $emaildest=$userPost->getEmail();
    $amount = $request->request->get("amount");
    $post->setAmount(($post->getAmount())+$amount); 
    $post->setDonors(($post->getDonors())+1);
    $em = $this->getDoctrine()->getManager();
    $em->persist($post);
    $em->flush();
    $user = $this->getUser();
    $nom = $user->getUsername();
    $message = (new \Swift_Message('Contribution sous FundINI'))
            ->setFrom('hamdidallagi7@gmail.com')
            ->setTo($emaildest)
            
            ->setBody("Une contribution de ".$amount."  de la part de MR/MM : ".$nom." en faveur de votre projet sous le titre :".$title);

            $mailer->send($message);
        $update = new Update(
            "http://monsite.com/notif/{$post->getUser()->getId()}",
            json_encode(['montant' => $amount,
                        'sender'=> $this->getUser()->getUsername(),
                        'project'=> $post->getTitle()]),
            false
        );

        // The Publisher service is an invokable object
        $publisher($update);
         

        

    return $this->redirectToRoute('app_pdf',array('id' => $id ,'amount'=>$amount));
    }
    
    /**
     * @Route("/{id}/{amount}/pdf", name="app_pdf")
     */
    public function pdf( Environment $twig , $id, $amount, Post $post)
    {
        
      $user = $this->getUser();
      
      $repository = $this->getDoctrine()
        ->getRepository(Post::class);
        $post = $repository->find($id);  
      
      // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reçu-imp.html.twig', [
            'post' =>$post ,'amount'=>$amount ,'user' =>$user
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();
        
        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/mypdf.pdf';
        
        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);
        
        // Send some text response

        $repositoryTag = $this->getDoctrine()
       ->getRepository(Tag::class);
        $tags = $repositoryTag->findAll();
        $content = $twig->render('reçu.html.twig', [
          'post' => $post ,'amount'=>$amount ,'user' =>$user ,'tags' => $tags
        ]);

        return new Response($content);
    }

  /**
     * @Route("/mail", name="app_mail")
     */
   
    public function mail( \Swift_Mailer $mailer)
{

    $user = $this->getUser();
    $email=$user->getEmail();
    $message = (new \Swift_Message('Mécénat'))
        ->setFrom('hamdidallagi7@gmail.com')
        ->setTo($email)
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'email.html.twig'
                 
            ),
            'text/html'
        )
        ->attach(\Swift_Attachment::fromPath('mypdf.pdf'))

        
         
    ;

    $mailer->send($message);

    return $this->redirectToRoute('app_home');
}

/**
     * @Route("/contact", name="app_contact")
     */
   
    public function contact( Environment $twig)
{
    $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
    $content = $twig->render('contact.html.twig',['tags' => $tags, 'user'=> $this->getUser()]);

    return new Response($content);
} 

  /**
     * @Route("/contact-mail", name="app_contact_mail")
     */
   
    public function contact_mail( \Swift_Mailer $mailer, Request $request)
{
      $nom = $request->request->get("name");
      $email = $request->request->get("email");
      $sujet = $request->request->get("sujet");
      $text = $request->request->get("message");
      $message = (new \Swift_Message($sujet))
        ->setFrom('hamdidallagi7@gmail.com')
        ->setTo($email)
         
        ->setBody("Mr/Mm ".$nom." nous écrit: ".$text)    
         
    ;

    $mailer->send($message);

    return $this->redirectToRoute('app_home');
}
  

     /**
     * @Route("/tag/{tag}", name="app_tag")
     */
    public function aff_tag(Environment $twig , $tag)
    {
     
     
     
    $TagTitle= $this->getDoctrine()
        ->getRepository(Tag::class)
        ->findOneBy(['name'=> $tag]);
    $TagId= $TagTitle ->getId();
    $TagPost = $this->getDoctrine()
        ->getRepository(Tag::class)
        ->find($TagId);
    $posts = $TagPost->getPosts();
    $repositoryCategory = $this->getDoctrine()
    ->getRepository(Category::class); 
    $categories = $repositoryCategory->findAll();  
    $cat='tout';
    $repositoryTag = $this->getDoctrine()
    ->getRepository(Tag::class);
    $tags = $repositoryTag->findAll();
    $content = $twig->render('causes-grid.html.twig',['user'=>$this->getUser(), 'posts' => $posts ,'categories' => $categories ,'cat' => $cat ,'tags' => $tags]);

    return new Response($content);
    }
   

}
