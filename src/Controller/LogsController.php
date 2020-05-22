<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

/**
 * @IsGranted("ROLE_SUPER_ADMIN")
 */
class LogsController extends AbstractController {
    
    /**
     * @Route("/logs", name="app_logs")
     */
    public function logs(Request $request)
    {
        $i = intval($request->query->get('i'));
        $date = (new \Datetime('-'.$i.'month'))->format('Y-m');
        $file = $this->getParameter('kernel.logs_dir') .'\admin\dev-' . $date .'.log';

        if (file_exists ( $file )) {
            $logs = file_get_contents($file);
        } else {
            $this->addFlash('error', 'Aucun fichier Log');
            $logs = null;
        }

        return $this->render('admin/logs.html.twig', ['logs' => $logs, 'date' => $date, 'i' => $i]);
    }
}
