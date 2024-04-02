<?php
/*
Plugin Name: Info Banner
Description: Information banner at the top of your site's pages
Version: 1.0
Author: Lucas Lacroix
Author URI: https://lucaslacroix.com/
*/

function ajouter_page_infobanner()
{
    add_menu_page(
        'Info Banner',          // Page title
        'Info Banner',          // Menu label
        'manage_options',       // role to access
        'info-banner',          // Slug
        'display_banner_page', // function to display page
        'dashicons-info'
    );
}

add_action('admin_menu', 'ajouter_page_infobanner');

function display_banner_page()
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if ($_POST['is-active'] == "on") {
            $isactive = 1;
        } else {
            $isactive = 0;
        }

        $newdata = array(
            'is-active' => $isactive,
            'text' => $_POST['text'],
            'url' => $_POST['url'],
            'end-date' => $_POST['end-date'],
            'text-color' => $_POST['text-color'],
            'background-color' => $_POST['background-color'],
            'close' => $_POST['close'],
            'cookies-day' => $_POST['cookies-day']
        );

        $data[0] = $newdata;
        file_put_contents(plugin_dir_path(__FILE__) . 'banner-data.json', json_encode($data, JSON_PRETTY_PRINT));
        header("Location: " . admin_url('admin.php?page=info-banner'));
        exit;
    }

    echo '
    <style>
    .formulaire div {
        display: flex;
        flex-direction: column;
        margin-bottom: 16px;
        max-width:520px;
    }

    p {
        max-width:700px;
    }

    form p {
        margin:0;
        color:#646464;
    }
    summary {
        cursor: pointer;
    }

    .checkbox {
        display: flex;
        flex-direction: row !important;
        gap: 2px;
        align-items: center;
    }

    #wpfooter {
        display: none;
    }

      </style>
    ';

    $langues = array(
        "fr" => array(
            "intro" => "Cette page d'administration vous permet de modifier le bandeau d'information en haut des pages de votre site.",
            "banner" => "Texte de la bannière",
            "link" => "Lien de la bannière",
            "if-empty" => "Laisser le champ vide si elle n'est pas clicable",
            "end" => "Date de fin",
            "display" => "Afficher sur le site",
            "advanced" => "Paramètres avancées",
            "color-text" => "Couleur du texte",
            "color-bg" => "Couleur de fond",
            "cookies" => "Durée des cookies (en jours)",
            "cookies-detail" => "Si un utilisateur ferme la bannière, cette dernière réapparaîtra qu'après la durée saisie.",
            "close-detail" => "Cette information est importante pour la navigation des personnes malvoyantes. <br>Elle précise l'action effectuée par la croix.",
            "close" => "Mot pour fermer",
            "update" => "Mettre à jour",
        ),
        "en" => array(
            "intro" => "This administration page allows you to modify the information banner at the top of your site's pages.",
            "banner" => "Banner text",
            "link" => "Banner link",
            "if-empty" => "Leave empty if not clickable",
            "end" => "End date",
            "display" => "Display on site",
            "advanced" => "Advanced settings",
            "color-text" => "Text color",
            "color-bg" => "Background color",
            "cookies" => "Cookie duration (days)",
            "cookies-detail" => "If a user closes the banner, it will only reappear after the entered duration.",
            "close-detail" => "This information is important for the navigation of visually impaired people. <br>It specifies the action performed by the cross.",
            "close" => "Close button text",
            "update" => "Update",
        ),
        "es" => array(
            "intro" => "Esta página de administración le permite modificar la pancarta de información en la parte superior de las páginas de su sitio.",
            "banner" => "Texto del banner",
            "link" => "Enlace del banner",
            "if-empty" => "Dejar vacío si no se puede hacer clic",
            "end" => "Fecha de fin",
            "display" => "Mostrar en el sitio",
            "advanced" => "Configuraciones avanzadas",
            "color-text" => "Color del texto",
            "color-bg" => "Color de fondo",
            "cookies" => "Duración de las cookies (días)",
            "cookies-detail" => "Si un usuario cierra la pancarta, esta última reaparecerá solo después de la duración ingresada.",
            "close-detail" => "Esta información es importante para la navegación de personas con discapacidad visual. <br>Especifica la acción realizada por la cruz.",
            "close" => "Texto del botón de cierre",
            "update" => "Actualizar",
        ),
        "de" => array(
            "intro" => "Diese Administrationsseite ermöglicht es Ihnen, das Informationsbanner am oberen Rand der Seiten Ihrer Website zu ändern.",
            "banner" => "Bannertext",
            "link" => "Banner-Link",
            "if-empty" => "Leer lassen, wenn nicht anklickbar",
            "end" => "Enddatum",
            "display" => "Auf der Website anzeigen",
            "advanced" => "Erweiterte Einstellungen",
            "color-text" => "Textfarbe",
            "color-bg" => "Hintergrundfarbe",
            "cookies" => "Dauer der Cookies (in Tagen)",
            "cookies-detail" => "Wenn ein Benutzer das Banner schließt, wird es erst nach Ablauf der eingegebenen Dauer erneut angezeigt.",
            "close-detail" => "Diese Information ist wichtig für die Navigation von sehbehinderten Menschen. <br>Sie gibt die Aktion an, die durch das Kreuz ausgeführt wird.",
            "close" => "Text des Schließen-Buttons",
            "update" => "Aktualisieren"
        )
    );

    $langueNavigateur = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

    if (!isset($langues[$langueNavigateur])) {
        $langueNavigateur = "en";
    }

    switch ($langueNavigateur):
        case "fr":
            $langueNavigateur = "fr";
            break;
        case "en":
            $langueNavigateur = "en";
            break;
        case "es":
            $langueNavigateur = "es";
            break;
        default:
        $langueNavigateur = "en";
    endswitch;

    echo "<h1>Info Banner</h1>
    <p>". $langues[$langueNavigateur]["intro"] . "</p>
    ";

    $content_json = file_get_contents(plugin_dir_path(__FILE__) . 'banner-data.json');

    if ($content_json !== false) {
        $data = json_decode($content_json, true);

        if ($data !== null) {
            echo "<form method='post' class='formulaire'>";
            foreach ($data as $info) {
                echo '
                <div>
                <label for="text">' . $langues[$langueNavigateur]["banner"] . '</label>
                <textarea id="text" name="text" rows="3" cols="33" maxlength="80">' . esc_html($info['text']) . '</textarea>
                </div>
                <div>
                <label for="url">' . $langues[$langueNavigateur]["link"] . '</label>
                <p>' . $langues[$langueNavigateur]["if-empty"] . '</p>
                <input type="url" name="url" id="url" value="' . esc_html($info['url']) . '" />
                </div>   
                <div>
                    <label for="end-date">' . $langues[$langueNavigateur]["end"] . '</label>
                    <input type="date" name="end-date" id="end-date" min="' . date('Y-m-d', time()) . '" value="' . esc_html($info['end-date']) . '" />
                </div>
                 <div class="checkbox">
                ';

                if ($info['is-active'] == 1) {
                    echo '<input type="checkbox" checked id="is-active" name="is-active" /><label for="is-active">' . $langues[$langueNavigateur]["display"] . '</label>';
                } else {
                    echo '<input type="checkbox" id="is-active" name="is-active" /><label for="is-active">' . $langues[$langueNavigateur]["display"] . '</label>';
                }
            }
            echo '</div>
                <div>
                <details>
                <summary>' . $langues[$langueNavigateur]["advanced"] . '</summary>
                
                <div>
                    <label for="text-color">' . $langues[$langueNavigateur]["color-text"] . '</label>
                    <input type="color" name="text-color" id="text-color" value="' . esc_html($info['text-color']) . '" />
                </div>
                <div>
                    <label for="background-color">' . $langues[$langueNavigateur]["color-bg"] . '</label>
                    <input type="color" name="background-color" id="background-color" value="' . esc_html($info['background-color']) . '" />
                </div>
                <div>
                    <label for="close">' . $langues[$langueNavigateur]["close"] . '</label>
                    <p>' . $langues[$langueNavigateur]["close-detail"] . '</p>
                    <input required type="text" name="close" id="close" value="' . esc_html($info['close']) . '" />
                </div>
                <div>
                    <label for="cookies-day">' . $langues[$langueNavigateur]["cookies"] . '</label>
                    <p>' . $langues[$langueNavigateur]["cookies-detail"] . '</p>
                    <input required type="number" name="cookies-day" id="cookies-day" min="1" max="60" value="' . esc_html($info['cookies-day']) . '" />
                </div>
                </details>
              
                </div>
             
            <button type="submit" class="button button-primary" />' . $langues[$langueNavigateur]["update"] . '</button>
            </form>';
        }
    }
}

