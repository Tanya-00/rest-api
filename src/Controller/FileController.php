<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Exception;

/**
 * @Route("/files")
 */

class FileController extends AbstractController
{
    /**
     * @Route("/upload", name="file", methods={"POST"})
     */
    public function upload(Request $request, FileRepository $fileRepository): Response
    {
        $data = $request->files->get('file');

        if($data) {
            $fileName = $this->getParameter('kernel.project_dir') . '/public/uploads';

            $file = new File();
            $file->setFileName($fileName);
            $file->setOriginalName($data->getClientOriginalName());

            $ent = $this->getDoctrine()->getManager();
            $ent->persist($file);
            $ent->flush();

            return $this->json([
                'status'=>200,
                'message'=>"The file upload was successful"
            ]);
        }

        return $this->json ([
            'status'=>400,
            'message'=>"Incorrect data"
        ]);
    }

    /**
     * @Route("/getfiles", name="getfile", methods={"GET"})
     */
    public function getFiles(Request $request, FileRepository $fileRepository) : Response
    {
        try {
            $fileList = $fileRepository->findAll();
            $fileResult = [];
            foreach ($fileList as $file) {
                $fileResult[] = [
                    'fileName' => $file->getOriginalName()
                ];
            }
            return $this->json ([
                'status'=>200,
                'files'=>$fileResult
            ]);
        } catch (Exception $error) {
            return $this->json ([
                'status'=>500,
                'message'=>"Something went wrong"
            ]);
        }

    }

    /**
     * @Route("/download", name="download", methods={"GET"})
     */
    public function downloadFile(Request $request, $originalName) : Response
    {
        try {
            $res = $this->getParameter('kernel.project_dir') . '/public/uploads'.$originalName;
            return new BinaryFileResponse($res);
        } catch (Exception $exception){
            return $this->json ([
                'status'=>400,
                'message'=>"There is no such file"
            ]);
        }
    }

    /**
     * @Route("/delete", name="delete", methods={"DELETE"})
     */
    public function delete_file(FileRepository $fileRepository, $name): Response
    {
        try {
            $file = $fileRepository->findOneBy(['originalName' => $name]);

            $ent = $this->getDoctrine()->getManager();
            $ent->remove($file);
            $ent->flush();

            $data = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $fileDir = scandir($data);
            foreach ($fileDir as $fileDel) {
                if ($fileDel == $name) {
                    array_map('unlink', glob($data . '/' . $fileDel));
                }
            }
            return $this->json ([
                'status'=>200,
                'message'=>"File deleted"
            ]);
        } catch (\Exception $exception) {
            return $this->json ([
                'status'=>400,
                'message'=>"The file has not been deleted, an exception has appeared"
            ]);
        }
    }
}
