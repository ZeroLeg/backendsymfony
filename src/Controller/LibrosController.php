<?php
namespace App\Controller;
use App\Repository\LibrosRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LibrosController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class LibrosController
{
    private $LibrosRepository;

    public function __construct(LibrosRepository $LibrosRepository)
    {
        $this->LibrosRepository = $LibrosRepository;
    }

    /**
     * @Route("libros/importador", name="add_libros", methods={"POST"})
     */
    public function addLibros(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $libros = $data['books'];

        foreach ($libros as $libro) {
            
            $isbn = $libro['isbn'];
            $title = $libro['title'];
            $subtitle = $libro['subtitle'];
            $author = $libro['author'];
            $published = new \DateTime('@'.strtotime($libro['published']));
            $publisher = $libro['publisher'];
            $pages = $libro['pages'];
            $description = $libro['description'];
            $category = $libro['category'];
            $website = $libro['website'];

            $this->LibrosRepository->saveLibro($isbn, $title, $subtitle, $author, $published, $publisher, $pages, $description, $category, $website );

        }

        return new JsonResponse(['status' => 'Libros anyadidos!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("libros", name="add_libro", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $isbn = $data['isbn'];
        $title = $data['title'];
        $subtitle = $data['subtitle'];
        $author = $data['author'];
        $published = new \DateTime('@'.strtotime($data['published']));
        $publisher = $data['publisher'];
        $pages = $data['pages'];
        $description = $data['description'];
        $category = $data['category'];
        $website = $data['website'];



        $this->LibrosRepository->saveLibro($isbn, $title, $subtitle, $author, $published, $publisher, $pages, $description, $category, $website );

        $response = new JsonResponse(['status' => 'Libro anyadido!'], Response::HTTP_CREATED);       
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("libros/{isbn}", name="get_libro", methods={"GET"})
     */
    public function get($isbn): JsonResponse
    {
        $libro = $this->LibrosRepository->findOneBy(['isbn' => $isbn]);

        $data = [
            'isbn' => $libro->getIsbn(), 
            'title' => $libro->getTitle(),
            'subtitle' => $libro->getSubtitle(),
            'author' => $libro->getAuthor(),
            'published' => $libro->getPublished(),
            'publisher' => $libro->getPublisher(),
            'pages' => $libro->getPages(),
            'description' => $libro->getDescription(),
            'category' => $libro->getCategory(),
            'imagenes' => $libro->getImagenes(),

        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("libros/category/{category}", name="get_libro_by_category", methods={"GET"})
     */
    public function getCategory($category): JsonResponse
    {
        $libros = $this->LibrosRepository->findBy(['category' => $category]);

        $data = [
            
        ];


        foreach ($libros as $libro) {
            
            $libro_insertado = [
                'isbn' => $libro->getIsbn(),
                'title' => $libro->getTitle(),
                'subtitle' => $libro->getSubtitle(),
                'author' => $libro->getAuthor(),
                'published' => $libro->getPublished(),
                'publisher' => $libro->getPublisher(),
                'pages' => $libro->getPages(),
                'description' => $libro->getDescription(),
                'category' => $libro->getCategory()
            ];

            array_push($data, $libro_insertado);
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("libros", name="get_all_libros", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $libros = $this->LibrosRepository->findAll();
        $data = [];

        foreach ($libros as $libro) {
            $libro_insertado = [
                'isbn' => $libro->getIsbn(),
                'title' => $libro->getTitle(),
                'subtitle' => $libro->getSubtitle(),
                'author' => $libro->getAuthor(),
                'published' => $libro->getPublished(),
                'publisher' => $libro->getPublisher(),
                'pages' => $libro->getPages(),
                'description' => $libro->getDescription(),
                'category' => $libro->getCategory(),
            ];

            array_push($data, $libro_insertado);
        }
        $response = new JsonResponse($data, Response::HTTP_OK);       
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("libros/{isbn}", name="delete_libros", methods={"DELETE"})
     */
    public function delete($isbn): JsonResponse
    {
        $libro = $this->LibrosRepository->findOneBy(['isbn' => $isbn]);

        $this->LibrosRepository->removeLibro($libro);
        
        $response = new JsonResponse(['status' => 'Libro borrado!'], Response::HTTP_O);       
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("libros/before/{date}", name="get_before_Date", methods={"GET"})
     */
    public function getBeforeDate($date): JsonResponse
    {
        $libros = $this->LibrosRepository->findAll();
        $data = [];
        $date_string = $date.'-01-01';
        $date_before = new \DateTime('@'.strtotime($date_string));

        foreach ($libros as $libro) {


            $libro_insertado = [
                'isbn' => $libro->getIsbn(),
                'title' => $libro->getTitle(),
                'subtitle' => $libro->getSubtitle(),
                'author' => $libro->getAuthor(),
                'published' => $libro->getPublished(),
                'publisher' => $libro->getPublisher(),
                'pages' => $libro->getPages(),
                'description' => $libro->getDescription(),
                'category' => $libro->getCategory()
            ];

            if($libro_insertado['published'] < $date_before)
                array_push($data, $libro_insertado);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("libros/add_img/{isbn}", name="add_img_libro", methods={"POST"})
     */
    public function addImgToLibro($isbn, Request $request): JsonResponse
    {
        
        $libro = $this->LibrosRepository->findOneBy(['isbn' => $isbn]);
        $data = json_decode($request->getContent(), true);

        empty($data['img_id']) ? true : $libro ->setImagenes($data['img_id']);

        $addImg = $this->LibrosRepository->saveImgToLibro( $libro );

        return new JsonResponse(['status' => 'Libro anyadido!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("libros/get_imgs/{isbn}", name="get_imgs", methods={"GET"})
     */
    public function getImgsId($isbn): JsonResponse
    {
        
        $libro = $this->LibrosRepository->findOneBy(['isbn' => $isbn]);
        $data = [];

        $libro_busqueda = [
            'imagenes' => $libro->getImagenesId(),
        ];

        $response = new JsonResponse($libro_busqueda, Response::HTTP_OK);       
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}

?>

