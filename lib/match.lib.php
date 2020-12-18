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

/**
 *	\file		lib/match.lib.php
 *	\ingroup	match
 *	\brief		This file is an example module library
 *				Put some comments here
 */

/**
 * @return array
 */
function matchAdminPrepareHead()
{
    global $langs, $conf;

    $langs->load('match@match');

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/match/admin/match_setup.php", 1);
    $head[$h][1] = $langs->trans("Parameters");
    $head[$h][2] = 'settings';
    $h++;
    $head[$h][0] = dol_buildpath("/match/admin/match_extrafields.php", 1);
    $head[$h][1] = $langs->trans("ExtraFields");
    $head[$h][2] = 'extrafields';
    $h++;
    $head[$h][0] = dol_buildpath("/match/admin/match_about.php", 1);
    $head[$h][1] = $langs->trans("About");
    $head[$h][2] = 'about';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$this->tabs = array(
    //	'entity:+tabname:Title:@match:/match/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
    //	'entity:-tabname:Title:@match:/match/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'match');

    return $head;
}

/**
 * Return array of tabs to used on pages for third parties cards.
 *
 * @param 	match	$object		Object company shown
 * @return 	array				Array of tabs
 */
function match_prepare_head(match $object)
{
    global $langs, $conf;
    $h = 0;
    $head = array();
    $head[$h][0] = dol_buildpath('/match/card.php', 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans("matchCard");
    $head[$h][2] = 'card';
    $h++;
	
	// Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@match:/match/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@match:/match/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'match');
	
	return $head;
}

/**
 * @param Form      $form       Form object
 * @param match  $object     match object
 * @param string    $action     Triggered action
 * @return string
 */
function getFormConfirmmatch($form, $object, $action)
{
    global $langs, $user;

    $formconfirm = '';

    if ($action === 'valid' && !empty($user->rights->match->write))
    {
        $body = $langs->trans('ConfirmValidatematchBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmValidatematchTitle'), $body, 'confirm_validate', '', 0, 1);
    }
    elseif ($action === 'accept' && !empty($user->rights->match->write))
    {
        $body = $langs->trans('ConfirmAcceptmatchBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmAcceptmatchTitle'), $body, 'confirm_accept', '', 0, 1);
    }
    elseif ($action === 'refuse' && !empty($user->rights->match->write))
    {
        $body = $langs->trans('ConfirmRefusematchBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmRefusematchTitle'), $body, 'confirm_refuse', '', 0, 1);
    }
    elseif ($action === 'reopen' && !empty($user->rights->match->write))
    {
        $body = $langs->trans('ConfirmReopenmatchBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmReopenmatchTitle'), $body, 'confirm_reopen', '', 0, 1);
    }
    elseif ($action === 'delete' && !empty($user->rights->match->write))
    {
        $body = $langs->trans('ConfirmDeletematchBody');
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmDeletematchTitle'), $body, 'confirm_delete', '', 0, 1);
    }
    elseif ($action === 'clone' && !empty($user->rights->match->write))
    {
        $body = $langs->trans('ConfirmClonematchBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmClonematchTitle'), $body, 'confirm_clone', '', 0, 1);
    }
    elseif ($action === 'cancel' && !empty($user->rights->match->write))
    {
        $body = $langs->trans('ConfirmCancelmatchBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmCancelmatchTitle'), $body, 'confirm_cancel', '', 0, 1);
    }

    return $formconfirm;
}

/**
 * @param integer   $ratio     ratio_win_loose
 * @return integer      
 */
function getRank($ratio)
{
    if ($ratio >= 0 && $ratio < 29)
    {
        return 1;
    } else if ($ratio >= 30 && $ratio < 39) {
        return 2;
    } else if ($ratio >= 40 && $ratio < 49) {
        return 3;
    } else if ($ratio >= 50 && $ratio < 59) {
        return 4;
    } else if ($ratio >= 60 && $ratio < 69) {
        return 5;
    } else if ($ratio >= 70 && $ratio < 79) {
        return 6;
    } else if ($ratio >= 80 && $ratio < 89) {
        return 7;
    } else if ($ratio >= 90 && $ratio < 101) {
        return 8;
    } 
}
