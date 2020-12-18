<?php

global $db, $player_all, $player_win, $player_loose;

$formHtml = new Form($db);
$formUserAll = $formHtml->select_dolusers($player_all, 'player_all', 1);
$formUserWin = $formHtml->select_dolusers($player_win, 'player_win', 1);
$formUserLoose = $formHtml->select_dolusers($player_loose, 'player_loose', 1);

print '<div id="filter_match">';

print '<table>';

print '<tr class="liste_titre"><td colspan="100%">';
print '<label for="player_all">Chercher tous les matchs de : </label>';
print $formUserAll;
print '&nbsp;&nbsp;<label for="player_win">Afficher tous les matchs gagnés par : </label>';
print $formUserWin;
print '&nbsp;&nbsp;<label for="player_loose">Afficher tous les matchs perdus par : </label>';
print $formUserLoose;
print '</td></tr>';

print '</table>';
print '</div>';

?>

<script type="text/javascript">

$(document).ready(function () 
{
    $divFilter = $('#filter_match tr');
    $('#match thead').prepend($divFilter);
});

</script>