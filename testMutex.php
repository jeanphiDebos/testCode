<?php
/** Vous ne pouvez pas utiliser le mot clé "new", un Mutex n'est pas un objet PHP **/
$mutex = Mutex::create();
/** Vous pouvez maintenant verrouiller le Mutex dans n'importe quel contexte **/
var_dump(Mutex::lock($mutex));
/** Il n'est pas valide que de tenter de détruire un Mutex verrouillé **/
var_dump(Mutex::unlock($mutex));
/** Toujours détruire un Mutex que vous avez créé **/
Mutex::destroy($mutex);
?>