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

require 'config.php';
dol_include_once('match/class/match.class.php');

if(empty($user->rights->match->read)) accessforbidden();

$langs->load('abricot@abricot');
$langs->load('match@match');


$massaction = GETPOST('massaction', 'alpha');
$confirmmassaction = GETPOST('confirmmassaction', 'alpha');
$toselect = GETPOST('toselect', 'array');

$object = new match($db);

$listViewName = 'match';
$inputPrefix = 'Listview_'.$listViewName.'_search_';

$fk_discipline = GETPOST($inputPrefix . 'fk_discipline');
$score_1 = GETPOST($inputPrefix . 'score_1');
$score_2 = GETPOST($inputPrefix . 'score_2');

$hookmanager->initHooks(array('matchlist'));

if ($object->isextrafieldmanaged)
{
    $extrafields = new ExtraFields($db);
    $extralabels = $extrafields->fetch_name_optionals_label($object->table_element);
}

$operator_score_1 = substr($score_1, 0, 1);
if ($operator_score_1 !== '<' && $operator_score_1 !== '>') 
{
	$operator_score_1 = '';
}

$operator_score_2 = substr($score_2, 0, 1);
if ($operator_score_2 !== '<' && $operator_score_2 !== '>') 
{
	$operator_score_2 = '';
}

/*
 * Actions
 */

$parameters=array();
$reshook=$hookmanager->executeHooks('doActions', $parameters, $object);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (!GETPOST('confirmmassaction', 'alpha') && $massaction != 'presend' && $massaction != 'confirm_presend')
{
    $massaction = '';
}


if (empty($reshook))
{
	// do action from GETPOST ...
}


/*
 * View
 */

llxHeader('', $langs->trans('matchList'), '', '');

//$type = GETPOST('type');
//if (empty($user->rights->match->all->read)) $type = 'mine';

// TODO ajouter les champs de son objet que l'on souhaite afficher
$keys = array_keys($object->fields);
$fieldList = 't.'.implode(', t.', $keys);
if (!empty($object->isextrafieldmanaged))
{
    $keys = array_keys($extralabels);
	if(!empty($keys)) {
		$fieldList .= ', et.' . implode(', et.', $keys);
	}
}

$sql = 'SELECT '.$fieldList;

// Add fields from hooks
$parameters=array('sql' => $sql);
$reshook=$hookmanager->executeHooks('printFieldListSelect', $parameters, $object);    // Note that $action and $object may have been modified by hook
$sql.=$hookmanager->resPrint;

$sql.= ' FROM '.MAIN_DB_PREFIX.'match t ';

if (!empty($object->isextrafieldmanaged))
{
    $sql.= ' LEFT JOIN '.MAIN_DB_PREFIX.'match_extrafields et ON (et.fk_object = t.rowid)';
}

$sql.= ' WHERE 1=1';

if ($fk_discipline > 0)
{
	$sql .= ' AND t.fk_discipline = ' . $fk_discipline;
}

//$sql.= ' AND t.entity IN ('.getEntity('match', 1).')';
//if ($type == 'mine') $sql.= ' AND t.fk_user = '.$user->id;


// Add where from hooks
$parameters=array('sql' => $sql);
$reshook=$hookmanager->executeHooks('printFieldListWhere', $parameters, $object);    // Note that $action and $object may have been modified by hook
$sql.=$hookmanager->resPrint;

$formcore = new TFormCore($_SERVER['PHP_SELF'], 'form_list_match', 'GET');

$nbLine = GETPOST('limit');
if (empty($nbLine)) $nbLine = !empty($user->conf->MAIN_SIZE_LISTE_LIMIT) ? $user->conf->MAIN_SIZE_LISTE_LIMIT : $conf->global->MAIN_SIZE_LISTE_LIMIT;

