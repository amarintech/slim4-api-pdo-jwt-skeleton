<?php
namespace Model;
class Tools extends Meta
{
    public static function toto()
    {
        return 'toto2';
    }
    public function test2()
    {
        $a = $this->test();
        return $a;
    }
    public static function VerifLang($lang){
        $langs = array('fr-fr','en-us','de-de','pt-pt'); 
        if (in_array($lang, $langs)){
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    public static function urlExists($url = null)
    {
        $tB = microtime(true);
        if ($url == null) {
            return false;
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode >= 200 && $httpcode < 300) {
            $tA = microtime(true);
            return round((($tA - $tB) * 1000), 0) . " ms";
        } else {
            return false;
        }
    }
    public static function Pagination($page, $liste, $limit, $debut)
    {
        $total = $liste['total'];
        /* fin */
        if ($total > $limit) {
            $total = ceil($total / $limit);
            if ($page > 1) {
                $debut = ($page * $limit) - $limit;
            } else {
                $debut = '0';
            }
            //echo "pagination on affiche 0,24 sur un total de $total page ici $debut $page";
            $page_debut = $page - 5;
            $page_final = $page + 5;
            if ($page_debut <= '0') {
                $page_debut = '1';
                $page_final = 10;
                //on enleve les 3 petits points ?
            } else {
                $page_final = $page_debut + 9;
            }
            if ($page_final > $total) {
                $page_final = $total;
                //on enleve les 3 petits points
            }
            $pagination['pagination'] = array(
                $page,
                $total,
            );
            $pagination['pagination_level'] = array(
                $page_debut,
                $page_final,
                $total,
            );
            $pagination['path'] = dirname(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) . "/";
            $pagination['path_title'] = "test path1";
            $page_next = $page + 1;
            $next = dirname(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) . "/page-$page_next/";
            if ($page <= '2') {
                $pagination['before'] = dirname(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) . "/";
                $pagination['before_title'] = "test before1";
                if ($page <= '1') {
                    if (!empty($args['page']) && $args['page'] == '1') {
                        $pagination['path'] = dirname(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) . "/";
                        $pagination['path_title'] = "test path1";
                        $page_next = $page + 1;
                        $next = dirname(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) . "/page-2/";
                    } else {
                        $pagination['path'] = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
                        $pagination['path_title'] = "test path1";
                        $page_next = $page + 1;
                        $next = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . "page-2/";
                        
                    }
                }
            } else {
                $page_before = $page - 1;
                $pagination['before'] = dirname(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)) . "/page-$page_before/";
                $pagination['before_title'] = "test before2";
            }
            $pagination['next'] = $next;
            $pagination['next_title'] = "test next1";
            if ($page == $total) {
                $pagination['next'] = "";
                $pagination['next_title'] = "test next2";
            }
            return $pagination;
        } else {
            return array();
        }
    }
    public static function unique_key($array, $keyname)
    {
        $new_array = array();
        foreach ($array as $key => $value) {
            if (!isset($new_array[$value->$keyname])) {
                $new_array[$value->$keyname] = $value;
            }
        }
        $new_array = array_values($new_array);
        return $new_array;
    }
    public static function skip_accents($str, $charset = 'utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);
        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        $str = preg_replace('#&[^;]+;#', '', $str);
        return $str;
    }
    public static function slugifyOLD($string, $delimiter = '-') {
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }
    public static function slugify($text)
    {
        $text = htmlentities($text, ENT_NOQUOTES, 'utf-8');
        $text = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $text);
        $text = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $text);
        $text = preg_replace('#&[^;]+;#', '', $text);
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return '';
        }
        return $text;
    }
}
