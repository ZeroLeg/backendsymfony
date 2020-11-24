<?php
namespace App\Controller;
use App\Repository\ImagenesRepository;
use App\Repository\LibrosRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImagenesController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class ImagenesController
{
    private $ImagenesRepository;

    private $LibrosRepository;

    public function __construct(ImagenesRepository $ImagenesRepository, LibrosRepository $LibrosRepository)
    {
        $this->ImagenesRepository = $ImagenesRepository;
        $this->LibrosRepository = $LibrosRepository;
    }
    /**
     * @Route("imagenes", name="add_imagen", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        
        $isbn = $request->get('isbn');
        $libro = $this->LibrosRepository->findOneBy(['isbn' => $isbn]);

        //return new JsonResponse(['status' => 'Imagen anyadida!'], Response::HTTP_CREATED);
        
        foreach($request->files as $uploadedFile) {
            $name = $uploadedFile->getClientOriginalName();
            $file = $uploadedFile->move('../../frontend/public/assets/', $name); //Path del front
        }

        $this->ImagenesRepository->saveImagen( "assets/".$name );
        $imagenes = $this->ImagenesRepository->findAll();
        $id = $imagenes[sizeof($imagenes)-1]->getId();

        $libro ->setImagenes($id);
        $addImg = $this->LibrosRepository->saveImgToLibro( $libro );

        return new JsonResponse(['status' => 'Imagen anyadida!'], Response::HTTP_CREATED);

    }

    /**
     * @Route("imagenes", name="get_all_imagenes", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $imagenes = $this->ImagenesRepository->findAll();
        $data = [];

        foreach ($imagenes as $imagen) {
            $imagen_insertado = [
                'id' => $imagen -> getId(),
                'url' => $imagen->getUrl()
            ];

            array_push($data, $imagen_insertado);
        }

        $response = new JsonResponse($data, Response::HTTP_OK);       
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("imagenes/{id}", name="get_src_url", methods={"GET"})
     */
    public function getUrl($id): JsonResponse
    {
        $imagen = $this->ImagenesRepository->findOneBy(['id' => $id]);;
        $data = [];

        array_push($data, $imagen->getUrl());

        $response = new JsonResponse($data, Response::HTTP_OK);       
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

}

?>