// List configuration
$listViewConfig = array(
	'view_type' => 'list' // default = [list], [raw], [chart]
	,'allow-fields-select' => true //Affiche le hamburger
	,'limit'=>array(
		'nbLine' => $nbLine
	)
	,'list' => array(
		'title' => $langs->trans('matchList')
		,'image' => 'title_generic.png'
		,'picto_precedent' => '<'
		,'picto_suivant' => '>'
		,'noheader' => 0
		,'messageNothing' => $langs->trans('Nomatch')
		,'picto_search' => img_picto('', 'search.png', '', 0)
		,'massactions'=>array(
			'yourmassactioncode'  => $langs->trans('YourMassActionLabel')
		)
		,'param_url' => '&limit='.$nbLine
	)
	
	,'subQuery' => array()
	,'link' => array()
	,'type' => array(
		'date_creation' => 'date' // [datetime], [hour], [money], [number], [integer]
		,'tms' => 'date'
	)
	,'search' => array(
		'date_creation' => array('search_type' => 'calendars', 'allow_is_null' => true)
		,'tms' => array('search_type' => 'calendars', 'allow_is_null' => false)
		,'ref' => array('search_type' => true, 'table' => 't', 'field' => 'ref')
		,'label' => array('search_type' => true, 'table' => array('t', 't'), 'field' => array('label')) // input text de recherche sur plusieurs champs
		,'status' => array('search_type' => match::$TStatus, 'to_translate' => true) // select html, la clé = le status de l'objet, 'to_translate' à true si nécessaire
		,'score_1' => array('search_type' => true, 'table' => 't', 'field' => 'score_1')
		,'score_2' => array('search_type' => true, 'table' => 't', 'field' => 'score_2')
		,'fk_discipline' => array('search_type' => 'override', 'no-auto-sql-search'=>1, 'override' => $object->showInputField($object->fields['fk_discipline'], 'fk_discipline', $fk_discipline,'','', $inputPrefix))
	)
	,'translate' => array()
	,'hide' => array(
		'rowid' // important : rowid doit exister dans la query sql pour les checkbox de massaction
	)
	,'title'=>array(
		'ref' => $langs->trans('Ref.')
		,'label' => $langs->trans('Label')
		,'date_creation' => $langs->trans('DateCre')
		,'tms' => $langs->trans('DateMaj')
	)
	,'eval'=>array(
		'ref' => '_getObjectNomUrl(\'@rowid@\', \'@val@\')'
		,'fk_user' => '_getUserNomUrl(@val@)' // Si on a un fk_user dans notre requête
		,'date_creation' => '_getDate(\'@val@\')'
		,'tms' => '_getDate(\'@val@\')'
	)
	,'sortfield'=>'date_creation'
	,'sortorder'=>'desc'
	//,'override' => $object->showInputField('fk_discipline', '0')
	,'operator' => array('score_1' => $operator_score_1, 'score_2' => $operator_score_2)
);

$r = new Listview($db, 'match');

foreach ($object->fields as $key => $field)
{
    // visible' says if field is visible in list (Examples: 0=Not visible, 1=Visible on list and create/update/view forms, 2=Visible on list only, 3=Visible on create/update/view form only (not list), 4=Visible on list and update/view form only (not create).
    // Using a negative value means field is not shown by default on list but can be selected for viewing)

	if(!empty($field['enabled']) && !isset($listViewConfig['title'][$key]) && !empty($field['visible']) && in_array($field['visible'], array(1, 2, 4, 5)) ) 
	{
        $listViewConfig['title'][$key] = $langs->trans($field['label']);
    }

	if(!isset($listViewConfig['hide'][$key]) && (empty($field['visible']) || $field['visible'] <= -1))
	{
        $listViewConfig['hide'][] = $key;
    }

	if(!isset($listViewConfig['eval'][$key]))
	{
        $listViewConfig['eval'][$key] = '_getObjectOutputField(\''.$key.'\', \'@rowid@\', \'@val@\')';
    }
}

function _getObjectOutputField($key, $fk_match = 0, $val = '')
{
    $match = getMatchFromCache($fk_match);
    if(!$match){return 'error';}

    return $match->showOutputField($match->fields[$key], $key, $match->{$key});
}

function getMatchFromCache($fk_match){
    global $db, $TMatchCache;


    if(empty($TMatchCache[$fk_match])){
        $match = new Match($db);
        if($match->fetch($fk_match, false) <= 0)
        {
            return false;
        }

        $TMatchCache[$fk_match] = $match;
    }
    else{
        $match = $TMatchCache[$fk_match];
    }

    return $match;
}

// Change view from hooks
$parameters=array(  'listViewConfig' => $listViewConfig);
$reshook=$hookmanager->executeHooks('listViewConfig',$parameters,$r);    // Note that $action and $object may have been modified by hook
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');
if ($reshook>0)
{
	$listViewConfig = $hookmanager->resArray;
}

echo $r->render($sql, $listViewConfig);

$parameters=array('sql'=>$sql);
$reshook=$hookmanager->executeHooks('printFieldListFooter', $parameters, $object);    // Note that $action and $object may have been modified by hook
print $hookmanager->resPrint;

$formcore->end_form();

llxFooter('');
$db->close();

/**
 * TODO remove if unused
 */
function _getObjectNomUrl($id, $ref)
{
	global $db;

	$o = new match($db);
	$res = $o->fetch($id, false, $ref);
	if ($res > 0)
	{
		return $o->getNomUrl(1);
	}

	return '';
}

/**
 * TODO remove if unused
 */
function _getUserNomUrl($fk_user)
{
	global $db;

	$u = new User($db);
	if ($u->fetch($fk_user) > 0)
	{
		return $u->getNomUrl(1);
	}

	return '';
}

function _getDate($date)
{
	$date = new DateTime($date);

	return $date->format('d/m/Y');
}