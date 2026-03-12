<!DOCTYPE html>
<html class="no-js" lang="fr">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $quote->type_ }} N° {{ $quote->id }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    
    <style>
        :root {
            --primary-color: #000000;
            --accent-color: #f3f3f3;
            --text-color: #333333;
            --light-text: #666666;
            --border-color: #e0e0e0;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: var(--text-color);
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        .tm_container {
            max-width: 800px; /* A4 width approx */
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
        }

        .tm_invoice_wrap {
            padding: 50px;
            min-height: 1123px; /* A4 height */
            box-sizing: border-box;
            position: relative;
        }

        /* Helpers */
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        
        /* Layout */
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo img {
            max-height: 80px;
            max-width: 200px;
        }

        .company-info {
            text-align: right;
            font-size: 12px;
            color: var(--light-text);
        }
        
        .company-info strong {
            color: var(--text-color);
            font-size: 14px;
        }

        .client-meta-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .client-info, .meta-info {
            width: 45%;
        }

        .meta-info {
            text-align: right;
        }

        .doc-title {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .info-label {
            color: var(--light-text);
            font-size: 12px;
            margin-bottom: 2px;
        }

        .info-value {
            font-weight: bold;
            font-size: 16px;
        }

        /* Table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .invoice-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }
        
        .invoice-table th.text-right { text-align: right; }

        .invoice-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .invoice-table tr:last-child td {
            border-bottom: 2px solid var(--primary-color);
        }

        /* Totals */
        .totals-section {
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 300px;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .totals-table tr:last-child td {
            border-bottom: none;
            padding-top: 15px;
        }

        .total-label {
            color: var(--light-text);
        }

        .total-value {
            text-align: right;
            font-weight: bold;
        }

        .grand-total {
            background-color: var(--primary-color);
            color: white;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            font-size: 11px;
            color: var(--light-text);
            text-align: center;
        }

        /* Controls */
        .controls {
            position: absolute;
            top: 20px;
            right: -150px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            background: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        
        .btn:hover { background: #555; }

        @media print {
            body { background: white; padding: 0; }
            .tm_container { box-shadow: none; max-width: 100%; margin: 0; }
            .controls, .tm_invoice_btns { display: none !important; }
            .tm_invoice_wrap { min-height: 0; padding: 30px; }
            .invoice-table th { -webkit-print-color-adjust: exact; }
            .grand-total { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>

<body>
    <div class="tm_container">
        <!-- Controls -->
        <div class="controls tm_invoice_btns">
            <a href="javascript:window.print()" class="btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimer
            </a>
            <button id="tm_download_btn" class="btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Télécharger
            </button>
        </div>

        <div class="tm_invoice_wrap" id="tm_download_section">
            
            <!-- Header -->
            <div class="header">
                <div class="logo">
                    @php
                        // URL du logo (identique à l'original)
                        $logoUrl = 'https://crm.synapsispharma.com/public/storage/configuration/fH5ZCxBbFvMJhC78U8daMrtzFwn37Ki6BhuDQjEv.jpg';
                        
                        // Tentative de récupération de l'image et conversion en base64
                        $base64Logo = null;
                        try {
                            // Désactiver les erreurs avec @, mais mieux vaut utiliser un gestionnaire d'erreur
                            $imageData = @file_get_contents($logoUrl);
                            if ($imageData !== false) {
                                // Détection du type MIME
                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                $mimeType = finfo_buffer($finfo, $imageData);
                                finfo_close($finfo);
                                // Si finfo échoue, on force le type jpg (par défaut)
                                if (!$mimeType) {
                                    $mimeType = 'image/jpeg';
                                }
                                $base64Logo = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                            }
                        } catch (Exception $e) {
                            // En cas d'erreur, laisser $base64Logo à null
                        }
                    @endphp

                    @if($base64Logo)
                        <img src="{{ $base64Logo }}" alt="Logo">
                    @else
                        <!-- Fallback : afficher le logo via l'URL d'origine (au cas où) ou un texte -->
                        <img src="{{ $logoUrl }}" alt="Logo" crossorigin="anonymous">
                    @endif
                </div>
                <div class="company-info">
                    <strong>{{ core()->getConfigData('general.general.registre_commerce.registre_commerce') }}</strong><br>
                    NINEA: {{ core()->getConfigData('general.general.ninea.ninea') }}<br>
                    {{ core()->getConfigData('general.general.adresse_siege.adresse_siege') }}<br>
                    @php
                        $emailContact = core()->getConfigData('general.general.email_contact.email_contact');
                        $telephoneContact = core()->getConfigData('general.general.telephone_contact.telephone_contact');
                    @endphp
                    @if($telephoneContact)
                        Tél.: {{ $telephoneContact }}<br>
                    @endif
                    @if($emailContact)
                        Email: {{ $emailContact }}
                    @endif
                </div>
            </div>

            <!-- Client & Meta -->
            <div class="client-meta-wrapper">
                <div class="client-info">
                    <div class="info-label">Facturé à</div>
                    <div class="info-value">{{ $quote->person->name }}</div>

                    @php
                        $personPhone = is_array($quote->person->contact_numbers ?? null)
                            ? ($quote->person->contact_numbers[0]['value'] ?? null)
                            : null;

                        $personOrg = $quote->person->organization->name ?? null;
                    @endphp

                    @if($personOrg)
                        <div style="margin-top:5px; color:#444;">
                            {{ $personOrg }}
                        </div>
                    @endif

                    @if($quote->person->address)
                        <div style="margin-top:5px; color:#666;">
                            {{ $quote->person->address }}
                        </div>
                    @endif

                    @if($personPhone)
                        <div style="margin-top:5px; color:#666;">
                            Tél. : {{ $personPhone }}
                        </div>
                    @endif

                    @if(! empty($quote->shipping_address) && is_array($quote->shipping_address))
                        
                        <div style="margin-top:3px; color:#666;">
                            @if(! empty($quote->shipping_address['address']))
                                {{ $quote->shipping_address['address'] }}<br>
                            @endif

                            @php
                                $shippingLine = [];

                                if (! empty($quote->shipping_address['postcode'])) {
                                    $shippingLine[] = $quote->shipping_address['postcode'];
                                }

                                if (! empty($quote->shipping_address['city'])) {
                                    $shippingLine[] = $quote->shipping_address['city'];
                                }
                            @endphp

                            @if(count($shippingLine))
                                {{ implode(' ', $shippingLine) }}<br>
                            @endif

                            @if(! empty($quote->shipping_address['country']))
                                {{ $quote->shipping_address['country'] }}
                            @endif
                        </div>
                    @endif
                </div>
                <div class="meta-info">
                    <div class="doc-title">{{ strtoupper($quote->type) }} N° {{ $quote->id }}</div>
                    <div class="info-label">Date d'émission</div>
                    <div class="info-value">{{ $quote->created_at->format('d/m/Y') }}</div>
                    @if($quote->expired_at)
                        <div class="info-label" style="margin-top:4px;">Date d'échéance</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($quote->expired_at)->format('d/m/Y') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Description</th>
                        <th class="text-right" style="width: 15%;">Prix Unitaire</th>
                        <th class="text-right" style="width: 10%;">Qté</th>
                        <th class="text-right" style="width: 25%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quote->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->name }}</strong><br>
                            <span style="font-size: 12px; color: #888;">{{ $item->additional['description'] ?? $item->product->description ?? '' }}</span>
                        </td>
                        <td class="text-right">{{ number_format($item->price, 0, ' ', '.') }} {{ $quote->devise }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->total + $item->tax_amount, 0, ' ', '.') }} {{ $quote->devise }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals-section">
                <table class="totals-table">
                    @php
                        // Calculer le sous-total à partir des items si sub_total n'est pas défini
                        $subTotal = $quote->sub_total ?? 0;
                        if ($subTotal == 0 || $subTotal == null) {
                            $subTotal = 0;
                            foreach ($quote->items as $item) {
                                $itemTotal = ($item->price * $item->quantity) - ($item->discount_amount ?? 0);
                                $subTotal += $itemTotal;
                            }
                        }
                        
                        $tvaRate = $quote->tva_rate ?? 0;
                        $tvaAmount = 0;
                        $totalTTC = $subTotal;
                        
                        if ($tvaRate && $tvaRate > 0) {
                            $tvaAmount = $subTotal * ($tvaRate / 100);
                            $totalTTC = $subTotal + $tvaAmount;
                        }
                    @endphp
                    <tr>
                        <td class="total-label">Sous-total HT</td>
                        <td class="total-value">{{ number_format($subTotal, 0, ' ', '.') }} {{ $quote->devise }}</td>
                    </tr>
                    
                    @if($tvaRate && $tvaRate > 0)
                    <tr>
                        <td class="total-label">TVA ({{ number_format($tvaRate, 2, ',', ' ') }}%)</td>
                        <td class="total-value">{{ number_format($tvaAmount, 0, ' ', '.') }} {{ $quote->devise }}</td>
                    </tr>
                    @endif

                    <!-- Acompte Logic -->
                    @if($quote->acompte && $quote->acompte != 0 && $quote->type == 'facture')
                        @php
                            $remaining = $totalTTC - $quote->acompte;
                        @endphp
                        <tr>
                            <td class="total-label">Total TTC</td>
                            <td class="total-value">{{ number_format($totalTTC, 0, ' ', '.') }} {{ $quote->devise }}</td>
                        </tr>
                        <tr>
                            <td class="total-label" style="color:red">Déjà payé (Acompte)</td>
                            <td class="total-value" style="color:red">- {{ number_format($quote->acompte, 0, ' ', '.') }} {{ $quote->devise }}</td>
                        </tr>
                        <tr>
                             <td colspan="2" style="padding:0;">
                                <div style="background: #000; color: white; padding: 10px; margin-top: 10px; display:flex; justify-content:space-between; align-items:center;">
                                    <span style="font-size:14px; text-transform:uppercase;">Reste à Payer</span>
                                    <span style="font-size:18px; font-weight:bold;">{{ number_format($remaining, 0, ' ', '.') }} {{ $quote->devise }}</span>
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="2" style="padding:0;">
                                <div style="background: #000; color: white; padding: 10px; margin-top: 10px; display:flex; justify-content:space-between; align-items:center;">
                                    <span style="font-size:14px; text-transform:uppercase;">Total TTC</span>
                                    <span style="font-size:18px; font-weight:bold;">
                                        {{ number_format($totalTTC, 0, ' ', '.') }} {{ $quote->devise }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

            <!-- Bank Info Footer -->
            @php
                $accountNumber = core()->getConfigData('general.general.numero_compte.numero_compte');
            @endphp

            @if($accountNumber)
            <div class="footer">
                <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; display: inline-block; width: 100%; box-sizing: border-box;">
                    <strong>Informations Bancaires</strong><br>
                    Banque: {{ core()->getConfigData('general.general.nom_banque.nom_banque') }} <br>
                    
                    Numéro de compte: {{ $accountNumber }}
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
        (function ($) {
            'use strict';

            $('#tm_download_btn').on('click', function () {
                var downloadSection = $('#tm_download_section');
                var a4Width = 595.28; 
                var a4Height = 841.89; 
                var canvasImageWidth = downloadSection.width();
                var canvasImageHeight = downloadSection.height();

                html2canvas(downloadSection[0], { 
                    allowTaint: false,
                    useCORS: true,
                    scale: 2,
                    backgroundColor: '#ffffff',
                    logging: true, // Pour déboguer, vous pouvez l'enlever en production
                })
                .then(function (canvas) {
                    var imgData = canvas.toDataURL('image/png', 1.0);
                    var pdf = new jspdf.jsPDF('p', 'pt', 'a4');
                    var imgWidth = a4Width;
                    var imgHeight = canvasImageHeight * (a4Width / canvasImageWidth);
                    
                    pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                    pdf.save('{{ $quote->type_ }}_{{ $quote->id }}.pdf');
                }).catch(function(error) {
                    console.error('Erreur html2canvas :', error);
                });
            });
        })(jQuery);
    </script>
</body>
</html>