function ajouter_html_en_haut_de_page()
{
    $content_json = file_get_contents(plugin_dir_path(__FILE__) . 'banner-data.json');

    if ($content_json !== false) {
        $data = json_decode($content_json, true);

        if ($data !== null) {
            foreach ($data as $info) {
                if ($info['is-active'] == "1") {
                    if ($info['end-date'] !== null && $info['end-date'] !== '') {
                        if (strtotime($info['end-date']) !== false) {
                            $currentTimestamp = time();
                            $endDateTimestamp = strtotime($info['end-date']);

                            if ($endDateTimestamp > $currentTimestamp) {
                                echo display_banner($info);
                            } else {
                                // La date de fin est passée
                            }
                        } else {
                            echo display_banner($info);
                        }
                    } else {
                        echo display_banner($info);
                    }
                }
            }
        }
    }
}

function display_banner($info)
{
    echo '
    <div id="top-information-banner" role="status">
        <div>';

    if ($info['url'] == "") {
        echo '<p>' . esc_html($info['text']) . '</p>';
    } else {
        echo '<a href="' . esc_html($info['url']) . '">' . esc_html($info['text']) . '</a>';
    }
    echo '
         <button aria-label="' . esc_html($info['close']) . '" onclick="close_banner()">
         <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:1.5"><path style="fill:none" d="M173.879 114.512h46.806v46.806h-46.806z" transform="translate(-89.1583 -58.71) scale(.51276)"/><path d="m938.2 5.05-9.864 9.864" style="fill:none;stroke:' . esc_html($info['text-color']) . ';stroke-width:1.26px" transform="matrix(1.19236 0 0 1.19236 -1100.7928 .1051)"/><path d="m938.2 5.05-9.864 9.864" style="fill:none;stroke:' . esc_html($info['text-color']) . ';stroke-width:1.26px" transform="matrix(0 1.19236 -1.19236 0 23.9022 -1100.7819)"/></svg></button>
        </div>
    </div>
    ';

    echo '
    <style>
    #top-information-banner {
        background-color: ' . esc_html($info['background-color']) . ';
        color: ' . esc_html($info['text-color']) . '; 
    }

    #top-information-banner div {
        max-width: 1224px;
        margin:0 auto;
        font-size: 14px;
        padding: 2px 12px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap:12px;
        font-weight:bold;
    }

    #top-information-banner button {
        background-color: rgba(0, 0, 0, 0);
        color: ' . esc_html($info['text-color']) . '; 
        margin:0;
        border:0;
        padding:2px;
        cursor:pointer;
    }
    #top-information-banner svg {
        height: 24px;
        width: 24px;
    }

    #top-information-banner p,  #top-information-banner a  {
        margin-bottom:0;
        width:100%;
        text-align: center;
        color: ' . esc_html($info['text-color']) . '; 
        text-decoration:none;
    }
    </style>
    <script>
        function close_banner() {
            document.getElementById("top-information-banner").parentNode.removeChild(document.getElementById("top-information-banner"));
            definir_cookie();
        }

        if (document.cookie.indexOf("hideInfoBanner") === -1) {
          
        } else {
            document.getElementById("top-information-banner").style.display = "none";
        }

        function definir_cookie() {
            var date = new Date();
            date.setTime(date.getTime() + (2 * 24 * 60 * 60 * 1000));
            var expires = "expires=" + date.toUTCString();
            // Définir le cookie avec le nom "hideInfoBanner" et la valeur "true" et la date dexpiration
            document.cookie = "hideInfoBanner=true;" + expires + ";path=/";
        }
    </script>
    ';
}

add_action('wp_head', 'ajouter_html_en_haut_de_page');
