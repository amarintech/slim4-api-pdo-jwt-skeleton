<?php
namespace Model {
    class Naf extends Meta
    {   
        public function ListCountNafs_dp_categorie($categorie_id, $dp)
        {
            $sql = "SELECT code_naf,libelle_naf,count(*) as count from abase as t1 LEFT JOIN pros_rel_categories as t2 ON t1.id = t2.pro_id WHERE t2.categorie_id = :categorie_id AND departement = :dp GROUP BY code_naf ORDER BY count DESC";
                    $query = $this
                        ->db
                        ->prepare($sql);
                    $query->execute(array(
                        'categorie_id' => $categorie_id,
                        'dp' => $dp,
                    ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListCountNafs_region_categorie($categorie_id, $region_id)
        {
            $sql = "SELECT code_naf,libelle_naf,count(*) as count from abase as t1 LEFT JOIN pros_rel_categories as t2 ON t1.id = t2.pro_id WHERE t2.categorie_id = :categorie_id AND region_id = :region_id GROUP BY code_naf ORDER BY count DESC";
                    $query = $this
                        ->db
                        ->prepare($sql);
                    $query->execute(array(
                        'categorie_id' => $categorie_id,
                        'region_id' => $region_id,
                    ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListCountNafs_ville_categorie($categorie_id, $ville_nom_search_cp)
        {
            switch ($ville_nom_search_cp) {
                case 'paris':

                    $sql = "SELECT code_naf,libelle_naf,count(*) as count from abase as t1 LEFT JOIN pros_rel_categories as t2 ON t1.id = t2.pro_id WHERE t2.categorie_id = :categorie_id AND cp LIKE '75%' GROUP BY code_naf ORDER BY count DESC";
                    $query = $this
                        ->db
                        ->prepare($sql);
                    $query->execute(array(
                        'categorie_id' => $categorie_id
                    ));

                break;
                case 'lyon':
                    $sql = "SELECT code_naf,libelle_naf,count(*) as count from abase as t1 LEFT JOIN pros_rel_categories as t2 ON t1.id = t2.pro_id WHERE t2.categorie_id = :categorie_id AND cp LIKE '69%' GROUP BY code_naf ORDER BY count DESC";
                    $query = $this
                        ->db
                        ->prepare($sql);
                    $query->execute(array(
                        'categorie_id' => $categorie_id
                    ));
                    break;
                case 'marseille':
                    $sql = "SELECT code_naf,libelle_naf,count(*) as count from abase as t1 LEFT JOIN pros_rel_categories as t2 ON t1.id = t2.pro_id WHERE t2.categorie_id = :categorie_id AND cp LIKE '13%' GROUP BY code_naf ORDER BY count DESC";
                    $query = $this
                        ->db
                        ->prepare($sql);
                    $query->execute(array(
                        'categorie_id' => $categorie_id
                    ));
                    break;
                default:
                    $sql = "SELECT code_naf,libelle_naf,count(*) as count from abase as t1 LEFT JOIN pros_rel_categories as t2 ON t1.id = t2.pro_id WHERE t2.categorie_id = :categorie_id AND ville_nom_search_cp = :ville_nom_search_cp GROUP BY code_naf ORDER BY count DESC";
                    $query = $this
                        ->db
                        ->prepare($sql);
                    $query->execute(array(
                        'categorie_id' => $categorie_id,
                        'ville_nom_search_cp' => $ville_nom_search_cp,
                    ));
                    break;
            }

            $details = $query->fetchAll();
            return $details;
        }
        public function GetDetails_where_lettre($lettre)
        {
            //categorie_1
            $sql = "SELECT * FROM code_naf WHERE lettre = :lettre LIMIT 1";
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'lettre' => $lettre,
            ));
            $section = $query->fetch();
            return $section;
        }
        public function ListNafsServices_from_nafs_where($nafs, $where, $where_id, $limit, $cp = '')
        {
            $naf_in = '';
            foreach ($nafs as $key => $value) {
                $naf_in .= "'$value->naf_2008',";
            }
            $naf_in = trim($naf_in, ',');
            //on cherche les nafs et les services correspondant par ordre de count dans la db
            if ($where == 'ville_nom_search_cp') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE code_naf in ($naf_in) AND (ville_nom_search_cp = :ville_nom_search_cp OR cp = :cp) GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'cp' => $cp,
                    'ville_nom_search_cp' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
            if ($where == 'dp') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE code_naf in ($naf_in) AND departement = :departement GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'departement' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
            if ($where == 'region') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE code_naf in ($naf_in) AND region_id = :region_id GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'region_id' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
        }
        public function ListNafsServices_from_numeros_where($numeros, $where, $where_id, $limit)
        {
            $numero_in = '';
            foreach ($numeros as $key => $value) {
                $numero_in .= "'$value->numero',";
            }
            $numero_in = trim($numero_in, ',');
            //on cherche les nafs et les services correspondant par ordre de count dans la db
            if ($where == 'ville_nom_search_cp') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE numero_naf IN ($numero_in) AND ville_nom_search_cp = :ville_nom_search_cp GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'ville_nom_search_cp' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
            if ($where == 'dp') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE numero_naf IN ($numero_in) AND departement = :departement GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'departement' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
            if ($where == 'region') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE numero_naf IN ($numero_in) AND region_id = :region_id GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'region_id' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
        }
        public function ListNafsServices_from_lettre_where($lettre, $where, $where_id, $limit)
        {
            //on cherche les nafs et les services correspondant par ordre de count dans la db
            if ($where == 'ville_nom_search_cp') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE lettre_naf = :lettre AND ville_nom_search_cp = :ville_nom_search_cp GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'lettre' => $lettre,
                    'ville_nom_search_cp' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
            if ($where == 'dp') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE lettre_naf = :lettre AND departement = :departement GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'lettre' => $lettre,
                    'departement' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
            if ($where == 'region') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE lettre_naf = :lettre AND region_id = :region_id GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'lettre' => $lettre,
                    'region_id' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
        }
        public function ListCategories_from_lettre_where($lettre, $where, $where_id, $limit)
        {
            //on cherche les nafs et les services correspondant par ordre de count dans la db
            if ($where == 'ville_nom_search_cp') {
                $sql = "SELECT *,count(*) as count FROM (SELECT t1.id,t1.code_naf,t2.categorie_id,libelle,t1.libelle_naf FROM abase as t1 LEFT JOIN nafs_rel_categories as t2 ON t1.code_naf = t2.naf_2008 LEFT JOIN categories_final ON t2.categorie_id = categories_final.id WHERE lettre_naf = :lettre AND ville_nom_search_cp = :ville_nom_search_cp and t2.categorie_id IS NOT NULL GROUP BY t1.id
                    UNION
                    SELECT t1.id,t1.code_naf,t2.categorie_id,libelle,t1.libelle_naf FROM abase as t1 LEFT JOIN pros_rel_categories as t2 ON t1.id = t2.pro_id LEFT JOIN categories_final ON t2.categorie_id = categories_final.id WHERE lettre_naf = :lettre AND ville_nom_search_cp = :ville_nom_search_cp and t2.categorie_id IS NOT NULL) as final GROUP BY categorie_id";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'lettre' => $lettre,
                    'ville_nom_search_cp' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
            if ($where == 'dp') {
                /* $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE lettre_naf = :lettre AND departement = :departement GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                 */
                $sql = "SELECT *,count(*) as count FROM (SELECT t1.id,t1.code_naf,t2.categorie_id,libelle,t1.libelle_naf FROM abase as t1 LEFT JOIN nafs_rel_categories as t2 ON t1.code_naf = t2.naf_2008 LEFT JOIN categories_final ON t2.categorie_id = categories_final.id WHERE lettre_naf = :lettre AND departement = :departement and t2.categorie_id IS NOT NULL GROUP BY t1.id
                    UNION
                    SELECT t1.id,t1.code_naf,t2.categorie_id,libelle,t1.libelle_naf FROM abase as t1 LEFT JOIN pros_rel_categories as t2 ON t1.id = t2.pro_id LEFT JOIN categories_final ON t2.categorie_id = categories_final.id WHERE lettre_naf = :lettre AND departement = :departement and t2.categorie_id IS NOT NULL) as final GROUP BY categorie_id";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'lettre' => $lettre,
                    'departement' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
            if ($where == 'region') {
                $sql = "SELECT abase.code_naf,libelle_2008,libelle_2008_slug,services,services_slug,count(*) as count FROM abase LEFT JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE lettre_naf = :lettre AND region_id = :region_id GROUP BY code_naf ORDER BY count DESC LIMIT $limit";
                $sql = "SELECT *,count(*) as count FROM (SELECT t1.id,t1.code_naf,t2.categorie_id,libelle,t1.libelle_naf FROM abase as t1 LEFT JOIN nafs_rel_categories as t2 ON t1.code_naf = t2.naf_2008 LEFT JOIN categories_final ON t2.categorie_id = categories_final.id WHERE lettre_naf = :lettre AND region_id = :region_id and t2.categorie_id IS NOT NULL GROUP BY t1.id
                UNION
                SELECT t1.id,t1.code_naf,t2.categorie_id,libelle,t1.libelle_naf FROM abase as t1 LEFT JOIN pros_rel_categories as t2 ON t1.id = t2.pro_id LEFT JOIN categories_final ON t2.categorie_id = categories_final.id WHERE lettre_naf = :lettre AND region_id = :region_id and t2.categorie_id IS NOT NULL) as final GROUP BY categorie_id";
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'lettre' => $lettre,
                    'region_id' => $where_id,
                ));
                $details = $query->fetchAll();
                return $details;
            }
        }
        public function GetDetails_where_division_slug($division_slug)
        {
            //categorie_2
            $sql = "SELECT * FROM code_naf WHERE division_slug = :division_slug LIMIT 1";
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'division_slug' => $division_slug,
            ));
            $division = $query->fetch();
            return $division;
        }
        public function GetDetails_where_titre_slug($titre_slug)
        {
            //categorie_2
            $sql = "SELECT * FROM code_naf WHERE titre_slug = :titre_slug LIMIT 1";
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'titre_slug' => $titre_slug,
            ));
            $division = $query->fetch();
            return $division;
        }
        public function CountNaf($naf){
            $sql = "SELECT count(*) as count FROM abase WHERE code_naf = :code_naf";
            $this
            ->db
            ->exec("SET CHARACTER SET utf8");
        $query = $this
            ->db
            ->prepare($sql);
        $query->execute(array(
            'code_naf' => $naf
        ));
        $details = $query->fetch();
        return $details->count;

        }
        public function GetDetails_where_naf($naf)
        {
            //$sql = "SELECT * FROM zne_tranche WHERE zne LIKE '$suite%'";
            //$sql = "SELECT * FROM zne_tranche t1 INNER JOIN zne_departements t2 on t1.territoire = t2.territoire WHERE t1.zne LIKE '$suite%' GROUP BY t1.territoire";
            //$sql = "SELECT count('code_naf') as nombre,code_naf,libelle_naf FROM abase WHERE code_naf = '923K' GROUP BY code_naf ORDER BY nombre DESC";
            $sql = "SELECT * FROM code_naf WHERE naf_2003 = :naf OR naf_2008 = :naf";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'naf' => $naf,
            ));
            $details = $query->fetch();
            return $details;
        }
        public function ListNafs_cp($cp, $nom_slug, $limit = '')
        {
            if (!empty($limit)) {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE cp = :cp AND ville_nom_search = :ville_nom_search AND code_naf != '0000Z' GROUP BY code_naf ORDER by count DESC LIMIT :limit";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->bindValue(':cp', $cp);
                $query->bindValue(':ville_nom_search', $nom_slug);
                $query->bindValue(':limit', intval($limit), $this->db::PARAM_INT);
            } else {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE cp = :cp AND ville_nom_search = :ville_nom_search AND code_naf != '0000Z' GROUP BY code_naf ORDER by count DESC";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->bindValue(':cp', $cp);
                $query->bindValue(':ville_nom_search', $nom_slug);
            }
            $query->execute();
            $details = $query->fetchAll();
            return $details;
        }
        public function ListNafs_multi_cp($cp, $limit = '')
        {
            if (!empty($limit)) {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE cp in ($cp) AND code_naf != '0000Z' GROUP BY code_naf ORDER by count DESC LIMIT :limit";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->bindValue(':limit', intval($limit), $this->db::PARAM_INT);
            } else {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE cp in ($cp) AND code_naf != '0000Z' GROUP BY code_naf ORDER by count DESC";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->bindValue(':cp', $cp);
            }
            $query->execute();
            $details = $query->fetchAll();
            return $details;
        }
        public function ListNafs_dp($dp, $limit = '')
        {
            if (!empty($limit)) {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE departement = :dp AND code_naf != '0000Z' GROUP BY code_naf ORDER by count DESC LIMIT :limit";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->bindValue(':dp', $dp);
                $query->bindValue(':limit', intval($limit), $this->db::PARAM_INT);
            } else {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE departement = :dp AND code_naf != '0000Z' GROUP BY code_naf ORDER by count DESC";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->bindValue(':dp', $dp);
            }
            $query->execute();
            $details = $query->fetchAll();
            return $details;
        }
        public function ListSections()
        {
            //$sql = "SELECT * FROM zne_tranche WHERE zne LIKE '$suite%'";
            //$sql = "SELECT * FROM zne_tranche t1 LEFT JOIN zne_departements t2 on t1.territoire = t2.territoire WHERE t1.zne LIKE '$suite%' GROUP BY t1.territoire";
            //$sql = "SELECT count('code_naf') as nombre,code_naf,libelle_naf FROM abase WHERE code_naf = '923K' GROUP BY code_naf ORDER BY nombre DESC";
            $sql = "SELECT * FROM code_naf group by section ORDER BY lettre ASC";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute();
            $details = $query->fetchAll();
            return $details;
        }
        public function ListSections_cp($cp)
        {
            $sql = "SELECT t.*,count(*) as count from ( SELECT code_naf,section,section_slug,division,division_slug,titre,titre_slug,lettre,libelle_2008_slug FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE cp = :cp
UNION ALL
SELECT code_naf,section,section_slug,division,division_slug,titre,titre_slug,lettre,libelle_2008_slug FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2003 WHERE cp = :cp) AS t GROUP BY section ORDER BY lettre ASC
";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'cp' => $cp,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListSections_dp($dp)
        {
            $sql = "SELECT t.*,count(*) as count from ( SELECT code_naf,section,section_slug,division,division_slug,titre,titre_slug,lettre,libelle_2008_slug FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE departement = :dp
UNION ALL
SELECT code_naf,section,section_slug,division,division_slug,titre,titre_slug,lettre,libelle_2008_slug FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2003 WHERE departement = :dp) AS t GROUP BY section ORDER BY lettre ASC";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'dp' => $dp,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListSections_region($region_id)
        {
            $sql = "SELECT code_naf,lettre_naf,count(*) as count FROM abase WHERE region_id = :region_id GROUP BY lettre_naf
ORDER BY lettre_naf ASC";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'region_id' => $region_id,
            ));
            $details = $query->fetchAll();
            foreach ($details as $key => $value) {
                $sql = "SELECT section,section_slug FROM code_naf WHERE lettre = :lettre_naf Order by section ASC";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'lettre_naf' => $value->lettre_naf,
                ));
                $section = $query->fetch();
                $value->section = $section->section;
                $value->section_slug = $section->section_slug;
            }
            usort($details, function ($item1, $item2) {
                return $item1->section <=> $item2->section;
            });
            return $details;
        }
        public function ListDivisions_ville_nom_search_cp_where_lettre($lettre, $ville_nom_search_cp)
        {
            $sql = "SELECT count(*) as count,t.* FROM
(SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2003 WHERE ville_nom_search_cp = :ville_nom_search_cp AND lettre = :lettre
UNION ALL
SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE ville_nom_search_cp = :ville_nom_search_cp AND lettre = :lettre) as t GROUP BY division";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'ville_nom_search_cp' => $ville_nom_search_cp,
                'lettre' => $lettre,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListDivisions_where_lettre($lettre)
        {
            //$sql = "SELECT * FROM zne_tranche WHERE zne LIKE '$suite%'";
            //$sql = "SELECT * FROM zne_tranche t1 LEFT JOIN zne_departements t2 on t1.territoire = t2.territoire WHERE t1.zne LIKE '$suite%' GROUP BY t1.territoire";
            //$sql = "SELECT count('code_naf') as nombre,code_naf,libelle_naf FROM abase WHERE code_naf = '923K' GROUP BY code_naf ORDER BY nombre DESC";
            $sql = "SELECT * FROM code_naf WHERE lettre = :lettre GROUP BY division ORDER BY division ASC";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'lettre' => $lettre,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListDivisions_dp_where_lettre($lettre, $dp)
        {
            $sql = "SELECT count(*) as count,t.* FROM
(SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2003 WHERE departement = :dp AND lettre = :lettre
UNION ALL
SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE departement = :dp AND lettre = :lettre) as t GROUP BY division";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'dp' => $dp,
                'lettre' => $lettre,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListDivisions_region($lettre, $region_id)
        {
            $sql = "SELECT count(*) as count,t.* FROM
(SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2003 WHERE region_id = :region_id AND lettre = :lettre
UNION ALL
SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE region_id = :region_id AND lettre = :lettre) as t GROUP BY division";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'region_id' => $region_id,
                'lettre' => $lettre,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListNafs_ville_nom_search_cp_where_titre($titre, $ville_nom_search_cp)
        {
            $sql = "SELECT count(*) as count,t.* FROM
(SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE cp = :cp AND titre_slug = :titre
UNION ALL
SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2003 WHERE cp = :cp AND titre_slug = :titre) as t GROUP BY libelle_2008";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'ville_nom_search_cp' => $ville_nom_search_cp,
                'titre' => $titre,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListNafs_dp_where_titre($titre, $dp)
        {
            $sql = "SELECT count(*) as count,t.* FROM
(SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE departement = :dp AND titre_slug = :titre
UNION ALL
SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2003 WHERE departement = :dp AND titre_slug = :titre) as t GROUP BY libelle_2008";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'dp' => $dp,
                'titre' => $titre,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListNafs_region($region_id, $limit = '')
        {
            if (!empty($limit)) {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE region_id = :region_id AND code_naf != '0000Z' GROUP BY code_naf ORDER by count DESC LIMIT :limit";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->bindValue(':region_id', $region_id);
                $query->bindValue(':limit', intval($limit), $this->db::PARAM_INT);
            } else {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE region_id = :region_id AND code_naf != '0000Z' GROUP BY code_naf ORDER by count DESC";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->bindValue(':region_id', $region_id);
            }
            $query->execute();
            $details = $query->fetchAll();
            return $details;
        }
        public function ListNafs_region_where_titre($titre, $region_id)
        {
            $sql = "SELECT count(*) as count,t.* FROM
(SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2008 WHERE region_id = :region_id AND titre_slug = :titre
UNION ALL
SELECT code_naf.* FROM abase INNER JOIN code_naf ON abase.code_naf = code_naf.naf_2003 WHERE region_id = :region_id AND titre_slug = :titre) as t GROUP BY libelle_2008";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'region_id' => $region_id,
                'titre' => $titre,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListNafs_cascades_sitemaps($naf, $libelle_slug)
        {
            $total = array();
            $sql = "SELECT t2.* FROM abase as t1 LEFT JOIN regions as t2 ON t1.region_id = t2.region_id WHERE code_naf = :naf AND t1.region_id != 15 GROUP BY t1.region_id";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'naf' => $naf,
            ));
            $regions = $query->fetchAll();
            foreach ($regions as $key => $region) {
                //$sql = "SELECT t2.* FROM abase as t1 LEFT JOIN departement as t2 ON t1.departement = t2.departement_id WHERE code_naf = :naf AND t1.region_id = :region_id GROUP BY t1.departement";
                $sql = "SELECT t2.* FROM abase as t1 LEFT JOIN departement as t2 ON t1.departement = t2.code WHERE code_naf = :naf AND t1.region_id = :region_id GROUP BY t1.departement";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'naf' => $naf,
                    'region_id' => $region->region_id,
                ));
                $total['url'][] = "/region-$region->nom_slug-$region->region_id/";
                $departements = $query->fetchAll();
                foreach ($departements as $key => $departement) {
                    $sql = "SELECT cp,ville,ville_nom_search,count(*) as count FROM abase WHERE code_naf = :naf AND departement = :departement group by ville_nom_search ORDER BY count";
                    $this
                        ->db
                        ->exec("SET CHARACTER SET utf8");
                    $query = $this
                        ->db
                        ->prepare($sql);
                    $query->execute(array(
                        'naf' => $naf,
                        'departement' => $departement->code,
                    ));
                    $villes = $query->fetchAll();
                    $total['url'][] = "/departement-$departement->nom_slug-$departement->code/";
                    foreach ($villes as $key => $ville) {
                        $total['url'][] = "/ville-$ville->ville_nom_search-$ville->cp/";
                    }
                }
            }
            return $total;
        }
        public function ListNafs_cascades($naf, $libelle_slug)
        {
            $total = array();
            $sql = "SELECT t2.*,count(*) as count FROM abase as t1 LEFT JOIN regions as t2 ON t1.region_id = t2.region_id WHERE code_naf = :naf AND t1.region_id != 15 GROUP BY t1.region_id";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'naf' => $naf,
            ));
            $regions = $query->fetchAll();
            foreach ($regions as $key => $region) {
                //$sql = "SELECT t2.* FROM abase as t1 LEFT JOIN departement as t2 ON t1.departement = t2.departement_id WHERE code_naf = :naf AND t1.region_id = :region_id GROUP BY t1.departement";
                $sql = "SELECT t2.*,count(*) as count FROM abase as t1 LEFT JOIN departement as t2 ON t1.departement = t2.code WHERE code_naf = :naf AND t1.region_id = :region_id GROUP BY t1.departement";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'naf' => $naf,
                    'region_id' => $region->region_id,
                ));
                $total['regions'][$region->region_id]['info'] = $region;
                $total['regions'][$region->region_id]['url']['link'] = $this->get_link_schema('url', 'naf_region', array(
                    'naf' => $naf,
                    'libelle' => $libelle_slug,
                    'slug' => $region->nom_slug,
                    'region_id' => $region->region_id,
                ));
                $total['regions'][$region->region_id]['url']['title'] = $this->get_variable_element('title', 'naf_region', array(
                    'naf' => $naf,
                    'libelle' => $libelle_slug,
                    'slug' => $region->nom_slug,
                    'region_id' => $region->region_id,
                ));
                $departements = $query->fetchAll();
                foreach ($departements as $key => $departement) {
                    $sql = "SELECT cp,ville,ville_nom_search,count(*) as count FROM abase WHERE code_naf = :naf AND departement = :departement group by ville_nom_search ORDER BY count limit 5";
                    $this
                        ->db
                        ->exec("SET CHARACTER SET utf8");
                    $query = $this
                        ->db
                        ->prepare($sql);
                    $query->execute(array(
                        'naf' => $naf,
                        'departement' => $departement->code,
                    ));
                    $villes = $query->fetchAll();
                    $total['regions'][$region->region_id]['departements'][$departement->code]['info'] = $departement;
                    $total['regions'][$region->region_id]['departements'][$departement->code]['url']['link'] = $this->get_link_schema('url', 'naf_departement', array(
                        'naf' => $naf,
                        'libelle' => $libelle_slug,
                        'slug' => $departement->nom_slug,
                        'dp' => $departement->code,
                    ));
                    $total['regions'][$region->region_id]['departements'][$departement->code]['url']['title'] = $this->get_variable_element('title', 'naf_departement', array(
                        'naf' => $naf,
                        'libelle' => $libelle_slug,
                        'slug' => $departement->nom_slug,
                        'dp' => $departement->code,
                    ));
                    foreach ($villes as $key => $ville) {
                        if (empty($ville->nom)) {
                            $ville->nom = ucfirst(strtolower($ville->ville));
                        }
                        if (empty($total['regions'][$region->region_id]['departements'][$departement->code]['villes'][$ville->ville_nom_search]['info'])) {
                            $total['regions'][$region->region_id]['departements'][$departement->code]['villes'][$ville->ville_nom_search]['info'] = $ville;
                            $total['regions'][$region->region_id]['departements'][$departement->code]['villes'][$ville->ville_nom_search]['url']['link'] = $this->get_link_schema('url', 'naf_cp', array(
                                'naf' => $naf,
                                'libelle' => $libelle_slug,
                                'slug' => $ville->ville_nom_search,
                                'cp' => $ville->cp,
                            ));
                            $total['regions'][$region->region_id]['departements'][$departement->code]['villes'][$ville->ville_nom_search]['url']['title'] = $this->get_variable_element('title', 'naf_cp', array(
                                'naf' => $naf,
                                'libelle' => $libelle_slug,
                                'slug' => $ville->ville_nom_search,
                                'cp' => $ville->cp,
                            ));
                        }
                        /*$total['regions'][$region->region_id]['departements'][$departement->code]['villes'][$ville->cp]['info'] = $ville;
                    $total['regions'][$region->region_id]['departements'][$departement->code]['villes'][$ville->cp]['link'] = $this->get_link_schema('url', 'naf_cp', array('naf'=>$naf,'libelle'=>$libelle_slug,'slug'=>$ville->nom_search,'cp'=>$ville->cp));
                    $total['regions'][$region->region_id]['departements'][$departement->code]['villes'][$ville->cp]['title'] = $this->get_variable_element('title', 'naf_cp', array('naf'=>$naf,'libelle'=>$libelle_slug,'slug'=>$ville->nom_search,'cp'=>$ville->cp));*/
                    }
                }
            }
            return $total;
        }
        public function ListNafs_where_titre($titre)
        {
            //$sql = "SELECT * FROM zne_tranche WHERE zne LIKE '$suite%'";
            //$sql = "SELECT * FROM zne_tranche t1 INNER JOIN zne_departements t2 on t1.territoire = t2.territoire WHERE t1.zne LIKE '$suite%' GROUP BY t1.territoire";
            //$sql = "SELECT count('code_naf') as nombre,code_naf,libelle_naf FROM abase WHERE code_naf = '923K' GROUP BY code_naf ORDER BY nombre DESC";
            $sql = "SELECT * FROM code_naf WHERE titre_slug = :titre";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'titre' => $titre,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListTitre_where_division($division)
        {
            //$sql = "SELECT * FROM zne_tranche WHERE zne LIKE '$suite%'";
            //$sql = "SELECT * FROM zne_tranche t1 INNER JOIN zne_departements t2 on t1.territoire = t2.territoire WHERE t1.zne LIKE '$suite%' GROUP BY t1.territoire";
            //$sql = "SELECT count('code_naf') as nombre,code_naf,libelle_naf FROM abase WHERE code_naf = '923K' GROUP BY code_naf ORDER BY nombre DESC";
            $sql = "SELECT * FROM code_naf WHERE division_slug = :division GROUP BY titre";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'division' => $division,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListTitre_ville_nom_search_cp_where_division($division, $ville_nom_search_cp)
        {
            $sql = "SELECT code_naf.*,count(code_naf.id) as count FROM abase INNER JOIN code_naf ON code_naf.naf_2008 = abase.code_naf WHERE division_slug = :division AND ville_nom_search_cp = :ville_nom_search_cp GROUP BY titre
      UNION
      SELECT code_naf.*,count(code_naf.id) as count FROM abase INNER JOIN code_naf ON code_naf.naf_2003 = abase.code_naf WHERE division_slug = :division AND ville_nom_search_cp = :ville_nom_search_cp GROUP BY titre ";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'ville_nom_search_cp' => $ville_nom_search_cp,
                'division' => $division,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListTitre_dp_where_division($division, $dp)
        {
            $sql = "SELECT code_naf.*,count(code_naf.id) as count FROM abase INNER JOIN code_naf ON code_naf.naf_2008 = abase.code_naf WHERE division_slug = :division AND departement = :dp GROUP BY titre
      UNION
      SELECT code_naf.*,count(code_naf.id) as count FROM abase INNER JOIN code_naf ON code_naf.naf_2003 = abase.code_naf WHERE division_slug = :division AND departement = :dp GROUP BY titre";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'dp' => $dp,
                'division' => $division,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListTitre_region_where_division($division, $region_id)
        {
            $sql = "SELECT code_naf.*,count(code_naf.id) as count FROM abase INNER JOIN code_naf ON code_naf.naf_2008 = abase.code_naf WHERE division_slug = :division AND region_id = :region_id GROUP BY titre
      UNION SELECT code_naf.*,count(code_naf.id) as count FROM abase INNER JOIN code_naf ON code_naf.naf_2003 = abase.code_naf WHERE division_slug = :division AND region_id = :region_id GROUP BY titre ";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'region_id' => $region_id,
                'division' => $division,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function CountNafs_ville($cp, $nom_slug)
        {
            //$sql = "SELECT t1.*,t4.*,t2.categorie_id,t3.parent_id, t3.nom as nom_cat, t3.url as url_cat, t3.keywords as keywords_cat FROM adresse_annuaire.abase as t1 INNER JOIN adresse_annuaire.magasins_rel_categories as t2 ON t1.magasin_id = t2.magasin_id INNER JOIN adresse_annuaire.categories as t3 ON t2.categorie_id = t3.categorie_id INNER JOIN adresse_annuaire.horaires as t4 ON t1.id = t4.pro_id WHERE cp = :cp";
            $sql = "SELECT code_naf,count(*) as count FROM abase WHERE cp = :cp and ville_nom_search = :ville_nom_search GROUP BY code_naf ORDER BY count DESC";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'cp' => $cp,
                'ville_nom_search' => $nom_slug,
            ));
            $count = $query->rowCount();
            return $count;
        }
        public function CountPros_ville_magasin_id_null($cp, $nom_slug)
        {
            //$sql = "SELECT t1.*,t4.*,t2.categorie_id,t3.parent_id, t3.nom as nom_cat, t3.url as url_cat, t3.keywords as keywords_cat FROM adresse_annuaire.abase as t1 INNER JOIN adresse_annuaire.magasins_rel_categories as t2 ON t1.magasin_id = t2.magasin_id INNER JOIN adresse_annuaire.categories as t3 ON t2.categorie_id = t3.categorie_id INNER JOIN adresse_annuaire.horaires as t4 ON t1.id = t4.pro_id WHERE cp = :cp";
            $sql = "SELECT count(*) as count FROM abase WHERE cp = :cp and ville_nom_search = :ville_nom_search and magasin_id IS NULL";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'cp' => $cp,
                'ville_nom_search' => $nom_slug,
            ));
            $details = $query->fetch();
            return $details;
        }
        public function ListPros_where_dp_magasin_id_null($dp)
        {
            //$sql = "SELECT t1.*,t4.*,t2.categorie_id,t3.parent_id, t3.nom as nom_cat, t3.url as url_cat, t3.keywords as keywords_cat FROM adresse_annuaire.abase as t1 INNER JOIN adresse_annuaire.magasins_rel_categories as t2 ON t1.magasin_id = t2.magasin_id INNER JOIN adresse_annuaire.categories as t3 ON t2.categorie_id = t3.categorie_id INNER JOIN adresse_annuaire.horaires as t4 ON t1.id = t4.pro_id WHERE cp = :cp";
            $sql = "SELECT * FROM abase WHERE departement = :dp and magasin_id IS NULL";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'dp' => $dp,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListCountNafs_where_lettre_emplacement($lettre, $what, $what_code, $slug_code)
        {
            if ($what == 'region') {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE lettre_naf = :lettre AND region_id = :what_code GROUP BY libelle_naf";
            }
            if ($what == 'departement') {
                $sql = "SELECT code_naf,libelle_naf,count(*) as count FROM abase WHERE lettre_naf = :lettre AND departement = :what_code GROUP BY libelle_naf";
            }
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'lettre' => $lettre,
                'what_code' => $what_code,
            ));
            $details = $query->fetchAll();
            foreach ($details as $value) {
                $slug_libelle = Tools::slugify($value->libelle_naf);
                if ($what == 'region') {
                    $value->url = $this->get_link_schema('url', 'naf_region', array(
                        'naf' => $value->code_naf,
                        'libelle' => $slug_libelle,
                        'slug' => $slug_code,
                        'region_id' => $what_code,
                    ));
                    $value->title = $this->get_variable_element('title', 'naf_region', array(
                        'naf' => $value->code_naf,
                        'libelle' => $slug_libelle,
                        'slug' => $slug_code,
                        'region_id' => $what_code,
                    ));
                }
                if ($what == 'departement') {
                    $value->url = $this->get_link_schema('url', 'naf_departement', array(
                        'naf' => $value->code_naf,
                        'libelle' => $slug_libelle,
                        'slug' => $slug_code,
                        'dp' => $what_code,
                    ));
                    $value->title = $this->get_variable_element('title', 'naf_departement', array(
                        'naf' => $value->code_naf,
                        'libelle' => $slug_libelle,
                        'slug' => $slug_code,
                        'dp' => $what_code,
                    ));
                }
            }
            return $details;
        }
        public function ListCount_n0_lettre($lettre, $what = '', $where = '')
        {
            if (!empty($what)) {
                if ($what == 'departement') {
                    $sql = "SELECT count(*) as count,cp,ville_nom_search_cp FROM abase WHERE lettre_naf = :lettre AND $what = :where group by ville_nom_search_cp";
                }
                if ($what == 'region_id') {
                    $sql = "SELECT count(*) as count,departement FROM abase WHERE lettre_naf = :lettre AND $what = :where group by departement";
                }
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'lettre' => $lettre,
                    'where' => $where,
                ));
                $details = $query->fetchAll();
                return $details;
            } else {
                $sql = "SELECT count(*) as count FROM abase WHERE lettre_naf = :lettre";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'lettre' => $lettre,
                ));
                $details = $query->fetch();
                return $details;
            }
        }
        public function ListNumero_division_slug($division_slug)
        {
            $sql = "SELECT numero FROM code_naf WHERE division_slug = :division_slug GROUP BY numero ";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'division_slug' => $division_slug,
            ));
            $details = $query->fetchAll();
            return $details;
        }
        public function ListCount_n1_division_slug($division_slug, $what = '', $where = '')
        {
            $sql = "SELECT numero FROM code_naf WHERE division_slug = :division_slug GROUP BY numero ";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'division_slug' => $division_slug,
            ));
            $details = $query->fetchAll();
            $numero_in = '';
            foreach ($details as $key => $value) {
                $numero_in .= "'$value->numero',";
            }
            $numero_in = trim($numero_in, ',');
            if (!empty($what)) {
                if ($what == 'departement') {
                    $sql = "SELECT count(*) as count,cp,ville_nom_search_cp FROM abase WHERE numero_naf IN ($numero_in) AND $what = $where GROUP BY ville_nom_search_cp";
                }
                if ($what == 'region_id') {
                    $sql = "SELECT count(*) as count,departement FROM abase WHERE numero_naf IN ($numero_in) AND $what = $where GROUP BY departement";
                }
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute();
                $result = $query->fetchAll();
                return $result;
            } else {
                $sql = "SELECT count(*) as count FROM abase WHERE numero_naf IN ($numero_in)";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'numero' => $value->numero,
                ));
                $total = $query->fetch()->count;
                return $total;
            }
        }
        public function ListCount_n2_numero($numero, $what = '', $where = '')
        {
            if (!empty($what)) {
                if ($what == 'departement') {
                    $sql = "SELECT count(*) as count,cp,ville_nom_search_cp FROM abase WHERE numero_naf = :numero AND $what = :where GROUP BY ville_nom_search_cp";
                }
                if ($what == 'region_id') {
                    $sql = "SELECT count(*) as count,departement FROM abase WHERE numero_naf = :numero AND $what = :where GROUP BY departement";
                }
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'numero' => $numero,
                    'where' => $where,
                ));
                $details = $query->fetchAll();
            } else {
                $sql = "SELECT count(*) as count FROM abase WHERE numero_naf = :numero GROUP BY numero_naf";
                $this
                    ->db
                    ->exec("SET CHARACTER SET utf8");
                $query = $this
                    ->db
                    ->prepare($sql);
                $query->execute(array(
                    'numero' => $numero,
                ));
                $details = $query->fetch();
            }
            return $details;
        }
        public function ListCount_n3_naf($code_naf, $what = '', $where = '')
        {
            $sql = "SELECT count(*) as count FROM abase WHERE code_naf = :code_naf GROUP BY numero_naf";
            $this
                ->db
                ->exec("SET CHARACTER SET utf8");
            $query = $this
                ->db
                ->prepare($sql);
            $query->execute(array(
                'code_naf' => $code_naf,
            ));
            $details = $query->fetch();
            return $details;
        }
    }
}
