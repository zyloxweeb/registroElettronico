<?php
include('../includes/fpdf186/fpdf.php');
include '../includes/database.php';

// Crea un nuovo documento PDF
$pdf = new FPDF();
$pdf->AddPage();

// Imposta il font per il titolo
$pdf->SetFont('Arial', 'B', 16);

// Aggiungi il titolo al documento
$pdf->Cell(0, 10, 'Elenco Insegnanti', 0, 1, 'C');

// Imposta il font per il contenuto
$pdf->SetFont('Arial', '', 12);

// Ottieni la lista degli insegnanti dal database
$sql = "SELECT id, name, subject FROM teachers";
$result = $conn->query($sql);

// Se ci sono insegnanti nel database
if ($result->num_rows > 0) {
    // Aggiungi intestazione della tabella
    $pdf->Cell(30, 10, 'ID', 1, 0, 'C');
    $pdf->Cell(70, 10, 'Nome', 1, 0, 'C');
    $pdf->Cell(90, 10, 'Materia', 1, 1, 'C');

    // Aggiungi righe con gli insegnanti
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(30, 10, $row['id'], 1, 0, 'C');
        $pdf->Cell(70, 10, $row['name'], 1, 0, 'L');
        $pdf->Cell(90, 10, $row['subject'], 1, 1, 'L');
    }
} else {
    // Se non ci sono insegnanti nel database
    $pdf->Cell(0, 10, 'Nessun insegnante trovato', 1, 1, 'C');
}

// Chiudi la connessione al database
$conn->close();

// Output del PDF
$pdf->Output('elenco_insegnanti.pdf', 'D');
?>
