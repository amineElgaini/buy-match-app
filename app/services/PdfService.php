<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    public static function generate(string $html, string $filename): string
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        $path = __DIR__ . '/../../storage/' . $filename;
        file_put_contents($path, $output);

        return $path;
    }
}
