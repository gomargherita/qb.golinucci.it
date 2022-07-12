<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        body {
            font-size: 11px;
        }

        @page {
            margin: 80px 25px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 150px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 60px;
            text-align: center;
            font-size: 0.9em;
            color: #822622;
            font-style: italic;
        }

        main {
            margin-top: 75px;
        }

        table tr td {
            padding: 1px 5px;
        }
    </style>
</head>

<body>
    <header>
        <div style="text-align:center;width: 100%;">
            <img src="data:image/jpeg;base64,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">
        </div>
    </header>
    <footer>
        <p>
            <b>Golinucci srl</b><br>
            Viale Bovio 194 - 47521 CESENA (FC) - P.IVA e C.F. 02027260401<br>
            Numero iscrizione RUI: B000070946 - Capitale Sociale € 116.000 I.V.<br>
            Telefono 0547-22351 - Fax 0547-25382 - Email info@golinucci.it - Sito Web www.golinucci.it<br>
            Broker di Assicurazioni Iscrizione RUI n. B000070946 del 19/03/2007 intermediario soggetto al controllo dell'Ivass
        </p>
    </footer>
    <main>
        <div>
            <div>
                <h1 style="text-align: center;">{{ $title }}</h1>
                <h2 style="text-align: center;">Modulo di proposta</h2>
            </div>
            <div style="margin:5px 0;">
                <p>
                    L'indicazione di premio e subordinata all’esame del questionario completo, datato e firmato da persona autorizzata, e di qualunque altro documento o informazione i Sottoscrittori ritengano necessari ai fini della corretta valutazione del rischio. E di estrema importanza che ilProponente sia a conoscenza dell’oggetto di questa assicurazione cosi che alle domande qui contenute possa essere data la corretta risposta. In caso di dubbio si prega di contattare il Vostro Broker poiché ogni informazione errata o inesatta potrebbe compromettere gli eventuali risarcimenti o potrebbe causare l’annullamento della polizza stessa.
                </p>
            </div>
            <div style="margin:5px 0;">
                <h3 style="text-align: center;">Avviso Importante</h3>
                <p style="text-decoration: underline;">
                    Le dichiarazioni inesatte e le reticenze del contraente, relative a circostanze tali che l’assicuratore non avrebbe dato il suo consenso o non Io avrebbe dato alle medesime condizioni se avesse conosciuto il vero stato delle cose, sono causa di annullamento del contratto quando il contraente ha agito con dolo o con colpa grave” (Art 1892 comma I Codice Civile).
                </p>
                <p>
                    Il Contraente ha l’obbligo di comunicare all’Assicuratore tutti gli elementi materiali e rilevanti relativamente alla valutazione ed all’assunzione del rischio; il mancato adempimento di detto obbligo potrà rendere invalidabile qualsiasi polizza, o potrà gravemente pregiudicare i Vostri diritti in caso di sinistro.
                </p>
            </div>
            <div style="margin:5px 0;">
                <table style="border-collapse: collapse;">
                    <tr>
                        <td>Cognome e Nome</td>
                        <td><b>{{ $fullname }}</b></td>
                    </tr>
                    <tr>
                        <td>Indirizzo</td>
                        <td><b>{{ $address }}</b></td>
                    </tr>
                    <tr>
                        <td>Codice Fiscale</td>
                        <td><b>{{ $fiscal_code }}</b></td>
                    </tr>
                    <tr>
                        <td>Ente di Appartenenza</td>
                        <td><b>{{ $authority_type }}</b></td>
                    </tr>
                    <tr>
                        <td>Denominazione Ente di Appartenenza</td>
                        <td><b>{{ $authority }}</b></td>
                    </tr>
                    <tr>
                        <td>Professione</td>
                        <td><b>{{ $profession }}</b></td>
                    </tr>
                    <tr>
                        <td>Specializzazione</td>
                        <td><b>{{ $specialization }}</b></td>
                    </tr>
                    <tr>
                        <td>Cellulare</td>
                        <td><b>{{ $phone }}</b></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><b>{{ $email }}</b></td>
                    </tr>
                </table>
            </div>
            <div style="margin:5px 0;">
                <h3>Condizioni di Polizza</h3>
                <p>Professione: {{ $profession }}</p>
                <ol>
                    <li>
                        MASSIMALE DI RISARCIMENTO: &euro; {{ $indemnity_limit }}
                    </li>
                    <li>
                        PERIODO DI RETROATTIVITA': 10 ANNI (ai quali si aggiungono gli anni di precedente copertura assicurativa trascorsi con Golinucci Srl con il massimo di 14 anni)
                    </li>
                </ol>
            </div>
            <div style="margin:5px 0;">
                <h3>Sinistrosità</h3>
                <p>
                    Io sottoscritto dichiaro di essere consapevole che, ai sensi dell’art. 8 b), sono escluse le Richieste di Risarcimento connesse a Fatti o Circostanze che fossero già note all’Assicurato prima della data di inizio del Periodo di Assicurazione. Configurano Fatti e Circostanze note:
                </p>
                <ol>
                    <li>La sentenza di condanna in sede civile o penale;</li>
                    <li>La notifica di avvio di indagini preliminari/rinvio a giudizio in sede penale;</li>
                    <li>La richiesta di risarcimento/notifica di atto di citazione in sede civile;</li>
                    <li>La comunicazione della struttura Sanitaria {{ $authority_type_public_flag ? 'Pubblica' :'Privata' }} di appartenenza o della sua impresa di assicurazione con la quale mettono in mora l’Assicurato per danni o perdite cagionate da fatto gravemente colposo;</li>
                    <li>La comunicazione formale con la quale la struttura Sanitaria {{ $authority_type_public_flag ? 'Pubblica' :'Privata' }} di Appartenenza o la loro impresa di assicurazione informa l’Assicurato dell’instaurazione di un giudizio da parte di un Terzo per un fatto che vede coinvolto l’Assicurato medesimo ai fini dell’azione di responsabilità amministrativa o di rivalsa per colpa grave;</li>
                    <li>La comunicazione formale con la quale la struttura Sanitaria {{ $authority_type_public_flag ? 'Pubblica' :'Privata' }} di Appartenenza o la loro impresa di assicurazione informa l’Assicurato dell’avvio di trattative stragiudiziali con un Terzo per un fatto che vede coinvolto l'Assicurato medesimo ai fini dell’azione di responsabilità amministrativa o di rivalsa per colpa grave</li>
                </ol>
                <p>SE PRIMA DELLA DATA DI SOTTOSCRIZIONE DEL PRESENTE MODULOSI FOSSE A CONOSCENZA DI FATTI E CIRCOSTANZE NOTE, COME SOPRA DEFINITO, LA SEGNALAZIONE VA FATTA ALLA PRECEDENTE COMPAGNIA ASSICURATIVA</p>
                <p><b>Inoltre il Sottoscritto dichiara di non svolgere il ruolo di Direttore di una C.R.A. (ex R.S.A.)</b></p>
                <p style="text-align:right;">
                    Per conferma&nbsp;
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAvgAAAL4BsOPnlwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAFxSURBVEiJ3da7axVREMfxz+jFBwEhighaWQghBIs0aSxTaCorOwX/gFgFbC0NISBYC5ImZUiIklI7qysY0MJGm5TaBCwUJ0VO4Hjdu4n30Tgwxc7j9z07e/bsRmYap50ah2hETEbEI+hExJcR6b7MzCcRcQ07mImIG5Aj8Kdl1FP4WmI/8aBTreA5Pg6w8r3M3IqIObzCJfzAvczcru/gdmYaxHEH+0XnO25VueEAuF/GkdjDzZ784AAs4Xfp/4zrDTV/A3AGF1uEAytV73tcwXkstAJwFtv4gMsN4h2sVX1vcKH4Wzw7DjCFbyX2BwQTeF31bOBcya2XWDugBGd7IWX7vavqX+B01bNzYkADZBefqtrlhtE1AvqeRZnZxbzDfT1TRpdYyszH/fp6rfWw64H8wsPMXD2pOIc7otUysxsR87h69OqPFHAEQfdfxRnT9+D/AtTPYDEi7g6hNX0cYGEI8b7WweaINXfrixj3b8sB5tOks04krOwAAAAASUVORK5CYII=" />
                </p>
            </div>
        </div>
        <div style="page-break-before: always;"></div>
        <div style="margin-top:100px;">
            <div style="margin:5px 0;">
                <h3>Dichiarazione</h3>
                <p>
                    II Sottoscritto/Proponente dichiara per proprio conto e a nome dei suoi Associati:
                </p>
                <ol>
                    <li>di prendere atto che questa Proposta non vincola né gli assicuratori né il Proponente alla stipulazione del contratto di assicurazione;</li>
                    <li>che le informazioni rese sul presente modulo sono conformi a verità;</li>
                    <li>di non aver mai avuto rifiutata e/o cancellata una copertura assicurativa di RC Professionale</li>
                    <li>di non aver sottaciuto alcun elemento rilevante per la valutazione del rischio</li>
                </ol>
                <p style="text-align:right;">
                    Firma: <span style="font-size:16px;font-style:italic;">{{ $fullname }}</span><br>
                    Data: {{ $data_generazione }}
                </p>
            </div>
            <div style="margin:5px 0;">
                <h3>Polizza di Responsabilità Civile nella forma CLAIMS MADE</h3>
                <p>
                    La presente proposta è relativa ad una polizza di responsabilità nella forma “claims made”. Ciò significa che la polizza copre: Le richieste di risarcimento presentate per la prima volta alle Persone Assicurate durante il periodo di validità della polizza e gli eventi dei quali le Persone Assicurate vengano a conoscenza durante il periodo di validità della polizza e che potrebbero originare un Reclamo futuro, a condizione che gli Assicuratori siano informati, durante il periodo di validità della polizza, delle Circostanze relative a tali eventi. Dopo la scadenza della polizza, nessun reclamo potrà essere avanzato a termini della polizza stessa, anche se l’evento che ha originato la richiesta danni si sia verificato durante il periodo di validità della polizza.<br>
                    Agli effetti dell’art. 1341 e 1342 del Codice Civile, il sottoscritto Contraente dichiara:
                </p>
                <ol>
                    <li>di aver preso atto che questo e un contratto di assicurazione nella forma «CLAIMS MADE» a coprire Ie Richieste di Risarcimento fatte per la prima volta contro l’Assicurato durante il Periodo di Assicurazione e da lui denunciate agli Assicuratori durante Io stesso periodo;</li>
                    <li>di approvare specificamente le disposizioni contenute nei sotto richiamati articoli della Polizza:</li>
                </ol>
                <p>
                    <b>ART 1.1.</b>: Forma dell’Assicurazione (Claims Made) — Retroattività; <b>ARTICOLO 8</b>: Esclusioni (in particolare quelle dell’assicurazione claims made — 8.1); <b>ARTICOLO 14</b>: Modalità della Proroga Automatica — Rescindibilità Annuale; <b>ARTICOLO 15</b>: Coesistenza di altre assicurazioni; <b>ARTICOLO 16</b>: Denuncia dei sinistri (esclusione dei sinistri denunciati dopo la cessazione del Periodo di Assicurazione); <b>ARTICOLO 17</b>: Recesso in caso di sinistro; <b>ARTICOLO 20</b>: Clausola Broker; <b>ARTICOLO 24</b>: Dichiarazione dell’assicurato
                </p>
                <p style="text-align:right;">
                    Firma: <span style="font-size:16px;font-style:italic;">{{ $fullname }}</span><br>
                    Data: {{ $data_generazione }}
                </p>
            </div>
            <div style="margin:5px 0;">
                <p>
                    II sottoscritto dichiara di aver ricevuto e preso visione delle condizioni di polizza.
                </p>
                <p style="text-align:right;">
                    Firma: <span style="font-size:16px;font-style:italic;">{{ $fullname }}</span><br>
                    Data: {{ $data_generazione }}
                </p>
            </div>
    </main>
</body>

</html>