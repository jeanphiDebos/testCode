<?php
/** Vous ne pouvez pas utiliser le mot cl� "new", un Mutex n'est pas un objet PHP **/
$mutex = Mutex::create();
/** Vous pouvez maintenant verrouiller le Mutex dans n'importe quel contexte **/
var_dump(Mutex::lock($mutex));
/** Il n'est pas valide que de tenter de d�truire un Mutex verrouill� **/
var_dump(Mutex::unlock($mutex));
/** Toujours d�truire un Mutex que vous avez cr�� **/
Mutex::destroy($mutex);
?>