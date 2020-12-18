<?php
/* Copyright (C) 2020 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!class_exists('SeedObject')) {
    /**
     * Needed if $form->showLinkedObjectBlock() is call or for session timeout on our module page
     */
    define('INC_FROM_DOLIBARR', true);
    require_once dirname(__FILE__) . '/../config.php';
}


class Match extends SeedObject
{
    /**
     * Canceled status
     */
    const STATUS_CANCELED = -1;
    /**
     * Draft status
     */
    const STATUS_DRAFT = 0;
    /**
     * Validated status
     */
    const STATUS_VALIDATED = 1;
    /**
     * Accepted status
     */
    const STATUS_FINISHED = 2;

    /** @var array $TStatus Array of translate key for each const */
    public static $TStatus = array(
        self::STATUS_DRAFT => 'matchStatusShortDraft', self::STATUS_VALIDATED => 'matchStatusShortValidated'
        //		,self::STATUS_REFUSED => 'matchStatusShortRefused'
        , self::STATUS_FINISHED => 'matchStatusShortAccepted'
    );

    /** @var string $table_element Table name in SQL */
    public $table_element = 'match';

    /** @var string $element Name of the element (tip for better integration in Dolibarr: this value should be the reflection of the class name with ucfirst() function) */
    public $element = 'match';

    /** @var int $isextrafieldmanaged Enable the fictionalises of extrafields */
    public $isextrafieldmanaged = 1;

    /** @var int $ismultientitymanaged 0=No test on entity, 1=Test with field entity, 2=Test with link by societe */
    public $ismultientitymanaged = 1;

    /**
     *  'type' is the field format.
     *  'label' the translation key.
     *  'enabled' is a condition when the field must be managed.
     *  'visible' says if field is visible in list (Examples: 0=Not visible, 1=Visible on list and create/update/view forms, 2=Visible on list only, 3=Visible on create/update/view form only (not list), 4=Visible on list and update/view form only (not create). Using a negative value means field is not shown by default on list but can be selected for viewing)
     *  'noteditable' says if field is not editable (1 or 0)
     *  'notnull' is set to 1 if not null in database. Set to -1 if we must set data to null if empty ('' or 0).
     *  'default' is a default value for creation (can still be replaced by the global setup of default values)
     *  'index' if we want an index in database.
     *  'foreignkey'=>'tablename.field' if the field is a foreign key (it is recommanded to name the field fk_...).
     *  'position' is the sort order of field.
     *  'searchall' is 1 if we want to search in this field when making a search from the quick search button.
     *  'isameasure' must be set to 1 if you want to have a total on list for this field. Field type must be summable like integer or double(24,8).
     *  'css' is the CSS style to use on field. For example: 'maxwidth200'
     *  'help' is a string visible as a tooltip on field
     *  'comment' is not used. You can store here any text of your choice. It is not used by application.
     *  'showoncombobox' if value of the field must be visible into the label of the combobox that list record
     *  'arraykeyval' to set list of value if type is a list of predefined values. For example: array("0"=>"Draft","1"=>"Active","-1"=>"Cancel")
     */

    public $fields = array(

        'ref' => array(
            'type' => 'varchar(50)',
            'length' => 50,
            'label' => 'Ref',
            'enabled' => 1,
            'visible' => 5,
            'notnull' => 1,
            'showoncombobox' => 1,
            'index' => 1,
            'position' => 10,
            'searchall' => 1,
            'comment' => 'Reference of object'
        ),

        'entity' => array(
            'type' => 'integer',
            'label' => 'Entity',
            'enabled' => 1,
            'visible' => 0,
            'default' => 1,
            'notnull' => 1,
            'index' => 1,
            'position' => 20
        ),

        'fk_discipline' => array(
            'type' => 'sellist:c_discipline:label:rowid::active=1',
            'label' => 'discipline',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 1,
            'default' => null,
            'index' => 1,
            'position' => 25,
        ),

        'date' => array(
            'type' => 'date',
            'label' => 'date',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 1,
            'default' => null,
            'index' => 1,
            'position' => 30,
        ),

        'fk_user_1_1' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'playerTeam1',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 1,
            'index' => 1,
            'position' => 50,
        ),

