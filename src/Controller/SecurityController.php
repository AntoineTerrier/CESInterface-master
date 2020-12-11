<?php

namespace App\Controller;

use App\Security\User;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($request->request->count() > 0) {
            $client = HttpClient::create();
            $user = new User();
            $hash = $encoder->encodePassword($user, $request->request->get('password')); 
            try {
                $response = $client->request('POST', 'http://localhost:3000/users/register/', [
                    'body' => [
                        'email' => $request->request->get('email'),
                        'password' => $hash,
                        'firstname' => $request->request->get('firstname'),
                        'lastname' => $request->request->get('lastname'),
                        'campus' => $request->request->get('campus')
                    ]
                ]);
            } catch (\Throwable $th) {
                throw $th;
            }
            if ($response->getStatusCode() === 200) {
                return $this->redirectToRoute('login');
            }
        }
        return $this->render('security/register.html.twig');
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}
}
