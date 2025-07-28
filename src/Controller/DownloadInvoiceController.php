<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Order; // assure-toi que c’est bien ton entité
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class DownloadInvoiceController extends AbstractController
{
    public function __invoke(Order $data): Response
    {
        $cart = $data->getCart();
        $decodedCart = [];
        $total = 0;
        foreach ($cart as $item) {
            $cartItem = json_decode($item, true);
            $cartItem['subtotal'] = $cartItem['quantity'] * $cartItem['price'];
            $total += $cartItem['subtotal'];
            $decodedCart[] = $cartItem;
        }
        // Logo en base64
        $logoPath = realpath(__DIR__ . '/../../public/images/logo.png');
        $logoBase64 = base64_encode(file_get_contents($logoPath));
        $logoSrc = 'data:image/png;base64,' . $logoBase64;

        $html = $this->renderView('/facture/invoice.html.twig', [
            'order' => $data,
            'cart' => $decodedCart,
            'totalHt' => $total,
            'total'  => $total,
            'tva'   => 0,
            'logoSrc' => $logoSrc,
            'paymentMethod' => "Espèce",
        ]);

        // Génération PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true); // utile pour charger les images distantes
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="facture-' . $data->getId() . '.pdf"',
            ]
        );
    }
}