        'fk_user_1_2' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'playerTeam1',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 0,
            'default' => null,
            'index' => 1,
            'position' => 51,
        ),

        'fk_user_2_1' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'playerTeam2',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 1,
            'default' => null,
            'index' => 1,
            'position' => 52,
        ),

        'fk_user_2_2' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'playerTeam2',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 0,
            'default' => null,
            'index' => 1,
            'position' => 53,
        ),

        'score_1' => array(
            'type' => 'integer',
            'label' => 'scoreTeam1',
            'enabled' => 1,
            'visible' => 1,
            'default' => 0,
            'notnull' => 1,
            'index' => 1,
            'position' => 70
        ),

        'score_2' => array(
            'type' => 'integer',
            'label' => 'scoreTeam2',
            'enabled' => 1,
            'visible' => 1,
            'default' => 0,
            'notnull' => 1,
            'index' => 1,
            'position' => 75
        ),

        'winner_1' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'winner_1',
            'enabled' => 1,
            'visible' => 5,
            'notnull' => 0,
            'default' => 0,
            'index' => 1,
            'position' => 80,
        ),

        'winner_2' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'winner_2',
            'enabled' => 1,
            'visible' => 5,
            'notnull' => 0,
            'default' => 0,
            'index' => 1,
            'position' => 85,
        ),

        'looser_1' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'looser_1',
            'enabled' => 1,
            'visible' => 5,
            'notnull' => 0,
            'default' => 0,
            'index' => 1,
            'position' => 90,
        ),

        'looser_2' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'looser_2',
            'enabled' => 1,
            'visible' => 5,
            'notnull' => 0,
            'default' => 0,
            'index' => 1,
            'position' => 95,
        ),

        'description' => array(
            'type' => 'text',
            'label' => 'matchDescription',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 0,
            'index' => 1,
            'position' => 100,
        ),

        'status' => array(
            'type' => 'integer',
            'label' => 'Status',
            'enabled' => 1,
            'visible' => 2,
            'notnull' => 1,
            'default' => '0',
            'index' => 1,
            'position' => 110,
            'arrayofkeyval' => array(
                0 => 'Draft',
                1 => 'Active',
                -1 => 'Canceled',
                2 => 'Finished'
            )
        ),

    );

    /** @var string $ref Object reference */
    public $ref;

    /** @var int $entity Object entity */
    public $entity;

    /** @var int $status Object status */
    public $status;

    /** @var string $label Object label */
    public $label;

    /** @var int $discipline Object discipline */
    public $discipline;

    /** @var int $date Object date */
    public $date;

    /** @var int $fk_user_1_1 Object fk_user_1_1 */
    public $fk_user_1_1;

    /** @var int $fk_user_1_2 Object fk_user_1_2 */
    public $fk_user_1_2;

    /** @var int $fk_user_2_1 Object fk_user_2_1 */
    public $fk_user_2_1;

    /** @var int $fk_user_2_2 Object fk_user_2_2 */
    public $fk_user_2_2;

    /** @var int $score_1 Object score_1 */
    public $score_1;

    /** @var int $score_2 Object score_2 */
    public $score_2;

    /** @var int $winner_1 Object winner_1 */
    public $winner_1;

    /** @var int $winner_2 Object winner_2 */
    public $winner_2;

    /** @var int $looser_1 Object looser_1 */
    public $looser_1;

    /** @var int $looser_2 Object looser_2 */
    public $looser_2;

    /** @var string $description Object description */
    public $description;



    /**
     * match constructor.
     * @param DoliDB    $db    Database connector
     */
    public function __construct($db)
    {
        global $conf;

        parent::__construct($db);

        $this->init();

        $this->status = self::STATUS_DRAFT;
        $this->entity = $conf->entity;
    }

    /**
     * @param User $user User object
     * @return int
     */
    public function save($user)
    {
        global $langs;

        if ($this->fk_user_1_1 == '-1') {
            $this->fk_user_1_1 = null;
        }

        if ($this->fk_user_2_1 == '-1') {
            $this->fk_user_2_1 = null;
        }

        if ($this->fk_user_1_2 == '-1') {
            $this->fk_user_1_2 = null;
        }

        if ($this->fk_user_2_2 == '-1') {
            $this->fk_user_2_2 = null;
        }

        $TExceptFields = array();
        $TExceptFields['ref'] = 'ref';
        $TExceptFields['status'] = 'status';

        foreach ($this->fields as $key => $value) {

            if ($value['notnull'] == 1 && is_null($this->{$key}) && empty($TExceptFields[$key])) {
                setEventMessage($langs->trans('miss_required_field'), 'errors');
                return -1;
            }
        }

        $res = $this->create($user);

        if (!empty($this->is_clone) || empty($this->ref)) {
            // TODO determinate if auto generate
            $this->ref = '(PROV' . $this->id . ')';
            $res = $this->update($user);
        }

        return $res;
    }

    /**
     * @see cloneObject
     * @return void
     */
    public function clearUniqueFields()
    {
        $this->ref = 'Copy of ' . $this->ref;
    }


    /**
     * @param User $user User object
     * @return int
     */
    public function delete(User &$user)
    {
        $this->deleteObjectLinked();

        $this->winner_looser(true);

        unset($this->fk_element); // avoid conflict with standard Dolibarr comportment
        return parent::delete($user);
    }

    /**
     * @return string
     */
    public function getRef()
    {
        if (preg_match('/^[\(]?PROV/i', $this->ref) || empty($this->ref)) {
            return $this->getNextRef();
        }

        return $this->ref;
    }

    /**
     * @return string
     */
    private function getNextRef()
    {
        global $db, $conf;

        require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';

        $mask = !empty($conf->global->MATCH_REF_MASK) ? $conf->global->MATCH_REF_MASK : 'MM{yy}{mm}-{0000}';
        $ref = get_next_value($db, $mask, 'match', 'ref');

        return $ref;
    }


    /**
     * @param User  $user   User object
     * @return int
     */
    public function setDraft($user)
    {
        if ($this->status === self::STATUS_VALIDATED) {
            $this->status = self::STATUS_DRAFT;
            $this->withChild = false;

            return $this->update($user);
        }

        return 0;
    }

    /**
     * @param User  $user   User object
     * @return int
     */
    public function setValid($user)
    {
        if ($this->status === self::STATUS_DRAFT || $this->status === self::STATUS_FINISHED) {
            // TODO determinate if auto generate
            $this->ref = $this->getRef();
            $this->fk_user_valid = $user->id;
            $this->status = self::STATUS_VALIDATED;
            $this->withChild = false;

            return $this->update($user);
        }

        return 0;
    }

    public function winner_looser($side)
    {
        if ($this->score_1 > $this->score_2)
        {
            $score_win = $this->score_1;
            $score_loose = $this->score_2;
            $this->winner_1 = $this->fk_user_1_1;
            $this->winner_2 = $this->fk_user_1_2;
            $this->looser_1 = $this->fk_user_2_1;
            $this->looser_2 = $this->fk_user_2_2;

            if (empty($side)) {
                $this->addGoal($score_win, $score_loose);
            } else {
                $this->deleteGoal($score_win, $score_loose);
            }
        } else {
            $score_win = $this->score_2;
            $score_loose = $this->score_1;
            $this->winner_1 = $this->fk_user_2_1;
            $this->winner_2 = $this->fk_user_2_2;
            $this->looser_1 = $this->fk_user_1_1;
            $this->looser_2 = $this->fk_user_1_2;

            if (empty($side)) {
                $this->addGoal($score_win, $score_loose);
            } else {
                $this->deleteGoal($score_win, $score_loose);
            }
        }
    }

    public function addGoal($score_win, $score_loose)
    {
        global $user;

        for ($i = 1; $i < 3; $i++) {
            //recup les 4 users
            ${'win_' . $i}  = new User($this->db);
            ${'win_' . $i}->fetch($this->{'winner_' . $i});

            ${'loose_' . $i}  = new User($this->db);
            ${'loose_' . $i}->fetch($this->{'looser_' . $i});

            //ajout nbre de match
            ${'win_' . $i}->array_options["options_nb_match"]++;
            ${'loose_' . $i}->array_options["options_nb_match"]++;

            //ajout nbre de match gagnés
            ${'win_' . $i}->array_options["options_nb_win"]++;

            //ajout nbre de match perdus
            ${'loose_' . $i}->array_options["options_nb_loose"]++;

            //ajout des points winners + loosers
            ${'win_' . $i}->array_options["options_nb_goal"] = ${'win_' . $i}->array_options["options_nb_goal"] + $score_win;
            ${'loose_' . $i}->array_options["options_nb_goal"] = ${'loose_' . $i}->array_options["options_nb_goal"] + $score_loose;

            //calcul des ratios
            ${'win_' . $i}->array_options["options_ratio_win_loose"] = ${'win_' . $i}->array_options["options_nb_win"] / ${'win_' . $i}->array_options["options_nb_match"] * 100;
            ${'loose_' . $i}->array_options["options_ratio_win_loose"] = ${'loose_' . $i}->array_options["options_nb_win"] / ${'loose_' . $i}->array_options["options_nb_match"] * 100;

            ${'win_' . $i}->update($user);
            ${'loose_' . $i}->update($user);
        }
    }

    public function deleteGoal($score_win, $score_loose)
    {
        global $user;

        for ($i = 1; $i < 3; $i++) {
            //recup les 4 users
            ${'win_' . $i}  = new User($this->db);
            ${'win_' . $i}->fetch($this->{'winner_' . $i});

            ${'loose_' . $i}  = new User($this->db);
            ${'loose_' . $i}->fetch($this->{'looser_' . $i});

            //ajout nbre de match
            ${'win_' . $i}->array_options["options_nb_match"]--;
            ${'loose_' . $i}->array_options["options_nb_match"]--;

            //ajout nbre de match gagnés
            ${'win_' . $i}->array_options["options_nb_win"]--;

            //ajout nbre de match gagnés
            ${'loose_' . $i}->array_options["options_nb_loose"]--;

            //ajout des points winners + loosers
            ${'win_' . $i}->array_options["options_nb_goal"] = ${'win_' . $i}->array_options["options_nb_goal"] - $score_win;
            ${'loose_' . $i}->array_options["options_nb_goal"] = ${'loose_' . $i}->array_options["options_nb_goal"] - $score_loose;

            //calcul des ratios
            if (empty(${'win_' . $i}->array_options["options_nb_match"])) 
            {
                ${'win_' . $i}->array_options["options_ratio_win_loose"] = 0;
            } else {
                ${'win_' . $i}->array_options["options_ratio_win_loose"] = ${'win_' . $i}->array_options["options_nb_win"] / ${'win_' . $i}->array_options["options_nb_match"] * 100;
            }
            
            if (empty(${'loose_' . $i}->array_options["options_nb_match"]))
            {
                ${'loose_' . $i}->array_options["options_ratio_win_loose"] = 0;
            } else {
                ${'loose_' . $i}->array_options["options_ratio_win_loose"] = ${'loose_' . $i}->array_options["options_nb_win"] / ${'loose_' . $i}->array_options["options_nb_match"] * 100;
            }

            ${'win_' . $i}->update($user);
            ${'loose_' . $i}->update($user);
        }
    }

    /**
     * @param User  $user   User object
     * @return int
     */
    public function setAccepted($user)
    {
        global $langs;

        if ($this->status === self::STATUS_VALIDATED) {
            if ($this->score_1 == $this->score_2) {
                setEventMessage($langs->trans('match_equality'), 'errors');
                return -1;
            }

            $this->winner_looser(false);
            $this->status = self::STATUS_FINISHED;
            $this->withChild = false;

            return $this->update($user);
        }

        return 0;
    }

    /**
     * @param User  $user   User object
     * @return int
     */
    public function setRefused($user)
    {
        if ($this->status === self::STATUS_VALIDATED) {
            $this->status = self::STATUS_REFUSED;
            $this->withChild = false;

            return $this->update($user);
        }

        return 0;
    }

    /**
     * @param User  $user   User object
     * @return int
     */
    public function setReopen($user)
    {
        if ($this->status === self::STATUS_FINISHED) {
            $this->winner_looser(true);

            $this->status = self::STATUS_VALIDATED;
            $this->withChild = false;

            return $this->update($user);
        }

        return 0;
    }


    /**
     * @param int    $withpicto     Add picto into link
     * @param string $moreparams    Add more parameters in the URL
     * @return string
     */
    public function getNomUrl($withpicto = 0, $moreparams = '')
    {
        global $langs;

        $result = '';
        $label = '<u>' . $langs->trans("Showmatch") . '</u>';
        if (!empty($this->ref)) $label .= '<br><b>' . $langs->trans('Ref') . ':</b> ' . $this->ref;

        $linkclose = '" title="' . dol_escape_htmltag($label, 1) . '" class="classfortooltip">';
        $link = '<a href="' . dol_buildpath('/match/card.php', 1) . '?id=' . $this->id . urlencode($moreparams) . $linkclose;

        $linkend = '</a>';

        $picto = 'generic';
        //        $picto='match@match';

        if ($withpicto) $result .= ($link . img_object($label, $picto, 'class="classfortooltip"') . $linkend);
        if ($withpicto && $withpicto != 2) $result .= ' ';

        $result .= $link . $this->ref . $linkend;

        return $result;
    }

    /**
     * @param int       $id             Identifiant
     * @param null      $ref            Ref
     * @param int       $withpicto      Add picto into link
     * @param string    $moreparams     Add more parameters in the URL
     * @return string
     */
    public static function getStaticNomUrl($id, $ref = null, $withpicto = 0, $moreparams = '')
    {
        global $db;

        $object = new match($db);
        $object->fetch($id, false, $ref);

        return $object->getNomUrl($withpicto, $moreparams);
    }


    /**
     * @param int $mode     0=Long label, 1=Short label, 2=Picto + Short label, 3=Picto, 4=Picto + Long label, 5=Short label + Picto, 6=Long label + Picto
     * @return string
     */
    public function getLibStatut($mode = 0)
    {
        return self::LibStatut($this->status, $mode);
    }

    /**
     * @param int       $status   Status
     * @param int       $mode     0=Long label, 1=Short label, 2=Picto + Short label, 3=Picto, 4=Picto + Long label, 5=Short label + Picto, 6=Long label + Picto
     * @return string
     */
    public static function LibStatut($status, $mode)
    {
        global $langs;

        $langs->load('match@match');
        $res = '';

        if ($status == self::STATUS_CANCELED) {
            $statusType = 'status9';
            $statusLabel = $langs->trans('matchStatusCancel');
            $statusLabelShort = $langs->trans('matchStatusShortCancel');
        } elseif ($status == self::STATUS_DRAFT) {
            $statusType = 'status0';
            $statusLabel = $langs->trans('matchStatusDraft');
            $statusLabelShort = $langs->trans('matchStatusShortDraft');
        } elseif ($status == self::STATUS_VALIDATED) {
            $statusType = 'status1';
            $statusLabel = $langs->trans('matchStatusValidated');
            $statusLabelShort = $langs->trans('matchStatusShortValidate');
        }
        //elseif ($status==self::STATUS_REFUSED) { $statusType='status5'; $statusLabel=$langs->trans('matchStatusRefused'); $statusLabelShort=$langs->trans('matchStatusShortRefused'); }
        elseif ($status == self::STATUS_FINISHED) {
            $statusType = 'status6';
            $statusLabel = $langs->trans('matchStatusAccepted');
            $statusLabelShort = $langs->trans('matchStatusShortAccepted');
        }

        if (function_exists('dolGetStatus')) {
            $res = dolGetStatus($statusLabel, $statusLabelShort, '', $statusType, $mode);
        } else {
            if ($mode == 0) $res = $statusLabel;
            elseif ($mode == 1) $res = $statusLabelShort;
            elseif ($mode == 2) $res = img_picto($statusLabel, $statusType) . $statusLabelShort;
            elseif ($mode == 3) $res = img_picto($statusLabel, $statusType);
            elseif ($mode == 4) $res = img_picto($statusLabel, $statusType) . $statusLabel;
            elseif ($mode == 5) $res = $statusLabelShort . img_picto($statusLabel, $statusType);
            elseif ($mode == 6) $res = $statusLabel . img_picto($statusLabel, $statusType);
        }

        return $res;
    }
}


//class matchDet extends SeedObject
//{
//    public $table_element = 'matchdet';
//
//    public $element = 'matchdet';
//
//
//    /**
//     * matchDet constructor.
//     * @param DoliDB    $db    Database connector
//     */
//    public function __construct($db)
//    {
//        $this->db = $db;
//
//        $this->init();
//    }
//}
