<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\Type\TeamType;
use AppBundle\Entity\Team;
use AppBundle\Entity\Pokemon;
use AppBundle\Entity\Ability;
use AppBundle\Entity\Type;
use Symfony\Component\Serializer\SerializerInterface;

class TeamController extends Controller
{
    /**
     * Matches /team/create
     *
     * @Route("/team/create", name="team_create_get", methods={"GET"})
     */
    public function createGetAction(Request $request)
    {
        $form = $this->createForm(TeamType::class, new Team(), ['action' => $this->generateUrl('team_create_post'),'method' => 'POST',]);
        return $this->render('team/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Matches /team/create
     *
     * @Route("/team/create", name="team_create_post", methods={"POST"})
     */
    public function createPostAction(Request $request)
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        $values = $form->getData();

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();
            $cachedItem = $this->get('cache.app')->deleteItem('team_list');
        }else{
            die('Error');
        }

        return $this->render('team/details.html.twig', [
                'team' => $team
            ]
        );
    }

    /**
     * Matches /team/edit/*
     *
     * @Route("/team/edit/{id}", name="team_edit_get", methods={"GET"})
     */
    public function editGetAction(int $id)
    {
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id);
        $form = $this->createForm(TeamType::class, $team, ['action' => $this->generateUrl('team_edit_post',['id' => $id]),'method' => 'POST',]);
        if(!$team){die('Error');}
        return $this->render('team/edit.html.twig', [
            'team' => $team, 'form' => $form->createView()
        ]);
    }

    /**
     * Matches /team/edit/*
     *
     * @Route("/team/edit/{id}", name="team_edit_post", methods={"POST"})
     */
    public function editPostAction(int $id, Request $request)
    {
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id);
        if(!$team){die('Error');}
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $values = $form->getData();
            $team->setName($values->getName());
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();
            $this->get('cache.app')->deleteItem('team_list');
            foreach($team->getPokemon() as $pokemon){
                foreach($pokemon->getType() as $type){
                    $this->get('cache.app')->deleteItem('team_list_'.$type->getId());
                }
            }
        }
        $form = $this->createForm(TeamType::class, $team, ['action' => $this->generateUrl('team_edit_post',['id' => $id]),'method' => 'POST',]);
        return $this->render('team/edit.html.twig', [
            'team' => $team, 'form' => $form->createView()
        ]);
    }

    /**
     * Matches /team/catch/*
     *
     * @Route("/team/catch/{id}", name="team_catch")
     */
    public function catchAction(int $id)
    {
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id);
        if($team){
            $newRandomPokemon = $this->getRandomPokemon();
            $pokemon = $this->createPokemon($newRandomPokemon);
            $entityManager = $this->getDoctrine()->getManager();
            $team->addPokemon($pokemon);
            $entityManager->persist($team);
            $entityManager->flush();
            return $this->render('team/catched.html.twig', ['team' => $team, 'pokemon' => $pokemon]);
        }else{
            die('Error');
        }
    }

    /**
     * Matches /team/list
     *
     * @Route("/team/list", name="team_list")
     */
    public function listAction(SerializerInterface $serializer)
    {
        $cacheKey = 'team_list';
        $cachedItem = $this->get('cache.app')->getItem($cacheKey);
        if (false === $cachedItem->isHit()) {
            $teams = $this->getDoctrine()->getRepository(Team::class)->findBy(array(), array('id' => 'DESC'));
            $cachedItem->set($serializer->serialize($teams,'json'), $cacheKey);
            $this->get('cache.app')->save($cachedItem);
        }
        $cachedItem = $this->get('cache.app')->getItem($cacheKey);
        $teams = json_decode($cachedItem->get(),true);
        return $this->render('team/list.html.twig', ['teams' => $teams,'is_filtered' => false]);
    }

    /**
     * Matches /team/filter/*
     *
     * @Route("/team/filter/{id}", name="team_filter")
     */
    public function filterAction(SerializerInterface $serializer, int $id)
    {
        $cacheKey = 'team_list_'.$id;
        $cachedItem = $this->get('cache.app')->getItem($cacheKey);
        if (false === $cachedItem->isHit()) {
            $teams = $this->getDoctrine()->getRepository(Team::class)->findTeamByPokemonType($id);
            $cachedItem->set($serializer->serialize($teams,'json'), $cacheKey);
            $this->get('cache.app')->save($cachedItem);
        }
        $cachedItem = $this->get('cache.app')->getItem($cacheKey);
        $teams = json_decode($cachedItem->get(),true);
        return $this->render('team/list.html.twig', ['teams' => $teams,'is_filtered' => true]);
    }

    protected function getRandomPokemon(){
        $baseUrl = "https://pokeapi.co/api/v2/pokemon";
        $pokemonCount = json_decode(file_get_contents($baseUrl))->count;
        while(true){
            $rand = rand(1,$pokemonCount);
            $content = @file_get_contents($baseUrl . '/' . $rand);
            if($content != false){
                $pokemon = json_decode($content);
                return $pokemon;
            }
        }
    }

    protected function createPokemon($newRandomPokemon){
        $abilities = array_map(function($ability) {
            return $ability->ability->name;
        }, $newRandomPokemon->abilities);

        $types = array_map(function($type) {
            return $type->type->name;
        }, $newRandomPokemon->types);

        $pokemon = $this->getDoctrine()->getRepository(Pokemon::class)->find($newRandomPokemon->id);
        if (!$pokemon) {
            $pokemon = new Pokemon();
            $pokemon->setId($newRandomPokemon->id);
            $pokemon->setName($newRandomPokemon->name);
            $pokemon->setSprite($newRandomPokemon->sprites->front_default);
            $pokemon->setExp($newRandomPokemon->base_experience);
            $entityManager = $this->getDoctrine()->getManager();
            $this->setAbilities($abilities,$pokemon,$entityManager);
            $this->setTypes($types,$pokemon,$entityManager);
            $entityManager->persist($pokemon);
            $entityManager->flush();
            $cachedItem = $this->get('cache.app')->deleteItem('team_list');
        }
        return $pokemon;
    }

    protected function setAbilities($abilities,&$pokemon,&$entityManager){
        foreach($abilities as $a){
            $ability = $this->getDoctrine()->getRepository(Ability::class)->findOneBy(['name' => $a]);
            if (!$ability) {
                $ability = new Ability();
                $ability->setName($a);
            }
            $pokemon->addAbility($ability);
            $entityManager->persist($ability);
            $entityManager->persist($pokemon);
        }
    }

    protected function setTypes($types,&$pokemon,&$entityManager){
        foreach($types as $t){
            $type = $this->getDoctrine()->getRepository(Type::class)->findOneBy(['name' => $t]);
            if (!$type) {
                $type = new Type();
                $type->setName($t);
            }
            $pokemon->addType($type);
            $entityManager->persist($type);
            $this->get('cache.app')->deleteItem('team_list_'.$type->getId());
            $entityManager->persist($pokemon);
        }
    }
}
