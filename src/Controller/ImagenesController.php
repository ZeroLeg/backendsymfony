<?php
namespace App\Controller;
use App\Repository\ImagenesRepository;
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

    public function __construct(ImagenesRepository $ImagenesRepository)
    {
        $this->ImagenesRepository = $ImagenesRepository;
    }

    /**
     * @Route("imagenes", name="add_imagen", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $url = $data['url'];
        $this->ImagenesRepository->saveImagen($url );

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

        return new JsonResponse($data, Response::HTTP_OK);
    }

}

?>

