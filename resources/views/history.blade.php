@extends('layout.master')
@section('content')
@section('header')
    @parent
    <style>
        [class^="col-"] {
            padding-right: 3em;
            text-align: justify;
        }
        p {
            margin: 1em 0;
        }
        #planHouse {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        #planHouse:hover {opacity: 0.7; cursor: pointer;}

        /* The Modal (background) */
        .modal {
            z-index: 11111;
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 88%;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content, #caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {transform:scale(0)}
            to {transform:scale(1)}
        }

        /* The Close Button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px){
            .modal-content {
                width: 100%;
            }
        }
    </style>
    @stop

    </div>
<div class="row">
    <div class="col-sm-12 col-md-12 col-xs-12">
        <p>
            &nbsp;
        </p>
    </div>
    <div class="col-sm-6 col-md-6 col-xs-12">
        <p>
            Wann das Haus gebaut wurde, ist nicht bekannt. Bei der Renovation 1957 wurde eine entsprechende Inschrift an
            der Fassade vorn rechts gefunden, die ersten zwei Zahlen fehlen aber. Sehr wahrscheinlich erfolgte der Bau
            anfangs des 17. Jahrhunderts. Das wieder frei gelegte Sgraffito weist auf jene Epoche hin. Damals bestand im
            1. Stock, wie die Sgraffito-Reste zeigen, eine Verbindungstüre zum damaligen Frizzoni-Haus. Als dann anfangs
            des 18. Jahrhunderts das Frizzoni-Haus in seiner jetzigen Gestalt gebaut wurde, wurde diese Türe zugemauert.
            Vielleicht wurden damals auch andere bauliche Veränderungen vorgenommen.<br>
            Bei der Renovation 1957 fand man an den Kanten der Fassade vorn rechts Sgraffitos aus dieser Zeit.
        </p>
        <p>
            Das Esszimmer und die darüberliegenden zwei Räume sind neuer. Dieser Teil wurde entweder beim Bau des
            Frizzoni-Hauses 1713 erbaut, oder beim Umbau 1827. Das ersieht man daran, dass die Wand des Esszimmers gegen
            den Gang bedeutend dicker ist als die Wand des Wohnzimmers gegen den Gang. Auch die Wand zwischen Esszimmer
            und Küche ist sehr dick. Es handelt sich hier offenbar um ehemalige Aussenmauern. Die Wand zwischen
            Esszimmer und Küche enthält im Innern Holzbalken, wie man beim Durchbruch der Durchreiche feststellte. Die
            Aussenmauern des Palazzins sind natürlich aus Holz, wie bei allen alten Engadiner Häusern, das heisst aus
            Baumstämmen, die dann aussen ca. einen Meter über dem Boden vorgemauert werden, d.h. mit einer Mauer bedeckt
            werden. Die Holzkonstruktion beginnt also etwa einen Meter über dem Erdboden.
        </p>
        <p>
            Das Wohnzimmer war also ursprünglich grösser und der Eingang war, von vorne gesehen, links vom Ofen, da der
            schmale Gang noch nicht existierte. Der Ofen im Wohnzimmer ist älter als der im Esszimmer. Er ist auch
            konstruktiv schlechter. Das Haus hatte ursprünglich vorn keinen Ausgang.
        </p>
    </div>
    <div class="col-sm-6 col-md-6 col-xs-12">
        <p>
            Die grundlegende Renovation erfolgte 1827 im Style des Spät-Klassizismus. Bei diesem Umbau legte man Wert
            auf eine regelmässige Fassade. Man vergrösserte wahrscheinlich auch damals die Fenster im Wohnzimmer, sofern
            man sie nicht neue machte. Täfer, Türen, Böden stammen aus dieser Zeit. Auch der Ofen im Esszimmer, auf
            jeden Fall der Ofen-Aufbau mit seinen neugotischen Verzierungen oberhalb der Türen. Die Türen des Aufbaus
            sind kunstvoll zusammengefügt, damit sie die Hitze aushalten.
        </p>
        <p>
            Der runde Stein im Ofenkästli hat uralte magische Bedeutung, er ist vielleicht so alt wie das älteste Haus.
        </p>
        <p>
            Küche, Gewölbe und Balkonzimmer wurden ziemlich sicher im jetzigen Zustand schon im 17. Jahrhundert,
            spätestens 1713 gebaut. Aus dieser Zeit stammen auch Türe und Fensterläden des Gewölbes und die eiserne Türe
            des Wohnzimmer-Ofens.
        </p>
        <p>
            Beim Umbau des Zimmers oberhalb des Wohnzimmers wurde interessanterweise das Seelenfenster an der Decke
            belassen, d.h. die Öffnung, durch welche beim Tod die Seele aus dem Haus entweichen kann.
        </p>
        <p>
            Die Küche mit dem darunterliegenden gewölbten Keller, das Gewölbe und das Zimmer darüber bilden den
            gemauerten, feuerfesten Kern. Deshalb auch die Eisentüren und die eisernen Läden beim Gewölbe. Im Gewölbe
            wurde die Wertsachen, die wichtigsten Dokumente, usw. aufbewahrt, um sie bei Brand- und Plünderungsgefahr zu
            retten.
        </p>
        <p>
            Der Umbau vom 1827 durch den in Berlin lebenden Zuckerbäcker Frizzoni war sehr praktisch, sehr modern, sehr
            rationalistisch. Später wurde dann in den 60er Jahren nochmals ein Umbau vorgenommen, der eher eine
            Verschlechterung bedeutete. Nach 1827 standen im hinteren Gang keine Kästen. Die Treppe, die in den 1. Stock
            führte, war offen, d.h. nur rechts mit einer Steinbrüstung versehen. Alle Wandschränke waren im hinteren
            Gang des 1. Stocks angebracht. Auf beiden Seiten des Raumes. Die Gänge waren hell und übersichtlich.
        </p>
    </div>
    <div class="col-sm-6 col-md-6 col-xs-12" style="border-top: .13333em solid #b7b7b7;">
        <p>
            Nun wurde der hintere Gang im 1. Stock zum Sääli ausgebaut. Ein Teil der, übrigens prachtvollen, Arvenkästen
            wurden im Gewölbe plaziert, der andere Teil links vom hinteren Eingang. Gleichzeitig wurde der äusserste
            schräge Kasten rechts erstellt, in etwas geringerer Qualität, und auch der Geschirrschrank im Gang. Die
            Küchentüre wurde ersetzt und in den hinteren Gang ein Abtritt eingebaut, an Stelle des Abtritts in der
            Scheune. Die pompejanischen Wandmalereien im Sääli, Gewölbe und Gang des 1. Stocks wurden ebenfalls damals
            gemacht, vielleicht durch einen italienischen Maler, ebenfalls die Sonnenuhr.
        </p>
        <p>Nicht geklärt ist unter anderem folgendes:
        <ul>
            <li>Wann wurde das Gotische Zimmer in die Winde gestellt? Da solche Zimmer ursprünglich der Beherbergung
                vorübergehender italienischer Saison-Arbeiter (Heuer etc.) dienten, ist anzunehmen, dass dieses Zimmer
                schon im 18. Vielleicht sogar im 17. Jahrhundert bei der Erbauung des Hauses, aufgestellt wurde
                (Jahreszahl oberhalb Türe 1687).
            </li>
            <li>Warum sind die beiden Haustüren im Stil Barock?</li>
            <li>Warum sind die Ornamente an den Arvenkästen Barock und nicht klassizistisch (die 3 eckigen
                Biedermeier-Messing-Schilder wurden später angebracht)? Vielleicht aus Stil-Verspätung, weil ein anderer
                Schreiner diese machte als die Täfer.
            </li>
            <li>Handelt es sich beim neuen Teil (Esszimmer) auch um einen Holzbau? Die Aussenmauern sind hier dünner als
                beim alten Teil.
            </li>
            <li>Warum ist die Balken-Konstruktion des Daches so merkwürdig und zum Teil unsorgfältig?</li>
        </ul>
        </p>
    </div>

    <div class="col-sm-6 col-md-6 col-xs-12" style="border-top: .13333em solid #b7b7b7;">
        <p>
            Im Jahre 1949 wurde das Palazzin für 38.000 fr. von der Familie Stein…(?) verkauft an: Adolf Guggenbühl,
            Jaques Schmid, Willi Schohaus und Sigmund Wolf. In den folgenden Jahren, vor allem nach dem Ausscheiden von
            J. Schmid und W. Schohaus, wurden für 60.000 fr. Umbauten und Renovationen vorgenommen: Einrichtungen von
            Kalt- und Warm-Wasser in der Küche, eines Badzimmers im Gewölbe, Erstellung eines Abortes in der Scheune,
            Verbesserung der elektrischen Installationen, Anbringen von Fenstern in der Spense, Errichtung eines Balkons
            im Balkonzimmer.
        </p>
        <p>
            Diese Arbeiten leitete der Architekt U. Könz von Guarda. Besonders teuer war die vollständige
            Fassadenrenovation 1957. Der Verputz wurde bis auf die Mauer abgeschlagen und neu gemacht. Die Malereien
            beim hinteren Eingang und die Sonnenuhr machte Könz junior.
        </p>
        <p>
            Bei dieser Gelegenheit wurden auch die Wände des Esszimmers, die lackiert waren und die Esszimmerdecke, die
            blau gestrichen war, abgelaugt, ferner wurde damals die Durchreiche herausgebrochen. Die Bänke im Esszimmer
            waren gleich nach dem Kauf errichtet worden.
        </p>
        <p>
            Die Möbel wurden im Laufe der Zeit angeschafft, bereits im Hause befanden sich lediglich der Esszimmertisch,
            der Tisch im Balkonzimmer, der Schragentisch in der Scheune, der grosse Tisch im Keller, die lange, schmale
            Truhe im unteren Gang, und die grosse Vorratstruhe im 2. Stock.
        </p>
    </div>
    <div class="col-sm-12 col-md-12 col-xs-12">
        <p style="font-weight: bold">
            Es ist der sehnliche Wunsch der jetzigen 2 Besitzerfamilien S. Wolf und A. Guggenbühl, dass dieses schöne
            Haus den Kindern und Kindeskindern möglichst lange erhalten bleibt.
        </p>
    </div>
    <div class="col-sm-12 col-md-12 col-xs-12">
        <p>
            <img id="planHouse" style="margin: 0 auto; text-align: center; display: block; width: 62%;" title="Zur Baugeschichte des Palazzins" alt="Zur Bauzgeschichte des Palazzins" src="{{asset('/docs/history/Zur-Baugeschichte-des-Palazzins-3.jpg')}}">
        <div id="planHouseModal" class="modal">


            <!-- Modal Content (The Image) -->
            <img class="modal-content" id="img01">

            <!-- Modal Caption (Image Text) -->
            <div id="caption"></div>
        </div>
        </p>
        <p>
            <a href="{{asset('/docs/history/Zur-Baugeschichte-des-Palazzins.pdf')}}">Als PDF herunterladen</a>
        </p>
    </div>
</div>
    <script>
        // Get the modal
        var modal = document.getElementById("planHouseModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById("planHouse");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        img.onclick = function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }

        // Get the <span> element that closes the modal
        var span = document.getElementById("planHouseModal");

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
    </script>

@endsection
