<!DOCTYPE html>
<html class="no-js" lang="fr">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $quote->type_ }} N° {{ $quote->id }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://crm.webmasteragency.fr/assets/css/style.css">
    
    <style>
        .color-list {
            display: inline-block;
            list-style-type: none;
            padding: 0;
        }

        .color-item {
            width: 30px;
            height: 30px;
            margin: 5px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .color-item.selected {
            border: 2px solid black;
        }
        
        /* Styles pour assurer la compatibilité */
        body {
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        /* Styles spécifiques pour l'impression et le PDF */
        .tm_container {
            position: relative;
            min-height: 100vh;
        }
        
        .tm_invoice_wrap {
            position: relative;
            min-height: 1123px; /* Hauteur approximative A4 (841.89pt ≈ 1123px) */
        }
        
        .bank-info-container {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            width: 100%;
        }
        
        @media print {
            .tm_invoice_btns,
            .color-list {
                display: none !important;
            }
            
            body {
                padding: 0;
                margin: 0;
            }
            
            .tm_container {
                height: 100vh;
            }
            
            .tm_invoice_wrap {
                min-height: 100vh;
                position: relative;
            }
        }
        
        /* Assurer que le contenu principal n'empiète pas sur les infos bancaires */
        .tm_invoice {
            padding-bottom: 100px; /* Espace pour les infos bancaires */
        }
    </style>
</head>

<body>
    <div class="tm_container">
        <div class="tm_invoice_wrap">
            <div class="tm_invoice tm_style2" id="tm_download_section">
                <div class="tm_invoice_in">
                    <!-- ================= HEADER ================= -->
                    <div class="tm_invoice_head tm_top_head tm_mb20">
                        <div class="tm_invoice_left">
                            <div class="tm_logo">
                                <img src="{{ asset('storage/'.core()->getConfigData('general.general.admin_logo.logo_image')) }}" alt="Logo" height="70">
                            </div>
                        </div>
                        <div class="tm_invoice_right">
                            <div class="tm_grid_row tm_col_3">
                                <div>
                                    <b class="tm_primary_color">Registre N°</b><br>
                                    {{ core()->getConfigData('general.general.registre_commerce.registre_commerce') }}
                                </div>
                                <div>
                                    <b class="tm_primary_color">Ninea</b><br>
                                    {{ core()->getConfigData('general.general.ninea.ninea') }}
                                </div>
                                <div>
                                    <b class="tm_primary_color">Adresse</b><br>
                                    {{ core()->getConfigData('general.general.adresse_siege.adresse_siege') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= CLIENT & TITLE ================= -->
                    <div class="tm_invoice_info tm_mb10">
                        <div class="tm_invoice_info_left">
                            <p class="tm_mb2"><b></b></p>
                            <p>
                                <b class="tm_f16 tm_primary_color">À : {{ $quote->person->name }}</b><br>
                            </p>
                        </div>
                        <div class="tm_invoice_info_right">
                            <p style="float: right" class="">Date : {{ $quote->created_at->format('d F Y') }}</p>
                            <div class="tm_ternary_color tm_f40 tm_invoice_title">
                                {{ strtoupper($quote->type) }} N° {{ $quote->id }}
                            </div>
                        </div>
                    </div>

                    <!-- ================= ITEMS TABLE ================= -->
                    <div class="tm_table tm_style1">
                        <div class="tm_round_border">
                            <div class="tm_table_responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="tm_width_7 tm_semi_bold" style="color: white; background-color:#0000ff" id="color">Description</th>
                                            <th class="tm_width_2 tm_semi_bold" style="color: white; background-color:#0000ff" id="color">Prix</th>
                                            <th class="tm_width_1 tm_semi_bold" style="color: white; background-color:#0000ff" id="color">Quantité</th>
                                            <th class="tm_width_2 tm_semi_bold tm_text_right" style="color: white; background-color:#0000ff" id="color">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quote->items as $item)
                                        <tr class="tm_gray_bg">
                                            <td class="tm_width_7">
                                                <p class="tm_m0 tm_f16 tm_primary_color">{{ $item->name }}</p>
                                                {{ $item->additional['description'] ?? $item->product->description ?? '' }}
                                            </td>
                                            <td class="tm_width_2">{{ number_format($item->price, 0, ' ', '.') }} {{ $quote->devise }}</td>
                                            <td class="tm_width_1">{{ $item->quantity }}</td>
                                            <td class="tm_width_2 tm_text_right">{{ number_format($item->total + $item->tax_amount, 0, ' ', '.') }} {{ $quote->devise }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- ================= TOTALS ================= -->
                        <div class="tm_invoice_footer tm_mb15 tm_m0_md">
                            <div class="tm_left_footer" style="margin-top:33px"></div>
                            <div class="tm_right_footer">
                                <table class="tm_mb15">
                                    <tbody>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">TOTAL HT</td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">
                                                {{ number_format($quote->sub_total, 0, ' ', '.') }} {{ $quote->devise }}
                                            </td>
                                        </tr>
                                        @if($quote->haveTax)
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">
                                                TVA 
                                                @if ($quote->devise == "CFA") (18%)
                                                @else (20%)
                                                @endif
                                            </td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                                                @if ($quote->devise == "CFA")
                                                    {{ number_format($quote->sub_total * 0.18, 0, ' ', '.') }}
                                                @else
                                                    {{ number_format($quote->sub_total * 0.2, 0, ' ', '.') }}
                                                @endif
                                                {{ $quote->devise }}
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td id="color" style="background-color:#0000ff" class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_white_color tm_radius_6_0_0_6">
                                                Total TTC
                                            </td>
                                            <td id="color" style="background-color:#0000ff" class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right tm_white_color tm_radius_0_6_6_0">
                                                @if($quote->haveTax)
                                                    @if ($quote->devise == "CFA")
                                                        {{ number_format($quote->sub_total + ($quote->sub_total * 0.18), 0, ' ', '.') }}
                                                    @else
                                                        {{ number_format($quote->sub_total + ($quote->sub_total * 0.2), 0, ' ', '.') }}
                                                    @endif
                                                @else
                                                    {{ number_format($quote->sub_total, 0, ' ', '.') }}
                                                @endif
                                                {{ $quote->devise }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tm_invoice_footer tm_type1">
                            <div class="tm_left_footer"></div>
                        </div>
                    </div>

                    <!-- ESPACE POUR ÉVITER QUE LE CONTENU NE CHEVAUCHE LES INFOS BANCAIRES -->
                    <div style="height: 80px;"></div>
                    
                    <!-- ================= INFOS BANCAIRES (TOUJOURS EN BAS) ================= -->
                    @if(core()->getConfigData('general.general.iban.iban'))
                    <div class="bank-info-container">
                        <div class="tm_note tm_font_style_normal tm_text_center" style="position: absolute; bottom: 0; left: 0; right: 0; width: 100%;">
                            <hr class="tm_mb15">
                            <p class="tm_mb2"> </p>
                            <p class="tm_m0" style="font-size: 10px !important">
                                Banque : {{ core()->getConfigData('general.general.nom_banque.nom_banque') }} </p>
                            <p class="tm_m0" style="font-size: 10px !important"> Code SWIFT : {{ core()->getConfigData('general.general.swift_code.swift_code') }} </p>
                            <p class="tm_m0" style="font-size: 10px !important"> IBAN : {{ core()->getConfigData('general.general.iban.iban') }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ================= BUTTONS & COLOR PICKER ================= -->
            <div class="tm_invoice_btns tm_hide_print">
                <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
                    <span class="tm_btn_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path>
                            <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></rect>
                            <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path>
                            <circle cx="392" cy="184" r="24" fill="currentColor"></circle>
                        </svg>
                    </span>
                    <span class="tm_btn_text">Imprimer</span>
                </a>
                <button id="tm_download_btn" class="tm_invoice_btn tm_color2">
                    <span class="tm_btn_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"></path>
                        </svg>
                    </span>
                    <span class="tm_btn_text">Télécharger</span>
                </button>

                <!-- ================= COLOR PICKER ================= -->
               <!-- <ul class="color-list">
                    <li class="color-item selected" style="background-color:#0000ff" onclick="changeColor('#0000ff', this)"></li>
                    <li class="color-item" style="background-color: red;" onclick="changeColor('red', this)"></li>
                    <li class="color-item" style="background-color: green;" onclick="changeColor('green', this)"></li>
                    <li class="color-item" style="background-color: blue;" onclick="changeColor('blue', this)"></li>
                    <li class="color-item" style="background-color: orange;" onclick="changeColor('orange', this)"></li>
                    <li class="color-item" style="background-color: purple;" onclick="changeColor('purple', this)"></li>
                    <li class="color-item" style="background-color: brown;" onclick="changeColor('brown', this)"></li>
                </ul> -->
            </div>
        </div>
    </div>

    <script>
        function changeColor(color, element) {
            // Change the background color of all elements with id="color"
            var items = document.querySelectorAll('#color');
            items.forEach(function(item) {
                item.style.backgroundColor = color;
            });

            // Remove the 'selected' class from all items
            var items = document.querySelectorAll('.color-item');
            items.forEach(function(item) {
                item.classList.remove('selected');
            });

            // Add the 'selected' class to the clicked item
            element.classList.add('selected');
        }
    </script>

    <script>
        (function ($) {
            'use strict';

            $('#tm_download_btn').on('click', function () {
                var downloadSection = $('#tm_download_section');
                var a4Width = 595.28; // Largeur A4 en points
                var a4Height = 841.89; // Hauteur A4 en points
                var canvasImageWidth = downloadSection.width();
                var canvasImageHeight = downloadSection.height();

                html2canvas(downloadSection[0], { 
                    allowTaint: true, 
                    useCORS: true,
                    scale: 2,
                    backgroundColor: '#ffffff'
                })
                .then(function (canvas) {
                    var imgData = canvas.toDataURL('image/png', 1.0);
                    var pdf = new jspdf.jsPDF('p', 'pt', 'a4');

                    // Calculer le rapport de conversion
                    var imgWidth = a4Width;
                    var imgHeight = canvasImageHeight * (a4Width / canvasImageWidth);

                    var heightLeft = imgHeight;
                    var position = 0;

                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= a4Height;

                    while (heightLeft > 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= a4Height;
                    }

                    pdf.save('{{ $quote->type_ }}_{{ $quote->id }}.pdf');
                })
                .catch(function (error) {
                    console.error('Erreur lors de la capture :', error);
                });
            });

        })(jQuery);
    </script>
</body>
</html>